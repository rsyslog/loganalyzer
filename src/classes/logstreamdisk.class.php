<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* LogStreamDisk provides access to the data on disk. In the most
	* cases this will be plain text files. If we need access to e.g.
	* zipped files, this will be handled by a separate driver.
	*
	* \version 2.0.1 2nd Version
	* \version 2.0.0 Init Version
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008 Adiscon GmbH.
	*
	* This file is part of phpLogCon.
	*
	* PhpLogCon is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	*
	* PhpLogCon is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with phpLogCon. If not, see <http://www.gnu.org/licenses/>.
	*
	* A copy of the GPL can be found in the file "COPYING" in this
	* distribution.
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

// --- Required Includes!
require_once($gl_root_path . 'include/constants_errors.php');
// --- 

class LogStreamDisk extends LogStream {
	private $_currentOffset = -1;
	private $_currentStartPos = -1;
	private $_fp = null;
	private $_bEOS = false;

	const _BUFFER_length = 8192;
	private $_buffer = false;
	private $_buffer_length = 0;
	private $_p_buffer = -1;

	private $_previousPageUID = -1;
	private $_lastPageUID = -1;

	// Constructor
	public function LogStreamDisk($streamConfigObj) {
		$this->_logStreamConfigObj = $streamConfigObj;
	}

	/**
	* Open the file with read access.
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function Open($arrProperties)
	{
		// Initialise Basic stuff within the Classs
		$this->RunBasicInits();

		// Check if file exists!
		$result = $this->Verify(); 
		if ( $result != SUCCESS) 
			return $result;
		
		// Now open the file 
		$this->_fp = fopen($this->_logStreamConfigObj->FileName, 'r');	
		$this->_currentOffset = ftell($this->_fp);
		$this->_currentStartPos = $this->_currentOffset;
		$this->_arrProperties = $arrProperties;

		return SUCCESS;
	}

	/**
	* Close the file.
	*
	* @return integer Error state
	*/
	public function Close() {
		
		if ( isset($this->_fp) )
		{
			if (!fclose($this->_fp)) {
				return ERROR_FILE_CANT_CLOSE;
			}
		}

		// return result
		return SUCCESS;
	}

	/**
	* Verify if the file exists!
	*
	* @return integer Error state
	*/
	public function Verify() {
		// Check if file exists!
		if(!file_exists($this->_logStreamConfigObj->FileName)) {
			return ERROR_FILE_NOT_FOUND;
		}

		// Check if file is readable!
		if(!is_readable($this->_logStreamConfigObj->FileName)) {
			return ERROR_FILE_NOT_READABLE;
		}

		// reached this point means success ;)!
		return SUCCESS;
	}
		

	private function ReadNextBlock() {
		$this->_bEOS = false;
		$bCheckForLastLf = false;

		if ($this->_readDirection == EnumReadDirection::Backward) {	
			// in this case we have to adjust a few settings
			$this->_p_buffer = self::_BUFFER_length ; // set the point to the right index

			// first of all, check if this is the first read
			if ($this->_buffer == false) {
				// this means that we have to read from the end
				fseek($this->_fp, 0, SEEK_END);
				$this->_currentOffset = ftell($this->_fp);
				$this->_p_buffer -= 1; // eat EOF
				$bCheckForLastLf = true;
			}

			$orig_offset = ftell($this->_fp) - $this->_buffer_length;

			if ($orig_offset <= 0) {
				// apparently we are at BOF so nothing to read
				return ERROR_EOS;
			}

			// jumb to the new position
			$orig_offset -= self::_BUFFER_length;
			if ($orig_offset <= 0) {
				// ok, we have to adjust the buffer pointer
				$this->_p_buffer += $orig_offset; // note orig_offset is negative, see if
				$orig_offset = 0;
			}
			fseek($this->_fp, $orig_offset);

		} else {
			$this->_p_buffer = 0;
		}

		$this->_buffer = fread($this->_fp, self::_BUFFER_length);
		$this->_buffer_length = strlen($this->_buffer);
		
		if ($bCheckForLastLf && $this->_buffer[$this->_p_buffer] == "\n") {
			// skip it (can only occur if you read backwards)
			$this->_p_buffer--;
			$this->_currentOffset--;
		}
		
		if ($this->_buffer == false)
			return ERROR_FILE_EOF;

		return SUCCESS;
	}

