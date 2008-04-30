<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* LogStream provides access to the log data. Be sure to always		*
	* use LogStream if you want to access a text file or database.		*
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

// --- Basic Includes
require_once($gl_root_path . 'classes/enums.class.php');
require_once($gl_root_path . 'include/constants_errors.php');
require_once($gl_root_path . 'include/constants_logstream.php');
// --- 


abstract class LogStream {
	protected $_readDirection = EnumReadDirection::Forward;
	protected $_sortOrder = EnumSortingOrder::Descending;
	protected $_filters = null;
	protected $_current_uId = -1;
	protected $_logStreamConfigObj = null;
	protected $_arrProperties = null;

	/**
	* Open the stream for read access.
	*
	* @param arrProperties string in: properties of interest. There can be no guarantee the logstream can actually deliver them.
	* @return integer Error stat
	*/
	public abstract function Open($arrProperties);

	/**
	* Close the current stream.
	*
	* @return integer Error stat
	*/
	public abstract function Close();

	/**
	* Read the next data from the current stream. If it reads
	* forwards or backwards depends on the current read direction.
	*
	* Example for reading forward:
	* Is the current uID == 4, readDirection set to forwards
	* ReadNext will provide uID 5 or EOS if no more data exist.
	*
	* Exampe for reading backward:
	* Is the current uID == 4, readDirection set to backwards
	* ReadNext will provide uID 3.
	*
	* Hint: If the current stream becomes unavailable an error
	* stated is retuned. A typical case is if a log rotation
	* changed the original data source.
	*
	* @param uID integer out: unique id of the data row 
	* @param arrProperitesOut array out: list with properties
	* @return integer Error state
	*/
	public abstract function ReadNext(&$uID, &$arrProperitesOut, $bParseMessage = true);

	/**
	* Read the data from a specific uID.
	* 
	* @param uID integer in: unique id of the data row 
	* @param arrProperitesOut array out: list with properties
	* @return integer Error state
	* @see ReadNext()
	*/
	public abstract function Read($uID, &$arrProperitesOut);


	/**
	* Sseek - a strange seek which has a skip capability
	* 
	* This method was introduced to enable the upper layer to jump to a specific 
	* position within the stream and/or skip some records. Probably this method is used by
	* a pager or to navigate from an overview page to a detailed page.
	*
	* mm: We had some discussion about the name of the this method. Initially we named
	* it Seek. While implementing I got pain in the stomach forced me to start a discussion about
	* the name and the functionality. The outcome is here - a strange seek method. Please do not
	* confuse it with a seek method, it is no seek, it is a strange seek. rger suggested to name
	* it diddledaddle, but I still feel uncomfortable with that name. Probably my imagination is
	* too poor associating any functionality of this method with such a name. So strange seek
	* is much better. It reminds me that is no seek, but a strange seek which does not work like
	* a typical seek like fseek in php but in some way similar. Here is how it works:
	*
	* If you Sseek to EOS for example and then call a NextRead you do not get a EOS return status. 
	* Instead you will obtain the last record in the stream. The similarity of Sseek with a seek
	* is when you use Sseek to jump to BOS. After calling a ReadNext will give you the first record
	* in the stream. Here are some samples:
	*
	*
	* Sample: 
	* To read the last record of a stream, do a 
	* seek(uid_out, EOS, 0) 
	* ReadNext 
	*
	* For the first record, similarly: 
	* seek(uid_out, BOS, 0) 
	* ReadNext 
	* 
	* To skip the next, say, 49 records from the current position, you first need to know the 
	* current uid. You may have obtained it by a previous ReadNext call. Then, do 
	* seek(uidCURR, UID, 50) 
	* ReadNext
	* 
	* @param uID integer in/out: is a unique ID from where to start, ignored in all modes except UID. 
	* On return, uID contains the uID of the record seeked to. It is undefined if an error occured. 
	* If no error ocucrred, the next call to ReadNext() will read the record whom's uID has been returned.
	* @param mode EnumSeek in: how the seek should be performed
	* @param numrecs integer in: number of records to seek from this position. Use 0 to seek to the
	* actual position, a positive value to seek the the record numrecs records forward or a negative
	* value to seek to a position numrecs backward
	* @return integer Error state
	*/
	public abstract function Sseek(&$uID, $mode, $numrecs);


	/**
	* If you are interested in how many messages are in the stream, call this method.
	* But be aware of that some stream can not provide a message count. This is probably
	* because of performance reason or any other. However, if GetMessageCount return -1
	* this does not mean that there is no message in the stream, it is just not countable.
	* If there is no message 0 will be returned.
	*
	* @return integer Amount of messages within the stream. -1 means that no count is available.
	*/
	public abstract function GetMessageCount();


	/**
	* This function returns the first UID for previous PAGE, if availbale! Otherwise will 
	* return -1!
	*/
	public abstract function GetPreviousPageUID();


	/**
	* This function returns the first UID for the last PAGE, if availbale! Otherwise will 
	* return -1!
	*/
	public abstract function GetLastPageUID();


