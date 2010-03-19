<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Admin Index File											
	*																	
	* -> Shows ...
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

// Configureable now!
$content['REDIRSECONDS'] =  GetConfigSetting("AdminChangeWaitTime", 2, CFGLEVEL_USER);
// ***					*** //

// --- CONTENT Vars
if ( isset($_GET['redir']) )
{
	$content['EXTRA_METATAGS'] = '<meta HTTP-EQUIV="REFRESH" CONTENT="' . $content['REDIRSECONDS'] . '; URL=' . urldecode($_GET['redir']) . '">';
	$content['SZREDIR'] = urldecode($_GET['redir']);
}
else
{
	$_GET['redir'] = "index.php";
}

if ( isset($_GET['msg']) )
	$content['SZMSG'] = DB_StripSlahes($_GET['msg']);
else
	$content['SZMSG'] = $content["LN_ADMIN_UNKNOWNSTATE"]; 

$content['TITLE'] = "LogAnalyzer - Redirecting to '" . $content['SZREDIR'] . "' in " . $content['REDIRSECONDS'] . " seconds";	// Title of the Page 
// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/result.html");
$page -> output(); 
// --- 

?>