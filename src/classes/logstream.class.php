<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* LogStream provides access to the log data. Be sure to always		*
	* use LogStream if you want to access a text file or database.		*
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

// --- Basic Includes
require_once($gl_root_path . 'classes/enums.class.php');
require_once($gl_root_path . 'classes/msgparser.class.php');
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
	protected $_arrFilterProperties = null; // Helper Array to store all detected properties from Filterstring

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
	* Verifies the logstream source
	*
	* @return integer Error stat
	*/
	public abstract function Verify();

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
	* This function returns the FIRST UID for the FIRST PAGE, if availbale! Otherwise will 
	* return -1!
	*/
	public abstract function GetFirstPageUID();

	/**
	* This function returns the current Page number, if availbale! Otherwise will 
	* return -1!
	*/
	public abstract function GetCurrentPageNumber();

	/**
	* This functions is used by charts/graph generator to obtain data
	*
	* @return integer Error stat
	*/
	public abstract function GetCountSortedByField($szFieldId, $nFieldType, $nRecordLimit);

	/**
	* This functions is used by reports to consolidate data
	*
	* @return integer Error stat
	*/
	public abstract function ConsolidateDataByField($szConsFieldId, $nRecordLimit, $szSortFieldId, $nSortingOrder, $bIncludeLogStreamFields = false, $bIncludeMinMaxDateFields = false);


	/**
	* This functions is used by reports to consolidate data
	*
	* @return integer Error stat
	*/
	public abstract function ConsolidateItemListByField($szConsFieldId, $nRecordLimit, $szSortFieldId, $nSortingOrder);


	/**
	* Gets a property and checks if the class is able to sort the records
	* by this property. 
	*
	* @ Returns either true or false.
	*
	*/
	public abstract function IsPropertySortable($myProperty);


	/**
	* This returns an Array of useful statsdata for this logstream source
	*/
	public abstract function GetLogStreamStats();


	/**
	* This returns just the count of records of the main data source
	*/
	public abstract function GetLogStreamTotalRowCount();


	/**
	* Helper function to cleanup all logdata which is older then the nDateTimeStamp!
	*/
	public abstract function CleanupLogdataByDate( $nDateTimeStamp );


	/*
	*	Helper function to set the message checksum, this will be used for database based logstream classes only
	*/
	public abstract function SaveMessageChecksum( $arrProperitesIn );


	/*
	*	Helper function to set the checksum for all messages in the current logstream class
	*/
	public abstract function UpdateAllMessageChecksum( );


	/*
	*	Helper function for logstream classes to clear filter based stuff
	*/
	public abstract function ResetFilters( );


	/*
	*	Helper function for logstream classes to check if all fields are available!
	*/
	public abstract function VerifyFields( $arrProperitesIn );

	
	/*
	*	Helper function for logstream classes to create missing indexes, only applies to database based logstream classes
	*/
	public abstract function CreateMissingFields( $arrProperitesIn );

	
	/*
	*	Helper function for logstream classes to check for data indexes, only applies to database based logstream classes
	*/
	public abstract function VerifyIndexes( $arrProperitesIn );


	/*
	*	Helper function for logstream classes to create missing indexes, only applies to database based logstream classes
	*/
	public abstract function CreateMissingIndexes( $arrProperitesIn );


	/*
	*	Helper function for logstream classes to check for missing triggers, only applies to database based logstream classes
	*/
	public abstract function VerifyChecksumTrigger( $myTriggerProperty );


	/*
	*	Helper function for logstream classes to create missing trigger, only applies to database based logstream classes
	*/
	public abstract function CreateMissingTrigger( $myTriggerProperty, $myCheckSumProperty );


	/*
	*	Helper function for logstream classes to create the SQL statement needed to create the trigger, only applies to database based logstream classes
	*/
	public abstract function GetCreateMissingTriggerSQL( $myDBTriggerField, $myDBTriggerCheckSumField );

	/*
	*	Helper function for logstream classes to check if the checksum field is configured correctly
	*/
	public abstract function VerifyChecksumField( );


	/*
	*	Helper function for logstream classes to change the checksum field from unsigned INT
	*/
	public abstract function ChangeChecksumFieldUnsigned( );


	/*
	* Helper functino to trigger initialisation of MsgParsers
	*/
	public function RunBasicInits()
	{
		$this->_logStreamConfigObj->InitMsgParsers();
	}

	/**
	* Set the filter for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	*/
	public function SetFilter($szFilters)
	{
		// prepend default Filters
		if ( strlen($this->_logStreamConfigObj->_defaultfilter) > 0 ) 
			$finalfilters = $this->_logStreamConfigObj->_defaultfilter . " " . $szFilters; 
		else
			$finalfilters = $szFilters; 

		OutputDebugMessage("LogStream|SetFilter: SetFilter combined = '" . $finalfilters . "'. ", DEBUG_DEBUG);

		// Reset Filters first to make sure we do not add multiple filters!
		$this->_filters = null;

		// Parse Filters from string 
		$this->ParseFilters($finalfilters);

		// return success
		return SUCCESS;	
	}

 	/**
	* Append filter definition for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	*/
	public function AppendFilter($szFilters)
	{
		OutputDebugMessage("LogStream|AppendFilter: SetFilter combined = '" . $szFilters . "'. ", DEBUG_DEBUG);
	
		// Parse Filters from string 
		$this->ParseFilters($szFilters);

		// return success
		return SUCCESS;	
	}

 	/**
	* Remove filters for a specific Fieldtype
	* 
	* @param filter object in: FieldID
	* @return integer Error state
	*/
	public function RemoveFilters($szFieldID)
	{
		// Removing Filters for this field!
		if ( isset($this->_filters[$szFieldID]) ) 
			unset($this->_filters[$szFieldID]); 

		// return success
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
	*	Implementation of ApplyFilters which can be used by all LogStream Classes!
	*	This function performs a check on the filters and actually triggers the 
	*	syslog parsers as well. 
	*/
	public function ApplyFilters($myResults, &$arrProperitesOut)
	{
		// IF result was unsuccessfull, return success - nothing we can do here.
		if ( $myResults >= ERROR ) 
			return SUCCESS;
		
		// Evaluation default is true
		$bFinalEval = true;

		// Process all filters
		if ( $this->_filters != null )
		{
			// Loop through set properties
			foreach( $arrProperitesOut as $propertyname => $propertyvalue )
			{
				// TODO: NOT SURE IF THIS WILL WORK ON NUMBERS AND OTHER TYPES RIGHT NOW
				if (	
						array_key_exists($propertyname, $this->_filters) &&
						isset($propertyvalue) /* && 
						!(is_string($propertyvalue) && strlen($propertyvalue) <= 0)*/ /* Negative because it only matters if the propvalure is a string*/
					)
				{ 

					// Perform first loop to determine the bEval Default
					foreach( $this->_filters[$propertyname] as $myfilter ) 
					{
						if ( 
								($myfilter[FILTER_TYPE] == FILTER_TYPE_NUMBER) ||
								($myfilter[FILTER_TYPE] == FILTER_TYPE_STRING && $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE && $propertyname != SYSLOG_MESSAGE)
							)
						{
							$bEval = false;
							break;	// IF found one INCLUDE or NUMERIC filter, the default has to be false!
						}
						else
							$bEval = true;
					}

					// Extra var needed for number checks!
					$bIsOrFilter = false; // If enabled we need to check for numbereval later
					$bOrFilter = false;

					// Perform second loop through all filters, to perform filtering
					foreach( $this->_filters[$propertyname] as $myfilter ) 
					{
						switch( $myfilter[FILTER_TYPE] )
						{
							case FILTER_TYPE_STRING:
								// Only filter if value is non zero
								if ( strlen($propertyvalue) > 0 && strlen($myfilter[FILTER_VALUE]) > 0 )
								{
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
										// Include Filter
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE ) 
										{
											// Set isOrFilter to true in this case
											$bIsOrFilter = true; 

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
										// Exclude Filter - handeled with AND filtering!
										else if ( $myfilter[FILTER_MODE] & FILTER_MODE_EXCLUDE ) 
										{
											if ( $myfilter[FILTER_MODE] & FILTER_MODE_SEARCHFULL ) 
											{
												if ( strtolower($propertyvalue) == strtolower($myfilter[FILTER_VALUE]) ) 
													$bEval = false;
											}
											else
											{
												if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) !== false ) 
													$bEval = false;
											}
										}
										break;
									}
								}
								else 
								{
									// Either filter value or property value was empty! 
									// This means we have no match
									$bEval = false;
								}

								break;
							case FILTER_TYPE_NUMBER:
								$bIsOrFilter = true; // Default is set to TRUE
								if ( is_numeric($arrProperitesOut[$propertyname]) )
								{
									if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE ) 
									{
										if ( $myfilter[FILTER_VALUE] == $arrProperitesOut[$propertyname] ) 
											$bOrFilter = true;
										else
											$bOrFilter = false;
									}
									else if ( $myfilter[FILTER_MODE] & FILTER_MODE_EXCLUDE ) 
									{
										if ( $myfilter[FILTER_VALUE] == $arrProperitesOut[$propertyname] ) 
											$bOrFilter = false;
										else
											$bOrFilter = true;
									}
								}
								else
								{
									// If wanted, we treat this filter as a success!
									if ( GetConfigSetting("TreatNotFoundFiltersAsTrue", 0, CFGLEVEL_USER) == 1 )
										$bOrFilter = true;
									else
										$bOrFilter = false;
								}
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
								else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_DATE ) 
								{
									// Get filter timestamp!
//									echo $myfilter[FILTER_VALUE];
									$nDateTimeStamp = GetTimeStampFromTimeString($myfilter[FILTER_VALUE]);
									
									// If not on logfile day, the Event is outside of our scope!
									if ( $nLogTimeStamp < $nDateTimeStamp || $nLogTimeStamp > ($nDateTimeStamp+86400) )
										$bEval = false;
								}
								break;
							default:
								// TODO!
								break;
						}

						// If was number filter, we apply it the evaluation.
						if ( $bIsOrFilter ) // && $bOrFilter ) 
						{
							// Fixed binary comparison to | instead of &!
							$bEval |= $bOrFilter;
	//echo "!" . $bOrFilter . "-" . $bEval . "!<br>";
						}
//						else
//							$bEval &= $bOrFilter;

					}
					
					// Combine filters with AND
					$bFinalEval &= $bEval;
				}

			}

			// Check if evaluation was successfull
			if ( !$bFinalEval ) 
			{
				// unmatching filter, reset property array
				foreach ( $this->_arrProperties as $property ) 
					$arrProperitesOut[$property] = '';

				// return error!
				return ERROR_FILTER_NOT_MATCH;
			}
			
			// Reached this point means filters did match!
			return SUCCESS;
		}
		else // No filters at all means success!
			return SUCCESS;
	}

	/**
	*	Helper function to obtain internal Filters Array
	*/
	public function ReturnFiltersArray()
	{	
		return $this->_filters; 
	}

	/**
	*	Helper function to find a fieldkey by using the SearchField 
	*/
	public function ReturnFilterKeyBySearchField($szSearchField)
	{	
		global $fields; 

		foreach ($fields as $myField) 
		{
			if ( $myField['SearchField'] == $szSearchField )
				return $myField['FieldID'];
		}
		
		return FALSE; 
	}


	/**
	*	Helper function to return all fields needed for filters
	*	Can be helpful for functions which need to add filtering fields
	*/
	public function ReturnFieldsByFilters()
	{	
		global $fields; 

		if ( $this->_filters != null )
		{
			// Return array keys
			$aResult = array_keys($this->_filters); 
			return $aResult; 
		}
		else // No fields at all!
			return null; 
	}

	/*
	*	Helper function to get the internal Field ID by database field name!
	*/
	public function GetFieldIDbyDatabaseMapping($szTableType, $szFieldName)
	{
		global $content, $dbmapping;

		foreach( $dbmapping[$szTableType]['DBMAPPINGS'] as $myFieldID => $myDBMapping ) 
		{
			if ( $myDBMapping == $szFieldName ) 
				return $myFieldID; 
		}

		// Default return! 
		return $szFieldName; 
	}

	/*
	*	Helper function to check a if a fieldname exists in the mapping
	*/
	public function CheckFieldnameInMapping($szTableType, $szFieldName)
	{
		global $content, $dbmapping;

		foreach( $dbmapping[$szTableType]['DBMAPPINGS'] as $myFieldID => $myDBMapping ) 
		{
			if ( $myDBMapping == $szFieldName ) 
				return true; // return found!
		}

		// Default FALSE! 
		return false; 
	}

	/*
	* --- PIRVATE HELPERS!
	*/

	/**
	*	Helper function to parse filters into a useful filter array we can work with.
	*/
	private function ParseFilters($szFilters)
	{
		global $fields;

		if ( isset($szFilters) && strlen($szFilters) > 0 )
		{
//OLD		$tmpEntries = explode(" ", $szFilters);
			// Use RegEx for intelligent splitting
			$szFilterRgx = '/[\s]++(?=(?:(?:[^"]*+"){2})*+[^"]*+$)(?=(?:(?:[^\']*+\'){2})*+[^\']*+$)(?=(?:[^()]*+\([^()]*+\))*+[^()]*+$)/x';
			$tmpEntries = preg_split($szFilterRgx, $szFilters, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
//DEBUG			print_r (  $tmpEntries );

			foreach($tmpEntries as $myEntry) 
			{
				// Continue if empty filter!
				if ( strlen(trim($myEntry)) <= 0 ) 
					continue;

				if ( 
						($pos = strpos($myEntry, ":")) !== false 
							&&
						($pos > 0 && substr($myEntry, $pos-1,1) != '\\') /* Only if character before is no backslash! */
					)
				{
					// Split key and value
					$tmpArray = explode(":", $myEntry, 2);
//print_r (  $tmpArray );
					
					// Continue if empty filter!
					if ( strlen(trim($tmpArray[FILTER_TMP_VALUE])) == 0 ) 
						continue;

					// Check for multiple values!
					if ( strpos($tmpArray[FILTER_TMP_VALUE], ",") )
					{
						// Split by comma and fill tmp Value array
						$tmpValueArray = explode(",", $tmpArray[FILTER_TMP_VALUE]);
						foreach($tmpValueArray as $myValueEntry)
						{
							// Append to temp array
							$tmpValues[] = array( FILTER_TMP_MODE => $this->SetFilterIncludeMode($myValueEntry), FILTER_TMP_VALUE => $myValueEntry );
						}
					}

					// Handle filter based
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
									if ( !is_numeric($szValue[FILTER_TMP_VALUE]) )
									{
										$tmpFacilityCode = $this->ConvertFacilityString($szValue[FILTER_TMP_VALUE]);
										if ( $tmpFacilityCode != -1 ) 
											$tmpValues[$mykey][FILTER_TMP_VALUE] = $tmpFacilityCode;
									}
								}
							}
							else
							{
								// First set Filter Mode
								$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE],$tmpFilterType);

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
									if ( !is_numeric($szValue[FILTER_TMP_VALUE]) )
									{
										$tmpFacilityCode = $this->ConvertSeverityString($szValue[FILTER_TMP_VALUE]);
										if ( $tmpFacilityCode != -1 ) 
											$tmpValues[$mykey][FILTER_TMP_VALUE] = $tmpFacilityCode;
									}
								}
							}
							else
							{
								// First set Filter Mode
								$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE], $tmpFilterType);

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
									if ( !is_numeric($szValue[FILTER_TMP_VALUE]) )
									{
										$tmpMsgTypeCode = $this->ConvertMessageTypeString($szValue[FILTER_TMP_VALUE]);
										if ( $tmpMsgTypeCode != -1 ) 
											$tmpValues[$mykey][FILTER_TMP_VALUE] = $tmpMsgTypeCode;
									}
								}

