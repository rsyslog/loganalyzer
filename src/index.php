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
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

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
{
	$currentUID = intval($_GET['uid']);
}
else
	$currentUID = UID_UNKNOWN;

// Init Pager variables
$content['uid_previous'] = UID_UNKNOWN;
$content['uid_next'] = UID_UNKNOWN;
$content['uid_first'] = UID_UNKNOWN;
$content['uid_last'] = UID_UNKNOWN;

// Init Sorting variables
$content['sorting'] = "";
$content['searchstr'] = "";
$content['highlightstr'] = "";
$content['EXPAND_HIGHLIGHT'] = "false";


//if ( isset($content['myserver']) ) 
//	$content['TITLE'] = "phpLogCon :: Home :: Server '" . $content['myserver']['Name'] . "'";	// Title of the Page 
//else
	$content['TITLE'] = "phpLogCon :: Home";

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

// --- BEGIN Custom Code
if ( isset($content['Sources'][$currentSourceID]) ) // && $content['Sources'][$currentSourceID]['SourceType'] == SOURCE_DISK )
{
	// Preprocessing the fields we need
	foreach($content['Columns'] as $mycolkey)
	{
		$content['fields'][$mycolkey]['FieldID'] = $mycolkey;
		$content['fields'][$mycolkey]['FieldCaption'] = $content[ $fields[$mycolkey]['FieldCaptionID'] ];
		$content['fields'][$mycolkey]['FieldType'] = $fields[$mycolkey]['FieldType'];
		$content['fields'][$mycolkey]['FieldSortable'] = $fields[$mycolkey]['Sortable'];
		$content['fields'][$mycolkey]['DefaultWidth'] = $fields[$mycolkey]['DefaultWidth'];
	}

	// Obtain and get the Config Object
	$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

	// Create LogStream Object 
	$stream = $stream_config->LogStreamFactory($stream_config);
	$stream->SetFilter($content['searchstr']);

	$res = $stream->Open( $content['Columns'], true );
	if ( $res == SUCCESS ) 
	{
		// TODO Implement ORDER
		$stream->SetReadDirection(EnumReadDirection::Backward);
		
		// Set current ID and init Counter
		$uID = $currentUID;
		$counter = 0;
		
		// If uID is known, we need to init READ first - this will also seek for available records first!
		if ($uID != UID_UNKNOWN) 
		{
			// First read will also set the start position of the Stream!
			$ret = $stream->Read($uID, $logArray);
		}
		else
			$ret = $stream->ReadNext($uID, $logArray);
		
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

			$content['uid_previous'] = $stream->GetPreviousPageUID();
			if ( $content['uid_previous'] != -1 )
				$content['main_pager_previous_found'] = true;
			else
				$content['main_pager_previous_found'] = false;
//echo $content['uid_previous'];

			$content['uid_last'] = $stream->GetLastPageUID();
			if ( $content['uid_last'] != -1 )
				$content['main_pager_last_found'] = true;
			else
				$content['main_pager_last_found'] = false;
//echo $content['uid_last'];

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
					if ( isset($logArray[$mycolkey]) )
					{
						// Set defaults
						$content['syslogmessages'][$counter]['values'][$mycolkey]['FieldAlign'] = $fields[$mycolkey]['FieldAlign'];
						$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldcssclass'] = $content['syslogmessages'][$counter]['cssclass'];
						$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldbgcolor'] = "";
						$content['syslogmessages'][$counter]['values'][$mycolkey]['hasdetails'] = "false";

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
								
							}
						}
						else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_STRING )
						{
							// kindly copy!
							$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = $logArray[$mycolkey];

							// Special Handling for the Syslog Message!
							if ( $mycolkey == SYSLOG_MESSAGE )
							{
								// Set truncasted message for display
								if ( isset($logArray[SYSLOG_MESSAGE]) )
								{
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = GetStringWithHTMLCodes(strlen($logArray[SYSLOG_MESSAGE]) > $CFG['ViewMessageCharacterLimit'] ? substr($logArray[SYSLOG_MESSAGE], 0, $CFG['ViewMessageCharacterLimit'] ) . " ..." : $logArray[SYSLOG_MESSAGE]);
								}
								else
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = "";

								// If we need to highlight some words ^^!
								if ( isset($content['highlightwords']) )
									$content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] = HighLightString( $content['highlightwords'], $content['syslogmessages'][$counter]['values'][$mycolkey]['fieldvalue'] );

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
										}
										else // Just set field value
											$content['syslogmessages'][$counter]['values'][$mycolkey]['messagesdetails'][$myIndex]['detailfieldvalue'] = $myfield['fieldvalue'];

									}
								}

							}
						}
					}
				}
				// ---

				// Increment Counter
				$counter++;
			} while ($stream->ReadNext($uID, $logArray) == SUCCESS && $counter < $CFG['ViewEntriesPerPage']);

//print_r ( $content['syslogmessages'] );

			if ( $content['main_recordcount'] == -1 || $content['main_recordcount'] > $CFG['ViewEntriesPerPage'] )
			{
				// Enable Pager in any case here!
				$content['main_pagerenabled'] = true;

				if ( $stream->ReadNext($uID, $logArray) == SUCCESS && isset($uID) ) 
				{
					$content['uid_next'] = $uID;
					$content['main_pager_next_found'] = true;
				}
				else if ( $currentUID != UID_UNKNOWN )
				{
					$content['main_pager_next_found'] = false;
				}
			}
			else	// Disable pager in this case!
				$content['main_pagerenabled'] = false;


			// This will enable to Main SyslogView
			$content['syslogmessagesenabled'] = "true";
		}
	}
	else
	{
		// This will disable to Main SyslogView and show an error message
		$content['syslogmessagesenabled'] = "false";
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