	/**
	* Read the data from a specific uID which means in this
	* case from a given offset of the file.
	* 
	* @param uID integer in/out: unique id of the data row 
	* @param arrProperitesOut array out: array filled with properties
	* @return integer Error state
	* @see ReadNext()
	*/
	public function Read($uID, &$arrProperitesOut) {
		$this->Sseek($uID, EnumSeek::UID, 0);
		$tmp = $this->_readDirection;
		$this->_readDirection = EnumReadDirection::Forward;
		$ret = $this->ReadNext($uID, $arrProperitesOut);
		if ($tmp == EnumReadDirection::Backward) {

			$this->_p_buffer -= 2; 
			$this->_currentStartPos = $this->_currentOffset -= 1;

			$this->_readDirection = $tmp;
			// we have to skip one line that we are back on the right position
			$this->ReadNext($dummy1, $dummy2);
		}
				
		return $ret;
	}

	/**
	* Read the next line from the file depending on the current
	* read direction.
	*
	* Hint: If the current stream becomes unavailable an error
	* stated is retuned. A typical case is if a log rotation
	* changed the original data source.
	*
	* @param uID integer out: uID is the offset of data row
	* @param arrProperitesOut array out: properties
	* @return integer Error state
	* @see ReadNext
	*/
	public function ReadNext(&$uID, &$arrProperitesOut, $bParseMessage = true)
	{
		do
		{
			// Read next entry first!
			if ($this->_readDirection == EnumReadDirection::Forward)
				$ret = $this->ReadNextForwards($uID, $arrProperitesOut);
			else
				$ret = $this->ReadNextBackwards($uID, $arrProperitesOut);
		
		// Only PARSE on success!
		if ( $ret == SUCCESS && $bParseMessage) 
		{
			// Line Parser Hook here
			$this->_logStreamConfigObj->_lineParser->ParseLine($arrProperitesOut[SYSLOG_MESSAGE], $arrProperitesOut);
			
			// Run optional Message Parsers now
			$this->_logStreamConfigObj->ProcessMsgParsers($arrProperitesOut[SYSLOG_MESSAGE], $arrProperitesOut);

			// Set uID to the PropertiesOut!
			$arrProperitesOut[SYSLOG_UID] = $uID;
		}

		// Loop until the filter applies, or another error occurs. 
		} while ( $this->ApplyFilters($ret, $arrProperitesOut) != SUCCESS && $ret == SUCCESS );

		// reached here means return result!
		return $ret;
	}

	private function ReadNextForwards(&$uID, &$arrProperitesOut) {
		if ($this->_bEOS) {
			return ERROR_EOS;
		}

		if ($this->_p_buffer < 0) {
			// init read
			$this->ReadNextBlock();
		}

		if (($this->_p_buffer == $this->_buffer_length || $this->_p_buffer == -1) && ($this->ReadNextBlock() != SUCCESS)) {
			return ERROR_UNDEFINED;
		}
		
		// Init variables dynamically
		$line = '';
		foreach ( $this->_arrProperties as $property ) 
			$arrProperitesOut[$property] = '';

		do {
			$pos = -1;
			if (($pos = strpos($this->_buffer, "\n", $this->_p_buffer)) !== false) {
				$uID = $this->_currentStartPos;
				$logLine = $line . substr($this->_buffer, $this->_p_buffer, $pos - $this->_p_buffer);
				$arrProperitesOut[SYSLOG_MESSAGE] = $logLine;

				// the buffer pointer currently points to the linefeed 
				// so we have to increment the pointer to eat it
				$this->_currentOffset += $pos - $this->_p_buffer + 1;
				$this->_p_buffer = $pos + 1;
				$this->_currentStartPos = $this->_currentOffset;
				return SUCCESS;
			}
			
			$line .= substr($this->_buffer, $this->_p_buffer, $this->_buffer_length - $this->_p_buffer);
			$this->_currentOffset += $this->_buffer_length - $this->_p_buffer;
		} while ($this->ReadNextBlock() == SUCCESS);

		if ( strlen($line) > 0 ) {
			$uID = $this->_currentStartPos;
			$arrProperitesOut[SYSLOG_MESSAGE] = $line;

			$this->_currentStartPos = $this->_currentOffset;
			return SUCCESS;
		}
		return ERROR_UNDEFINED;
	}