/* OBSELETE CODE 
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									// First set Filter Mode
									$tmpValues[$mykey][FILTER_TMP_MODE] = $this->SetFilterIncludeMode($szValue);
								}
*/
							}
							else
							{
								// First set Filter Mode
								$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE], $tmpFilterType);

								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
								{
									$tmpMsgTypeCode = $this->ConvertMessageTypeString($tmpArray[FILTER_TMP_VALUE]);
									if ( $tmpMsgTypeCode != -1 ) 
										$tmpArray[FILTER_TMP_VALUE] = $tmpMsgTypeCode;
								}
							}
							// --- 
							break;
						/* BEGIN Eventlog based fields */
						case "eventid": 
							$tmpKeyName = SYSLOG_EVENT_ID; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
							// --- Extra numeric Check 
							if ( isset($tmpValues) ) 
							{
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									if ( is_numeric($szValue[FILTER_TMP_VALUE]) )
										$tmpValues[$mykey][FILTER_TMP_VALUE] = $szValue[FILTER_TMP_VALUE];
									else
										$tmpValues[$mykey][FILTER_TMP_VALUE] = "";
								}
							}
							else
							{
								// First set Filter Mode
								$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE], $tmpFilterType);

								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
									$tmpArray[FILTER_TMP_VALUE] = "";

							}
							// --- 
							break;
						case "eventcategory": 
							$tmpKeyName = SYSLOG_EVENT_CATEGORY; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
							// --- Extra numeric Check 
							if ( isset($tmpValues) ) 
							{
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									if ( is_numeric($szValue[FILTER_TMP_VALUE]) )
										$tmpValues[$mykey][FILTER_TMP_VALUE] = $szValue[FILTER_TMP_VALUE];
									else
										$tmpValues[$mykey][FILTER_TMP_VALUE] = "";
								}
							}
							else
							{
								// First set Filter Mode
								$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE], $tmpFilterType);

								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
									$tmpArray[FILTER_TMP_VALUE] = "";
							}
							// --- 
							break;
						case "eventlogtype": 
							$tmpKeyName = SYSLOG_EVENT_LOGTYPE; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case "eventlogsource": 
							$tmpKeyName = SYSLOG_EVENT_SOURCE; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case "eventuser": 
							$tmpKeyName = SYSLOG_EVENT_USER; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						/* END Eventlog based fields */
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
						case "timereported": 
							$tmpKeyName = SYSLOG_DATE; 
							$tmpFilterType = FILTER_TYPE_DATE;
							$tmpTimeMode = DATEMODE_RANGE_DATE; 
							break;
						case "processid": 
							$tmpKeyName = SYSLOG_PROCESSID; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						/* BEGIN WebLog based fields */
						case SYSLOG_WEBLOG_USER: 
							$tmpKeyName = SYSLOG_WEBLOG_USER; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case SYSLOG_WEBLOG_METHOD: 
							$tmpKeyName = SYSLOG_WEBLOG_METHOD; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case SYSLOG_WEBLOG_URL: 
							$tmpKeyName = SYSLOG_WEBLOG_URL; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;

						case SYSLOG_WEBLOG_QUERYSTRING: 
							$tmpKeyName = SYSLOG_WEBLOG_QUERYSTRING; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case SYSLOG_WEBLOG_PVER: 
							$tmpKeyName = SYSLOG_WEBLOG_PVER; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case SYSLOG_WEBLOG_STATUS: 
							$tmpKeyName = SYSLOG_WEBLOG_STATUS; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
							// --- Extra numeric Check 
							if ( isset($tmpValues) ) 
							{
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									if ( is_numeric($szValue[FILTER_TMP_VALUE]) )
										$tmpValues[$mykey][FILTER_TMP_VALUE] = $szValue[FILTER_TMP_VALUE];
									else
										$tmpValues[$mykey][FILTER_TMP_VALUE] = "";
								}
							}
							else
							{
								// First set Filter Mode
								$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE], $tmpFilterType);

								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
									$tmpArray[FILTER_TMP_VALUE] = "";
							}
							// --- 
							break;

						case SYSLOG_WEBLOG_BYTESSEND: 
							$tmpKeyName = SYSLOG_WEBLOG_BYTESSEND; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
							// --- Extra numeric Check 
							if ( isset($tmpValues) ) 
							{
								foreach( $tmpValues as $mykey => $szValue ) 
								{
									if ( is_numeric($szValue[FILTER_TMP_VALUE]) )
										$tmpValues[$mykey][FILTER_TMP_VALUE] = $szValue[FILTER_TMP_VALUE];
									else
										$tmpValues[$mykey][FILTER_TMP_VALUE] = "";
								}
							}
							else
							{
								// First set Filter Mode
								$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE], $tmpFilterType);

								if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
									$tmpArray[FILTER_TMP_VALUE] = "";
							}
							// --- 
							break;
						case SYSLOG_WEBLOG_REFERER: 
							$tmpKeyName = SYSLOG_WEBLOG_REFERER; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						case SYSLOG_WEBLOG_USERAGENT: 
							$tmpKeyName = SYSLOG_WEBLOG_USERAGENT; 
							$tmpFilterType = FILTER_TYPE_STRING;
							break;
						/* END WebLog based fields */
						default:
							// Custom Field, try to find field!
							$szSearchFilterKey = $tmpArray[FILTER_TMP_KEY]; 
							foreach ($fields as $aField)
							{
								if ($aField['SearchField'] == $szSearchFilterKey)
								{
									$tmpKeyName = $aField['FieldID'];
									break;
								}
							}
							if ( isset($fields[$tmpKeyName]) && isset($fields[$tmpKeyName]['SearchField']) )
							{
								$tmpFilterType = $fields[$tmpKeyName]['FieldType'];
								
								// Handle numeric fields!
								if ( $tmpFilterType == FILTER_TYPE_NUMBER )
								{
									// --- Extra numeric Check 
									if ( isset($tmpValues) ) 
									{
										foreach( $tmpValues as $mykey => $szValue ) 
										{
											if ( is_numeric($szValue[FILTER_TMP_VALUE]) )
												$tmpValues[$mykey][FILTER_TMP_VALUE] = $szValue[FILTER_TMP_VALUE];
											else
												$tmpValues[$mykey][FILTER_TMP_VALUE] = "";
										}
									}
									else
									{
										// First set Filter Mode
										$tmpArray[FILTER_TMP_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE], $tmpFilterType);

										if ( !is_numeric($tmpArray[FILTER_TMP_VALUE]) )
											$tmpArray[FILTER_TMP_VALUE] = "";
									}
									// --- 
								}
								// Nothing to do actually!
