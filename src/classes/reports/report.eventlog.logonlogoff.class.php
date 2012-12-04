<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* Logon/Logoff Report is a basic report for EventLog based Data
	*
	* \version 1.0.0 Init Version
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008-2011 Adiscon GmbH.
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

// --- Basic Includes!
require_once($gl_root_path . 'classes/reports/report.class.php');
// --- 

class Report_logonlogoff extends Report {
	// Common Properties
	public $_reportVersion = 1;										// Internally Version of the ReportEngine
	public $_reportID = "report.eventlog.logonlogoff.class";		// ID for the report, needs to be unique!
	public $_reportFileBasicName = "report.eventlog.logonlogoff";	// Basic Filename for reportfiles
	public $_reportTitle = "EventLog Logon/Logoff Report";			// Display name for the report
	public $_reportDescription = "This is a EventLog Logon/Logoff Summary Report";
	public $_reportHelpArticle = "http://loganalyzer.adiscon.com/plugins/reports/eventlog-logonlogoff";
	public $_reportNeedsInit = false;								// True means that this report needs additional init stuff
	public $_reportInitialized = false;								// True means report is installed

	// Advanced Report Options
	private $_maxHosts = 20;										// Threshold for maximum hosts to analyse!
	private $_maxLogOnLogOffsPerHost = 100;							// Threshold for maximum amount of logon/logoffs to analyse per host
	private $_colorThreshold = 10;									// Threshold for coloured display of Eventcounter

	// Constructor
	public function Report_logonlogoff() {
//		$this->_logStreamConfigObj = $streamConfigObj;

		// Fill fields we need for this report
		$this->_arrProperties[] = SYSLOG_UID;
		$this->_arrProperties[] = SYSLOG_DATE;
		$this->_arrProperties[] = SYSLOG_HOST;
		$this->_arrProperties[] = SYSLOG_MESSAGETYPE;
		$this->_arrProperties[] = SYSLOG_SEVERITY;
		$this->_arrProperties[] = SYSLOG_EVENT_ID;
		$this->_arrProperties[] = SYSLOG_EVENT_SOURCE;
		$this->_arrProperties[] = SYSLOG_EVENT_USER;
//		$this->_arrProperties[] = SYSLOG_MESSAGE;
		$this->_arrProperties[] = MISC_CHECKSUM;

		// Init Customfilters Array
		$this->_arrCustomFilters['_maxHosts'] = array (	'InternalID'	=> '_maxHosts', 
														'DisplayLangID'	=> 'ln_report_maxHosts_displayname', 
														'DescriptLangID'=> 'ln_report_maxHosts_description', 
														FILTER_TYPE		=> FILTER_TYPE_NUMBER, 
														'DefaultValue'	=> 20, 
														'MinValue'		=> 1,
/*														'MaxValue'		=> 0,*/
												); 
		$this->_arrCustomFilters['_maxLogOnLogOffsPerHost'] = 
												array (	'InternalID'	=> '_maxLogOnLogOffsPerHost', 
														'DisplayLangID'	=> 'ln_report_maxLogOnLogOffsPerHost_displayname', 
														'DescriptLangID'=> 'ln_report_maxLogOnLogOffsPerHost_description', 
														FILTER_TYPE		=> FILTER_TYPE_NUMBER, 
														'DefaultValue'	=> 100, 
														'MinValue'		=> 1,
/*														'MaxValue'		=> 0,*/
												); 
		$this->_arrCustomFilters['_colorThreshold'] = 
												array (	'InternalID'	=> '_colorThreshold', 
														'DisplayLangID'	=> 'ln_report_colorThreshold_displayname', 
														'DescriptLangID'=> 'ln_report_colorThreshold_description', 
														FILTER_TYPE		=> FILTER_TYPE_NUMBER, 
														'DefaultValue'	=> 10, 
														'MinValue'		=> 1,
/*														'MaxValue'		=> 0,*/
												); 

		

	}

	/**
	* startDataProcessing, analysing data
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function startDataProcessing()
	{
		global $content, $severity_colors, $gl_starttime, $fields; 

		// Create Filter string, append filter for EventLog Type msgs!
		$szFilters =	$this->_filterString . " " . 
						$fields[SYSLOG_MESSAGETYPE]['SearchField'] . ":=" . IUT_NT_EventReport . ",=" . IUT_WEVTMONV2 . " ";	/* Include EventLog v1 and v2 */

