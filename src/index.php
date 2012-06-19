<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Main Index File											
	*																	
	* -> Loads the main LogAnalyzer Site									
	*																	
	* All directives are explained within this file
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
	* distribution				
	* 
	* Adiscon LogAnalyzer is also available under a commercial license.
	* For details, contact info@adiscon.com or visit
	* http://loganalyzer.adiscon.com/commercial
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
include($gl_root_path . 'classes/logstream.class.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!
// ---

// --- Define Extra Stylesheet!
$content['EXTRA_STYLESHEET']  = '<link rel="stylesheet" href="css/highlight.css" type="text/css">' . "\r\n";
// --- 

// --- CONTENT Vars
if ( isset($_GET['uid']) ) 
{
	// Now check by numeric as uid can be larger than INT values
	if ( is_numeric($_GET['uid']) ) 
		$content['uid_current'] = $_GET['uid']; 
	else
		$content['uid_current'] = UID_UNKNOWN;
}
else
	$content['uid_current'] = UID_UNKNOWN;

// copy needed for export function
$content['uid_original'] = $content['uid_current'];

// --- Set Autoreload as meta refresh
if ( $content['uid_current'] == UID_UNKNOWN )
{
	$content['ViewEnableAutoReloadSeconds_visible'] = true;
	if ( $content['ViewEnableAutoReloadSeconds'] > 0 )
		$content['EXTRA_METATAGS'] = '<META HTTP-EQUIV=REFRESH CONTENT=' . $content['ViewEnableAutoReloadSeconds'] . '>' . "\r\n";
}
else
	$content['ViewEnableAutoReloadSeconds_visible'] = false;

// --- Read direction parameter
if ( isset($_GET['direction']) )
{
	// Copy to content array
	$content['direction'] = $_GET['direction'];
}
else 
	$content['direction'] = "";

// Check for reading direction
if ( $content['direction'] == "desc" )
	$content['read_direction'] = EnumReadDirection::Forward;
else
	$content['read_direction'] = EnumReadDirection::Backward;
// --- 

// If direction is DESC, should we SKIP one? 
if ( isset($_GET['skipone']) && $_GET['skipone'] == "true" ) 
	$content['skipone'] = true;
else
	$content['skipone'] = false;
// ---

// Init Export Stuff!
$content['EXPORT_ENABLED'] = true;

// Init Pager variables
$content['uid_previous'] = UID_UNKNOWN;
$content['uid_next'] = UID_UNKNOWN;
$content['uid_first'] = UID_UNKNOWN;
$content['uid_last'] = UID_UNKNOWN;
$content['main_pagerenabled'] = false;
$content['main_pager_first_found'] = false;
$content['main_pager_previous_found'] = false;
$content['main_pager_next_found'] = false;
$content['main_pager_last_found'] = false;

// Init Sorting variables
$content['sorting'] = "";
$content['searchstr'] = "";
$content['searchstr_htmlform'] = "";
$content['highlightstr'] = "";
$content['highlightstr_htmlform'] = "";
$content['EXPAND_HIGHLIGHT'] = "false";

// --- Read and process filters from search dialog!
if ( (isset($_POST['search']) || isset($_GET['search'])) || (isset($_POST['filter']) || isset($_GET['filter'])) )
{
	// Copy search over
	if		( isset($_POST['search']) )
		$mysearch = $_POST['search'];
	else if ( isset($_GET['search']) )
		$mysearch = $_GET['search'];

	if		( isset($_POST['filter']) )
		$myfilter = $_POST['filter'];
	else if ( isset($_GET['filter']) )
		$myfilter = $_GET['filter'];

	// Optionally read highlight words
	if ( isset($_POST['highlight']) )
	{
		$content['highlightstr'] = $_POST['highlight'];
		$content['highlightstr_htmlform'] = htmlspecialchars($_POST['highlight']);
	}
	else if ( isset($_GET['highlight']) )
	{
		$content['highlightstr'] = $_GET['highlight'];
		$content['highlightstr_htmlform'] = htmlspecialchars($_GET['highlight']);
	}
	
	// Message is just appended
	if ( isset($myfilter) && strlen($myfilter) > 0 )
	{
		$content['searchstr'] = $myfilter;
		$content['searchstr_htmlform'] = htmlspecialchars($myfilter);
	}

	if ( strlen($content['highlightstr']) > 0 ) 
	{
		$searchArray = array("\\", "/", ".", ">");
		$replaceArray = array("\\\\", "\/", "\.", ">");

		// user also wants to highlight words!
		if ( strpos($content['highlightstr'], ",") === false)
		{

			$content['highlightwords'][0]['highlight_html'] = htmlspecialchars($content['highlightstr']);
			$content['highlightwords'][0]['highlight'] = str_replace( $searchArray, $replaceArray, $content['highlightstr']);
			$content['highlightwords'][0]['cssclass'] = "highlight_1";
			$content['highlightwords'][0]['htmlcode'] = '<span class="' . $content['highlightwords'][0]['cssclass'] . '">' . $content['highlightwords'][0]['highlight']. '</span>';
		}
		else
		{
			// Split array into words
			$tmparray = explode( ",", $content['highlightstr'] );
			foreach( $tmparray as $word ) 
				$content['highlightwords'][]['highlight_html'] = htmlspecialchars($word);
			
			// Assign other variables needed for this array entry
			for ($i = 0; $i < count($content['highlightwords']); $i++)
			{
				$content['highlightwords'][$i]['highlight'] = str_replace( $searchArray, $replaceArray, $content['highlightwords'][$i]['highlight_html']);
				$content['highlightwords'][$i]['cssclass'] = "highlight_" . ($i+1);
				$content['highlightwords'][$i]['htmlcode'] = '<span class="' . $content['highlightwords'][$i]['cssclass'] . '">' . $content['highlightwords'][$i]['highlight']. '</span>';
			}
		}
		
		// Default expand Highlight Arrea!
		$content['EXPAND_HIGHLIGHT'] = "true";
	}

	// Enable oracle link!
	if ( isset($content['searchstr']) && strlen($content['searchstr']) > 0 )
	{
		$content['enabledoraclesearchstr'] = true;
		$content['oraclesearchlink'] = $content['BASEPATH'] . "asktheoracle.php?type=searchstr&query=" . urlencode($content['searchstr']) . "&uid=" . $content['uid_current'];
	}
}
// --- 

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();

// Append custom title part!
if ( isset($content['searchstr_htmlform']) && strlen($content['searchstr_htmlform']) > 0 ) 
	$content['TITLE'] .= " :: Results for the search '" . $content['searchstr_htmlform'] . "'";	// Append search
else
	$content['TITLE'] .= " :: All Syslogmessages";
// --- END CREATE TITLE


// --- BEGIN Custom Code

// Do not BLOCK other Site Calls
WriteClosePHPSession();

if ( isset($content['Sources'][$currentSourceID]) ) 
{
	// Obtain and get the Config Object
	$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

	// Create LogStream Object 
	$stream = $stream_config->LogStreamFactory($stream_config);
	$stream->SetFilter($content['searchstr']);
	
	// Copy current used columns here!
	$content['Columns'] = $content['Views'][$currentViewID]['Columns'];

	// --- Init the fields we need
	foreach($content['Columns'] as $mycolkey)
	{
		if ( isset($fields[$mycolkey]) )
		{
			$content['fields'][$mycolkey]['FieldID'] = $mycolkey;
			$content['fields'][$mycolkey]['FieldCaption'] = $fields[$mycolkey]['FieldCaption'];
			$content['fields'][$mycolkey]['FieldType'] = $fields[$mycolkey]['FieldType'];
			$content['fields'][$mycolkey]['FieldSortable'] = $stream->IsPropertySortable($mycolkey); // $fields[$mycolkey]['Sortable'];
			$content['fields'][$mycolkey]['DefaultWidth'] = $fields[$mycolkey]['DefaultWidth'];

			if ( $mycolkey == SYSLOG_MESSAGE )
				$content['fields'][$mycolkey]['colspan'] = ''; //' colspan="2" ';
			else
				$content['fields'][$mycolkey]['colspan'] = '';
		}
	}
	// --- 

	$res = $stream->Open( $content['Columns'], true );
	if ( $res == SUCCESS ) 
	{
		// TODO Implement ORDER
		$stream->SetReadDirection($content['read_direction']);

		// Set current ID and init Counter
		$uID = $content['uid_current'];
		$counter = 0;

		// If uID is known, we need to init READ first - this will also seek for available records first!
		if ($uID != UID_UNKNOWN) 
		{
			// First read will also set the start position of the Stream!
			$ret = $stream->Read($uID, $logArray);
		}
		else
			$ret = $stream->ReadNext($uID, $logArray);

		// --- Check if Read was successfull!
		if ( $ret == SUCCESS )
		{
			// If Forward direction is used, we need to SKIP one entry!
			if ( $content['read_direction'] == EnumReadDirection::Forward )
			{
				// Ok the current ID is our NEXT ID in this reading direction, so we save it!
				$content['uid_next'] = $uID;

				if ( $content['skipone'] ) 
				{
					// Skip this entry and move to the next
					$stream->ReadNext($uID, $logArray);
				}
			}
		}
		else if ( $ret == ERROR_MSG_SKIPMESSAGE )
		{
			do
			{
				// Skip until we find a suitable entry
				$ret = $stream->ReadNext($uID, $logArray);
			} while ($ret == ERROR_MSG_SKIPMESSAGE);
		}
		
		// check for error return state!
		if ( $ret != SUCCESS )
		{
			// This will disable to Main SyslogView and show an error message
			$content['syslogmessagesenabled'] = "false";
			$content['detailederror'] = $content['LN_ERROR_NORECORDS'];
			$content['detailederror_code'] = ERROR_NOMORERECORDS;
		}
		// ---

		// We found matching records, so continue
		if ( $ret == SUCCESS )
		{
			// --- PreChecks to be done
			// Set Record Count
			$content['main_recordcount'] = $stream->GetMessageCount();
			if ( $content['main_recordcount'] != -1 )
				$content['main_recordcount_found'] = true;
			else
				$content['main_recordcount_found'] = false;
			
			$content['main_currentpagenumber'] = $stream->GetCurrentPageNumber();
			if ( $content['main_currentpagenumber'] >= 0 )
				$content['main_currentpagenumber_found'] = true;
			else
				$content['main_currentpagenumber_found'] = false;
//echo $content['main_currentpagenumber'];
			// ---

			// --- Obtain characters limits first!
			$myMsgCharLimit = GetConfigSetting("ViewMessageCharacterLimit", 80, CFGLEVEL_USER);
			$myStrCharLimit = GetConfigSetting("ViewStringCharacterLimit", 30, CFGLEVEL_USER);
			// ---

			//Loop through the messages!
			do
			{
				// --- Extra stuff for suppressing messages
				if (
						GetConfigSetting("SuppressDuplicatedMessages", 0, CFGLEVEL_USER) == 1 
						&&
						isset($logArray[SYSLOG_MESSAGE])
					)
				{

					if ( !isset($szLastMessage) ) // Only set lastmgr
						$szLastMessage = $logArray[SYSLOG_MESSAGE];
					else
					{
						// Skip if same msg
						if ( $szLastMessage == $logArray[SYSLOG_MESSAGE] )
						{
							// Set last mgr
							$szLastMessage = $logArray[SYSLOG_MESSAGE];

							// --- Extra Loop to get the next entry!
							do
							{
								$ret = $stream->ReadNext($uID, $logArray);
							} while ( $ret == ERROR_MSG_SKIPMESSAGE );
							// --- 

							// Skip entry
							continue;
						}
					}
				}
				// --- 

				// --- Set CSS Class
				if ( $counter % 2 == 0 )
					$content['syslogmessages'][$counter]['cssclass'] = "line1";
				else
					$content['syslogmessages'][$counter]['cssclass'] = "line2";
				// --- 

				// --- Copy other needed properties
				$content['syslogmessages'][$counter]['MiscShowDebugGridCounter'] = $content['MiscShowDebugGridCounter'];
				// --- 

				// --- Now we populate the values array!
				foreach($content['Columns'] as $mycolkey)
				{
					if ( isset($fields[$mycolkey]) && isset($logArray[$mycolkey]) )
					{
						// Set defaults
						$content['syslogmessages'][$counter]['values'][$mycolkey]['FieldColumn'] = $mycolkey;
						$content['syslogmessages'][$counter]['values'][$mycolkey]['uid'] = $uID;
						$content['syslogmessages'][$counter]['values'][$mycolkey]['FieldAlign'] = $fields[$mycolkey]['FieldAlign'];
						$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldcssclass'] = $content['syslogmessages'][$counter]['cssclass'];
						$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = "";
						$content['syslogmessages'][$counter]['values'][$mycolkey]['isnowrap'] = "nowrap";
						$content['syslogmessages'][$counter]['values'][$mycolkey]['hasdetails'] = "false";
						$content['syslogmessages'][$counter]['values'][$mycolkey]['detailimagealign'] = "TOP";

						// Set default link 
						$content['syslogmessages'][$counter]['values'][$mycolkey]['detaillink'] = "#";
						
						// Now handle fields types differently
						if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_DATE )
						{
							$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetFormatedDate($logArray[$mycolkey]); 
						}
						else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_NUMBER )
						{
							$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = $logArray[$mycolkey];

							// Special style classes and colours for SYSLOG_FACILITY
							if ( $mycolkey == SYSLOG_FACILITY )
							{
//								if ( isset($logArray[$mycolkey][SYSLOG_FACILITY]) && strlen($logArray[$mycolkey][SYSLOG_FACILITY]) > 0)
								if ( isset($logArray[$mycolkey]) && is_numeric($logArray[$mycolkey]) )
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $facility_colors[ $logArray[SYSLOG_FACILITY] ] . '" ';
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldcssclass'] = "lineColouredBlack";

									// Set Human readable Facility!
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetFacilityDisplayName( $logArray[$mycolkey] );
								}
								else
								{
									// Use default colour!
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $facility_colors[SYSLOG_LOCAL0] . '" ';
								}

								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, SYSLOG_FACILITY);
							}
							else if ( $mycolkey == SYSLOG_SEVERITY )
							{
//								if ( isset($logArray[$mycolkey][SYSLOG_SEVERITY]) && strlen($logArray[$mycolkey][SYSLOG_SEVERITY]) > 0)
								if ( isset($logArray[$mycolkey]) && is_numeric($logArray[$mycolkey]) )
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $severity_colors[ $logArray[SYSLOG_SEVERITY] ] . '" ';
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldcssclass'] = "lineColouredWhite";

									// Set Human readable Facility!
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetSeverityDisplayName( $logArray[$mycolkey] );
								}
								else
								{
									// Use default colour!
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $severity_colors[SYSLOG_INFO] . '" ';
								}

								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, SYSLOG_SEVERITY);
							}
							else if ( $mycolkey == SYSLOG_MESSAGETYPE )
							{
//								if ( isset($logArray[$mycolkey][SYSLOG_MESSAGETYPE]) )
								if ( isset($logArray[$mycolkey]) && is_numeric($logArray[$mycolkey]) )
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $msgtype_colors[ $logArray[SYSLOG_MESSAGETYPE] ] . '" ';
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldcssclass'] = "lineColouredBlack";

									// Set Human readable Facility!
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetMessageTypeDisplayName( $logArray[$mycolkey] );
								}
								else
								{
									// Use default colour!
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $msgtype_colors[IUT_Unknown] . '" ';
								}

								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, SYSLOG_MESSAGETYPE);
							}
							else if ( $mycolkey == SYSLOG_PROCESSID )
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, SYSLOG_PROCESSID);
							}
							/* Eventlog based fields */
							else if ( $mycolkey == SYSLOG_EVENT_ID )
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, SYSLOG_EVENT_ID);
							}
							else if ( $mycolkey == SYSLOG_EVENT_CATEGORY )
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, SYSLOG_EVENT_CATEGORY);
							}
							// WebServer Type fields
							else if ( $mycolkey == SYSLOG_WEBLOG_STATUS ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, SYSLOG_WEBLOG_STATUS);
							}
							else 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_NUMBER, $mycolkey);
							}
						}
						else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_STRING )
						{
							// Set some basic variables first
							$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = $logArray[$mycolkey];		// May contain the field value trunscated 
							$content['syslogmessages'][$counter]['values'][$mycolkey]['rawfieldvalue'] = $logArray[$mycolkey];	// helper variable used for Popups!
							$content['syslogmessages'][$counter]['values'][$mycolkey]['encodedfieldvalue'] = PrepareStringForSearch($logArray[$mycolkey]); // Convert into filter format for submenus

							// --- Check for reached string character limit
							if ( $mycolkey != SYSLOG_MESSAGE ) 
							{
								if ( $myStrCharLimit > 0 )
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetStringWithHTMLCodes(strlen($logArray[$mycolkey]) > $myStrCharLimit ? substr($logArray[$mycolkey], 0, $myStrCharLimit) . "..." : $logArray[$mycolkey]);
							}
							// --- 

							// Special Handling for the Syslog Message!
							if ( $mycolkey == SYSLOG_MESSAGE )
							{
								// No NOWRAP for Syslog Message!
								$content['syslogmessages'][$counter]['values'][$mycolkey]['isnowrap'] = "";

								// Set truncasted message for display
								if ( isset($logArray[SYSLOG_MESSAGE]) )
								{
									if ( $myMsgCharLimit > 0 )
										$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetStringWithHTMLCodes(strlen($logArray[SYSLOG_MESSAGE]) > $myMsgCharLimit ? substr($logArray[SYSLOG_MESSAGE], 0, $myMsgCharLimit) . " ..." : $logArray[SYSLOG_MESSAGE]);
									else
										$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetStringWithHTMLCodes($logArray[SYSLOG_MESSAGE]);

									// Enable LINK property! for this field
									$content['syslogmessages'][$counter]['values'][$mycolkey]['ismessagefield'] = true;
									$content['syslogmessages'][$counter]['values'][$mycolkey]['detaillink'] = "details.php?uid=" . $uID;
								}
								else
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = "";
								}

								// If we need to highlight some words ^^!
								if ( isset($content['highlightwords']) )
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = HighLightString( $content['highlightwords'], $content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] );

								// --- HOOK here to add context links!
								AddContextLinks($content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue']);
								// --- 

								if ( GetConfigSetting("ViewEnableDetailPopups", 0, CFGLEVEL_USER) )
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['popupcaption'] = GetAndReplaceLangStr( $content['LN_GRID_POPUPDETAILS'], $logArray[SYSLOG_UID]);
									$content['syslogmessages'][$counter]['values'][$mycolkey]['hasdetails'] = "true";
									$content['syslogmessages'][$counter]['values'][$mycolkey]['detailimagealign'] = "left"; // Other alignment needed!

									foreach($content['syslogmessages'][$counter]['values'] as $mykey => $myfield)
									{
										// Set Caption!
										$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][]['detailfieldtitle']= $content['fields'][$mykey]['FieldCaption'];

										// Get ArrayIndex
										$myIndex = count($content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails']) - 1;

										// --- Set CSS Class
										if ( $myIndex % 2 == 0 )
											$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailscssclass'] = "line1";
										else
											$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailscssclass'] = "line2";
										// --- 

										// If message field, we need to handle differently!
										if ( $mykey == SYSLOG_MESSAGE )
										{
											// Get DetailMsg with linebreaks
											$szDetailMsg = ReplaceLineBreaksInString(GetStringWithHTMLCodes($logArray[SYSLOG_MESSAGE]));

											if ( isset($content['highlightwords']) )
												$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] = HighLightString( $content['highlightwords'], $szDetailMsg);
											else
												$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] = $szDetailMsg;

											// --- HOOK here to add context links!
											AddContextLinks( $content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] );
											// ---
										}
										else // Just set field value
											$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] = isset($myfield['rawfieldvalue']) ? $myfield['rawfieldvalue'] : $myfield['fieldvalue'];
									}
								}

								if ( strlen($content['searchstr']) > 0 )
								{
									// Set OnClick Menu for SYSLOG_MESSAGE
									$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
									$content['syslogmessages'][$counter]['values'][$mycolkey]['hasdropdownbutton'] = true;
									$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
										'ButtonUrl' => '?uid=' . $uID, 
										'ButtonTarget' => '_top', 
										'ButtonAppendUrl' => true,
										'DisplayName' => $content['LN_VIEW_MESSAGECENTERED'], 
										'IconSource' => $content['MENU_BULLET_GREEN']
										);
								}
							}
							else if ( $mycolkey == SYSLOG_SYSLOGTAG ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_SYSLOGTAG);
							}
							else if ( $mycolkey == SYSLOG_HOST ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_HOST);
							}
							/* Eventlog based fields */
							else if ( $mycolkey == SYSLOG_EVENT_LOGTYPE ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_EVENT_LOGTYPE);
							}
							else if ( $mycolkey == SYSLOG_EVENT_SOURCE ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_EVENT_SOURCE);
							}
							else if ( $mycolkey == SYSLOG_EVENT_USER ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_EVENT_USER);
							}
							// WebServer Type fields
							else if ( $mycolkey == SYSLOG_WEBLOG_USER ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_WEBLOG_USER);
							}
							else if ( $mycolkey == SYSLOG_WEBLOG_METHOD ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_WEBLOG_METHOD);
							}
							else if ( $mycolkey == SYSLOG_WEBLOG_URL ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_WEBLOG_URL);
							}
							else if ( $mycolkey == SYSLOG_WEBLOG_QUERYSTRING ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_WEBLOG_QUERYSTRING);
							}
							else if ( $mycolkey == SYSLOG_WEBLOG_PVER ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_WEBLOG_PVER);
							}
							else if ( $mycolkey == SYSLOG_WEBLOG_REFERER ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_WEBLOG_REFERER);
							}
							else if ( $mycolkey == SYSLOG_WEBLOG_USERAGENT ) 
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, SYSLOG_WEBLOG_USERAGENT);
							}
							else
							{
								// Add context menu
								AddOnClickMenu( $content['syslogmessages'][$counter]['values'][$mycolkey], FILTER_TYPE_STRING, $mycolkey);
							}
						}
					}
				}
				// ---

				// Increment Counter
				$counter++;
				
				// --- Extra Loop to get the next entry!

				// temporary store the current last $uID
				$lastUid = $uID;

				do
				{
					$ret = $stream->ReadNext($uID, $logArray);
				} while ( $ret == ERROR_MSG_SKIPMESSAGE );
				// --- 
			} while ( $counter < $content['CurrentViewEntriesPerPage'] && ($ret == SUCCESS) );