//								else if ( $tmpFilterType == FILTER_TYPE_STRING )
							}
							else
								// Unknown filter
								$tmpFilterType = FILTER_TYPE_UNKNOWN;
						//done!
					}

					// Add to detected filter array
					if ( $this->_arrFilterProperties == null || !in_array($tmpKeyName, $this->_arrFilterProperties) )
						$this->_arrFilterProperties[] = $tmpKeyName; 

					// Ignore if unknown filter!
					if ( $tmpFilterType != FILTER_TYPE_UNKNOWN ) 
					{
						// --- Set Filter!
						$this->_filters[$tmpKeyName][][FILTER_TYPE] = $tmpFilterType;
						$iNum = count($this->_filters[$tmpKeyName]) - 1;

						if		( isset($tmpTimeMode) )
						{
							$this->_filters[$tmpKeyName][$iNum][FILTER_DATEMODE] = $tmpTimeMode;
							$this->_filters[$tmpKeyName][$iNum][FILTER_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE]); // remove FilterMode characters from value
							$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = $tmpArray[FILTER_TMP_VALUE];
//echo $this->_filters[$tmpKeyName][$iNum][FILTER_VALUE]; 
//exit;
						}
						else if ( isset($tmpValues) ) 
						{
//print_r( $tmpValues );
							foreach( $tmpValues as $szValue )
							{
								// Continue if empty!
								if ( strlen($szValue[FILTER_TMP_VALUE]) == 0 ) 
									continue;

								if ( isset($this->_filters[$tmpKeyName][$iNum][FILTER_VALUE]) )
								{
									// Create new Filter!
									$this->_filters[$tmpKeyName][][FILTER_TYPE] = $tmpFilterType;
									$iNum = count($this->_filters[$tmpKeyName]) - 1;
								}

								// Set Filter Mode
								if ( isset($szValue[FILTER_TMP_MODE]) ) 
									$this->_filters[$tmpKeyName][$iNum][FILTER_MODE] = $szValue[FILTER_TMP_MODE];
								else
									$this->_filters[$tmpKeyName][$iNum][FILTER_MODE] = $this->SetFilterIncludeMode($szValue[FILTER_TMP_VALUE]);

								// Set Value
								$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = $szValue[FILTER_TMP_VALUE];
							}
						}
						else
						{
							// Set Filter Mode
							if ( isset($tmpArray[FILTER_TMP_MODE]) ) 
								$this->_filters[$tmpKeyName][$iNum][FILTER_MODE] = $tmpArray[FILTER_TMP_MODE];
							else
								$this->_filters[$tmpKeyName][$iNum][FILTER_MODE] = $this->SetFilterIncludeMode($tmpArray[FILTER_TMP_VALUE]);
							
							// Set Filter value!
							$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = $tmpArray[FILTER_TMP_VALUE];
						}

						// Reverse string prepareation
						$searchArray = array(
												'/(?<!\+)\+/',	// First one replaces all single + into spaces, but unfortunatelly replaces ONE + from a double ++ 
												'/ (?=\+)/',	// This is a helper, removes spaces if a + is following
//												'/\+\+/',		// Not needed, due the rules above, a double + has already become a single +
											);
						$replaceArray = array(
												" ", 
												"", 
//												"+", 
											);
						
