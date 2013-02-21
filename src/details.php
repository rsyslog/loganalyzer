<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Details File											
	*																	
	* -> Shows all possible details of a syslog message
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
//$content['EXTRA_STYLESHEET']  = '<link rel="stylesheet" href="css/highlight.css" type="text/css">' . "\r\n";
//$content['EXTRA_STYLESHEET'] .= '<link rel="stylesheet" href="css/menu.css" type="text/css">';
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

// Copy UID for later use ...
$content['uid_fromgetrequest'] = $content['uid_current'];

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
// --- 

// --- If set read direction property!

// Set direction default
$content['read_direction'] = EnumReadDirection::Backward;

if ( isset($_GET['direction']) )
{
	if ( $_GET['direction'] == "next" ) 
	{
		$content['skiprecords'] = 1;
		$content['read_direction'] = EnumReadDirection::Backward;
	}
	else if ( $_GET['direction'] == "previous" ) 
	{
		$content['skiprecords'] = 1;
		$content['read_direction'] = EnumReadDirection::Forward;
	}
	else if ( $_GET['direction'] == "desc" )
	{
		$content['read_direction'] = EnumReadDirection::Forward;
	}
}

// Read filter property in
	if		( isset($_POST['filter']) )
		$myfilter = $_POST['filter'];
	else if ( isset($_GET['filter']) )
		$myfilter = $_GET['filter'];
	else 
		$myfilter = "";
// ---

// Init Sorting variables
$content['sorting'] = "";
$content['searchstr'] = $myfilter;
$content['highlightstr'] = "";
$content['EXPAND_HIGHLIGHT'] = "false";