//print_r ( $content['syslogmessages'] );

			// Move below processing - Read First and LAST UID's before start reading the stream!
//			$content['uid_last'] = $stream->GetLastPageUID();
///			$content['uid_first'] = $stream->GetFirstPageUID();

			if ( $content['main_recordcount'] == -1 || $content['main_recordcount'] > $content['CurrentViewEntriesPerPage'] )
			{
				// Enable Pager in any case here!
				$content['main_pagerenabled'] = true;
				
/*
				// temporary store the current last $uID
				$lastUid = $uID;

				// --- Handle uid_next page button 
				if ( $content['read_direction'] == EnumReadDirection::Backward )
				{
					if ( $stream->ReadNext($uID, $logArray) == SUCCESS && isset($uID) ) 
					{
						$content['uid_next'] = $uID;
						$content['main_pager_next_found'] = true;
					}
					else if ( $content['uid_current'] != UID_UNKNOWN )
						$content['main_pager_next_found'] = false;
//echo $content['uid_next'] . "!!!";
				}
				// --- 
*/

/*
				// --- Handle uid_previous page button 
				if ( $content['uid_current'] != UID_UNKNOWN )
				{
					if ( $content['read_direction'] == EnumReadDirection::Forward )
					{
						if ( $ret == SUCCESS ) 
						{
							// Try to read the next one!
							$ret = $stream->ReadNext($uID, $tmp);
							if ( $ret == SUCCESS ) 
								$content['main_pager_previous_found'] = true;
							else
								$content['main_pager_previous_found'] = false;
						}
						else
							$content['main_pager_previous_found'] = false;
					}
					else if ( $content['read_direction'] == EnumReadDirection::Backward )
						$content['main_pager_previous_found'] = true;
				}
				else
					$content['main_pager_previous_found'] = false;
				//echo $content['uid_previous'];
				// --- 
*/

				// --- Handle uid_previous page button 
				if ( $content['read_direction'] == EnumReadDirection::Forward )
				{
					if ( $ret == SUCCESS ) 
					{
						// Try to read the next one!
						$ret = $stream->ReadNext($uID, $tmp);
						if ( $ret == SUCCESS ) 
							$content['main_pager_previous_found'] = true;
						else
							$content['main_pager_previous_found'] = false;
					}
					else
						$content['main_pager_previous_found'] = false;
				}
				else
				{
					if ( $content['uid_current'] == $content['uid_previous'] ) 
						$content['main_pager_previous_found'] = false;
					else
						$content['main_pager_previous_found'] = true;
				}


				// --- 

				// --- Handle uid_last and uid_next page button 
				if ( $content['read_direction'] == EnumReadDirection::Forward )
				{
					if ( $content['uid_current'] == $content['uid_last'] ) 
					{
						$content['main_pager_last_found'] = false;
						$content['main_pager_next_found'] = false;
					}
					else
					{
						$content['main_pager_last_found'] = true;
						$content['main_pager_next_found'] = true;
					}

					// Restore uid_current if necessary
					$content['uid_current'] = $lastUid;
				}
				else
				{
					// If last error code was nomorerecords, there are no more pages
					if ( $ret == ERROR_NOMORERECORDS ) 
					{
						$content['main_pager_last_found'] = false;
						$content['main_pager_next_found'] = false;
					}
					else
					{
						// Set NEXT uid
						$content['uid_next'] = $uID;
						$content['main_pager_last_found'] = true;
						$content['main_pager_next_found'] = true;
					}
				}
				// --- 

//!!!!!!!!
/*
				// if we found a last uid, and if it is not the current one (which means we already are on the last page ;)!
				if ( $content['uid_last'] != -1 && $content['uid_last'] != $content['uid_current'])
					$content['main_pager_last_found'] = true;
				else
					$content['main_pager_last_found'] = false;
				//echo $content['uid_last'];
				
				// Handle next button only if Forward is used now!
				if ( $content['read_direction'] == EnumReadDirection::Forward )
				{
					if ( $content['uid_current'] == $content['uid_last'] ) 
						// Last page already !
						$content['main_pager_next_found'] = false;
					else	
						// User clicked back, so there is a next page for sure
						$content['main_pager_next_found'] = true;

					// As we went back, we need to change the currend uid to the latest read one
					$content['uid_current'] = $lastUid;
				}
*/
				// --- 
				
				// --- Handle uid_first page button 
				if ( $content['uid_current'] == $content['uid_first'] ) 
				{
					$content['main_pager_first_found'] = false;
					$content['main_pager_previous_found'] = false; // If there is no FIRST, there is no going back!
				}
				else if ( !$content['main_pager_previous_found'] )
					$content['main_pager_first_found'] = false;
				else
					$content['main_pager_first_found'] = true;
				// --- 

//				$content['uid_first']
/*
				// --- Handle uid_first page button 
				if (	$content['main_pager_previous_found'] == false || 
						$content['uid_current'] == UID_UNKNOWN || 
						$content['uid_current'] == $content['uid_first'] ) 
				{
					$content['main_pager_first_found'] = false;
					$content['main_pager_previous_found'] = false; // If there is no FIRST, there is no going back!
				}
				else
					$content['main_pager_first_found'] = true;
				// --- 
*/
			}
			else	// Disable pager in this case!
				$content['main_pagerenabled'] = false;

			if ( $content['read_direction'] == EnumReadDirection::Forward )
			{
				// Back Button was clicked, so we need to flip the array 
//				print_r( $content['syslogmessages'] );
				$content['syslogmessages'] = array_reverse ( $content['syslogmessages'] );
//				print_r( $content['syslogmessages'] );
			}

			// This will enable to Main SyslogView
			$content['syslogmessagesenabled'] = "true";
		}
	}
	else
	{
		// This will disable to Main SyslogView and show an error message
		$content['syslogmessagesenabled'] = "false";
		$content['detailederror'] = GetErrorMessage($res);
		$content['detailederror_code'] = $res;

		if ( isset($extraErrorDescription) )
			$content['detailederror'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
	}

	// Close file!
	$stream->Close();
}
else
{
	$content['syslogmessagesenabled'] = "false";
	$content['detailederror'] = GetAndReplaceLangStr( $content['LN_GEN_ERROR_SOURCENOTFOUND'], $currentSourceID);
	$content['detailederror_code'] = ERROR_SOURCENOTFOUND;
}
// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "index.html");
$page -> output(); 
// --- 

