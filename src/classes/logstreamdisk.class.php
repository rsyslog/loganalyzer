<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
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
	* Copyright (C) 2008-2010 Adiscon GmbH.
	*
	* This file is part of LogAnalyzer.
	*
	* LogAnalyzer is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	*
	* LogAnalyzer is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with LogAnalyzer. If not, see <http://www.gnu.org/licenses/>.
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
		global $content; 
		
		// --- Check if Filename is within allowed directories!
		$szFileDirName = dirname($this->_logStreamConfigObj->FileName) . '/'; 
		$bIsAllowedDir = false; 
		foreach($content['DiskAllowed'] as $szAllowedDir)
		{
			if ( strpos($szFileDirName, $szAllowedDir) !== FALSE ) 
			{
				$bIsAllowedDir = true; 
				break; 
			}
		}
		if ( !$bIsAllowedDir ) 
		{
			global $extraErrorDescription;
			$extraErrorDescription = GetAndReplaceLangStr( $content['LN_ERROR_PATH_NOT_ALLOWED_EXTRA'], $this->_logStreamConfigObj->FileName, implode(", ", $content['DiskAllowed']) ); 

			return ERROR_PATH_NOT_ALLOWED;
		}

		
		// ---

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
		global $content, $gl_starttime;

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
				$retParser = $this->_logStreamConfigObj->_lineParser->ParseLine($arrProperitesOut[SYSLOG_MESSAGE], $arrProperitesOut);
				
				// Run optional Message Parsers now
				$retParser = $this->_logStreamConfigObj->ProcessMsgParsers($arrProperitesOut[SYSLOG_MESSAGE], $arrProperitesOut);

				// Check if we have to skip the message!
				if ( $retParser == ERROR_MSG_SKIPMESSAGE )
					$ret = $retParser;
				
				// Set uID to the PropertiesOut!
				$arrProperitesOut[SYSLOG_UID] = $uID;
			}

			// Check how long we are running. If only two seconds of execution time are left, we abort further reading!
			$scriptruntime = intval(microtime_float() - $gl_starttime);
			if ( $content['MaxExecutionTime'] > 0 && $scriptruntime > ($content['MaxExecutionTime']-2) )
			{
				// This may display a warning message, so the user knows we stopped reading records because of the script timeout. 
				$content['logstream_warning'] = "false";
				$content['logstream_warning_details'] = $content['LN_WARNING_LOGSTREAMDISK_TIMEOUT'];
				$content['logstream_warning_code'] = ERROR_FILE_NOMORETIME;
				
				// Return error code 
				return ERROR_FILE_NOMORETIME;
			}

		// Loop until the filter applies, or another error occurs, and we still have TIME!
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
		if ( $this->_arrProperties != null )
		{
			foreach ( $this->_arrProperties as $property ) 
				$arrProperitesOut[$property] = '';
		}

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
		// Only perform lastUID scan if there are NO filters, for performance REASONS!
		if ( $this->_filters != null )
			return UID_UNKNOWN;

		// Helper variables
		$myuid = -1;
		$counter = 0;
		$tmpOldDirection = $this->_readDirection;	// Store for later use
		$tmpuID = $this->_currentOffset+1;			// Store for later use
		$tmpArray = array();
		
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
		$ret = $this->Read($tmpuID, $tmpArray);