// --- BEGIN Custom Code
if ( isset($content['Sources'][$currentSourceID]) ) // && $content['uid_current'] != UID_UNKNOWN ) // && $content['Sources'][$currentSourceID]['SourceType'] == SOURCE_DISK )
{
	// Obtain and get the Config Object
	$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

	// Create LogStream Object 
	$stream = $stream_config->LogStreamFactory($stream_config);
	$stream->SetFilter($content['searchstr']);

	// --- Init the fields we need
	foreach($fields as $mycolkey => $myfield)
	{
		$content['fields'][$mycolkey]['FieldID'] = $mycolkey;
		$content['fields'][$mycolkey]['FieldCaption'] = $myfield['FieldCaption'];
		$content['fields'][$mycolkey]['FieldType'] = $myfield['FieldType'];
		$content['fields'][$mycolkey]['DefaultWidth'] = $myfield['DefaultWidth'];

		// Append to columns array
		$content['AllColumns'][] = $mycolkey;
	}
	// --- 

	$res = $stream->Open( $content['AllColumns'], true );
	if ( $res == SUCCESS ) 
	{
		// Set Read direction
		$stream->SetReadDirection($content['read_direction']);
		
		// Set current ID and init Counter
		$uID = $content['uid_current'];
		
		if ( $uID != UID_UNKNOWN )	// We know the UID, so read from where we know
			$ret = $stream->Read($uID, $logArray);
		else						// Unknown UID, so we start from first!
			$ret = $stream->ReadNext($uID, $logArray);

		// --- If set we move forward / backward!
		if ( isset($content['skiprecords']) && $content['skiprecords'] >= 1 )
		{
			$counter = 0;
			while( $counter < $content['skiprecords'] && ($ret = $stream->ReadNext($uID, $logArray)) == SUCCESS)
			{
				// Increment Counter
				$counter++;
			}
		}
		// --- 

		// Set new current uid!
		if ( isset($uID) && $uID != UID_UNKNOWN ) 
			$content['uid_current'] = $uID;
		
		// now we know enough to set the page title!
		$content['TITLE'] = "LogAnalyzer :: " . $content['LN_DETAILS_DETAILSFORMSG'] . " '" . $uID . "'";

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
			// ---

			// Loop through fields - Copy value into fields list! We are going to use this list here
			$counter = 0;
			foreach($content['fields'] as $mycolkey => $myfield)
			{
				if ( isset($logArray[$mycolkey]) && ( is_array($logArray[$mycolkey]) || (is_string($logArray[$mycolkey]) && strlen($logArray[$mycolkey]) > 0)) || (is_numeric($logArray[$mycolkey])) )
				{
					$content['fields'][$mycolkey]['fieldenabled'] = true;

	//				// Default copy value into array!
	//				$content['fields'][$mycolkey]['FieldValue'] = $logArray[$mycolkey];

					// --- Set CSS Class
					if ( $counter % 2 == 0 )
						$content['fields'][$mycolkey]['cssclass'] = "line1";
					else
						$content['fields'][$mycolkey]['cssclass'] = "line2";

					if ( $mycolkey == SYSLOG_MESSAGE )
						$content['fields'][$mycolkey]['menucssclass'] = "cellmenu1_naked";
					else
						$content['fields'][$mycolkey]['menucssclass'] = "cellmenu1";
					// --- 

					// Set defaults
					$content['fields'][$mycolkey]['fieldbgcolor'] = "";
					$content['fields'][$mycolkey]['hasdetails'] = "false";

					if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_DATE )
					{
						$content['fields'][$mycolkey]['fieldvalue'] = GetFormatedDate($logArray[$mycolkey]); 
						// TODO: Show more!
					}
					else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_NUMBER )
					{
						$content['fields'][$mycolkey]['fieldvalue'] = $logArray[$mycolkey];

						// Special style classes and colours for SYSLOG_FACILITY
						if ( $mycolkey == SYSLOG_FACILITY )
						{
//							if ( isset($logArray[$mycolkey][SYSLOG_FACILITY]) && strlen($logArray[$mycolkey][SYSLOG_FACILITY]) > 0)
							if ( isset($logArray[$mycolkey]) && is_numeric($logArray[$mycolkey]) )
							{
								$content['fields'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $facility_colors[ $logArray[SYSLOG_FACILITY] ] . '" ';
								$content['fields'][$mycolkey]['cssclass'] = "lineColouredBlack";

								// Set Human readable Facility!
								$content['fields'][$mycolkey]['fieldvalue'] = GetFacilityDisplayName( $logArray[$mycolkey] );
							}
							else
							{
								// Use default colour!
								$content['fields'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $facility_colors[SYSLOG_LOCAL0] . '" ';
							}
						}
						else if ( $mycolkey == SYSLOG_SEVERITY )
						{
//							if ( isset($logArray[$mycolkey][SYSLOG_SEVERITY]) && strlen($logArray[$mycolkey][SYSLOG_SEVERITY]) > 0)
							if ( isset($logArray[$mycolkey]) && is_numeric($logArray[$mycolkey]) )
							{
								$content['fields'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $severity_colors[ $logArray[SYSLOG_SEVERITY] ] . '" ';
								$content['fields'][$mycolkey]['cssclass'] = "lineColouredWhite";

								// Set Human readable Facility!
								$content['fields'][$mycolkey]['fieldvalue'] = GetSeverityDisplayName( $logArray[$mycolkey] );
							}
							else
							{
								// Use default colour!
								$content['fields'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $severity_colors[SYSLOG_INFO] . '" ';
							}
						}
						else if ( $mycolkey == SYSLOG_MESSAGETYPE )
						{
//							if ( isset($logArray[$mycolkey][SYSLOG_MESSAGETYPE]) )
							if ( isset($logArray[$mycolkey]) && is_numeric($logArray[$mycolkey]) )
							{
								$content['fields'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $msgtype_colors[ $logArray[SYSLOG_MESSAGETYPE] ] . '" ';
								$content['fields'][$mycolkey]['cssclass'] = "lineColouredBlack";

								// Set Human readable Facility!
								$content['fields'][$mycolkey]['fieldvalue'] = GetMessageTypeDisplayName( $logArray[$mycolkey] );
							}
							else
							{
								// Use default colour!
								$content['fields'][$mycolkey]['fieldbgcolor'] = 'bgcolor="' . $msgtype_colors[IUT_Unknown] . '" ';
							}
							
						}
					}
					else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_STRING )
					{
						if ( $mycolkey == SYSLOG_MESSAGE )
							$content['fields'][$mycolkey]['fieldvalue'] = ReplaceLineBreaksInString( GetStringWithHTMLCodes($logArray[$mycolkey]) );
						else	// kindly copy!
							$content['fields'][$mycolkey]['fieldvalue'] = ReplaceLineBreaksInString( $logArray[$mycolkey] );

						// --- HOOK here to add context links!
						AddContextLinks($content['fields'][$mycolkey]['fieldvalue']);
						// --- 
					}

					// Increment helpcounter
					$counter++;
				}
				else
					$content['fields'][$mycolkey]['fieldenabled'] = false;

			}
			
			// --- Now Check for dynamic fields!
			$counter = 0;
			foreach($logArray as $mydynkey => $mydynvalue)
			{
				// Check if field is already in fields array
				if (  !isset($content['fields'][$mydynkey]) && isset($mydynvalue) && strlen($mydynvalue) > 0 )
				{
					$content['dynamicfields'][$mydynkey]['dynfieldkey'] = $mydynkey;
					$content['dynamicfields'][$mydynkey]['dynfieldvalue'] = $mydynvalue;
					
					// --- Set CSS Class
					if ( $counter % 2 == 0 )
						$content['dynamicfields'][$mydynkey]['dyncssclass'] = "line1";
					else
						$content['dynamicfields'][$mydynkey]['dyncssclass'] = "line2";
					// ---

					// Increment helpcounter
					$counter++;
				}
			}
			// Enable dynamic Fields
			if ( isset($content['dynamicfields']) )
				$content['dynamicfieldsenabled'] = "true";
			// --- 

//	echo "<pre>";
//	var_dump($content['dynamicfields']);
//	echo "</pre>";
			
			// Enable pager if the count is above 1 or we don't know the record count!
			if ( $content['main_recordcount'] > 1 || $content['main_recordcount'] == -1 )
			{
				// Enable Pager in any case here!
				$content['main_pagerenabled'] = true;

				// --- Handle uid_first page button 
				if ( $content['uid_fromgetrequest'] == $content['uid_first'] && $content['read_direction'] != EnumReadDirection::Forward ) 
					$content['main_pager_first_found'] = false;
				else
				{
					// Probe next item !
					$ret = $stream->ReadNext($uID, $tmpArray);

					if ( $content['read_direction'] == EnumReadDirection::Backward )
					{
						if ( $content['uid_fromgetrequest'] != UID_UNKNOWN )
							$content['main_pager_first_found'] = true;
						else
							$content['main_pager_first_found'] = false;
					}
					else
					{
						if ( $ret == SUCCESS && $uID != $content['uid_fromgetrequest'])
							$content['main_pager_first_found'] = true;
						else
							$content['main_pager_first_found'] = false;
					}
				}
				// --- 

				// --- Handle uid_last page button 
				if ( $content['uid_fromgetrequest'] == $content['uid_last'] && $content['read_direction'] != EnumReadDirection::Backward ) 
					$content['main_pager_last_found'] = false;
				else
				{
					// Probe next item !
					$ret = $stream->ReadNext($uID, $tmpArray);

					if ( $content['read_direction'] == EnumReadDirection::Forward )
					{
						if ( $ret != SUCCESS || $uID != $content['uid_current'] )
							$content['main_pager_last_found'] = true;
						else
							$content['main_pager_last_found'] = false;
					}
					else
					{
						if ( $ret == SUCCESS && $uID != $content['uid_current'] )
							$content['main_pager_last_found'] = true;
						else
							$content['main_pager_last_found'] = false;
					}
				}
				// --- 

				// --- Handle uid_last page button 
				// Option the last UID from the stream!
//				$content['uid_last'] = $stream->GetLastPageUID();
//				$content['uid_first'] = $stream->GetFirstPageUID();

				// --- Handle uid_first and uid_previousbutton
				if ( $content['uid_current'] == $content['uid_first'] || !$content['main_pager_first_found'] ) 
				{
					$content['main_pager_first_found'] = false;
					$content['main_pager_previous_found'] = false;
				}
				else
				{
					$content['main_pager_first_found'] = true;
					$content['main_pager_previous_found'] = true;
				}
				// ---

				// --- Handle uid_next and uid_last button 
				if ( /*$content['uid_current'] == $content['uid_last'] ||*/ !$content['main_pager_last_found'] ) 
				{
					$content['main_pager_next_found'] = false;
					$content['main_pager_last_found'] = false;
				}
				else
				{
					$content['main_pager_next_found'] = true;
					$content['main_pager_last_found'] = true;
				}
				// ---
			}
			else	// Disable pager in this case!
				$content['main_pagerenabled'] = false;

			// This will enable to Main SyslogView
			$content['messageenabled'] = "true";
		}
		else
		{
			// Disable view and print error state!
			$content['messageenabled'] = "false";

			// Set error code 
			$content['error_code'] = $ret;
			

		if ( $ret == ERROR_UNDEFINED ) 
			$content['detailederror'] = "Undefined error happened within the logstream.";
		else 
			$content['detailederror'] = "Unknown or unhandeled error occured.";
		// Add extra error stuff
		if ( isset($extraErrorDescription) )
			$content['detailederror'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);

		}
	}
	else
	{
		// This will disable to Main SyslogView and show an error message
		$content['messageenabled'] = "false";

		// Set error code 
		$content['error_code'] = $ret;

		if ( $ret == ERROR_FILE_NOT_FOUND ) 
			$content['detailederror'] = $content['LN_ERROR_FILE_NOT_FOUND'];
		else if ( $ret == ERROR_FILE_NOT_READABLE ) 
			$content['detailederror'] = $content['LN_ERROR_FILE_NOT_READABLE'];
		else 
			$content['detailederror'] = $content['LN_ERROR_UNKNOWN'];
	}

	// Close file!
	$stream->Close();
}
// --- 

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();

if ( $content['messageenabled'] == "true" ) 
{
	// Append custom title part!
	$content['TITLE'] .= " :: Details for '" . $content['uid_current'] . "'";
}
else
{
	// APpend to title Page title
	$content['TITLE'] .= " :: Unknown uid";
}
// --- END CREATE TITLE


// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "details.html");
$page -> output(); 
// --- 


?>