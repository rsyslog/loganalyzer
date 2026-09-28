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
$safeRedirect = SecureRedirect(isset($_GET['redir']) ? $_GET['redir'] : 'index.php');
$content['SZREDIR'] = HtmlEscapeText($safeRedirect);

// Only automatically redirect if above 0. The redirect is constrained to a
// relative internal path before it is emitted into the meta tag or link.
if ( $content['REDIRSECONDS'] > 0 )
	$content['EXTRA_METATAGS'] = '<meta HTTP-EQUIV="REFRESH" CONTENT="' . intval($content['REDIRSECONDS']) . '; URL=' . HtmlEscapeAttribute($safeRedirect) . '">';

if ( isset($_GET['msg']) )
	$content['SZMSG'] = HtmlEscapeText(DB_StripSlahes(is_scalar($_GET['msg']) ? $_GET['msg'] : ''));
else
	$content['SZMSG'] = HtmlEscapeText($content["LN_ADMIN_UNKNOWNSTATE"]);

if ( $content['REDIRSECONDS'] > 0 ) {
	$content['TITLE'] = HtmlEscapeText("LogAnalyzer - Redirecting to '" . $safeRedirect . "' in " . intval($content['REDIRSECONDS']) . " seconds");	// Title of the Page
	$content['LN_ADMIN_RESULTLINK'] = GetAndReplaceLangStr($content['LN_ADMIN_RESULTREDIRECT'], HtmlEscapeAttribute($safeRedirect), intval($content['REDIRSECONDS']));
} else {
	$content['TITLE'] = HtmlEscapeText("LogAnalyzer - Redirecting to '" . $safeRedirect . "'");	// Title of the Page
	$content['LN_ADMIN_RESULTLINK'] = GetAndReplaceLangStr($content['LN_ADMIN_RESULTCLICK'], HtmlEscapeAttribute($safeRedirect));
}
// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/result.html");
$page -> output(); 
// --- 

?>