	/**
	* This function returns the current Page number, if availbale! Otherwise will 
	* return -1!
	*/
	public abstract function GetCurrentPageNumber();

	
	/**
	* Gets a property and checks if the class is able to sort the records
	* by this property. 
	*
	* @ Returns either true or false.
	*
	*/
	public abstract function IsPropertySortable($myProperty);

	/**
	* Set the filter for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	*/
	public function SetFilter($szFilters)
	{
		// Parse Filters from string
		$this->ParseFilters($szFilters);
		return SUCCESS;	
	}
 
	/**
	* Set the direction the stream should read data.
	*
	* @param enumReadDirectionfilter EnumReadDirection in: The new direction.
	* @return integer Error state
	*/
	public function SetReadDirection($enumReadDirection) 
	{
		// Set the new read direction!
		$this->_readDirection = $enumReadDirection;
		return SUCCESS;
	}

	/**
	* Set the sorting order for the stream 
	*
	* @param newSortOrder EnumSortingOrder in: The new sort order.
	* @return integer Error state
	*/
	public function SetSortOrder($newSortOrder) 
	{
		// Set the new read direction!
		$this->_sortOrder = $newSortOrder;
		return SUCCESS;
	}
	
	/**
	*	Helper function to parse filters into a useful filter array we can work with.
	*/
	private function ParseFilters($szFilters)
	{
		if ( isset($szFilters) && strlen($szFilters) > 0 )
		{
			$tmpEntries = explode(" ", $szFilters);
			foreach($tmpEntries as $myEntry) 
			{
				// Continue if empty filter!
				if ( strlen(trim($myEntry)) <= 0 ) 
					continue;

				if ( strpos($myEntry, ":") !== false )
				{
					// Split key and value
					$tmpArray = explode(":", $myEntry, 2);

					// Continue if empty filter!
					if ( strlen(trim($tmpArray[FILTER_TMP_VALUE])) == 0 ) 
						continue;

					// Check for multiple values!
					if ( strpos($tmpArray[FILTER_TMP_VALUE], ",") )
						$tmpValues = explode(",", $tmpArray[FILTER_TMP_VALUE]);

					switch( $tmpArray[FILTER_TMP_KEY] )
					{
						case "facility": 
							$tmpKeyName = SYSLOG_FACILITY; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
							// --- Extra Check to convert string representations into numbers!
							if ( isset($tmpValues) ) 
							{
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									if ( !is_numeric($szValue) )
									{
										$tmpFacilityCode = $this->ConvertFacilityString($szValue);
										if ( $tmpFacilityCode != -1 ) 
											$tmpValues[$mykey] = $tmpFacilityCode;
									}
								}
							}
							else
							{
								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
								{
									$tmpFacilityCode = $this->ConvertFacilityString($tmpArray[FILTER_TMP_VALUE]);
									if ( $tmpFacilityCode != -1 ) 
										$tmpArray[FILTER_TMP_VALUE] = $tmpFacilityCode;
								}
							}
							// --- 
							break;
						case "severity": 
							$tmpKeyName = SYSLOG_SEVERITY; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
							// --- Extra Check to convert string representations into numbers!
							if ( isset($tmpValues) ) 
							{
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									if ( !is_numeric($szValue) )
									{
										$tmpFacilityCode = $this->ConvertSeverityString($szValue);
										if ( $tmpFacilityCode != -1 ) 
											$tmpValues[$mykey] = $tmpFacilityCode;
									}
								}
							}
							else
							{
								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
								{
									$tmpFacilityCode = $this->ConvertSeverityString($tmpArray[FILTER_TMP_VALUE]);
									if ( $tmpFacilityCode != -1 ) 
										$tmpArray[FILTER_TMP_VALUE] = $tmpFacilityCode;
								}
							}
							// --- 
							break;
						case "messagetype": 
							$tmpKeyName = SYSLOG_MESSAGETYPE; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
							// --- Extra Check to convert string representations into numbers!
							if ( isset($tmpValues) ) 
							{
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									if ( !is_numeric($szValue) )
									{
										$tmpMsgTypeCode = $this->ConvertMessageTypeString($szValue);
										if ( $tmpMsgTypeCode != -1 ) 
											$tmpValues[$mykey] = $tmpMsgTypeCode;
									}
								}
							}
							else
							{
								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
								{
									$tmpMsgTypeCode = $this->ConvertMessageTypeString($tmpArray[FILTER_TMP_VALUE]);
									if ( $tmpMsgTypeCode != -1 ) 
										$tmpArray[FILTER_TMP_VALUE] = $tmpMsgTypeCode;
								}
							}
							// --- 
							break;
						case "syslogtag": 
							$tmpKeyName = SYSLOG_SYSLOGTAG; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case "source": 
							$tmpKeyName = SYSLOG_HOST; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case "datefrom": 
							$tmpKeyName = SYSLOG_DATE; 
							$tmpFilterType = FILTER_TYPE_DATE;
							$tmpTimeMode = DATEMODE_RANGE_FROM; 
							break;
						case "dateto": 
							$tmpKeyName = SYSLOG_DATE; 
							$tmpFilterType = FILTER_TYPE_DATE;
							$tmpTimeMode = DATEMODE_RANGE_TO; 
							break;
						case "datelastx": 
							$tmpKeyName = SYSLOG_DATE; 
							$tmpFilterType = FILTER_TYPE_DATE;
							$tmpTimeMode = DATEMODE_LASTX; 
							break;
						default:
							$tmpFilterType = FILTER_TYPE_UNKNOWN;
							break;
							// Unknown filter
					}

					// Ignore if unknown filter!
					if ( $tmpFilterType != FILTER_TYPE_UNKNOWN ) 
					{
						// --- Set Filter!
						$this->_filters[$tmpKeyName][][FILTER_TYPE] = $tmpFilterType;
						$iNum = count($this->_filters[$tmpKeyName]) - 1;

						if		( isset($tmpTimeMode) )
						{
							$this->_filters[$tmpKeyName][$iNum][FILTER_DATEMODE] = $tmpTimeMode;
							$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = $tmpArray[FILTER_TMP_VALUE];
						}
						else if ( isset($tmpValues) ) 
						{
							foreach( $tmpValues as $szValue ) 
							{
								// Continue if empty!
								if ( strlen(trim($szValue)) == 0 ) 
									continue;

								if ( isset($this->_filters[$tmpKeyName][$iNum][FILTER_VALUE]) )
								{
									// Create new Filter!
									$this->_filters[$tmpKeyName][][FILTER_TYPE] = $tmpFilterType;
									$iNum = count($this->_filters[$tmpKeyName]) - 1;
								}

								// Set Filter Mode
								$this->_filters[$tmpKeyName][$iNum][FILTER_MODE] = $this->SetFilterIncludeMode($szValue);

								// Set Value
								$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = $szValue;
							}
						}
						else
						{
							// Set Filter Mode
							$this->_filters[$tmpKeyName][$iNum][FILTER_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE]);
							
							// Set Filter value!
							$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = $tmpArray[FILTER_TMP_VALUE];
						}
						// ---
					}