	private function ReadNextBackwards(&$uID, &$arrProperitesOut) {
		if ($this->_bEOS) {
			return ERROR_EOS;
		}

		if ($this->_p_buffer < 0) {
			// a negative buffer means that the we have to adjust
			// the offset
			$this->_currentOffset++;
			if ($this->ReadNextBlock() != SUCCESS) {
				return ERROR_UNDEFINED;
			}
		}
		
		// Init variables dynamically
		$line = '';
		foreach ( $this->_arrProperties as $property ) 
			$arrProperitesOut[$property] = '';

		do {
			$pos = -1;
			$neg_offset = ($this->_buffer_length - $this->_p_buffer) * -1;
			if (($pos = strrpos($this->_buffer, "\n", $neg_offset)) !== false) {
				// note that we are at the position of the linefeed, 
				// this is recognize in the next few calculation
				$uID = $this->_currentOffset -= $this->_p_buffer - $pos;
				$arrProperitesOut[SYSLOG_MESSAGE] = substr($this->_buffer, $pos + 1, $this->_p_buffer - $pos) . $line;

				$this->_currentOffset--; // eat the lf
				$this->_p_buffer = $pos - 1;

				return SUCCESS;
			}

			$line = substr($this->_buffer, 0, $this->_p_buffer) . $line;
			$this->_currentOffset -= $this->_p_buffer; 

		} while ($this->ReadNextBlock() == SUCCESS);

		if ( strlen($line) > 0 ) {
			// this case should only happend if we are on BOF
			$this->_bEOS = true;

			$uID = 0;
			$arrProperitesOut[SYSLOG_MESSAGE] = $line;
			
			return SUCCESS;
		}
		return ERROR_EOS;
	}

	/**
	* Implementation of Seek
	*/
	public function Sseek(&$uID, $mode, $numrecs) {
		// in any case we reset the buffer
		$this->ResetBuffer();

		$ret = -1;

		switch ($mode) { 
			case EnumSeek::BOS:
				$ret = fseek($this->_fp, 0);
				$this->_currentOffset = $this->_currentStartPos = 0;
				break;
			case EnumSeek::EOS: 
				// a simple ReadNextBackup will do all the work
				// for us, because we have reset the buffer
				// remember the current readDirection

				$tmp = $this->_readDirection;
				$this->_readDirection = EnumReadDirection::Backward;
				$ret = $this->ReadNextBackwards($uID, $dummy2);
				if ($tmp == EnumReadDirection::Forward) {
					// in this case we have to correct the buffer,
					// because we have read backwards even the current
					// readDirection is forwards
					$this->_p_buffer += 2; 
					$this->_currentStartPos = $this->_currentOffset;
				}
				$this->_readDirection = $tmp;
				break;
			case EnumSeek::UID:
				$ret = fseek($this->_fp, $uID);
				$this->_currentOffset = $this->_currentStartPos = $uID;
				break;
		}

		if ($ret != SUCCESS)
			return ERROR_UNDEFINED;

		return $this->Skip($uID, $numrecs);
	}

