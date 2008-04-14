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
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
include($gl_root_path . 'classes/logstream.class.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd

// Init Langauge first!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

// Helpers for frontend filtering!
InitFilterHelpers();	
// ***					*** //

// --- Extra Stylesheet!
$content['EXTRA_STYLESHEET'] = '<link rel="stylesheet" href="css/highlight.css" type="text/css">';
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

// Read and process filters from search dialog!
if ( (isset($_POST['search']) || isset($_GET['search'])) && (isset($_POST['filter']) || isset($_GET['filter'])) )
{
	// Copy search over
	if ( isset($_POST['search']) )
		$mysearch = $_POST['search'];
	else
		$mysearch = $_GET['search'];

	if ( isset($_POST['search']) )
		$myfilter = $_POST['filter'];
	else
		$myfilter = $_GET['filter'];
	
	// Optionally read highlight words
	if ( isset($_POST['highlight']) )
		$content['highlightstr'] = $_POST['highlight'];
	else if ( isset($_GET['highlight']) )
		$content['highlightstr'] = $_GET['highlight'];
	
	// Evaluate search now
	if ( $mysearch == $content['LN_SEARCH_PERFORMADVANCED']) 
	{
		if ( isset($_POST['filter_datemode']) )
		{
			$filters['filter_datemode'] = intval($_POST['filter_datemode']);
			if ( $filters['filter_datemode'] == DATEMODE_RANGE )
			{
				// Read range values 
				if ( isset($_POST['filter_daterange_from_year']) ) 
					$filters['filter_daterange_from_year'] = intval($_POST['filter_daterange_from_year']);
				if ( isset($_POST['filter_daterange_from_month']) ) 
					$filters['filter_daterange_from_month'] = intval($_POST['filter_daterange_from_month']);
				if ( isset($_POST['filter_daterange_from_day']) ) 
					$filters['filter_daterange_from_day'] = intval($_POST['filter_daterange_from_day']);
				if ( isset($_POST['filter_daterange_to_year']) ) 
					$filters['filter_daterange_to_year'] = intval($_POST['filter_daterange_to_year']);
				if ( isset($_POST['filter_daterange_to_month']) ) 
					$filters['filter_daterange_to_month'] = intval($_POST['filter_daterange_to_month']);
				if ( isset($_POST['filter_daterange_to_day']) ) 
					$filters['filter_daterange_to_day'] = intval($_POST['filter_daterange_to_day']);
				
				// Append to searchstring
				$content['searchstr'] .= "datefrom:" .	$filters['filter_daterange_from_year'] . "-" . 
														$filters['filter_daterange_from_month'] . "-" . 
														$filters['filter_daterange_from_day'] . "T00:00:00 ";
				$content['searchstr'] .= "dateto:" .	$filters['filter_daterange_to_year'] . "-" . 
														$filters['filter_daterange_to_month'] . "-" . 
														$filters['filter_daterange_to_day'] . "T23:59:59 ";

			}
			else if ( $filters['filter_datemode'] == DATEMODE_LASTX )
			{
				if ( isset($_POST['filter_daterange_last_x']) ) 
				{
					$filters['filter_daterange_last_x'] = intval($_POST['filter_daterange_last_x']);
					$content['searchstr'] .= "datelastx:" .	$filters['filter_daterange_last_x'] . " ";
				}
			}
		}

		if ( isset($_POST['filter_facility']) && count($_POST['filter_facility']) < 18 ) // If we have more than 18 elements, this means all facilities are enabled
		{
			$tmpStr = "";
			foreach ($_POST['filter_facility'] as $tmpfacility) 
			{
				if ( strlen($tmpStr) > 0 )
					$tmpStr .= ",";
				$tmpStr .= $tmpfacility;  
			}
			$content['searchstr'] .= "facility:" . $tmpStr . " ";
		}

		if ( isset($_POST['filter_severity']) && count($_POST['filter_severity']) < 7 ) // If we have more than 7 elements, this means all facilities are enabled)
		{
			$tmpStr = "";
			foreach ($_POST['filter_severity'] as $tmpfacility) 
			{
				if ( strlen($tmpStr) > 0 )
					$tmpStr .= ",";
				$tmpStr .= $tmpfacility;  
			}
			$content['searchstr'] .= "severity:" . $tmpStr . " ";
		}

		// Spaces need to be converted!
		if ( isset($_POST['filter_syslogtag']) && strlen($_POST['filter_syslogtag']) > 0 )
		{
			if ( strpos($_POST['filter_syslogtag'], " ") === false)
				$content['searchstr'] .= "syslogtag:" . $_POST['filter_syslogtag'] . " ";
			else
				$content['searchstr'] .= "syslogtag:" . str_replace(" ", ",", $_POST['filter_syslogtag']) . " ";
		}
		
		// Spaces need to be converted!
		if ( isset($_POST['filter_source']) && strlen($_POST['filter_source']) > 0 )
		{
			if ( strpos($_POST['filter_source'], " ") === false)
				$content['searchstr'] .= "source:" . $_POST['filter_source'] . " ";
			else
				$content['searchstr'] .= "source:" . str_replace(" ", ",", $_POST['filter_source']) . " ";
		}
		
		// Message is just appended
		if ( isset($_POST['filter_message']) && strlen($_POST['filter_message']) > 0 )
			$content['searchstr'] .= $_POST['filter_message'];

	}
	else if ( $mysearch == $content['LN_SEARCH']) 
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
if ( isset($content['Sources'][$currentSourceID]) && $content['Sources'][$currentSourceID]['SourceType'] == SOURCE_DISK )
{
	// Obtain and get the Config Object
	$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

	// Create LogStream Object 
	$stream = $stream_config->LogStreamFactory($stream_config);
	$stream->SetFilter($content['searchstr']);
	$stream->Open( array ( SYSLOG_DATE, SYSLOG_FACILITY, SYSLOG_FACILITY_TEXT, SYSLOG_SEVERITY, SYSLOG_SEVERITY_TEXT, SYSLOG_HOST, SYSLOG_SYSLOGTAG, SYSLOG_MESSAGE, SYSLOG_MESSAGETYPE ), true);
	$stream->SetReadDirection(EnumReadDirection::Backward);

	$uID = $currentUID;
	$counter = 0;
	
	if ($uID != UID_UNKNOWN) 
	{
		// First read will also set the start position of the Stream!
		$ret = $stream->Read($uID, $logArray);
	}
	else
		$ret = $stream->ReadNext($uID, $logArray);


	if ( $ret == SUCCESS )
	{
		//Loop through the messages!
		do
		{
			// Copy Obtained array 
			$content['syslogmessages'][] = $logArray;

			// Copy UID
			$content['syslogmessages'][$counter]['UID'] = $uID;

			// --- Popup Details
			if ( isset($CFG['ViewEnableDetailPopups']) && $CFG['ViewEnableDetailPopups'] == 1 )
			{
				$content['syslogmessages'][$counter]['popupcaption'] = GetAndReplaceLangStr( $content['LN_GRID_POPUPDETAILS'], $content['syslogmessages'][$counter]['UID']);
				$content['syslogmessages'][$counter]['popupdetails'] = "true";
				foreach($content['syslogmessages'][$counter] as $mykey => $myfield)
				{
					// Set key!
					$content['syslogmessages'][$counter]['messagesdetails'][]['fieldtitle']= $mykey;

					// Get ArrayIndex
					$myIndex = count($content['syslogmessages'][$counter]['messagesdetails']) - 1;

					// --- Set CSS Class
					if ( $myIndex % 2 == 0 )
						$content['syslogmessages'][$counter]['messagesdetails'][$myIndex]['cssclass'] = "line1";
					else
						$content['syslogmessages'][$counter]['messagesdetails'][$myIndex]['cssclass'] = "line2";
					// --- 

					// Set field value
					$content['syslogmessages'][$counter]['messagesdetails'][$myIndex]['fieldvalue']= $myfield;
				}
			}
			else
				$content['syslogmessages'][$counter]['popupdetails'] = "false";
			// --- 

			// Set truncasted message for display
			if ( isset($logArray[SYSLOG_MESSAGE]) )
				$content['syslogmessages'][$counter][SYSLOG_MESSAGETRUNSCATED] = GetStringWithHTMLCodes(strlen($logArray[SYSLOG_MESSAGE]) > $CFG['ViewMessageCharacterLimit'] ? substr($logArray[SYSLOG_MESSAGE], 0, $CFG['ViewMessageCharacterLimit'] ) . " ..." : $logArray[SYSLOG_MESSAGE]);
			else
				$content['syslogmessages'][$counter][SYSLOG_MESSAGETRUNSCATED] = "";

			if ( isset($content['highlightwords']) )
			{	
				// We need to highlight some words ^^!
				foreach( $content['highlightwords'] as $highlightword ) 
					$content['syslogmessages'][$counter][SYSLOG_MESSAGETRUNSCATED] = preg_replace( "/(" . $highlightword['highlight'] . ")/i", '<span class="' . $highlightword['cssclass'] . '">\\1</span>', $content['syslogmessages'][$counter][SYSLOG_MESSAGETRUNSCATED] );
			}

			// --- Create Displayable DataStamp 
			$content['syslogmessages'][$counter][SYSLOG_DATE_FORMATED] = GetFormatedDate($content['syslogmessages'][$counter][SYSLOG_DATE]); 
			// --- 

			// --- Set CSS Class
			if ( $counter % 2 == 0 )
				$content['syslogmessages'][$counter]['cssclass'] = "line1";
			else
				$content['syslogmessages'][$counter]['cssclass'] = "line2";
			// --- 

			// --- Set Syslog severity and facility col colors
			if ( isset($content['syslogmessages'][$counter][SYSLOG_SEVERITY]) && strlen($content['syslogmessages'][$counter][SYSLOG_SEVERITY]) > 0)
			{
				$content['syslogmessages'][$counter]['severity_color'] = $severity_colors[$content['syslogmessages'][$counter][SYSLOG_SEVERITY]];
				$content['syslogmessages'][$counter]['severity_cssclass'] = "lineColouredWhite";
			}
			else
			{
				// Use default colour!
				$content['syslogmessages'][$counter]['severity_color'] = $severity_colors[SYSLOG_INFO];
				$content['syslogmessages'][$counter]['severity_cssclass'] = $content['syslogmessages'][$counter]['cssclass'];
			}

			if ( isset($content['syslogmessages'][$counter][SYSLOG_FACILITY]) && strlen($content['syslogmessages'][$counter][SYSLOG_FACILITY]) > 0)
			{
				$content['syslogmessages'][$counter]['facility_color'] = $facility_colors[$content['syslogmessages'][$counter][SYSLOG_FACILITY]];
				$content['syslogmessages'][$counter]['facility_cssclass'] = "lineColouredBlack";
			}
			else
			{
				// Use default colour!
				$content['syslogmessages'][$counter]['facility_color'] = $facility_colors[SYSLOG_LOCAL0];
				$content['syslogmessages'][$counter]['facility_cssclass'] = $content['syslogmessages'][$counter]['cssclass'];
			}
			// --- 

			// Increment Counter
			$counter++;
		} while ($stream->ReadNext($uID, $logArray) == SUCCESS && $counter <= $CFG['ViewEntriesPerPage']);

		if ( $stream->ReadNext($uID, $logArray) == SUCCESS ) 
		{
			$content['uid_next'] = $uID;
			// Enable Pager
			$content['main_pagerenabled'] = "true";
		}
		else if ( $currentUID != UID_UNKNOWN )
		{
			// We can still go back, enable Pager
			$content['main_pagerenabled'] = "true";
		}

		// This will enable to Main SyslogView
		$content['syslogmessagesenabled'] = "true";
	}
	else
	{
		// TODO DISPLAY MISSING LOGDATA!
	}

	// Close file!
	$stream->Close();
}


// DEBUG, create TESTING DATA!
//$content['syslogmessages'][0] = array ( SYSLOG_DATE => "Feb  7 17:56:24", SYSLOG_FACILITY => 0, SYSLOG_FACILITY_TEXT => "kernel", SYSLOG_SEVERITY => 5, SYSLOG_SEVERITY_TEXT => "notice", SYSLOG_HOST => "localhost", SYSLOG_SYSLOGTAG => "RSyslogTest", SYSLOG_MESSAGE => "Kernel log daemon terminating.", SYSLOG_MESSAGETYPE => IUT_Syslog, );
//$content['syslogmessages'][1] = array ( SYSLOG_DATE => "Feb  6 18:56:24", SYSLOG_FACILITY => 0, SYSLOG_FACILITY_TEXT => "kernel", SYSLOG_SEVERITY => 5, SYSLOG_SEVERITY_TEXT => "notice", SYSLOG_HOST => "localhost", SYSLOG_SYSLOGTAG => "RSyslogTest", SYSLOG_MESSAGE => "Kernel log daemon terminating.", SYSLOG_MESSAGETYPE => IUT_Syslog, );

// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "index.html");
$page -> output(); 
// --- 

?>