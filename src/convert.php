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
define('STEPSCRIPTNAME', "convert.php");	// Helper variable for the STEP helper functions
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_installhelpers.php');

// This site can not require LOGIN
define('IS_NOLOGINPAGE', true);
$content['IS_NOLOGINPAGE'] = true;
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
	DieWithErrorMsg( $content['LN_CONVERT_ERRORINSTALLED'] );
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
$content['LN_CONVERT_TITLETOP'] = GetAndReplaceLangStr( $content['LN_CONVERT_TITLETOP'], $content['CONVERT_STEP'] );
// --- 

// --- Start Setup Processing
if ( $content['CONVERT_STEP'] == 2 )
{	
	// Check the database connect
	$link_id = mysql_connect( $CFG['UserDBServer'], $CFG['UserDBUser'], $CFG['UserDBPass']);
	if (!$link_id) 
		RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr( $content['LN_INSTALL_ERRORCONNECTFAILED'], $CFG['UserDBServer']) . "<br>" . DB_ReturnSimpleErrorMsg() );
	
	// Try to select the DB!
	$db_selected = mysql_select_db($CFG['UserDBName'], $link_id);
	if(!$db_selected) 
		RevertOneStep( $content['INSTALL_STEP']-1,GetAndReplaceLangStr( $content['LN_INSTALL_ERRORACCESSDENIED'], $CFG['UserDBName']) . "<br>" . DB_ReturnSimpleErrorMsg());

	
}
else if ( $content['CONVERT_STEP'] == 3 )
{	
	// Predefine sql helper vars
	$content['sql_sucess'] = 0;
	$content['sql_failed'] = 0;

	// Import default database if user db is enabled!
	if ( $_SESSION['UserDBEnabled'] == 1 )
	{
		// Init $totaldbdefs
		$totaldbdefs = "";

		// Read the table GLOBAL definitions 
		ImportDataFile( $content['BASEPATH'] . "include/db_template.txt" );

		// Process definitions ^^
		if ( strlen($totaldbdefs) <= 0 )
		{
			$content['failedstatements'][ $content['sql_failed'] ]['myerrmsg'] = GetAndReplaceLangStr( $content['LN_INSTALL_ERRORINVALIDDBFILE'], $content['BASEPATH'] . "include/db_template.txt");
			$content['failedstatements'][ $content['sql_failed'] ]['mystatement'] = "";
			$content['sql_failed']++;
		}

		// Replace stats_ with the custom one ;)
		$totaldbdefs = str_replace( "`logcon_", "`" . $_SESSION["UserDBPref"], $totaldbdefs );
		
		// Now split by sql command
		$mycommands = split( ";\n", $totaldbdefs );
		
//		// check for different linefeed
//		if ( count($mycommands) <= 1 )
//			$mycommands = split( ";\n", $totaldbdefs );

		//Still only one? Abort
		if ( count($mycommands) <= 1 )
		{
			$content['failedstatements'][ $content['sql_failed'] ]['myerrmsg'] = GetAndReplaceLangStr( $content['LN_INSTALL_ERRORINSQLCOMMANDS'], $content['BASEPATH'] . "include/db_template.txt"); 
			$content['failedstatements'][ $content['sql_failed'] ]['mystatement'] = "";
			$content['sql_failed']++;
		}

		// Append INSERT Statement for Config Table to set the GameVersion and Database Version ^^!
		$mycommands[count($mycommands)] = "INSERT INTO `" . $_SESSION["UserDBPref"] . "config` (`propname`, `propvalue`, `is_global`) VALUES ('database_installedversion', '1', 1)";

		// --- Now execute all commands
		ini_set('error_reporting', E_WARNING); // Enable Warnings!
		InitUserDbSettings();

		// Establish DB Connection
		DB_Connect();

		for($i = 0; $i < count($mycommands); $i++)
		{
			if ( strlen(trim($mycommands[$i])) > 1 )
			{
				$result = DB_Query( $mycommands[$i], false );
				if ($result == FALSE)
				{
					$content['failedstatements'][ $content['sql_failed'] ]['myerrmsg'] = DB_ReturnSimpleErrorMsg();
					$content['failedstatements'][ $content['sql_failed'] ]['mystatement'] = $mycommands[$i];

					// --- Set CSS Class
					if ( $content['sql_failed'] % 2 == 0 )
						$content['failedstatements'][ $content['sql_failed'] ]['cssclass'] = "line1";
					else
						$content['failedstatements'][ $content['sql_failed'] ]['cssclass'] = "line2";
					// --- 

					$content['sql_failed']++;
				}
				else
					$content['sql_sucess']++;

				// Free result
				DB_FreeQuery($result);
			}
		}
	}
}
// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "convert.html");
$page -> output(); 
// ---

// --- Helper functions
// ---
?>