	/**
	*	
	* @param numrecs integer in: If positiv, skip 
	* @return uid integer Error state
	*/
	private function Skip($uID, $numrecs) {
		if ($numrecs == 0)
			return SUCCESS;
	
		if ($numrecs > 0) {
			/* due to performance reason we use php's fgets instead of ReadNext method
			while (!feof($this->_fp)) {
        fgets($this->_fp);
        $numrecs--;
				if ($numrecs == 0) {
					break;
				}
				$this->_currentOffset = ftell($this->_fp);
			}
			*/
			while ($this->ReadNextForwards($dummy1, $dummy2) == SUCCESS)
			{
				fgets($this->_fp);
				$numrecs--;

				//---  Extra check to set the correct $_previousPageUID!
				if ( $numrecs == $this->_logStreamConfigObj->_pageCount ) 
					$this->_previousPageUID = $this->_currentOffset;
				//--- 

				if ($numrecs == 0) {
					break;
				}
				$this->_currentOffset = ftell($this->_fp);
			}
		} 
		else 
		{
			while ($this->ReadNextBackwards($dummy1, $dummy2) == SUCCESS)
			{
				$numrecs++;

				//---  Extra check to set the correct $_previousPageUID!
				if ( $numrecs == $this->_logStreamConfigObj->_pageCount ) 
					$this->_previousPageUID = $this->_currentOffset;
				//--- 
				
				if ($numrecs == 0) {
					break;
				}
			}
		}
		
		// where we are?
		$uID = $this->_currentOffset;
		
		if ($numrecs != 0) {
			// obviously there were not enough records to skip
			return ERROR_NOMORERECORDS;
		}
		return SUCCESS;
	}


	/**
	* Set the filter for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	public function SetFilter($filter) {
		return SUCCESS;	
	}
	*/

	/**
	* GetMessageCount will always return -1 which means
	* that the message count is not available. We refuse
	* the request of the message count due to a count would
	* require to read the whole file which would be a big 
	* pain if the file is e.g. 1 gb.
	*/
	public function GetMessageCount() {
		return -1;
	}

	/**
	* This function returns the first UID for previous PAGE, if availbale! 
	* Otherwise will return -1!
	*/
	public function GetPreviousPageUID()
	{
		return $this->_previousPageUID;
	}

	/**
	* This function returns the FIRST UID for the FIRST PAGE! 
	* NOT IMPLEMENTED RIGHT NOW!
	*/
	public function GetFirstPageUID()
	{
		return -1;
	}

	/**
	* This function returns the first UID for the last PAGE! 
	* This is not possible in this logstream, so it always returns -1!
	*/
	public function GetLastPageUID()
	{
		// Obtain last UID if enough records are available!
		
		// Helper variables
		$myuid = -1;
		$counter = 0;
		$tmpOldDirection = $this->_readDirection;
		
		if ( $this->_sortOrder == EnumSortingOrder::Ascending ) 
		{
			// Move to the beginning of END file!
			$this->Sseek($myuid, EnumSeek::EOS, 0);

			// Switch reading direction!
			$this->_readDirection = EnumReadDirection::Backward;
		}
		else if ( $this->_sortOrder == EnumSortingOrder::Descending ) 
		{
			// Move to the beginning of the file!
			$this->Sseek($myuid, EnumSeek::BOS, 0);

			// Switch reading direction!
			$this->_readDirection = EnumReadDirection::Forward;
		}

		// Now we move for one page, we do not need to process the syslog messages!
		$ret = $this->ReadNext($myuid, $tmpArray, false);

		// Save the current UID as LastPage UID!
		$this->_lastPageUID = $myuid;
		
		// --- Restore reading direction and file position!
		$this->_readDirection = $tmpOldDirection;
		if ( $this->_readDirection == EnumReadDirection::Forward )
			$this->Sseek($myuid, EnumSeek::BOS, 0);
		else
			$this->Sseek($myuid, EnumSeek::EOS, 0);
		// --- 
	
		// Return result!
		return $this->_lastPageUID;
	}

	/**
	* This function returns the current Page number, if availbale! 
	* Otherwise will return -1!
	*/
	public function GetCurrentPageNumber()
	{
		return -1;
	}