//						$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = str_replace( '+', ' ', $this->_filters[$tmpKeyName][$iNum][FILTER_VALUE]);
						$this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] = preg_replace( $searchArray, $replaceArray, $this->_filters[$tmpKeyName][$iNum][FILTER_VALUE] ); 
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
					
					// Replace "\:" with ":", so we can filter with it ^^
					if ( strpos($myEntry, ":") !== false ) 
						$myEntry = str_replace("\\:", ":", $myEntry);

					// Check for Begin and Ending Quotes and remove them from the search value!
					$myEntry = preg_replace('/\\"/i', "$1", $myEntry);

					// Assign value to filter array
					$this->_filters[SYSLOG_MESSAGE][$iNum][FILTER_VALUE] = $myEntry; 
				}
			}
		}

		// Debug print
//		print_r ($this->_filters);
	}

	/*
	*	Helper function needed in SetFilterIncludeMode 
	*/
	private function SetFilterIncludeMode(&$szValue, $myFilterType = FILTER_TYPE_STRING) // Default = String!
	{
		// Init BIT!
		$myBits = FILTER_MODE_INCLUDE;

		// If Filter is Included 
		$pos = strpos($szValue, "+");
		if ( $pos !== false && $pos == 0 )
		{
			//trunscate +
			$szValue = substr( $szValue, 1);
			$myBits = FILTER_MODE_INCLUDE;
		}

		// If Filter is Excluded
		$pos = strpos($szValue, "-");
		if ( $pos !== false && $pos == 0 )
		{
			//trunscate -
			$szValue = substr( $szValue, 1);
			$myBits = FILTER_MODE_EXCLUDE;
		}

		// If Filter is a FULL text match!				
		$pos = strpos($szValue, "=");
		if ( $pos !== false && $pos == 0 )
		{
			//trunscate -
			$szValue = substr( $szValue, 1);

			// Add BIT if not NUMBER FIELD!
			if ( $myFilterType != FILTER_TYPE_NUMBER )
				$myBits |= FILTER_MODE_SEARCHFULL;
		}

		// If Filter is a REGEX match!				
		$pos = strpos($szValue, "~");
		if ( $pos !== false && $pos == 0 )
		{
			//trunscate -
			$szValue = substr( $szValue, 1);
			// Add BIT if not NUMBER FIELD!
			if ( $myFilterType != FILTER_TYPE_NUMBER )
				$myBits |= FILTER_MODE_SEARCHREGEX;
		}
		// --- 

		// return result 
		return $myBits;
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