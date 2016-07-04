<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Details File											
	*																	
	* ->	This "oracle" is a helper page which generates and shows a bunch
	*		of usefull links ;)!
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

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!
// ---

// --- Define Extra Stylesheet!
//$content['EXTRA_STYLESHEET']  = '<link rel="stylesheet" href="css/highlight.css" type="text/css">' . "\r\n";
//$content['EXTRA_STYLESHEET'] .= '<link rel="stylesheet" href="css/menu.css" type="text/css">';
// --- 

// --- READ Vars
if ( isset($_GET['type']) ) 
	$content['oracle_type'] = $_GET['type'];
else
	$content['oracle_type'] = "";

if ( isset($_GET['query']) ) 
	$content['oracle_query'] = $_GET['query'];
else
	$content['oracle_query'] = "";

if ( isset($_GET['uid']) ) 
	$content['uid_current'] = $_GET['uid'];
else
	$content['uid_current'] = "-1";

// Init 

// --- BEGIN Custom Code

// Set readable type
if ( $content['oracle_type'] == "ip" ) 
{
	$content['oracle_type_readable'] = "ip";
	$content['oracle_kb_type'] = "ip";

	if ( IsInternalIP($content['oracle_query']) )
		$content['showonlinesearches'] = false;
	else
		$content['showonlinesearches'] = true;
}
else if ( $content['oracle_type'] == "domain" ) 
{
	$content['oracle_type_readable'] = "domain";
	$content['oracle_kb_type'] = "name";
	$content['showonlinesearches'] = true;
}
else if ( $content['oracle_type'] == "searchstr" ) 
{
	$content['oracle_type_readable'] = "custom search";
	$content['oracle_kb_type'] = "misc";
	$content['showonlinesearches'] = false;
}
else
{
	$content['oracle_type_readable'] = "unknown type";
	$content['oracle_kb_type'] = "";
	$content['showonlinesearches'] = false;
}

$content['ORACLE_HELP_DETAIL'] = GetAndReplaceLangStr( $content['LN_ORACLE_HELP_DETAIL'], $content['oracle_type_readable'], urlencode($content['oracle_query']) ) ;
$content['ORACLE_HELP_TEXT'] = GetAndReplaceLangStr( $content['LN_ORACLE_HELP_TEXT'], $content['oracle_type_readable'], urlencode($content['oracle_query']), $content['LN_ORACLE_HELP_TEXT_EXTERNAL'] ) ;
$content['ORACLE_WHOIS'] = GetAndReplaceLangStr( $content['LN_ORACLE_WHOIS'], $content['oracle_type_readable'], urlencode($content['oracle_query']) ) ;
$content['WhoisUrl'] = "http://kb.monitorware.com/kbsearch.php?sa=whois&oid=" . $content['oracle_kb_type'] . "&origin=phplogcon&q=" . urlencode($content['oracle_query']); 

// Set Field Captions!
$content['LN_FIELDS_MESSAGE'] = $fields[SYSLOG_MESSAGE]['FieldCaption'];
$content['LN_FIELDS_HOST'] = $fields[SYSLOG_HOST]['FieldCaption'];


// Enable help links!
$content['helplinksenabled'] = true;

// Loop through all Sources
$i = 0;
foreach( $content['Sources'] as $mySource )
{
	$myHelpLink['SourceName'] = $mySource['Name'];
	$myHelpLink['MsgUrl'] = $content['BASEPATH'] . "index.php?filter=" . urlencode($content['oracle_query']) . "&search=Search&sourceid=" . $mySource['ID'];
//	$myHelpLink['MsgDisplayName'] = GetAndReplaceLangStr( $content['LN_ORACLE_SEARCHINFIELD'], "Message" );
	$myHelpLink['SourceUrl'] = $content['BASEPATH'] . "index.php?filter=" . urlencode("source:=" . $content['oracle_query']) . "&search=Search&sourceid=" . $mySource['ID'];
//	$myHelpLink['SourceDisplayName'] = GetAndReplaceLangStr( $content['LN_ORACLE_SEARCHINFIELD'], "Source" );

	// --- Set CSS Class
	if ( $i % 2 == 0 )
		$myHelpLink['cssclass'] = "line1";
	else
		$myHelpLink['cssclass'] = "line2";
	$i++;
	// --- 
	
	// Add to help Link array!
	$content['HelpLinks'][] = $myHelpLink;
}
// --- 

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
// Append custom title part!
$content['TITLE'] .= GetAndReplaceLangStr( $content['LN_ORACLE_TITLE'], urlencode($content['oracle_query']));
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "asktheoracle.html");
$page -> output(); 
// --- 

?>