<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Main Index File											
	*																	
	* -> Loads the main PhpLogCon Site									
	*																	
	* All directives are explained within this file
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
	* distribution				
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Init Langauge first!
// IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

// Include LogStream facility
include($gl_root_path . 'classes/logstream.class.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!
// ---

// --- Define Extra Stylesheet!
$content['EXTRA_STYLESHEET']  = '<link rel="stylesheet" href="css/highlight.css" type="text/css">' . "\r\n";
$content['EXTRA_STYLESHEET'] .= '<link rel="stylesheet" href="css/menu.css" type="text/css">';
// --- 

// --- CONTENT Vars
if ( isset($_GET['uid']) ) 
	$content['uid_current'] = intval($_GET['uid']);
else
	$content['uid_current'] = UID_UNKNOWN;

// --- Set Autoreload as meta refresh
if ( $content['uid_current'] == UID_UNKNOWN )
{
	$content['ViewEnableAutoReloadSeconds_visible'] = true;
	if ( $content['ViewEnableAutoReloadSeconds'] > 0 )
		$content['EXTRA_METATAGS'] = '<META HTTP-EQUIV=REFRESH CONTENT=' . $content['ViewEnableAutoReloadSeconds'] . '>' . "\r\n";
}
else
	$content['ViewEnableAutoReloadSeconds_visible'] = false;

// Read direction parameter
if ( isset($_GET['direction']) && $_GET['direction'] == "desc" ) 
	$content['read_direction'] = EnumReadDirection::Forward;
else
	$content['read_direction'] = EnumReadDirection::Backward;

// If direction is DESC, should we SKIP one? 
if ( isset($_GET['skipone']) && $_GET['skipone'] == "true" ) 
	$content['skipone'] = true;
else
	$content['skipone'] = false;
// ---


// Init Pager variables
// $content['uid_previous'] = UID_UNKNOWN;
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
$content['highlightstr'] = "";
$content['EXPAND_HIGHLIGHT'] = "false";

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

function PrepareStringForSearch($myString)
{
	return str_replace(" ", "+", $myString);
}
// ---

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
		$content['highlightstr'] = $_POST['highlight'];
	else if ( isset($_GET['highlight']) )
		$content['highlightstr'] = $_GET['highlight'];
	
//	else if ( $mysearch == $content['LN_SEARCH']) 
	{
		// Message is just appended
		if ( isset($myfilter) && strlen($myfilter) > 0 )
			$content['searchstr'] = $myfilter;
	}

	if ( strlen($content['highlightstr']) > 0 ) 
	{
		$searchArray = array("\\", "/", ".", ">");
		$replaceArray = array("\\\\", "\/", "\.", ">");

		// user also wants to highlight words!
		if ( strpos($content['highlightstr'], ",") === false)
		{

			$content['highlightwords'][0]['highlight_raw'] = $content['highlightstr'];
			$content['highlightwords'][0]['highlight'] = str_replace( $searchArray, $replaceArray, $content['highlightstr']);
			$content['highlightwords'][0]['cssclass'] = "highlight_1";
			$content['highlightwords'][0]['htmlcode'] = '<span class="' . $content['highlightwords'][0]['cssclass'] . '">' . $content['highlightwords'][0]['highlight']. '</span>';
		}
		else
		{
			// Split array into words
			$tmparray = explode( ",", $content['highlightstr'] );
			foreach( $tmparray as $word ) 
				$content['highlightwords'][]['highlight_raw'] = $word;
			
			// Assign other variables needed for this array entry
			for ($i = 0; $i < count($content['highlightwords']); $i++)
			{
				$content['highlightwords'][$i]['highlight'] = str_replace( $searchArray, $replaceArray, $content['highlightwords'][$i]['highlight_raw']);
				$content['highlightwords'][$i]['cssclass'] = "highlight_" . ($i+1);
				$content['highlightwords'][$i]['htmlcode'] = '<span class="' . $content['highlightwords'][$i]['cssclass'] . '">' . $content['highlightwords'][$i]['highlight']. '</span>';
			}
		}
		
		// Default expand Highlight Arrea!
		$content['EXPAND_HIGHLIGHT'] = "true";
	}
}
// --- 

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();

// Append custom title part!
if ( isset($content['searchstr']) && strlen($content['searchstr']) > 0 ) 
	$content['TITLE'] .= " :: Results for the search '" . $content['searchstr'] . "'";	// Append search
else
	$content['TITLE'] .= " :: All Syslogmessages";
// --- END CREATE TITLE

