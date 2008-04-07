<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* LogStream provides access to the log data. Be sure to always		*
	* use LogStream if you want to access a text file or database.		*
	*																	*
	* All directives are explained within this file						*
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
	public abstract function ReadNext(&$uID, &$arrProperitesOut);

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
	* Set the direction the stream should read data.
	*
	* @param enumReadDirectionfilter EnumReadDirection in: The new direction.
	* @return integer Error state
	*/
	public abstract function SetReadDirection($enumReadDirection);
	
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
	* Provides a list of properties which the stream is able to sort for.
	*
	* @return array List of properties. Null if the stream is not sortable.
	*/
	public abstract function GetSortOrderProperties();

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
							break;
						case "severity": 
							$tmpKeyName = SYSLOG_SEVERITY; 
							$tmpFilterType = FILTER_TYPE_NUMBER;
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
							echo "WTF - Unknown filter";
							break;
							// Unknown filter
					}

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

	/**
	*	Helper function to parse filters into a useful filter array we can work with.
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
									if ( $myfilter[FILTER_MODE] == FILTER_MODE_INCLUDE ) 
									{
										if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) === false ) 
											$bEval = false;
									}
									// Exclude Filter
									else if ( $myfilter[FILTER_MODE] == FILTER_MODE_EXCLUDE ) 
									{
										if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) !== false ) 
											$bEval = false;
									}
								}
								// Otherwise we use OR Handling!
								else
								{
									$bIsOrFilter = true; // Set isOrFilter to true 
									if ( stripos($propertyvalue, $myfilter[FILTER_VALUE]) !== false ) 
										$bOrFilter = true;
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


}

?>