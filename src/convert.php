<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Configuration Converter File											
	*																	
	* -> Helps to convert from config file to userdb if desired 
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

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd

// --- PreCheck if conversion is allowed!
if ( 
		(isset($CFG['UserDBEnabled']) && $CFG['UserDBEnabled']) &&
		(isset($CFG['UserDBConvertAllowed']) && $CFG['UserDBConvertAllowed']) 
	) 
{
	// Setup static values
	define('MAX_STEPS', 6);
	$content['web_theme'] = "default";
	$content['user_theme'] = "default";
}
else
	DieWithErrorMsg( 'phpLogCon is not allowed to convert your settings into the user database.<br><br> If you want to convert your convert your settings, add the variable following into your config.php: <br><b>$CFG[\'UserDBConvertAllowed\'] = true;</b><br><br> Click <A HREF="index.php">here</A> to return to pgpLogCon start page.');
// --- 

// --- CONTENT Vars
$content['TITLE'] = "phpLogCon :: " . $content['LN_CONVERT_TITLE'];
// --- 

// --- Read Vars
if ( isset($_GET['step']) )
{
	$content['CONVERT_STEP'] = intval(DB_RemoveBadChars($_GET['step']));
	if ( $content['CONVERT_STEP'] > MAX_STEPS ) 
		$content['CONVERT_STEP'] = 1;
}
else
	$content['CONVERT_STEP'] = 1;

// Set Next Step 
$content['CONVERT_NEXT_STEP'] = $content['CONVERT_STEP'];

if ( MAX_STEPS > $content['CONVERT_STEP'] )
{
	$content['NEXT_ENABLED'] = "true";
	$content['FINISH_ENABLED'] = "false";
	$content['CONVERT_NEXT_STEP']++;
}
else
{
	$content['NEXT_ENABLED'] = "false";
	$content['FINISH_ENABLED'] = "true";
}
// --- 

// --- BEGIN Custom Code

// Set Bar Images
$content['BarImagePlus'] = $gl_root_path . "images/bars/bar-middle/green_middle_17.png";
$content['BarImageLeft'] = $gl_root_path . "images/bars/bar-middle/green_left_17.png";
$content['BarImageRight'] = $gl_root_path . "images/bars/bar-middle/green_right_17.png";
$content['WidthPlus'] = intval( $content['CONVERT_STEP'] * (100 / MAX_STEPS) ) - 8;
$content['WidthPlusText'] = "Installer Step " . $content['CONVERT_STEP'];

// --- Set Title
$content['TITLE'] = GetAndReplaceLangStr( $content['TITLE'], $content['CONVERT_STEP'] );
// --- 

// --- Start Setup Processing
if ( $content['CONVERT_STEP'] == 2 )
{	
	// Check the database connect
	$link_id = mysql_connect( $CFG['UserDBServer'], $CFG['UserDBUser'], $CFG['UserDBPass']);
	if (!$link_id) 
		RevertOneStep( $content['INSTALL_STEP']-1, "Connect to " .$CFG['UserDBServer'] . " failed! Check Servername, Port, User and Password!<br>" . DB_ReturnSimpleErrorMsg() );
	
	// Try to select the DB!
	$db_selected = mysql_select_db($CFG['UserDBName'], $link_id);
	if(!$db_selected) 
		RevertOneStep( $content['INSTALL_STEP']-1, "Cannot use database  " .$CFG['UserDBName'] . "! If the database does not exists, create it or check access permissions! <br>" . DB_ReturnSimpleErrorMsg());

	
}
else if ( $content['CONVERT_STEP'] == 3 )
{	

}
// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "convert.html");
$page -> output(); 
// ---

// --- Helper functions

function RevertOneStep($stepback, $errormsg)
{
	header("Location: convert.php?step=" . $stepback . "&errormsg=" . urlencode($errormsg) );
	exit;
}

function ForwardOneStep()
{
	global $content; 

	header("Location: convert.php?step=" . ($content['CONVERT_STEP']+1) );
	exit;
}
// ---
?>