	/*
	* Implementation of IsPropertySortable
	*
	* For now, sorting is only possible for the UID Property!
	*/
	public function IsPropertySortable($myProperty)
	{
		global $fields;

		// TODO: HARDCODED | FOR NOW only FALSE!
		return false;

		if ( isset($fields[$myProperty]) && $myProperty == SYSLOG_UID )
			return true;
		else
			return false;
	}

	/**
	* Implementation of GetCountSortedByField 
	*
	* For now, the disk source needs to loop through the whole file 
	* to consolidate and sort the data
	*
	* @return integer Error stat
	*/
	public function GetCountSortedByField($szFieldId, $nFieldType, $nRecordLimit)
	{
		// We loop through all loglines! this may take a while!
		$uID = UID_UNKNOWN;
		$ret = $this->ReadNext($uID, $logArray);
		if ( $ret == SUCCESS )
		{
			do
			{
				if ( isset($logArray[$szFieldId]) )
				{
					if ( isset($aResult[ $logArray[$szFieldId] ]) )
						$aResult[ $logArray[$szFieldId] ]++;
					else
						$aResult[ $logArray[$szFieldId] ] = 1;
					/*
					if ( isset($aResult[ $logArray[$szFieldId] ][CHARTDATA_COUNT]) )
						$aResult[ $logArray[$szFieldId] ][CHARTDATA_COUNT]++;
					else
					{
						$aResult[ $logArray[$szFieldId] ][CHARTDATA_NAME] = $logArray[$szFieldId];
						$aResult[ $logArray[$szFieldId] ][CHARTDATA_COUNT] = 1;
					}
					*/
				}
			} while ( ($ret = $this->ReadNext($uID, $logArray)) == SUCCESS );

			// Sort Array, so the highest count comes first!
			array_multisort($aResult, SORT_NUMERIC, SORT_DESC);

			// finally return result!
			return $aResult;
		}
		else
			return ERROR_NOMORERECORDS;
	}


	/**
	* Set the direction the stream should read data.
	*
	* 
	*
	* @param enumReadDirectionfilter EnumReadDirection in: The new direction.
	* @return integer Error state
	*
	public function SetReadDirection($enumReadDirection) {
		
		// only if the read direction change we have do do anything
		if ($this->_readDirection == $enumReadDirection)
			return SUCCESS;

		$this->_readDirection = $enumReadDirection;
		return SUCCESS;
	}
	*/

	private function ResetBuffer() {
		$this->_bEOS = false;
		$this->_buffer = false;
		$this->_buffer_length = 0;
		$this->_p_buffer = -1;
	}

