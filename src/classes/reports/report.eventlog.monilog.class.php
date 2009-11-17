<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* Monilog Report is a basic report for EventLog and Syslog data
	*
	* \version 1.0.0 Init Version
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008-2009 Adiscon GmbH.
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

// --- Basic Includes!
require_once($gl_root_path . 'classes/reports/report.class.php');
// --- 

class Report_monilog extends Report {
	// Common Properties
	public $_reportVersion = 1;									// Internally Version of the ReportEngine
	public $_reportID = "report.eventlog.monilog.class";		// ID for the report, needs to be unique!
	public $_reportFileBasicName = "report.eventlog.monilog";	// Basic Filename for reportfiles
	public $_reportTitle = "EventLog Summary Report";			// Display name for the report
	public $_reportDescription = "This is a EventLog Summary Report based on Monilog";
	public $_reportHelpArticle = "";
	public $_reportNeedsInit = false;							// True means that this report needs additional init stuff
	public $_reportInitialized = false;							// True means report is installed

	// Advanced Report Options
	private $_maxHosts = 20;									// Threshold for maximum hosts to analyse!
	private $_maxEventsPerHost = 100;							// Threshold for maximum amount of events to analyse per host
/*	private $_currentOffset = -1;
	private $_currentStartPos = -1;
	private $_fp = null;
	private $_bEOS = false;

	const _BUFFER_length = 8192;
	private $_buffer = false;
	private $_buffer_length = 0;
	private $_p_buffer = -1;
*/

	// Constructor
	public function Report_monilog() {
//		$this->_logStreamConfigObj = $streamConfigObj;

		// Fill fields we need for this report
		$this->_arrProperties[] = SYSLOG_UID;
		$this->_arrProperties[] = SYSLOG_DATE;
		$this->_arrProperties[] = SYSLOG_HOST;
//		$this->_arrProperties[] = SYSLOG_MESSAGETYPE;
//		$this->_arrProperties[] = SYSLOG_FACILITY;
		$this->_arrProperties[] = SYSLOG_SEVERITY;
		$this->_arrProperties[] = SYSLOG_EVENT_ID;
//		$this->_arrProperties[] = SYSLOG_EVENT_LOGTYPE;
		$this->_arrProperties[] = SYSLOG_EVENT_SOURCE;
//		$this->_arrProperties[] = SYSLOG_EVENT_CATEGORY;
//		$this->_arrProperties[] = SYSLOG_EVENT_USER;
		$this->_arrProperties[] = SYSLOG_MESSAGE;
		$this->_arrProperties[] = MISC_CHECKSUM;

	}