// --- BEGIN Custom Code
if ( isset($content['Sources'][$currentSourceID]) ) // && $content['Sources'][$currentSourceID]['SourceType'] == SOURCE_DISK )
{
	// Obtain and get the Config Object
	$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

	// Create LogStream Object 
	$stream = $stream_config->LogStreamFactory($stream_config);
	$stream->SetFilter($content['searchstr']);

	// --- Init the fields we need
	foreach($content['Columns'] as $mycolkey)
	{
		if ( isset($fields[$mycolkey]) )
		{
			$content['fields'][$mycolkey]['FieldID'] = $mycolkey;
			$content['fields'][$mycolkey]['FieldCaption'] = $content[ $fields[$mycolkey]['FieldCaptionID'] ];
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

		// Read First and LAST UID's before start reading the stream!
 		$content['uid_last'] = $stream->GetLastPageUID();
 		$content['uid_first'] = $stream->GetFirstPageUID();

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
		else
		{
			// This will disable to Main SyslogView and show an error message
			$content['syslogmessagesenabled'] = "false";
			$content['detailederror'] = "No syslog messages found.";
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

			//Loop through the messages!
			do
			{
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
								if ( isset($logArray[$mycolkey][SYSLOG_FACILITY]) && strlen($logArray[$mycolkey][SYSLOG_FACILITY]) > 0)
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

								// Set OnClick Menu for SYSLOG_FACILITY
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=facility%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . GetFacilityDisplayName( $logArray[$mycolkey] ). "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . SYSLOG_FACILITY . '&q=' . GetFacilityDisplayName($logArray[$mycolkey]), 
									'DisplayName' => $content['LN_VIEW_SEARCHFOR'] . " " . $content['LN_FIELDS_FACILITY'] . " '" . GetFacilityDisplayName($logArray[$mycolkey]) . "'", 
									'IconSource' => $content['MENU_NETWORK']
									);
							}
							else if ( $mycolkey == SYSLOG_SEVERITY )
							{
								if ( isset($logArray[$mycolkey][SYSLOG_SEVERITY]) && strlen($logArray[$mycolkey][SYSLOG_SEVERITY]) > 0)
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

								// Set OnClick Menu for SYSLOG_SEVERITY
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=severity%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . GetSeverityDisplayName( $logArray[$mycolkey] ). "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . SYSLOG_SEVERITY . '&q=' . GetSeverityDisplayName($logArray[$mycolkey]), 
									'DisplayName' => $content['LN_VIEW_SEARCHFOR'] . " " . $content['LN_FIELDS_SEVERITY'] . " '" . GetSeverityDisplayName($logArray[$mycolkey]) . "'", 
									'IconSource' => $content['MENU_NETWORK']
									);
							}
							else if ( $mycolkey == SYSLOG_MESSAGETYPE )
							{
								if ( isset($logArray[$mycolkey][SYSLOG_MESSAGETYPE]) )
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

								// Set OnClick Menu for SYSLOG_MESSAGETYPE
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=messagetype%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . GetMessageTypeDisplayName( $logArray[$mycolkey] ). "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
							}
							/* Eventlog based fields */
							else if ( $mycolkey == SYSLOG_EVENT_ID )
							{
								// Set OnClick Menu for SYSLOG_EVENT_ID
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=eventid%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . SYSLOG_EVENT_ID . '&q=' . $logArray[$mycolkey], 
									'DisplayName' => $content['LN_VIEW_SEARCHFOR'] . " " . $content['LN_FIELDS_EVENTID'] . " '" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_NETWORK']
									);
							}
						}
						else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_STRING )
						{
							// kindly copy!
							$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = $logArray[$mycolkey];

							// Special Handling for the Syslog Message!
							if ( $mycolkey == SYSLOG_MESSAGE )
							{
								// No NOWRAP for Syslog Message!
								$content['syslogmessages'][$counter]['values'][$mycolkey]['isnowrap'] = "";

								// Set truncasted message for display
								if ( isset($logArray[SYSLOG_MESSAGE]) )
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetStringWithHTMLCodes(strlen($logArray[SYSLOG_MESSAGE]) > $CFG['ViewMessageCharacterLimit'] ? substr($logArray[SYSLOG_MESSAGE], 0, $CFG['ViewMessageCharacterLimit'] ) . " ..." : $logArray[SYSLOG_MESSAGE]);

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

								if ( isset($CFG['ViewEnableDetailPopups']) && $CFG['ViewEnableDetailPopups'] == 1 )
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['popupcaption'] = GetAndReplaceLangStr( $content['LN_GRID_POPUPDETAILS'], $logArray[SYSLOG_UID]);
									$content['syslogmessages'][$counter]['values'][$mycolkey]['hasdetails'] = "true";
									
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
											if ( isset($content['highlightwords']) )
												$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] = HighLightString( $content['highlightwords'],GetStringWithHTMLCodes($logArray[SYSLOG_MESSAGE]) );
											else
												$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] = GetStringWithHTMLCodes($logArray[SYSLOG_MESSAGE]);

											// --- HOOK here to add context links!
											AddContextLinks( $content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] );
											// ---
										}
										else // Just set field value
											$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] = $myfield['fieldvalue'];
									}
								}

								if ( strlen($content['searchstr']) > 0 )
								{
									// Set OnClick Menu for SYSLOG_MESSAGE
									$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
									$content['syslogmessages'][$counter]['values'][$mycolkey]['hasdropdownbutton'] = true;
									$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
										'ButtonUrl' => '?uid=' . $uID, 
										'DisplayName' => $content['LN_VIEW_MESSAGECENTERED'], 
										'IconSource' => $content['MENU_BULLET_GREEN']
										);
								}
							}
							else if ( $mycolkey == SYSLOG_SYSLOGTAG ) 
							{
								// Set OnClick Menu for SYSLOG_SYSLOGTAG
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=syslogtag%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . SYSLOG_SYSLOGTAG . '&q=' . $logArray[$mycolkey], 
									'DisplayName' => $content['LN_VIEW_SEARCHFOR'] . " " . $content['LN_FIELDS_SYSLOGTAG'] . " '" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_NETWORK']
									);
							}
							else if ( $mycolkey == SYSLOG_HOST ) 
							{
								// Set OnClick Menu for SYSLOG_HOST
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=source%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
							}
							/* Eventlog based fields */
							else if ( $mycolkey == SYSLOG_EVENT_LOGTYPE ) 
							{
								// Set OnClick Menu for SYSLOG_EVENT_LOGTYPE
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=eventlogtype%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . SYSLOG_EVENT_LOGTYPE . '&q=' . $logArray[$mycolkey], 
									'DisplayName' => $content['LN_VIEW_SEARCHFOR'] . " " . $content['LN_FIELDS_EVENTLOGTYPE'] . " '" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_NETWORK']
									);
							}
							else if ( $mycolkey == SYSLOG_EVENT_SOURCE ) 
							{
								// Set OnClick Menu for SYSLOG_EVENT_SOURCE
								$content['syslogmessages'][$counter]['values'][$mycolkey]['hasbuttons'] = true;
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => '?filter=eventlogsource%3A' . $logArray[$mycolkey] . '&search=Search' . $content['additional_url_sourceonly'], 
									'DisplayName' => $content['LN_VIEW_FILTERFOR'] . "'" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_BULLET_BLUE']
									);
								$content['syslogmessages'][$counter]['values'][$mycolkey]['buttons'][] = array( 
									'ButtonUrl' => 'http://kb.monitorware.com/kbsearch.php?sa=Search&origin=phplogcon&oid=' . SYSLOG_EVENT_SOURCE . '&q=' . $logArray[$mycolkey], 
									'DisplayName' => $content['LN_VIEW_SEARCHFOR'] . " " . $content['LN_FIELDS_EVENTSOURCE'] . " '" . $logArray[$mycolkey] . "'", 
									'IconSource' => $content['MENU_NETWORK']
									);
							}
						}
					}
				}
				// ---

				// Increment Counter
				$counter++;
			} while ($counter < $content['ViewEntriesPerPage'] && ($ret = $stream->ReadNext($uID, $logArray)) == SUCCESS);

//print_r ( $content['syslogmessages'] );

			if ( $content['main_recordcount'] == -1 || $content['main_recordcount'] > $content['ViewEntriesPerPage'] )
			{
				// Enable Pager in any case here!
				$content['main_pagerenabled'] = true;
				
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
				}
				// --- 

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
				
				// --- Handle uid_last page button 
//!!!!!!!!
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
				// --- 
				
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

		if ( $res == ERROR_FILE_NOT_FOUND ) 
			$content['detailederror'] = "Syslog file could not be found.";
		else if ( $res == ERROR_FILE_NOT_READABLE ) 
			$content['detailederror'] = "Syslog file is not readable, read access may be denied. ";
		else 
			$content['detailederror'] = "Unknown or unhandled error occured (Error Code " . $res . ") ";
	}

	// Close file!
	$stream->Close();
}
// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "index.html");
$page -> output(); 
// --- 

?>