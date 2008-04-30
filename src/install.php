<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Main Index File											
	*																	
	* -> Installer File
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
define('IN_PHPLOGCON_INSTALL', true); // Extra for INSTALL Script!
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');

// Init Langauge first!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

InitBasicPhpLogCon();
//InitPhpLogCon();

// Set some static values
define('MAX_STEPS', 8);
$content['web_theme'] = "default";
$content['user_theme'] = "default";
$configsamplefile = $content['BASEPATH'] . "include/config.sample.php"; 

//ini_set('error_reporting', E_ERROR); // NO PHP ERROR'S!
// ***					*** //

// --- CONTENT Vars
$content['TITLE'] = "phpLogCon :: Installer Step %1";
// --- 

// --- Read Vars
if ( isset($_GET['step']) )
{
	$content['INSTALL_STEP'] = intval(DB_RemoveBadChars($_GET['step']));
	if ( $content['INSTALL_STEP'] > MAX_STEPS ) 
		$content['INSTALL_STEP'] = 1;
}
else
	$content['INSTALL_STEP'] = 1;

// Set Next Step 
$content['INSTALL_NEXT_STEP'] = $content['INSTALL_STEP'];

if ( MAX_STEPS > $content['INSTALL_STEP'] )
{
	$content['NEXT_ENABLED'] = "true";
	$content['FINISH_ENABLED'] = "false";
	$content['INSTALL_NEXT_STEP']++;
}
else
{
	$content['NEXT_ENABLED'] = "false";
	$content['FINISH_ENABLED'] = "true";
}
// --- 



// --- BEGIN Custom Code

// --- Set Bar Image
	$content['BarImagePlus'] = $gl_root_path . "images/bars/bar-middle/green_middle_17.png";
	$content['BarImageLeft'] = $gl_root_path . "images/bars/bar-middle/green_left_17.png";
	$content['BarImageRight'] = $gl_root_path . "images/bars/bar-middle/green_right_17.png";
	$content['WidthPlus'] = intval( $content['INSTALL_STEP'] * (100 / MAX_STEPS) ) - 8;
	$content['WidthPlusText'] = "Installer Step " . $content['INSTALL_STEP'];
// --- 

// --- Set Title
GetAndReplaceLangStr( $content['TITLE'], $content['INSTALL_STEP'] );
// --- 

