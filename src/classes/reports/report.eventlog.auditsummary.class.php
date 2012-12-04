<?php
/*
	-----------------------------------------------------------------
	LogAnalyzer - http://loganalyzer.adiscon.com
	-----------------------------------------------------------------
	SOX Audit Report is compliant with Sarbanes-Oxley (SOX) Act, 2002 
	and based on EventLog Data
	
	Version 1.0.0 Init Version
															
	All directives are explained within this file	
	-----------------------------------------------------------------
	Copyright (c) 2012, Adiscon GmbH. All rights reserved.

	No fee is required for NON-COMMERCIAL use; however, commercial use is not permitted under this license.

	Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

		* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
		* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
		* Any redistribution, use, or modification is licensed solely for  NON-COMMERCIAL purposes. Commercial use requires a commercial license. If in doubt, commercial use is any use by a for-profit or government organization.  Non-commercial use is assumed for personal use and use in public schools and universities and tax-exempt charities. If in doubt, inquire at info@adiscon.com with a description of your intended use.
	 
	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	-----------------------------------------------------------------
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

class Report_auditsummary extends Report {
	// Common Properties
	public $_reportVersion = 1;										// Internally Version of the ReportEngine
	public $_reportID = "report.eventlog.auditsummary.class";		// ID for the report, needs to be unique!
	public $_reportFileBasicName = "report.eventlog.auditsummary";	// Basic Filename for reportfiles
	public $_reportTitle = "EventLog Audit Summary Report";			// Display name for the report
	public $_reportDescription = "This is a EventLog Audit Summary Report";
	public $_reportHelpArticle = "http://loganalyzer.adiscon.com/plugins/reports/eventlog-auditsummary";
	public $_reportNeedsInit = false;								// True means that this report needs additional init stuff
	public $_reportInitialized = false;								// True means report is installed

	// Advanced Report Options
	private $_maxHosts = 20;									// Threshold for maximum hosts to analyse!
	private $_maxauditsummarysPerHost = 100;					// Threshold for maximum amount of logon/logoffs to analyse per host
	private $_colorThreshold = 10;								// Threshold for coloured display of Eventcounter
	private $_events_logon = 1;									// Enable analysis of Logon Events
	private $_events_logoff = 1;								// Enable analysis of Logoff Events
	private $_events_logonfail = 1;								// Enable analysis of Logon failures Events
	private $_events_policychangeevents = 1;					// Enable analysis of Audit policy changes
	private $_events_objectaccess = 1;							// Enable analysis of Object access Events
	private $_events_systemevents = 1;							// Enable analysis of System Events
	private $_events_hostsessionevents = 1;						// Enable analysis of Host session Events 
	private $_events_useraccchangeevents = 1;					// Enable analysis of User Account changes
	private $_events_auditpolicychangesevents = 1;				// Enable analysis of Audit policiy changes Events
	private $_events_useractions = 1;							// Enable analysis of individual User actions
	private $_events_hostactions = 1;							// Enable analysis of individual Host actions

	// Constructor
	public function Report_auditsummary() {
//		$this->_logStreamConfigObj = $streamConfigObj;

		// Fill fields we need for this report
		$this->_arrProperties[] = SYSLOG_UID;
		$this->_arrProperties[] = SYSLOG_DATE;
		$this->_arrProperties[] = SYSLOG_HOST;
		$this->_arrProperties[] = SYSLOG_MESSAGETYPE;
		$this->_arrProperties[] = SYSLOG_SEVERITY;
		$this->_arrProperties[] = SYSLOG_EVENT_ID;
		$this->_arrProperties[] = SYSLOG_EVENT_LOGTYPE;
		$this->_arrProperties[] = SYSLOG_EVENT_SOURCE;
		$this->_arrProperties[] = SYSLOG_EVENT_USER;
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
		$this->_arrCustomFilters['_maxauditsummarysPerHost'] = 
												array (	'InternalID'	=> '_maxauditsummarysPerHost', 
														'DisplayLangID'	=> 'ln_report_maxAuditEventsPerHost_displayname', 
														'DescriptLangID'=> 'ln_report_maxAuditEventsPerHost_description', 
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
		
		/* Boolean Options */ 
		$this->_arrCustomFilters['_events_logon'] = 
												array (	'InternalID'	=> '_events_logon', 
														'DisplayLangID'	=> 'ln_report_events_logon_displayname', 
														'DescriptLangID'=> 'ln_report_events_logon_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_logoff'] = 
												array (	'InternalID'	=> '_events_logoff', 
														'DisplayLangID'	=> 'ln_report_events_logoff_displayname', 
														'DescriptLangID'=> 'ln_report_events_logoff_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_logonfail'] = 
												array (	'InternalID'	=> '_events_logonfail', 
														'DisplayLangID'	=> 'ln_report_events_logonfail_displayname', 
														'DescriptLangID'=> 'ln_report_events_logonfail_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_policychangeevents'] = 
												array (	'InternalID'	=> '_events_policychangeevents', 
														'DisplayLangID'	=> 'ln_report_events_policychangeevents_displayname', 
														'DescriptLangID'=> 'ln_report_events_policychangeevents_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_objectaccess'] = 
												array (	'InternalID'	=> '_events_objectaccess', 
														'DisplayLangID'	=> 'ln_report_events_objectaccess_displayname', 
														'DescriptLangID'=> 'ln_report_events_objectaccess_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_systemevents'] = 
												array (	'InternalID'	=> '_events_systemevents', 
														'DisplayLangID'	=> 'ln_report_events_systemevents_displayname', 
														'DescriptLangID'=> 'ln_report_events_systemevents_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_hostsessionevents'] = 
												array (	'InternalID'	=> '_events_hostsessionevents', 
														'DisplayLangID'	=> 'ln_report_events_hostsessionevents_displayname', 
														'DescriptLangID'=> 'ln_report_events_hostsessionevents_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_useraccchangeevents'] = 
												array (	'InternalID'	=> '_events_useraccchangeevents', 
														'DisplayLangID'	=> 'ln_report_events_useraccchangeevents_displayname', 
														'DescriptLangID'=> 'ln_report_events_useraccchangeevents_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_auditpolicychangesevents'] = 
												array (	'InternalID'	=> '_events_auditpolicychangesevents', 
														'DisplayLangID'	=> 'ln_report_events_auditpolicychangesevents_displayname', 
														'DescriptLangID'=> 'ln_report_events_auditpolicychangesevents_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_useractions'] = 
												array (	'InternalID'	=> '_events_useractions', 
														'DisplayLangID'	=> 'ln_report_events_useractions_displayname', 
														'DescriptLangID'=> 'ln_report_events_useractions_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
												); 
		$this->_arrCustomFilters['_events_hostactions'] = 
												array (	'InternalID'	=> '_events_hostactions', 
														'DisplayLangID'	=> 'ln_report_events_hostactions_displayname', 
														'DescriptLangID'=> 'ln_report_events_hostactions_description', 
														FILTER_TYPE		=> FILTER_TYPE_BOOL, 
														'DefaultValue'	=> 1, 
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
			$this->Consolidateauditsummarys(); // ($arrHosts);

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
		//TODO Implement License check



		return ERROR;
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
						else if ( $tmpfilterid == '_maxauditsummarysPerHost' ) 
							$this->_maxauditsummarysPerHost = intval($szNewVal); 
						else if ( $tmpfilterid == '_colorThreshold' ) 
							$this->_colorThreshold = intval($szNewVal); 
					}
					else if ( $this->_arrCustomFilters[$tmpfilterid][FILTER_TYPE] == FILTER_TYPE_BOOL )
					{ 
						if ( $tmpfilterid == '_events_logon' ) 
							$this->_events_logon = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_logoff' ) 
							$this->_events_logoff = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_logonfail' ) 
							$this->_events_logonfail = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_policychangeevents' ) 
							$this->_events_policychangeevents = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_objectaccess' ) 
							$this->_events_objectaccess = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_systemevents' ) 
							$this->_events_systemevents = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_hostsessionevents' ) 
							$this->_events_hostsessionevents = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_useraccchangeevents' ) 
							$this->_events_useraccchangeevents = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_auditpolicychangesevents' ) 
							$this->_events_auditpolicychangesevents = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_useractions' ) 
							$this->_events_useractions = intval($szNewVal); 
						else if ( $tmpfilterid == '_events_hostactions' ) 
							$this->_events_hostactions = intval($szNewVal); 
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
	private function Consolidateauditsummarys() // ( $arrHosts )
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

			// --- Process Logons
			if ( $this->_events_logon == 1 ) 
			{
				$content["report_consdata"]["logon"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("528,4624" /* Include EventIDs for new and old Eventlog API*/, "ln_report_logonevents" /* Logon Events */); 
				$content["report_consdata"]["logon"]['DataCaption'] = $content["ln_report_logonevents"]; 
				$content["report_consdata"]["logon"]['cons_count'] = count($content["report_consdata"]["logon"]['cons_events']); 
			}
			// ---

			// --- Process Logoffs
			if ( $this->_events_logoff == 1 ) 
			{
				$content["report_consdata"]["logoff"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("538,4634" /* Include EventIDs for new and old Eventlog API*/, "ln_report_logoffevents" /* Logoff Events */); 
				$content["report_consdata"]["logoff"]['DataCaption'] = $content["ln_report_logoffevents"]; 
				$content["report_consdata"]["logoff"]['cons_count'] = count($content["report_consdata"]["logoff"]['cons_events']); 
			}
			// ---

			// --- Process Logon failures
			if ( $this->_events_logonfail == 1 ) 
			{
				$content["report_consdata"]["logonfail"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("529,530,531,532,533,534,535,536,537,4625,4626,4627,4628,4629,4630,4631,4632,4633" /* Include EventIDs for new and old Eventlog API*/, "ln_report_logonfailevents" /* Logoff Events */); 
				$content["report_consdata"]["logonfail"]['DataCaption'] = $content["ln_report_logonfailevents"]; 
				$content["report_consdata"]["logonfail"]['cons_count'] = count($content["report_consdata"]["logonfail"]['cons_events']); 
			}
			// ---

			// --- Process Audigpolicy changes
			if ( $this->_events_policychangeevents == 1 ) 
			{
				$content["report_consdata"]["auditpolchanged"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("617,618,619,643,4713,4714,4715,4719,4739" /* Include EventIDs for new and old Eventlog API*/, "ln_report_policychangeevents" /* Logoff Events */); 
				$content["report_consdata"]["auditpolchanged"]['DataCaption'] = $content["ln_report_policychangeevents"]; 
				$content["report_consdata"]["auditpolchanged"]['cons_count'] = count($content["report_consdata"]["auditpolchanged"]['cons_events']); 
			}
			// ---

			// --- Process Objectaccess
			if ( $this->_events_objectaccess == 1 ) 
			{
				$content["report_consdata"]["objectaccess"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("567,4663" /* Include EventIDs for new and old Eventlog API*/, "ln_report_objectaccessevents" /* Object access Events */); 
				$content["report_consdata"]["objectaccess"]['DataCaption'] = $content["ln_report_objectaccessevents"]; 
				$content["report_consdata"]["objectaccess"]['cons_count'] = count($content["report_consdata"]["objectaccess"]['cons_events']); 
			}
			// ---

			// --- Process System events
			if ( $this->_events_systemevents == 1 ) 
			{
				$content["report_consdata"]["systemevents"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("512,513,520,4108,4109,4616,4697" /* Include EventIDs for new and old Eventlog API*/, "ln_report_systemevents" /* System Events */); 
				$content["report_consdata"]["systemevents"]['DataCaption'] = $content["ln_report_systemevents"]; 
				$content["report_consdata"]["systemevents"]['cons_count'] = count($content["report_consdata"]["systemevents"]['cons_events']); 
			}
			// ---

			// --- Process Host Session events
			if ( $this->_events_hostsessionevents == 1 ) 
			{
				$content["report_consdata"]["hostsessionevents"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("682,683,4778,4779" /* Include EventIDs for new and old Eventlog API*/, "ln_report_hostsessionevents" /* Host session Events */); 
				$content["report_consdata"]["hostsessionevents"]['DataCaption'] = $content["ln_report_hostsessionevents"]; 
				$content["report_consdata"]["hostsessionevents"]['cons_count'] = count($content["report_consdata"]["hostsessionevents"]['cons_events']); 
			}
			// ---

			// --- Process User Account Changes events
			if ( $this->_events_useraccchangeevents == 1 ) 
			{
				$content["report_consdata"]["useraccchangeevents"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("642" /* Include EventIDs for new and old Eventlog API*/, "ln_report_useraccchangeevents" /* User Account Changes */); 
				$content["report_consdata"]["useraccchangeevents"]['DataCaption'] = $content["ln_report_useraccchangeevents"]; 
				$content["report_consdata"]["useraccchangeevents"]['cons_count'] = count($content["report_consdata"]["useraccchangeevents"]['cons_events']); 
			}
			// ---

			// --- Process Audit policy changes events
			if ( $this->_events_auditpolicychangesevents == 1 ) 
			{
				$content["report_consdata"]["auditpolicychangeevents"]['cons_events'] = $this->ConsolidateAuditSummaryByIDs("612, 807, 4719, 4912" /* Include EventIDs for new and old Eventlog API*/, "ln_report_auditpolicychangeevents" /* Logoff Events */); 
				$content["report_consdata"]["auditpolicychangeevents"]['DataCaption'] = $content["ln_report_auditpolicychangeevents"]; 
				$content["report_consdata"]["auditpolicychangeevents"]['cons_count'] = count($content["report_consdata"]["auditpolicychangeevents"]['cons_events']); 
			}
			// ---

			// --- Individual User Actions
			if ( $this->_events_useractions == 1 ) 
			{
				$content["report_detaildata_users"] = $this->ConsolidateAuditSummaryByField( SYSLOG_EVENT_USER, "ln_report_individualuseractions" /* User Actions */); 
				$content["report_detaildata_users_caption"] = $content["ln_report_individualuseractions"]; 
				$content["report_detaildata_users_cons_count"] = count($content["report_detaildata_users"]); 
			}
			// ---

			// --- Individual Host Actions
			if ( $this->_events_hostactions == 1 ) 
			{
				$content["report_detaildata_hosts"] = $this->ConsolidateAuditSummaryByField( SYSLOG_HOST, "ln_report_individualhostactions" /* Host Actions */); 
				$content["report_detaildata_hosts_caption"] = $content["ln_report_individualhostactions"]; 
				$content["report_detaildata_hosts_cons_count"] = count($content["report_detaildata_hosts"]); 
			}
			// ---

			// Start Postprocessing
			foreach( $content["report_consdata"] as &$tmpConsolidatedData ) 
			{
				// Only process events if there are some
				if ( is_array($tmpConsolidatedData['cons_events']) && count($tmpConsolidatedData['cons_events']) > 0 )
				{
					// First use callback function to sort array
					uasort($tmpConsolidatedData['cons_events'], "MultiSortArrayByItemCountDesc");

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
			}

			// Only process events if there are some
			if ( is_array($content["report_detaildata_users"]) && count($content["report_detaildata_users"]) > 0 )
			{
				// First use callback function to sort array
				uasort($content["report_detaildata_users"], "MultiSortArrayByItemCountDesc");
			}
			// --- 
		}

		// Work done!
		return SUCCESS;
	}


	/**
	*	Helper function to consolidate specific audit events  by EventIDs
	*/
	private function ConsolidateAuditSummaryByIDs($szFilterEventIDs, $szLangAuditID) //
	{
		global $content, $gl_starttime, $fields; 

		// Init variables
		$myConsAuditData = array(); 
		$myItemCount = 0; 

		// Set custom filters
		$this->_streamObj->ResetFilters();
		$this->_streamObj->SetFilter( 
			$this->_filterString . " " . 
			$fields[SYSLOG_MESSAGETYPE]['SearchField'] . ":=" . IUT_NT_EventReport . ",=" . IUT_WEVTMONV2 . " " . 
			$fields[SYSLOG_EVENT_ID]['SearchField'] . ":=" . $szFilterEventIDs ); 
		$myConsAuditData = $this->_streamObj->ConsolidateDataByField( SYSLOG_EVENT_USER, $this->_maxauditsummarysPerHost, SYSLOG_EVENT_USER, SORTING_ORDER_DESC, null, true, true );

		// Process all Logons
		if ( is_array($myConsAuditData) )
		{
			foreach ( $myConsAuditData as &$myConsData )
			{
				// Set Basic data entries
				if (!isset( $content['filter_severity_list'][$myConsData[SYSLOG_SEVERITY]] )) 
					$myConsData[SYSLOG_SEVERITY] = SYSLOG_NOTICE; // Set default in this case

				// Add to logonItemCount!
				$myItemCount += $myConsData['itemcount']; 
			}
		}
		else
		{
			// No data found, so return an empty array!
			$myConsAuditData = array(); 
		}


		// Set data for Consolidate Logons as well
		$content["report_consolidated"][$content[$szLangAuditID]]["TargetLink"] = $content[$szLangAuditID]; 
		$content["report_consolidated"][$content[$szLangAuditID]]["itemcount"] = $myItemCount; 
		// --------------------------- 

		// TimeStats
		$nowtime = microtime_float();
		$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s ";
		// ---

		// return results!
		return $myConsAuditData; 
	}


	/**
	*	Helper function to consolidate specific audit events by User
	*/
	private function ConsolidateAuditSummaryByField($fieldid, $szLangAuditID) //
	{
		global $content, $gl_starttime, $fields; 

		// Init variables
		$myConsAuditData = array(); 
		$myItemCount = 0; 

		// Set custom filters
		$this->_streamObj->ResetFilters();
		$this->_streamObj->SetFilter( 
			$this->_filterString . " " . 
			$fields[SYSLOG_MESSAGETYPE]['SearchField'] . ":=" . IUT_NT_EventReport . ",=" . IUT_WEVTMONV2 . " " . 
			$fields[SYSLOG_EVENT_LOGTYPE]['SearchField'] . ":=Security" ); 
		$myConsAuditData = $this->_streamObj->ConsolidateDataByField( $fieldid, $this->_maxauditsummarysPerHost, $fieldid, SORTING_ORDER_DESC, null, false, true );

		// Process all Logons
		if ( is_array($myConsAuditData) )
		{
			foreach ( $myConsAuditData as &$myConsData )
			{
				// Add to logonItemCount!
				$myItemCount += $myConsData['itemcount']; 
			}
		}
		else
		{
			// No data found, so return an empty array!
			$myConsAuditData = array(); 
		}


		// Set data for Consolidate Logons as well
		$content["report_consolidated"][$content[$szLangAuditID]]["TargetLink"] = $content[$szLangAuditID]; 
		$content["report_consolidated"][$content[$szLangAuditID]]["itemcount"] = $myItemCount; 
		// --------------------------- 

		// TimeStats
		$nowtime = microtime_float();
		$content["report_rendertime"] .= number_format($nowtime - $gl_starttime, 2, '.', '') . "s ";
		// ---

		// return results!
		return $myConsAuditData; 
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