		// Set Filter string
		$this->_streamObj->SetFilter( $szFilters );

		// Need to Open stream first!
		$res = $this->_streamObj->Open( $this->_arrProperties, true );
		if ( $res == SUCCESS )
		{
			// Set to common content variables
			$this->SetCommonContentVariables();

			// Set report specific content variables
			$content["_colorThreshold"] = $this->_colorThreshold;

			// --- Report logic starts here
			$content["report_rendertime"] = "";

			// Step 1: Gather Summaries 
			// Obtain data from the logstream!
			$content["report_summary"] = $this->_streamObj->ConsolidateDataByField( SYSLOG_HOST, 0, SYSLOG_HOST, SORTING_ORDER_DESC, null, false );

			// TimeStats
			$nowtime = microtime_float();
			$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s, ";

			// If data is valid, we have an array!
			if ( is_array($content["report_summary"]) && count($content["report_summary"]) > 0 )
			{
				// Count Total Events
				$iTotalEvents = 0;

				foreach ($content["report_summary"] as &$tmpReportData )
				{
					$tmpReportData['DisplayName'] = $tmpReportData[SYSLOG_HOST];
					$tmpReportData['bgcolor'] = "#BBBBBB"; // $severity_colors[ $tmpReportData[SYSLOG_SEVERITY] ];

					$iTotalEvents += $tmpReportData['itemcount']; 
				}

				// Prepent Item with totalevents count
				$totalItem['DisplayName'] = "Total Events"; 
				$totalItem['bgcolor'] = "#999999";
				$totalItem['itemcount'] = $iTotalEvents; 

				// Prepent to array
				array_unshift( $content["report_summary"], $totalItem );
			}
			else
				return ERROR_REPORT_NODATA; 

			// This function will consolidate the Events based per Host!
			$this->ConsolidateLogonLogoffs(); // ($arrHosts);

			// TimeStats
			$nowtime = microtime_float();
			$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s ";
			// ---
		}
		else
			return $ret;
		
