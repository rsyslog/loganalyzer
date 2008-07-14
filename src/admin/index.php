<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Admin Index File											
	*																	
	* -> Shows ...
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
$gl_root_path = './../';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
// include($gl_root_path . 'classes/logstream.class.php');

// Set PAGE to be ADMINPAGE!
define('IS_ADMINPAGE', true);
$content['IS_ADMINPAGE'] = true;

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!

// Init admin langauge file now!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/admin.php' );

// --- Define Extra Stylesheet!
//$content['EXTRA_STYLESHEET']  = '<link rel="stylesheet" href="css/highlight.css" type="text/css">' . "\r\n";
//$content['EXTRA_STYLESHEET'] .= '<link rel="stylesheet" href="css/menu.css" type="text/css">';
// --- 

// --- CONTENT Vars
/* 
if ( isset($_GET['uid']) ) 
	$content['uid_current'] = intval($_GET['uid']);
else
	$content['uid_current'] = UID_UNKNOWN;

// Copy UID for later use ...
$content['uid_fromgetrequest'] = $content['uid_current'];

// Init Pager variables
$content['uid_first'] = UID_UNKNOWN;
$content['uid_last'] = UID_UNKNOWN;
$content['main_pagerenabled'] = false;
$content['main_pager_first_found'] = false;
$content['main_pager_previous_found'] = false;
$content['main_pager_next_found'] = false;
$content['main_pager_last_found'] = false;

// Set Default reading direction 
$content['read_direction'] = EnumReadDirection::Backward;

// If set read direction property!
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
}
*/

/*
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
*/

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_index.html");
$page -> output(); 
// --- 


?>