<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-
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
	* distribution.
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');

InitPhpLogCon();
InitFrontEndDefaults();	// Only in WebFrontEnd
// ***					*** //

// --- BEGIN Custom Code
if ( isset($_SERVER['HTTP_REFERER']) )
	$szRedir = $_SERVER['HTTP_REFERER']; 
else
	$szRedir = "index.php"; // Default


if ( isset($_GET['op']) )
{
	if ( $_GET['op'] == "changestyle" && isset($_GET['stylename']) ) 
	{
		if ( VerifyTheme($_GET['stylename']) ) 
			$_SESSION['CUSTOM_THEME'] = $_GET['stylename'];
	}

	if ( $_GET['op'] == "changelang" && isset($_GET['langcode']) ) 
	{
		if ( VerifyLanguage($_GET['langcode']) ) 
			$_SESSION['CUSTOM_LANG'] = $_GET['langcode'];
	}

	if ( $_GET['op'] == "changepagesize" && isset($_GET['pagesizeid']) ) 
	{
		if ( intval($_GET['pagesizeid']) >= 0 && intval($_GET['pagesizeid']) < count($content['pagesizes']) ) 
			$_SESSION['PAGESIZE_ID'] = intval($_GET['pagesizeid']);
	}

	if ( $_GET['op'] == "autoreload" && isset($_GET['autoreloadtime']) ) 
	{
		if ( intval($_GET['autoreloadtime']) >= 0 && intval($_GET['autoreloadtime']) < count($content['reloadtimes']) ) 
			$_SESSION['AUTORELOAD_ID'] = intval($_GET['autoreloadtime']);
	}
	
}

// Final redirect
RedirectPage( $szRedir );
// --- 
?>