		// Return success!
		return SUCCESS;
	}


	/**
	* InitReport, empty
	*
	*/
	public function InitReport()
	{
		// Nothing to do
		return SUCCESS;
	}


	/**
	* RemoveReport, empty
	*
	*/
	public function RemoveReport()
	{
		// Nothing to do
		return SUCCESS;
	}


	/**
	* validateLicense, check license code
	*
	*/
	public function validateLicense()
	{
		// This is a free report!
		return SUCCESS;
	}

	/**
	* Init advanced settings from _customFilters string
	*/
	public function InitAdvancedSettings()
	{
		// Parse and Split _customFilters
		if ( strlen($this->_customFilters) > 0 ) 
		{
			// First of all split by comma
			$tmpFilterValues = explode( ",", $this->_customFilters );
		
			//Loop through mappings
			foreach ($tmpFilterValues as &$myFilterValue )
			{
				// Split subvalues
				$tmpArray = explode( "=>", $myFilterValue );
				
				// Set into temporary array
				$tmpfilterid = trim($tmpArray[0]);
				
				// Set advanced property
				if ( isset($this->_arrCustomFilters[$tmpfilterid]) ) 
				{
					// Copy New value first!
					$szNewVal = trim($tmpArray[1]);

					// Negated logic
					if ( 
							$this->_arrCustomFilters[$tmpfilterid][FILTER_TYPE] == FILTER_TYPE_NUMBER && 
							!(isset($this->_arrCustomFilters[$tmpfilterid]['MinValue']) && intval($szNewVal) < $this->_arrCustomFilters[$tmpfilterid]['MinValue']) && 
							!(isset($this->_arrCustomFilters[$tmpfilterid]['MaxValue']) && intval($szNewVal) >= $this->_arrCustomFilters[$tmpfilterid]['MaxValue']) 
						) 
					{
						if ( $tmpfilterid == '_maxHosts' ) 
							$this->_maxHosts = intval($szNewVal); 
						else if ( $tmpfilterid == '_maxLogOnLogOffsPerHost' ) 
							$this->_maxLogOnLogOffsPerHost = intval($szNewVal); 
						else if ( $tmpfilterid == '_colorThreshold' ) 
							$this->_colorThreshold = intval($szNewVal); 
					}
					else
					{
						// Write to debuglog
						OutputDebugMessage("Failed setting advanced report option property '" . $tmpfilterid . "', value not in value range!", DEBUG_ERROR);
					}
				}
			}
		}
	}


	/*
	* Implementation of CheckLogStreamSource
	*/
	public function CheckLogStreamSource( $mySourceID )
	{
		// Call basic report Check function 
		$res = $this->CheckLogStreamSourceByPropertyArray( $mySourceID, array(SYSLOG_HOST, MISC_CHECKSUM, SYSLOG_DATE, SYSLOG_EVENT_ID, SYSLOG_MESSAGETYPE), null );

		// return results!
		return $res;
	}


	/*
	* Implementation of CreateLogStreamIndexes | Will create missing INDEXES
	*/
	public function CreateLogStreamIndexes( $mySourceID )
	{
		// Call basic report Check function 
		$res = $this->CreateLogStreamIndexesByPropertyArray( $mySourceID, array(SYSLOG_HOST, MISC_CHECKSUM, SYSLOG_DATE, SYSLOG_EVENT_ID, SYSLOG_MESSAGETYPE) );

		// return results!
		return $res;
	}


	/*
	* Implementation of CreateLogStreamIndexes | Will create missing TRIGGER
	*/
	public function CreateLogStreamTrigger( $mySourceID )
	{
		// Dummy return SUCCESS!
		return SUCCESS;
	}


	// --- Private functions...
	/**
	*	Helper function to consolidate events 
	*/
	private function ConsolidateLogonLogoffs() // ( $arrHosts )
	{
		global $content, $gl_starttime, $fields; 

		// Now open the stream for data processing
		$res = $this->_streamObj->Open( $this->_arrProperties, true );
		if ( $res == SUCCESS )
		{
			// --- New Method to consolidate data!
			// TimeStats
			$nowtime = microtime_float();
			$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s ";

			// Update all Checksums first!
//not needed			$this->_streamObj->UpdateAllMessageChecksum(); 

			// TimeStats
			$nowtime = microtime_float();
			$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s ";

			// Get all LOGON Data
			// Set custom filters
			$this->_streamObj->ResetFilters();
			$this->_streamObj->SetFilter( 
				$this->_filterString . " " . 
				$fields[SYSLOG_MESSAGETYPE]['SearchField'] . ":=" . IUT_NT_EventReport . ",=" . IUT_WEVTMONV2 . " " . 
				$fields[SYSLOG_EVENT_ID]['SearchField'] . ":=528,4624" ); /* Include EventIDs for new and old Eventlog API*/
			$content["report_consdata"]['logon']['cons_events'] = $this->_streamObj->ConsolidateDataByField( SYSLOG_EVENT_USER, $this->_maxLogOnLogOffsPerHost, SYSLOG_EVENT_USER, SORTING_ORDER_DESC, null, true, true );
			foreach ( $content["report_consdata"]['logon']['cons_events'] as &$myConsData )
			{
				// Set Basic data entries
				if (!isset( $content['filter_severity_list'][$myConsData[SYSLOG_SEVERITY]] )) 
					$myConsData[SYSLOG_SEVERITY] = SYSLOG_NOTICE; // Set default in this case
			}
			// Set Basic properties
			$content["report_consdata"]['logon']['DataCaption'] = "Logon Events"; 


			// Get all LOGOFF Data
			// Set custom filters
			$this->_streamObj->ResetFilters();
			$this->_streamObj->SetFilter( 
				$this->_filterString . " " . 
				$fields[SYSLOG_MESSAGETYPE]['SearchField'] . ":=" . IUT_NT_EventReport . ",=" . IUT_WEVTMONV2 . " " . 
				$fields[SYSLOG_EVENT_ID]['SearchField'] . ":=538,4634" ); /* Include EventIDs for new and old Eventlog API*/
			$content["report_consdata"]['logoff']['cons_events'] = $this->_streamObj->ConsolidateDataByField( SYSLOG_EVENT_USER, $this->_maxLogOnLogOffsPerHost, SYSLOG_EVENT_USER, SORTING_ORDER_DESC, null, true, true );
			foreach ( $content["report_consdata"]['logoff']['cons_events'] as &$myConsData )
			{
				// Set Basic data entries
				if (!isset( $content['filter_severity_list'][$myConsData[SYSLOG_SEVERITY]] )) 
					$myConsData[SYSLOG_SEVERITY] = SYSLOG_NOTICE; // Set default in this case
			}
			// Set Basic properties
			$content["report_consdata"]['logoff']['DataCaption'] = "Logoff Events"; 

/*			foreach ( $arrHosts as $myHost ) 
			{
				// Set custom filters
				$this->_streamObj->ResetFilters();
				$this->_streamObj->SetFilter( $this->_filterString . " " . $fields[SYSLOG_MESSAGETYPE]['SearchField'] . ":=" . IUT_NT_EventReport . ",=" . IUT_WEVTMONV2 . " " . $fields[SYSLOG_HOST]['SearchField'] . ":=" . $myHost );

				// Set Host Item Basics if not set yet
				$content["report_consdata"][ $myHost ][SYSLOG_HOST] = $myHost; 

				// Get Data for single host
				$content["report_consdata"][ $myHost ]['cons_events'] = $this->_streamObj->ConsolidateDataByField( SYSLOG_EVENT_ID, $this->_maxLogOnLogOffsPerHost, SYSLOG_EVENT_USER, SORTING_ORDER_DESC, null, true, true );
				//print_r ($fields[SYSLOG_MESSAGE]);
				foreach ( $content["report_consdata"][ $myHost ]['cons_events'] as &$myConsData )
				{
					// Set Basic data entries
					if (!isset( $content['filter_severity_list'][$myConsData[SYSLOG_SEVERITY]] )) 
						$myConsData[SYSLOG_SEVERITY] = SYSLOG_NOTICE; // Set default in this case
				}
			}
*/

			// TimeStats
			$nowtime = microtime_float();
			$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s ";
			// ---


			// Start Postprocessing
			foreach( $content["report_consdata"] as &$tmpConsolidatedData ) 
			{
				// First use callback function to sort array
				uasort($tmpConsolidatedData['cons_events'], "MultiSortArrayByItemCountDesc");

/*
				// Remove entries according to _maxLogOnLogOffsPerHost
				if ( count($tmpConsolidatedComputer['cons_events']) > $this->_maxLogOnLogOffsPerHost )
				{
					$iDropCount = 0;

					do
					{
						array_pop($tmpConsolidatedComputer['cons_events']);
						$iDropCount++; 
					} while ( count($tmpConsolidatedComputer['cons_events']) > $this->_maxLogOnLogOffsPerHost ); 
					
					// Append a dummy entry which shows count of all other events
					if ( $iDropCount > 0 ) 
					{
						$lastEntry[SYSLOG_SEVERITY] = SYSLOG_NOTICE; 
						$lastEntry[SYSLOG_EVENT_ID] = "-"; 
						$lastEntry[SYSLOG_EVENT_SOURCE] = $content['LN_GEN_ALL_OTHER_EVENTS']; 
						$lastEntry[SYSLOG_MESSAGE] = $content['LN_GEN_ALL_OTHER_EVENTS']; 
						$lastEntry['itemcount'] = $iDropCount; 
						$lastEntry['FirstEvent_Date'] = "-"; 
						$lastEntry['LastEvent_Date'] = "-";

						$tmpConsolidatedComputer['cons_events'][] = $lastEntry; 
					}
				}
*/
				// TimeStats
				$nowtime = microtime_float();
				$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s ";

				// PostProcess Events!
				foreach( $tmpConsolidatedData["cons_events"]  as &$tmpMyEvent ) 
				{
					$tmpMyEvent['FirstEvent_Date_Formatted'] = GetFormatedDate( $tmpMyEvent['firstoccurrence_date'] );
					$tmpMyEvent['LastEvent_Date_Formatted'] = GetFormatedDate( $tmpMyEvent['lastoccurrence_date'] );
					$tmpMyEvent['syslogseverity_text'] = $content['filter_severity_list'][ $tmpMyEvent['syslogseverity'] ]["DisplayName"]; 
					$tmpMyEvent['syslogseverity_bgcolor'] = $this->GetSeverityBGColor($tmpMyEvent['syslogseverity']); 
				}
			}
			// --- 
		}

		// Work done!
		return SUCCESS;
	}

	/*
	*	Helper function to obtain Severity background color
	*/
	private function GetSeverityBGColor( $nSeverity )
	{
		global $severity_colors;

		if ( isset( $severity_colors[$nSeverity] ) ) 
			return $severity_colors[$nSeverity]; 
		else
			return $severity_colors[SYSLOG_INFO]; //Default
	}

}

?>