	/**
	* startDataProcessing, analysing data
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function startDataProcessing()
	{
		global $content, $severity_colors, $gl_starttime; 

		// Set Filter string
		$this->_streamObj->SetFilter( $this->_filterString );

		// Need to Open stream first!
		$res = $this->_streamObj->Open( $this->_arrProperties, true );
		if ( $res == SUCCESS )
		{
//
//		// Verify Datasource first!
//		if ( $this->verifyDataSource() == SUCCESS ) 
//		{
			// Get Settings and set to global content variable 
			$content["report_title"] = $this->GetCustomTitle();
			$content["report_comment"] = $this->GetCustomComment();
			$content["report_version"] = $this->GetReportVersion();

			// --- Report logic starts here
			$content["report_rendertime"] = "";
			

			// Step 1: Gather Summaries 
			// Obtain data from the logstream!
			$content["report_summary"] = $this->_streamObj->ConsolidateDataByField( SYSLOG_SEVERITY, 0, SYSLOG_SEVERITY, SORTING_ORDER_DESC, null, false );

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
					$tmpReportData['DisplayName'] = GetSeverityDisplayName( $tmpReportData[SYSLOG_SEVERITY] );
					$tmpReportData['bgcolor'] = $severity_colors[ $tmpReportData[SYSLOG_SEVERITY] ];

					$iTotalEvents += $tmpReportData['ItemCount']; 
				}

				// Prepent Item with totalevents count
				$totalItem['DisplayName'] = "Total Events"; 
				$totalItem['bgcolor'] = "999999";
				$totalItem['ItemCount'] = $iTotalEvents; 

				// Prepent to array
				array_unshift( $content["report_summary"], $totalItem );
			}

			// Get List of hosts
			$content["report_computers"] = $this->_streamObj->ConsolidateItemListByField( SYSLOG_HOST, $this->_maxHosts, SYSLOG_HOST, SORTING_ORDER_DESC );

			// TimeStats
			$nowtime = microtime_float();
			$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s, ";

			// Create plain hosts list for Consolidate function
			foreach ( $content["report_computers"] as $tmpComputer ) 
				$arrHosts[] = $tmpComputer[SYSLOG_HOST]; 

			// This function will consolidate the Events based per Host!
			$this->ConsolidateEventsPerHost($arrHosts);

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
		// Nothing todo
		return SUCCESS;
	}


	/**
	* RemoveReport, empty
	*
	*/
	public function RemoveReport()
	{
		// Nothing todo
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


	// --- Private functions...


	/**
	*	Helper function to consolidate events 
	*/
	private function ConsolidateEventsPerHost( $arrHosts )
	{
		global $content; 
		
		// Set Filter string
//		$this->_streamObj->SetFilter( $this->_filterString );

		// Now open the stream for data processing
		$res = $this->_streamObj->Open( $this->_arrProperties, true );
		if ( $res == SUCCESS )
		{
			// Set reading direction
			$this->_streamObj->SetReadDirection( EnumReadDirection::Backward );

			// Init uid helper
			$uID = UID_UNKNOWN;
			
			// Start reading data
			$ret = $this->_streamObj->Read($uID, $logArray);
			
			// Found first data record
			if ( $ret == SUCCESS )
			{
				do
				{
					// Check if Event from host is in our hosts array
					if ( in_array($logArray[SYSLOG_HOST], $arrHosts) ) 
					{
						// Set Host Item Basics if not set yet
						if ( !isset($content["report_consdata"][ $logArray[SYSLOG_HOST] ][SYSLOG_HOST]) )
						{
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ][SYSLOG_HOST] = $logArray[SYSLOG_HOST]; 
						}

						// Calc checksum
						if ( !isset($logArray[MISC_CHECKSUM]) || $logArray[MISC_CHECKSUM] == 0 ) 
						{
							// Calc crc32 from message, we use this as index
							$logArray[MISC_CHECKSUM] = crc32( $logArray[SYSLOG_MESSAGE] ); 
							$strChecksum = $logArray[MISC_CHECKSUM];
						}

						// Check if entry exists in result array
						if ( isset($content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]) ) 
						{
							// Increment counter and set First/Last Event date
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['ItemCount']++; 
							
							// Set FirstEvent date if necessary!
							if ( $logArray[SYSLOG_DATE][EVTIME_TIMESTAMP] < $content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['FirstEvent_Date'][EVTIME_TIMESTAMP] ) 
								$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['FirstEvent_Date'] = $logArray[SYSLOG_DATE];

							// Set LastEvent date if necessary!
							if ( $logArray[SYSLOG_DATE][EVTIME_TIMESTAMP] > $content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['LastEvent_Date'][EVTIME_TIMESTAMP] ) 
								$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['LastEvent_Date'] = $logArray[SYSLOG_DATE];
						}
						else
						{
							// Set Basic data entries
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ][SYSLOG_SEVERITY] = $logArray[SYSLOG_SEVERITY]; 
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ][SYSLOG_EVENT_ID] = $logArray[SYSLOG_EVENT_ID]; 
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ][SYSLOG_EVENT_SOURCE] = $logArray[SYSLOG_EVENT_SOURCE]; 
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ][SYSLOG_MESSAGE] = $logArray[SYSLOG_MESSAGE]; 

							// Set Counter and First/Last Event date
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['ItemCount'] = 1; 
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['FirstEvent_Date'] = $logArray[SYSLOG_DATE]; 
							$content["report_consdata"][ $logArray[SYSLOG_HOST] ]['cons_events'][ $strChecksum ]['LastEvent_Date'] = $logArray[SYSLOG_DATE];
						}

					}
					
					// Get next data record
					$ret = $this->_streamObj->ReadNext($uID, $logArray);

				} while ( $ret == SUCCESS );

				// Start Postprocessing
				foreach( $content["report_consdata"] as &$tmpConsolidatedComputer ) 
				{
					// First use callback function to sort array
					uasort($tmpConsolidatedComputer['cons_events'], "MultiSortArrayByItemCountDesc");
					
					// Remove entries according to _maxEventsPerHost
					if ( count($tmpConsolidatedComputer['cons_events']) > $this->_maxEventsPerHost )
					{
						do
						{
							array_pop($tmpConsolidatedComputer['cons_events']);
						} while ( count($tmpConsolidatedComputer['cons_events']) > $this->_maxEventsPerHost ); 
					}


					// PostProcess Events!
					foreach( $tmpConsolidatedComputer["cons_events"] as &$tmpMyEvent ) 
					{
						$tmpMyEvent['FirstEvent_Date_Formatted'] = GetFormatedDate( $tmpMyEvent['FirstEvent_Date'] );
						$tmpMyEvent['LastEvent_Date_Formatted'] = GetFormatedDate( $tmpMyEvent['LastEvent_Date'] );
						$tmpMyEvent['syslogseverity_text'] = $content['filter_severity_list'][ $tmpMyEvent['syslogseverity'] ]["DisplayName"]; 
					}


				}

			}
			else
				return $ret;
		}

		// Work done!
		return SUCCESS;
	}
/*
	private function ResetBuffer() {
		$this->_bEOS = false;
		$this->_buffer = false;
		$this->_buffer_length = 0;
		$this->_p_buffer = -1;
	}
*/
}


	/**
	*	Helper function for multisorting multidimensional arrays
	*/
	function MultiSortArrayByItemCountDesc( $arrayFirst, $arraySecond )
	{
		// Do not sort in this case
		if ($arrayFirst['ItemCount'] == $arraySecond['ItemCount'])
			return 0;
		
		// Move up or down
		return ($arrayFirst['ItemCount'] < $arraySecond['ItemCount']) ? 1 : -1;
	}

	/**
	*	Helper function for multisorting multidimensional arrays
	*/
	function MultiSortArrayByItemCountAsc( $arrayFirst, $arraySecond )
	{
		// Do not sort in this case
		if ($arrayFirst['ItemCount'] == $arraySecond['ItemCount'])
			return 0;
		
		// Move up or down
		return ($arrayFirst['ItemCount'] < $arraySecond['ItemCount']) ? -1 : 1;
	}


?>