	/**
	*	Implementation of ApplyFilters in the LogSTreamDisk Class. 
	*	This function performs a check on the filters and actually triggers the 
	*	syslog parsers as well. 
	*/
	protected function ApplyFilters($myResults, &$arrProperitesOut)
	{
		// IF result was unsuccessfull, return success - nothing we can do here.
		if ( $myResults >= ERROR ) 
			return SUCCESS;

		if ( $this->_filters != null )
		{
			// Evaluation default for now is true
			$bEval = true;

			// Loop through set properties
			foreach( $arrProperitesOut as $propertyname => $propertyvalue )
			{
				// TODO: NOT SURE IF THIS WILL WORK ON NUMBERS AND OTHER TYPES RIGHT NOW
				if (	
						array_key_exists($propertyname, $this->_filters) && 
						isset($propertyvalue) && 
						!(is_string($propertyvalue) && strlen($propertyvalue) <= 0 ) /* Negative because it only matters if the propvalure is a string*/
					)
				{ 
					// Extra var needed for number checks!
					$bIsOrFilter = false; // If enabled we need to check for numbereval later
					$bOrFilter = false;

					// Found something to filter, so do it!
					foreach( $this->_filters[$propertyname] as $myfilter ) 
					{
						switch( $myfilter[FILTER_TYPE] )
						{
							case FILTER_TYPE_STRING:
								// If Syslog message, we have AND handling!
								if ( $propertyname == SYSLOG_MESSAGE )
								{
									// Include Filter
									if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE ) 
									{
										if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) === false ) 
											$bEval = false;
									}
									// Exclude Filter
									else if ( $myfilter[FILTER_MODE] & FILTER_MODE_EXCLUDE ) 
									{
										if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) !== false ) 
											$bEval = false;
									}
								}
								// Otherwise we use OR Handling!
								else
								{
									$bIsOrFilter = true; // Set isOrFilter to true 

									// Include Filter
									if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE ) 
									{
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_SEARCHFULL ) 
										{
											if ( strtolower($propertyvalue) == strtolower($myfilter[FILTER_VALUE]) ) 
												$bOrFilter = true;
										}
										else
										{
											if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) !== false ) 
												$bOrFilter = true;
										}
									}
									// Exclude Filter
									else if ( $myfilter[FILTER_MODE] & FILTER_MODE_EXCLUDE ) 
									{
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_SEARCHFULL ) 
										{
											if ( strtolower($propertyvalue) != strtolower($myfilter[FILTER_VALUE]) ) 
												$bOrFilter = true;
										}
										else
										{
											if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) === false ) 
												$bOrFilter = true;
										}
									}
									break;
								}
								break;
							case FILTER_TYPE_NUMBER:
								$bIsOrFilter = true; // Set to true in any case!
								if ( $myfilter[FILTER_VALUE] == $arrProperitesOut[$propertyname] ) 
									$bOrFilter = true;
								break;
							case FILTER_TYPE_DATE:
								// Get Log TimeStamp
								$nLogTimeStamp = $arrProperitesOut[$propertyname][EVTIME_TIMESTAMP];

								if ( $myfilter[FILTER_DATEMODE] == DATEMODE_LASTX ) 
								{
									// Get current timestamp
									$nNowTimeStamp = time();

									if		( $myfilter[FILTER_VALUE] == DATE_LASTX_HOUR )
										$nLastXTime = 60 * 60; // One Hour!
									else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_12HOURS )
										$nLastXTime = 60 * 60 * 12; // 12 Hours!
									else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_24HOURS )
										$nLastXTime = 60 * 60 * 24; // 24 Hours!
									else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_7DAYS )
										$nLastXTime = 60 * 60 * 24 * 7; // 7 days
									else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_31DAYS )
										$nLastXTime = 60 * 60 * 24 * 31; // 31 days
									else
										// WTF default? 
										$nLastXTime = 86400;
									// If Nowtime + LastX is higher then the log timestamp, the this logline is to old for us.
									if ( ($nNowTimeStamp - $nLastXTime) > $nLogTimeStamp )
										$bEval = false;
								}
								else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_FROM ) 
								{
									// Get filter timestamp!
 									$nFromTimeStamp = GetTimeStampFromTimeString($myfilter[FILTER_VALUE]);
									
									// If logtime is smaller then FromTime, then the Event is outside of our scope!
									if ( $nLogTimeStamp < $nFromTimeStamp )
										$bEval = false;
								}
								else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_TO ) 
								{
									// Get filter timestamp!
//									echo $myfilter[FILTER_VALUE];
									$nToTimeStamp = GetTimeStampFromTimeString($myfilter[FILTER_VALUE]);
									
									// If logtime is smaller then FromTime, then the Event is outside of our scope!
									if ( $nLogTimeStamp > $nToTimeStamp )
										$bEval = false;
								}

								break;
							default:
								// TODO!
								break;
						}
					}
					
					// If was number filter, we apply it the evaluation.
					if ( $bIsOrFilter ) 
						$bEval &= $bOrFilter;

					if ( !$bEval ) 
					{
						// unmatching filter, rest property array
						foreach ( $this->_arrProperties as $property ) 
							$arrProperitesOut[$property] = '';

						// return error!
						return ERROR_FILTER_NOT_MATCH;
					}
				}
			}
			
			// Reached this point means filters did match!
			return SUCCESS;
		}
		else // No filters at all means success!
			return SUCCESS;
	}

}

?>