// --- BEGIN Define Helper functions
function HighLightString($highlightArray, $strmsg)
{
	if ( isset($highlightArray) )
	{
		// TODO OPTIMIZE - USING FONT TAG as SPAN is HIDDEN if MESSAGE POPUP is ENABNLED!
		foreach( $highlightArray as $highlightword ) 
			$strmsg = preg_replace( "/(" . $highlightword['highlight'] . ")/i", '<font class="' . $highlightword['cssclass'] . '">\\1</font>', $strmsg );
	}

	// return result
	return $strmsg;
}

/*
*	Prepare a string for search! spaces will be replaces with a single +
*	+ will be replaced with a double ++
*/
function PrepareStringForSearch($myString)
{
	// Create Find & Replace arrays
	$searchArray = array("+", " ");
	$replaceArray = array("++", "+");

	// str_replace(' ', '+', $logArray[$mycolkey]);
	return str_replace($searchArray, $replaceArray, $myString);
}

function AddOnClickMenu(&$fieldGridItem, $fieldType, $FieldID)
{
	global $content, $fields, $myStrCharLimit; 

	// Set OnClick Menu for SYSLOG_SYSLOGTAG
	$fieldGridItem['hasbuttons'] = true;
	
	// Set Field Caption
	if ( isset($content['fields'][$FieldID]['FieldCaption']) && strlen( $content['fields'][$FieldID]['FieldCaption']) > 0 ) 
		$szFieldDisplayName = $content['fields'][$FieldID]['FieldCaption'];
	else
		$szFieldDisplayName = $FieldID;
	
	// Set FieldSearch Value
	if ( $fieldType == FILTER_TYPE_STRING && isset($fieldGridItem['encodedfieldvalue']) ) 
	{
		$szEncodedFieldValue = urlencode($fieldGridItem['encodedfieldvalue']);
	}
	else
		$szEncodedFieldValue = $fieldGridItem['fieldvalue'];

	// Set FieldSearchName 
	if ( isset($fields[$FieldID]['SearchField']) )
		$szSearchFieldName = $fields[$FieldID]['SearchField'];
	else
		$szSearchFieldName = $FieldID;

	// Menu Option to append filter
	if ( strlen($content['searchstr']) > 0 )
	{
		$fieldGridItem['buttons'][] = array( 
			'ButtonUrl' => '?filter=' . urlencode($content['searchstr']) . '+' . $szSearchFieldName . '%3A%3D' . $szEncodedFieldValue . '&search=Search' . $content['additional_url_sourceonly'], 
			'ButtonTarget' => '_top', 
			'ButtonAppendUrl' => true,
			'DisplayName' => GetAndReplaceLangStr($content['LN_VIEW_ADDTOFILTER'], $fieldGridItem['fieldvalue']), 
			'IconSource' => $content['MENU_BULLET_GREEN']
			);
		$fieldGridItem['buttons'][] = array( 
			'ButtonUrl' => '?filter=' . urlencode($content['searchstr']) . '+' . $szSearchFieldName . '%3A-%3D' . $szEncodedFieldValue . '&search=Search' . $content['additional_url_sourceonly'], 
			'ButtonTarget' => '_top', 
			'ButtonAppendUrl' => true,
			'DisplayName' => GetAndReplaceLangStr($content['LN_VIEW_EXCLUDEFILTER'], $fieldGridItem['fieldvalue']), 
			'IconSource' => $content['MENU_BULLET_GREEN']
			);
	}
	
	// More Menu entries
	$fieldGridItem['buttons'][] = array( 
		'ButtonUrl' => '?filter=' . $szSearchFieldName . '%3A%3D' . $szEncodedFieldValue . '&search=Search' . $content['additional_url_sourceonly'], 
		'ButtonTarget' => '_top', 
		'ButtonAppendUrl' => true,
		'DisplayName' => GetAndReplaceLangStr($content['LN_VIEW_FILTERFORONLY'], $fieldGridItem['fieldvalue']), 
		'IconSource' => $content['MENU_BULLET_BLUE']
		);
	$fieldGridItem['buttons'][] = array( 
		'ButtonUrl' => '?filter=' . $szSearchFieldName . '%3A-%3D' . $szEncodedFieldValue . '&search=Search' . $content['additional_url_sourceonly'], 
		'ButtonTarget' => '_top', 
		'ButtonAppendUrl' => true,
		'DisplayName' => GetAndReplaceLangStr($content['LN_VIEW_SHOWALLBUT'], $fieldGridItem['fieldvalue']), 
		'IconSource' => $content['MENU_BULLET_BLUE']
		);

	// Add Online Search Button
	if ( isset($fields[$FieldID]['SearchOnline']) && $fields[$FieldID]['SearchOnline'] && strlen($fieldGridItem['fieldvalue']) > 0 )
	{
		$fieldGridItem['buttons'][] = array( 
			'ButtonUrl' => 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . $FieldID . '&q=' . $szEncodedFieldValue, 
			'ButtonTarget' => '_top', 
			'ButtonAppendUrl' => true,
			'DisplayName' => $content['LN_VIEW_SEARCHFOR'] . " " . $szFieldDisplayName . " '" . $fieldGridItem['fieldvalue'] . "'", 
			'IconSource' => $content['MENU_NETWORK']
			);

		if ( GetConfigSetting("InlineOnlineSearchIcons", 1, CFGLEVEL_USER) == 1 )
		{
			// Enable SearchOnline Icon
			$fieldGridItem['searchonline'] = true; 
			$fieldGridItem['SearchOnlineUrl'] = 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . $FieldID . '&q=' . $szEncodedFieldValue; 
		}
	}
	
	// Search for links within the fieldcontent!
	if ( $fieldType == FILTER_TYPE_STRING && preg_match("#([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", $fieldGridItem['rawfieldvalue'], $szLink) >= 1 )
	{
		$fieldGridItem['buttons'][] = array( 
			'ButtonUrl' => $szLink[0], 
			'ButtonTarget' => '_blank', 
			'ButtonAppendUrl' => false,
			'DisplayName' => GetAndReplaceLangStr($content['LN_VIEW_VISITLINK'], strlen($szLink[0]) > $myStrCharLimit ? substr($szLink[0], 0, $myStrCharLimit) . "..." : $szLink[0] ), 
			'IconSource' => $content['MENU_NETWORK']
			);
	}

}
// ---

?>