// --- Start Setup Processing
if ( $content['INSTALL_STEP'] == 2 )
{	
	// Check if file permissions are correctly
	$content['fileperm'][0]['FILE_NAME'] = $content['BASEPATH'] . "config.php"; 
	$content['fileperm'][0]['FILE_TYPE'] = "file"; 
//	$content['fileperm'][1]['FILE_NAME'] = $content['BASEPATH'] . "gamelogs/"; 
//	$content['fileperm'][1]['FILE_TYPE'] = "dir"; 

//	Check file by file
	$bSuccess = true;
	for($i = 0; $i < count($content['fileperm']); $i++)
	{
		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$content['fileperm'][$i]['cssclass'] = "line1";
		else
			$content['fileperm'][$i]['cssclass'] = "line2";
		// --- 

		if ( $content['fileperm'][$i]['FILE_TYPE'] == "dir" ) 
		{
			// Get Permission mask
			$perms = fileperms( $content['fileperm'][$i]['FILE_NAME'] );

			// World
			$iswriteable = (($perms & 0x0004) ? true : false) && (($perms & 0x0002) ? true : false);
			if ( $iswriteable ) 
			{
				$content['fileperm'][$i]['BGCOLOR'] = "#007700";
				$content['fileperm'][$i]['ISSUCCESS'] = "Writeable"; 
			}
			else
			{
				$content['fileperm'][$i]['BGCOLOR'] = "#770000";
				$content['fileperm'][$i]['ISSUCCESS'] = "NOT Writeable"; 
				$bSuccess = false;
			}
		}
		else
		{
			if ( !is_file($content['fileperm'][$i]['FILE_NAME']) ) 
			{
				// Try to create an empty file
				touch($content['fileperm'][$i]['FILE_NAME']);
			}

			if ( is_file($content['fileperm'][$i]['FILE_NAME']) ) 
			{
				if ( is_writable($content['fileperm'][$i]['FILE_NAME']) ) 
				{
					$content['fileperm'][$i]['BGCOLOR'] = "#007700";
					$content['fileperm'][$i]['ISSUCCESS'] = "Writeable"; 
				}
				else
				{
					$content['fileperm'][$i]['BGCOLOR'] = "#770000";
					$content['fileperm'][$i]['ISSUCCESS'] = "NOT Writeable"; 
					$bSuccess = false;
				}
			}
			else
			{
				$content['fileperm'][$i]['BGCOLOR'] = "#770000";
				$content['fileperm'][$i]['ISSUCCESS'] = "File does NOT exist!"; 
				$bSuccess = false;
			}
		}
	}

	if ( !$bSuccess )
	{
		$content['NEXT_ENABLED'] = "false";
		$content['RECHECK_ENABLED'] = "true";
		$content['iserror'] = "true";
		$content['errormsg'] = "One file or directory (or more) are not writeable, please check the file permissions (chmod 777)!";
	}

	// Check if sample config file is available
	if ( !is_file($configsamplefile) || GetFileLength($configsamplefile) <= 0 )
	{
		$content['NEXT_ENABLED'] = "false";
		$content['RECHECK_ENABLED'] = "true";
		$content['iserror'] = "true";
		$content['errormsg'] = "The sample configuration file '" . $configsamplefile . "' is missing. You have not fully uploaded phplogcon.";
	}
	
}
else if ( $content['INSTALL_STEP'] == 3 )
{	
	// --- Read and predefine Database options
	if ( isset($_SESSION['UserDBEnabled']) ) { $content['UserDBEnabled'] = $_SESSION['UserDBEnabled']; } else { $content['UserDBEnabled'] = false; }
	if ( isset($_SESSION['UserDBServer']) ) { $content['UserDBServer'] = $_SESSION['UserDBServer']; } else { $content['UserDBServer'] = "localhost"; }
	if ( isset($_SESSION['UserDBPort']) ) { $content['UserDBPort'] = $_SESSION['UserDBPort']; } else { $content['UserDBPort'] = "3306"; }
	if ( isset($_SESSION['UserDBName']) ) { $content['UserDBName'] = $_SESSION['UserDBName']; } else { $content['UserDBName'] = "phplogcon"; }
	if ( isset($_SESSION['UserDBPref']) ) { $content['UserDBPref'] = $_SESSION['UserDBPref']; } else { $content['UserDBPref'] = "logcon_"; }
	if ( isset($_SESSION['UserDBUser']) ) { $content['UserDBUser'] = $_SESSION['UserDBUser']; } else { $content['UserDBUser'] = "user"; }
	if ( isset($_SESSION['UserDBPass']) ) { $content['UserDBPass'] = $_SESSION['UserDBPass']; } else { $content['UserDBPass'] = ""; }
	if ( $content['UserDBEnabled'] == 1 )
	{
		$content['UserDBEnabled_true'] = "checked";
		$content['UserDBEnabled_false'] = "";
	}
	else
	{
		$content['UserDBEnabled_true'] = "";
		$content['UserDBEnabled_false'] = "checked";
	}
	// ---

	// --- Read and predefine Frontend options
	if ( isset($_SESSION['ViewMessageCharacterLimit']) ) { $content['ViewMessageCharacterLimit'] = $_SESSION['ViewMessageCharacterLimit']; } else { $content['ViewMessageCharacterLimit'] = 80; }
	if ( isset($_SESSION['ViewEntriesPerPage']) ) { $content['ViewEntriesPerPage'] = $_SESSION['ViewEntriesPerPage']; } else { $content['ViewEntriesPerPage'] = 50; }
	if ( isset($_SESSION['ViewEnableDetailPopups']) ) { $content['ViewEnableDetailPopups'] = $_SESSION['ViewEnableDetailPopups']; } else { $content['ViewEnableDetailPopups'] = 1; }
	if ( $content['ViewEnableDetailPopups'] == 1 )
	{
		$content['ViewEnableDetailPopups_true'] = "checked";
		$content['ViewEnableDetailPopups_false'] = "";
	}
	else
	{
		$content['ViewEnableDetailPopups_true'] = "";
		$content['ViewEnableDetailPopups_false'] = "checked";
	}
	// ---
	
	// Disable the bottom next button, as the Form in this step has its own button!
//	$content['NEXT_ENABLED'] = "false";

	// Check for Error Msg
	if ( isset($_GET['errormsg']) )
	{
		$content['iserror'] = "true";
		$content['errormsg'] = DB_RemoveBadChars( urldecode($_GET['errormsg']) );
	}
}
else if ( $content['INSTALL_STEP'] == 4 )
{	
	// --- Read Database Vars
	if ( isset($_POST['UserDBEnabled']) )
	{
		$_SESSION['UserDBEnabled'] = DB_RemoveBadChars($_POST['UserDBEnabled']);
		if ( $_SESSION['UserDBEnabled'] == 1 )
		{
			// Read vars
			if ( isset($_POST['UserDBServer']) )
				$_SESSION['UserDBServer'] = DB_RemoveBadChars($_POST['UserDBServer']);
			else
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBSERVER'] );

			if ( isset($_POST['UserDBPort']) )
				$_SESSION['UserDBPort'] = intval(DB_RemoveBadChars($_POST['UserDBPort']));
			else
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBPORT'] );

			if ( isset($_POST['UserDBName']) )
				$_SESSION['UserDBName'] = DB_RemoveBadChars($_POST['UserDBName']);
			else
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBNAME'] );

			if ( isset($_POST['UserDBPref']) )
				$_SESSION['UserDBPref'] = DB_RemoveBadChars($_POST['UserDBPref']);
			else
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBPREF'] );

			if ( isset($_POST['UserDBUser']) )
				$_SESSION['UserDBUser'] = DB_RemoveBadChars($_POST['UserDBUser']);
			else
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING']. $content['LN_CFG_DBUSER'] );

			if ( isset($_POST['UserDBPass']) )
				$_SESSION['UserDBPass'] = DB_RemoveBadChars($_POST['UserDBPass']);
			else
				$_SESSION['UserDBPass'] = "";

			// Now Check database connect
			$link_id = mysql_connect( $_SESSION['UserDBServer'], $_SESSION['UserDBUser'], $_SESSION['UserDBPass']);
			if (!$link_id) 
				RevertOneStep( $content['INSTALL_STEP']-1, "Connect to " .$_SESSION['UserDBServer'] . " failed! Check Servername, Port, User and Password!<br>" . DB_ReturnSimpleErrorMsg() );
			
			// Try to select the DB!
			$db_selected = mysql_select_db($_SESSION['UserDBName'], $link_id);
			if(!$db_selected) 
				RevertOneStep( $content['INSTALL_STEP']-1, "Cannot use database  " .$_SESSION['UserDBName'] . "! If the database does not exists, create it or check access permissions! <br>" . DB_ReturnSimpleErrorMsg());
		}
	}
	// ---

	// --- Read Frontend Vars
	if ( isset($_POST['ViewMessageCharacterLimit']) )
	{
		$_SESSION['ViewMessageCharacterLimit'] = intval( DB_RemoveBadChars($_POST['ViewMessageCharacterLimit']) );
		if ( $_SESSION['ViewMessageCharacterLimit'] < 0 )
			$_SESSION['ViewMessageCharacterLimit'] = 80; // Fallback default!
	}
	else
		$_SESSION['ViewMessageCharacterLimit'] = 80; // Fallback default!

	if ( isset($_POST['ViewEntriesPerPage']) )
	{
		$_SESSION['ViewEntriesPerPage'] = intval( DB_RemoveBadChars($_POST['ViewEntriesPerPage']) );
		if ( $_SESSION['ViewEntriesPerPage'] < 0 )
			$_SESSION['ViewEntriesPerPage'] = 50; // Fallback default!
	}
	else
		$_SESSION['ViewEntriesPerPage'] = 50; // Fallback default!

	if ( isset($_POST['ViewEnableDetailPopups']) )
		$_SESSION['ViewEnableDetailPopups'] = intval( DB_RemoveBadChars($_POST['ViewEnableDetailPopups']) );
	else
		$_SESSION['ViewEnableDetailPopups'] = 1; // Fallback default!
	// ---

	// If UserDB is disabled, skip next step!
	if ( $_SESSION['UserDBEnabled'] == 0 )
		ForwardOneStep();
}
else if ( $content['INSTALL_STEP'] == 5 )
{
	$content['sql_sucess'] = 0;
	$content['sql_failed'] = 0;

	// Import default database if user db is enabled!
	if ( $_SESSION['UserDBEnabled'] == 1 )
	{
		// Init $totaldbdefs
		$totaldbdefs = "";

		// Read the table GLOBAL definitions 
		ImportDataFile( $content['BASEPATH'] . "contrib/db_template.txt" );

		// Process definitions ^^
		if ( strlen($totaldbdefs) <= 0 )
		{
			$content['failedstatements'][ $content['sql_failed'] ]['myerrmsg'] = "Error, invalid Database Defintion File (to short!), file '" . $content['BASEPATH'] . "contrib/db_template.txt" . "'! <br>Maybe the file was not correctly uploaded?";
			$content['failedstatements'][ $content['sql_failed'] ]['mystatement'] = "";
			$content['sql_failed']++;
		}

		// Replace stats_ with the custom one ;)
		$totaldbdefs = str_replace( "`logcon_", "`" . $_SESSION["UserDBPref"], $totaldbdefs );
		
		// Now split by sql command
		$mycommands = split( ";\r\n", $totaldbdefs );
		
		// check for different linefeed
		if ( count($mycommands) <= 1 )
			$mycommands = split( ";\n", $totaldbdefs );

		//Still only one? Abort
		if ( count($mycommands) <= 1 )
		{
			$content['failedstatements'][ $content['sql_failed'] ]['myerrmsg'] = "Error, invalid Database Defintion File (no statements found!) in '" . $content['BASEPATH'] . "contrib/db_template.txt" . "'!<br> Maybe the file was not correctly uploaded, or a strange bug with your system? Contact phpLogCon forums for assistance!";
			$content['failedstatements'][ $content['sql_failed'] ]['mystatement'] = "";
			$content['sql_failed']++;
		}

		// Append INSERT Statement for Config Table to set the GameVersion and Database Version ^^!
		$mycommands[count($mycommands)] = "INSERT INTO `" . $_SESSION["UserDBPref"] . "config` (`name`, `value`) VALUES ('database_installedversion', '1')";

		// --- Now execute all commands
		ini_set('error_reporting', E_WARNING); // Enable Warnings!
		InitPhpLogConConfigFile();

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
	else // Skip this step in this case!
		ForwardOneStep();
}
else if ( $content['INSTALL_STEP'] == 6 )
{
	if ( $_SESSION['UserDBEnabled'] == 1 )
	{
		if ( isset($_SESSION['MAIN_Username']) )
			$content['MAIN_Username'] = $_SESSION['MAIN_Username'];
		else
			$content['MAIN_Username'] = "";

		$content['MAIN_Password1'] = "";
		$content['MAIN_Password2'] = "";

		// Check for Error Msg
		if ( isset($_GET['errormsg']) )
		{
			$content['iserror'] = "true";
			$content['errormsg'] = DB_RemoveBadChars( urldecode($_GET['errormsg']) );
		}
	}
	else // NO Database means NO user management, so next step!
		ForwardOneStep();
}
else if ( $content['INSTALL_STEP'] == 7 )
{
	if ( $_SESSION['UserDBEnabled'] == 1 )
	{
		if ( isset($_POST['username']) )
			$_SESSION['MAIN_Username'] = DB_RemoveBadChars($_POST['username']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, "Username needs to be specified" );

		if ( isset($_POST['password1']) )
			$_SESSION['MAIN_Password1'] = DB_RemoveBadChars($_POST['password1']);
		else
			$_SESSION['MAIN_Password1'] = "";

		if ( isset($_POST['password2']) )
			$_SESSION['MAIN_Password2'] = DB_RemoveBadChars($_POST['password2']);
		else
			$_SESSION['MAIN_Password2'] = "";

		if (	
				strlen($_SESSION['MAIN_Password1']) <= 4 ||
				$_SESSION['MAIN_Password1'] != $_SESSION['MAIN_Password2'] 
			)
			RevertOneStep( $content['INSTALL_STEP']-1, "Either the password does not match or is to short!" );

		// --- Now execute all commands
		ini_set('error_reporting', E_WARNING); // Enable Warnings!
		InitPhpLogConConfigFile();

		// Establish DB Connection
		DB_Connect();

		// Everything is fine, lets go create the User!
		CreateUserName( $_SESSION['MAIN_Username'], $_SESSION['MAIN_Password1'], 0 );
	}

	// Init Source Options
	if ( isset($_SESSION['SourceType']) ) { $content['SourceType'] = $_SESSION['SourceType']; } else { $content['SourceType'] = SOURCE_DISK; }
	CreateSourceTypesList($content['SourceType']);
	if ( isset($_SESSION['SourceName']) ) { $content['SourceName'] = $_SESSION['SourceName']; } else { $content['SourceName'] = "My Syslog Source"; }

	// SOURCE_DISK specific
	if ( isset($_SESSION['SourceLogLineType']) ) { $content['SourceLogLineType'] = $_SESSION['SourceLogLineType']; } else { $content['SourceLogLineType'] = ""; }
	CreateLogLineTypesList($content['SourceLogLineType']);
	if ( isset($_SESSION['SourceDiskFile']) ) { $content['SourceDiskFile'] = $_SESSION['SourceDiskFile']; } else { $content['SourceDiskFile'] = "/var/log/syslog"; }

	// SOURCE_DB specific
	if ( isset($_SESSION['SourceDBType']) ) { $content['SourceDBType'] = $_SESSION['SourceDBType']; } else { $content['SourceDBType'] = DB_MYSQL; }
	CreateDBTypesList($content['SourceDBType']);
	if ( isset($_SESSION['SourceDBName']) ) { $content['SourceDBName'] = $_SESSION['SourceDBName']; } else { $content['SourceDBName'] = "phplogcon"; }
	if ( isset($_SESSION['SourceDBTableType']) ) { $content['SourceDBTableType'] = $_SESSION['SourceDBTableType']; } else { $content['SourceDBTableType'] = "monitorware"; }
	if ( isset($_SESSION['SourceDBServer']) ) { $content['SourceDBServer'] = $_SESSION['SourceDBServer']; } else { $content['SourceDBServer'] = "localhost"; }
	if ( isset($_SESSION['SourceDBTableName']) ) { $content['SourceDBTableName'] = $_SESSION['SourceDBTableName']; } else { $content['SourceDBTableName'] = "systemevents"; }
	if ( isset($_SESSION['SourceDBUser']) ) { $content['SourceDBUser'] = $_SESSION['SourceDBUser']; } else { $content['SourceDBUser'] = "user"; }
	if ( isset($_SESSION['SourceDBPassword']) ) { $content['SourceDBPassword'] = $_SESSION['SourceDBPassword']; } else { $content['SourceDBPassword'] = ""; }
	if ( isset($_SESSION['SourceDBEnableRowCounting']) ) { $content['SourceDBEnableRowCounting'] = $_SESSION['SourceDBEnableRowCounting']; } else { $content['SourceDBEnableRowCounting'] = "false"; }
	if ( $content['SourceDBEnableRowCounting'] == "true" )
	{
		$content['SourceDBEnableRowCounting_true'] = "checked";
		$content['SourceDBEnableRowCounting_false'] = "";
	}
	else
	{
		$content['SourceDBEnableRowCounting_true'] = "";
		$content['SourceDBEnableRowCounting_false'] = "checked";
	}

	// Check for Error Msg
	if ( isset($_GET['errormsg']) )
	{
		$content['iserror'] = "true";
		$content['errormsg'] = DB_RemoveBadChars( urldecode($_GET['errormsg']) );
	}
}
else if ( $content['INSTALL_STEP'] == 8 )
{
	// Read vars
	if ( isset($_POST['SourceType']) )
		$_SESSION['SourceType'] = DB_RemoveBadChars($_POST['SourceType']);
	else
		RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_SOURCETYPE'] );

	if ( isset($_POST['SourceName']) )
		$_SESSION['SourceName'] = DB_RemoveBadChars($_POST['SourceName']);
	else
		RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_NAMEOFTHESOURCE'] );

	// Check DISK Parameters!
	if ( $_SESSION['SourceType'] == SOURCE_DISK) 
	{
		if ( isset($_POST['SourceLogLineType']) )
			$_SESSION['SourceLogLineType'] = DB_RemoveBadChars($_POST['SourceLogLineType']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LOGLINETYPE'] );

		if ( isset($_POST['SourceDiskFile']) )
			$_SESSION['SourceDiskFile'] = DB_RemoveBadChars($_POST['SourceDiskFile']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_SYSLOGFILE'] );

		// Check if access to the configured file is possible
		if ( !is_file($_SESSION['SourceDiskFile']) )
			RevertOneStep( $content['INSTALL_STEP']-1, "Failed to open the syslog file " .$_SESSION['SourceDiskFile'] . "! Check if the file exists and phplogcon has sufficient rights to it<br>" );
	}
	else if ( $_SESSION['SourceType'] == SOURCE_DB)
	{
		if ( isset($_POST['SourceDBType']) )
			$_SESSION['SourceDBType'] = DB_RemoveBadChars($_POST['SourceDBType']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DATABASETYPEOPTIONS'] );

		if ( isset($_POST['SourceDBName']) )
			$_SESSION['SourceDBName'] = DB_RemoveBadChars($_POST['SourceDBName']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBNAME'] );

		if ( isset($_POST['SourceDBTableType']) )
			$_SESSION['SourceDBTableType'] = DB_RemoveBadChars($_POST['SourceDBTableType']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBTABLETYPE'] );

		if ( isset($_POST['SourceDBServer']) )
			$_SESSION['SourceDBServer'] = DB_RemoveBadChars($_POST['SourceDBServer']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBSERVER'] );

		if ( isset($_POST['SourceDBTableName']) )
			$_SESSION['SourceDBTableName'] = DB_RemoveBadChars($_POST['SourceDBTableName']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBTABLENAME'] );

		if ( isset($_POST['SourceDBUser']) )
			$_SESSION['SourceDBUser'] = DB_RemoveBadChars($_POST['SourceDBUser']);
		else
			RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBUSER'] );

		if ( isset($_POST['SourceDBPassword']) )
			$_SESSION['SourceDBPassword'] = DB_RemoveBadChars($_POST['SourceDBPassword']);
		else
			$_SESSION['SourceDBPassword'] = "";

		if ( isset($_POST['SourceDBEnableRowCounting']) )
		{
			$_SESSION['SourceDBEnableRowCounting'] = DB_RemoveBadChars($_POST['SourceDBEnableRowCounting']);
			if ( $_SESSION['SourceDBEnableRowCounting'] != "true" )
				$_SESSION['SourceDBEnableRowCounting'] = "false";
		}

		// TODO: Check database connectivity!
	}

	// If we reached this point, we have gathered all necessary information to create our configuration file ;)!
	$filebuffer = LoadDataFile($configsamplefile);
	
	// Start replacing existing sample configurations
	$patterns[] = "/\\\$CFG\['ViewMessageCharacterLimit'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['ViewEntriesPerPage'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['ViewEnableDetailPopups'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['UserDBEnabled'\] = [0-9]{1,2};/";
	$replacements[] = "\$CFG['ViewMessageCharacterLimit'] = " . $_SESSION['ViewMessageCharacterLimit'] . ";";
	$replacements[] = "\$CFG['ViewEntriesPerPage'] = " . $_SESSION['ViewEntriesPerPage'] . ";";
	$replacements[] = "\$CFG['ViewEnableDetailPopups'] = " . $_SESSION['ViewEnableDetailPopups'] . ";";
	$replacements[] = "\$CFG['UserDBEnabled'] = " . $_SESSION['UserDBEnabled'] . ";";
	
	//User Database	Options
	if ( $_SESSION['UserDBEnabled'] == 1 )
	{
		// TODO!
	}

	//Add the first source! 
	$firstsource =	"\$CFG['Sources']['Source1']['ID'] = 'Source1';\r\n" . 
					"\$CFG['Sources']['Source1']['Name'] = '" . $_SESSION['SourceName'] . "';\r\n" . 
					"\$CFG['Sources']['Source1']['SourceType'] = " . $_SESSION['SourceType'] . ";\r\n";
	if ( $_SESSION['SourceType'] == SOURCE_DISK ) 
	{
		$firstsource .=	"\$CFG['Sources']['Source1']['LogLineType'] = '" . $_SESSION['SourceLogLineType'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DiskFile'] = '" . $_SESSION['SourceDiskFile'] . "';\r\n" . 
						"";
	}
	else if ( $_SESSION['SourceType'] == SOURCE_DB ) 
	{
		$firstsource .=	"\$CFG['Sources']['Source1']['DBTableType'] = '" . $_SESSION['SourceDBTableType'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DBType'] = '" . $_SESSION['SourceDBType'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DBServer'] = '" . $_SESSION['SourceDBServer'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DBName'] = '" . $_SESSION['SourceDBName'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DBUser'] = '" . $_SESSION['SourceDBUser'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DBPassword'] = '" . $_SESSION['SourceDBPassword'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DBTableName'] = '" . $_SESSION['SourceDBTableName'] . "';\r\n" . 
						"\$CFG['Sources']['Source1']['DBEnableRowCounting'] = " . $_SESSION['SourceDBEnableRowCounting'] . ";\r\n" . 
						"";
	}
	$patterns[] = "/\/\/ --- \%Insert Source Here\%/";
	$replacements[] = $firstsource;

	// One call to replace them all ^^
	$filebuffer = preg_replace( $patterns, $replacements, $filebuffer );
//	echo $filebuffer;

	// Create file and write config into it!
	$handle = fopen( $content['BASEPATH'] . "config.php" , "w");
	if ( $handle === false ) 
		RevertOneStep( $content['INSTALL_STEP']-1, "Coult not create the configuration file " . $content['BASEPATH'] . "config.php" . "! Check File permissions!!!" );
	
	fwrite($handle, $filebuffer);
	fclose($handle);
}
// --- 



// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "install.html");
$page -> output(); 
// ---

// --- Helper functions

function RevertOneStep($stepback, $errormsg)
{
	header("Location: install.php?step=" . $stepback . "&errormsg=" . urlencode($errormsg) );
	exit;
}

function ForwardOneStep()
{
	global $content; 

	header("Location: install.php?step=" . ($content['INSTALL_STEP']+1) );
	exit;
}

function LoadDataFile($szFileName)
{
	global $content;

	// Lets read the table definitions :)
	$buffer = "";
	$handle = @fopen($szFileName, "r");
	if ($handle === false) 
		RevertOneStep( $content['INSTALL_STEP']-1, "Error reading the file " . $szFileName . "! Check if the file exists!" );
	else
	{
		while (!feof($handle)) 
		{
			$buffer .= fgets($handle, 4096);
		}
	   fclose($handle);
	}

	// return file buffer!
	return $buffer;
}

function ImportDataFile($szFileName)
{
	global $content, $totaldbdefs;

	// Lets read the table definitions :)
	$handle = @fopen($szFileName, "r");
	if ($handle === false) 
		RevertOneStep( $content['INSTALL_STEP']-1, "Error reading the default database defintion file " . $szFileName . "! Check if the file exists!!!" );
	else
	{
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);

			$pos = strpos($buffer, "--");
			if ($pos === false)
				$totaldbdefs .= $buffer; 
			else if ( $pos > 2 && strlen( trim($buffer) ) > 1 )
				$totaldbdefs .= $buffer; 
		}
	   fclose($handle);
	}
}

// ---
?>