					// Unset unused variables
					if ( isset($tmpArray) ) 
						unset($tmpArray);
					if ( isset($tmpValues) ) 
						unset($tmpValues);
					if ( isset($tmpTimeMode) ) 
						unset($tmpTimeMode);
				}
				else
				{	
					// No ":", so we treat it as message filter!
					$this->_filters[SYSLOG_MESSAGE][][FILTER_TYPE] = FILTER_TYPE_STRING;
					$iNum = count($this->_filters[SYSLOG_MESSAGE]) - 1;
					$this->_filters[SYSLOG_MESSAGE][$iNum][FILTER_MODE] = $this->SetFilterIncludeMode($myEntry);
					$this->_filters[SYSLOG_MESSAGE][$iNum][FILTER_VALUE] = $myEntry;
				}
			}
		}

		// Debug print
//		print_r ($this->_filters);
	}

	/*
	*	Helpre function needed in ParseFilters 
	*/
	private function SetFilterIncludeMode(&$szValue)
	{
		// Set Filtermode
		$pos = strpos($szValue, "+");
		if ( $pos !== false && $pos == 0 )
		{
			//trunscate +
			$szValue = substr( $szValue, 1);
			return FILTER_MODE_INCLUDE;
		}

		$pos = strpos($szValue, "-");
		if ( $pos !== false && $pos == 0 )
		{
			//trunscate -
			$szValue = substr( $szValue, 1);
			return FILTER_MODE_EXCLUDE;
		}

		// Default is include which means +
		return FILTER_MODE_INCLUDE;
	}

	/*
	*	Helper function to convert a facility string into a facility number
	*/
	private function ConvertFacilityString($szValue)
	{
		global $content;

		foreach ( $content['filter_facility_list'] as $myfacility )
		{
			if ( stripos( $myfacility['DisplayName'], $szValue) !== false ) 
				return $myfacility['ID'];
		}
		
		// reached here means we failed to convert the facility!
		return -1;
	}

	/*
	*	Helper function to convert a severity string into a severity number
	*/
	private function ConvertSeverityString($szValue)
	{
		global $content;

		foreach ( $content['filter_severity_list'] as $myfacility )
		{
			if ( stripos( $myfacility['DisplayName'], $szValue) !== false ) 
				return $myfacility['ID'];
		}
		
		// reached here means we failed to convert the facility!
		return -1;
	}

	/*
	*	Helper function to convert a messagetype string into a messagetype number
	*/
	private function ConvertMessageTypeString($szValue)
	{
		global $content;

		foreach ( $content['filter_messagetype_list'] as $mymsgtype )
		{
			if ( stripos( $mymsgtype['DisplayName'], $szValue) !== false ) 
				return $mymsgtype['ID'];
		}
		
		// reached here means we failed to convert the facility!
		return -1;
	}
	

}

?>