//		if ( $this->_readDirection == EnumReadDirection::Forward )
//			$this->Sseek($myuid, EnumSeek::BOS, 0);
//		else
//			$this->Sseek($myuid, EnumSeek::EOS, 0);
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
	* Implementation of GetLogStreamStats 
	*
	* Returns an Array og logstream statsdata 
	*	Count of Data Items
	*	Total Filesize
	*/
	public function GetLogStreamStats()
	{
		// Get some file data!
/*
			// return results!
			return $stats;
		}
		else
*/
		// NOT IMPLEMENTED YET!
		return null;
	}


	/**
	* Implementation of GetLogStreamTotalRowCount 
	*
	* not implemented yet!
	*/
	public function GetLogStreamTotalRowCount()
	{
		//not implemented
		return null; 
	}


	/**
	* Implementation of the CleanupLogdataByDate
	*
	* not implemented!
	*/
	public function CleanupLogdataByDate( $nDateTimeStamp )
	{
		//not implemented
		return null; 
	}

	/*
	*	Implementation of the SaveMessageChecksum
	*
	*	not implemented!
	*/
	public function SaveMessageChecksum( $arrProperitesIn )
	{
		return SUCCESS; 
	}


	/*
	*	Implementation of the UpdateAllMessageChecksum
	*
	*	not implemented!
	*/
	public function UpdateAllMessageChecksum( )
	{
		return SUCCESS; 
	}


	/*
	*	Helper function to clear the current querystring!
	*/
	public function ResetFilters()
	{
		// nothing todo in this logstream 
		return SUCCESS; 
	}


	/*
	*	Helper function to verify fields | not needed in disk logstream!
	*/
	public function VerifyFields( $arrProperitesIn )
	{
		return SUCCESS; 
	}

	
	/*
	*	Helper function to create missing fields | not needed in disk logstream!
	*/
	public function CreateMissingFields( $arrProperitesIn )
	{
		return SUCCESS; 
	}

	
	/*
	*	Helper function to verify indexes | not needed in disk logstream!
	*/
	public function VerifyIndexes( $arrProperitesIn )
	{
		return SUCCESS; 
	}


	/*
	*	Helper function to create missing indexes | not needed in disk logstream!
	*/
	public function CreateMissingIndexes( $arrProperitesIn )
	{
		return SUCCESS; 
	}


	/*
	*	Helper function to verify triggers | not needed in disk logstream!
	*/
	public function VerifyChecksumTrigger( $myTriggerProperty )
	{
		return SUCCESS; 
	}


	/*
	*	Helper function to verify triggers | not needed in disk logstream!
	*/
	public function CreateMissingTrigger( $myTriggerProperty, $myCheckSumProperty )
	{
		return SUCCESS; 
	}


	/*
	*	Helper function to create missing  triggers | not needed in disk logstream!
	*/
	public function GetCreateMissingTriggerSQL( $myDBTriggerField, $myDBTriggerCheckSumField )
	{
		return SUCCESS; 
	}


	/*
	*	Helper function to verify checksum field | not needed in disk logstream!
	*/
	public function VerifyChecksumField( )
	{
		return SUCCESS; 
	}


	/*
	*	Helper function to correct the checksum field | not needed in disk logstream!
	*/
	public function ChangeChecksumFieldUnsigned( )
	{
		return SUCCESS; 
	}


	/**
	* Implementation of ConsolidateItemListByField 
	*
	* In the native MYSQL Logstream, the database will do most of the work
	*
	* @return integer Error stat
	*/
	public function ConsolidateItemListByField($szConsFieldId, $nRecordLimit, $szSortFieldId, $nSortingOrder)
	{
		global $content, $fields;

		// --- Set Options 
		$nConsFieldType = $fields[$szConsFieldId]['FieldType'];
		// --- 

		// We loop through all loglines! this may take a while!
		$uID = UID_UNKNOWN;

		// Needed to reset file position!
		$this->Sseek($uID, EnumSeek::BOS, 0);

		$ret = $this->Read($uID, $logArray);
		if ( $ret == SUCCESS )
		{
			// Initialize Array variable
			$aResult = array();
			
			// Loop through messages
			do
			{
				if ( isset($logArray[$szConsFieldId]) )
				{
					if ( $nConsFieldType == FILTER_TYPE_DATE ) 
					{
						// Convert to FULL Day Date for now!
						$myFieldData = date( "Y-m-d", $logArray[$szFieldId][EVTIME_TIMESTAMP] );
					}
					else // Just copy the value!
						$myFieldData = $logArray[$szConsFieldId];

					if ( isset($aResult[ $myFieldData ]) )
						$aResult[ $myFieldData ]['ItemCount']++;
					else
					{
						// Initialize entry if we haven't exceeded the RecordLImit yet!
						if ( $nRecordLimit == 0 || count($aResult) < ($nRecordLimit-1) ) // -1 because the last entry will become all others 
						{
							// Init entry
							$aResult[ $myFieldData ][$szSortFieldId] = $logArray[$szSortFieldId];
							$aResult[ $myFieldData ]['ItemCount'] = 1;
						}
						else
						{
							// Count record to others 
							if ( isset($aResult[ $content['LN_STATS_OTHERS'] ]) )
								$aResult[ $content['LN_STATS_OTHERS'] ]['ItemCount']++;
							else
								$aResult[ $content['LN_STATS_OTHERS'] ]['ItemCount'] = 1;
						}
					}
				}
			} while ( ($ret = $this->ReadNext($uID, $logArray)) == SUCCESS );

			// Use callback function to sort array
			if ( $nSortingOrder == SORTING_ORDER_DESC )
				uasort($aResult, "MultiSortArrayByItemCountDesc");
			else
				uasort($aResult, "MultiSortArrayByItemCountAsc");

			if ( isset($aResult[ $content['LN_STATS_OTHERS'] ]) )
			{
				// This will move the "Others" Element to the last position!
				$arrEntryCopy = $aResult[ $content['LN_STATS_OTHERS'] ];
				unset($aResult[ $content['LN_STATS_OTHERS'] ]);
				$aResult[ $content['LN_STATS_OTHERS'] ] = $arrEntryCopy;
			}

			// finally return result!
			if ( count($aResult) > 0 ) 
				return $aResult;
			else
				return ERROR_NOMORERECORDS;
		}
		else
			return $ret;
	}

	/**
	* Implementation of ConsolidateDataByField 
	*
	* In the native MYSQL Logstream, the database will do most of the work
	*
	* @return integer Error stat
	*/
	public function ConsolidateDataByField($szConsFieldId, $nRecordLimit, $szSortFieldId, $nSortingOrder, $aIncludeCustomFields = null, $bIncludeLogStreamFields = false, $bIncludeMinMaxDateFields = false)
	{
		global $content, $fields;

		// --- Set Options 
		$nConsFieldType = $fields[$szConsFieldId]['FieldType'];
		// --- 

		// We loop through all loglines! this may take a while!
		$uID = UID_UNKNOWN;

		// Needed to reset file position!
		$this->Sseek($uID, EnumSeek::BOS, 0);

		$ret = $this->Read($uID, $logArray);
		if ( $ret == SUCCESS )
		{
			// Initialize Array variable
			$aResult = array();
			
			// Loop through messages
			do
			{
				if ( isset($logArray[$szConsFieldId]) )
				{
					// --- Special Case for the checksum field, we need to generate the checksum ourself!
					if ( $szConsFieldId == MISC_CHECKSUM ) 
						$logArray[$szConsFieldId] = crc32( $logArray[SYSLOG_MESSAGE] ); 
					// --- 

					if ( $nConsFieldType == FILTER_TYPE_DATE ) 
					{
						// Convert to FULL Day Date for now!
						$myFieldData = date( "Y-m-d", $logArray[$szFieldId][EVTIME_TIMESTAMP] );
					}
					else // Just copy the value!
						$myFieldData = $logArray[$szConsFieldId];

					// Extra Check to avoid empty counters!
					if ( strlen($myFieldData) <= 0 ) 
						$myFieldData = $content['LN_STATS_OTHERS']; 

					if ( isset($aResult[ $myFieldData ]) )
					{
						$aResult[ $myFieldData ]['ItemCount']++;
						$aResult[ $myFieldData ]['LastOccurrence_Date'] = $logArray[SYSLOG_DATE];
					}
					else
					{
						// Initialize entry if we haven't exceeded the RecordLImit yet!
						if ( $nRecordLimit == 0 || count($aResult) < ($nRecordLimit-1) ) // -1 because the last entry will become all others 
						{
							// Init entry
							if ( $bIncludeLogStreamFields ) 
								$aResult[ $myFieldData ] = $logArray;
							else if ( $aIncludeCustomFields != null ) 
							{
								foreach ( $aIncludeCustomFields as $myFieldName ) 
								{
									if ( $logArray[$myFieldName] ) 
										$aResult[ $myFieldData ][$myFieldName] = $logArray[$myFieldName]; 
								}
							}
							else
								$aResult[ $myFieldData ][$szSortFieldId] = $logArray[$szSortFieldId];

							$aResult[ $myFieldData ]['ItemCount'] = 1;

							$aResult[ $myFieldData ]['FirstOccurrence_Date'] = $logArray[SYSLOG_DATE]; 
							$aResult[ $myFieldData ]['LastOccurrence_Date'] = $logArray[SYSLOG_DATE];
						}
						else
						{
							// Count record to others 
							if ( isset($aResult[ $content['LN_STATS_OTHERS'] ]) )
								$aResult[ $content['LN_STATS_OTHERS'] ]['ItemCount']++;
							else
								$aResult[ $content['LN_STATS_OTHERS'] ]['ItemCount'] = 1;
						}
					}
				}
			} while ( ($ret = $this->ReadNext($uID, $logArray)) == SUCCESS );

			// Use callback function to sort array
			if ( $nSortingOrder == SORTING_ORDER_DESC )
				uasort($aResult, "MultiSortArrayByItemCountDesc");
			else
				uasort($aResult, "MultiSortArrayByItemCountAsc");

			if ( isset($aResult[ $content['LN_STATS_OTHERS'] ]) )
			{
				// This will move the "Others" Element to the last position!
				$arrEntryCopy = $aResult[ $content['LN_STATS_OTHERS'] ];
				unset($aResult[ $content['LN_STATS_OTHERS'] ]);
				$aResult[ $content['LN_STATS_OTHERS'] ] = $arrEntryCopy;
			}

			// finally return result!
			if ( count($aResult) > 0 ) 
				return $aResult;
			else
				return ERROR_NOMORERECORDS;
		}
		else
			return $ret;
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
		global $content;

		// We loop through all loglines! this may take a while!
		$uID = UID_UNKNOWN;
		$ret = $this->ReadNext($uID, $logArray);
		if ( $ret == SUCCESS )
		{
			// Initialize Array variable
			$aResult = array();
			
			// Loop through messages
			do
			{
				if ( isset($logArray[$szFieldId]) )
				{
					if ( $nFieldType == FILTER_TYPE_DATE ) 
					{
						// Convert to FULL Day Date for now!
						$myFieldData = date( "Y-m-d", $logArray[$szFieldId][EVTIME_TIMESTAMP] );
					}
					else // Just copy the value!
						$myFieldData = $logArray[$szFieldId];

					if ( isset($aResult[ $myFieldData ]) )
						$aResult[ $myFieldData ]++;
					else
					{
						// Initialize entry if we haven't exceeded the RecordLImit yet!
						if ( count($aResult) < ($nRecordLimit-1) ) // -1 because the last entry will become all others 
							$aResult[ $myFieldData ] = 1;
						else
						{
							// Count record to others 
							if ( isset($aResult[ $content['LN_STATS_OTHERS'] ]) )
								$aResult[ $content['LN_STATS_OTHERS'] ]++;
							else
								$aResult[ $content['LN_STATS_OTHERS'] ] = 1;
						}
					}
				}
			} while ( ($ret = $this->ReadNext($uID, $logArray)) == SUCCESS );

			// Sort Array, so the highest count comes first!
			arsort($aResult);
//			array_multisort($aResult, SORT_NUMERIC, SORT_DESC);

			if ( isset($aResult[ $content['LN_STATS_OTHERS'] ]) )
			{
				// This will move the "Others" Element to the last position!
				$arrEntryCopy = $aResult[ $content['LN_STATS_OTHERS'] ];
				unset($aResult[ $content['LN_STATS_OTHERS'] ]);
				$aResult[ $content['LN_STATS_OTHERS'] ] = $arrEntryCopy;
			}

			// finally return result!
			if ( count($aResult) > 0 ) 
				return $aResult;
			else
				return ERROR_NOMORERECORDS;
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
}
?>