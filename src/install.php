<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Main Index File											
	*																	
	* -> Installer File
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
define('IN_PHPLOGCON_INSTALL', true);		// Extra for INSTALL Script!
define('STEPSCRIPTNAME', "install.php");	// Helper variable for the STEP helper functions
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_installhelpers.php');

// Init Langauge first!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

InitBasicPhpLogCon();
if ( InitPhpLogConConfigFile(false) ) 
	DieWithErrorMsg( $content['LN_INSTALL_ERRORINSTALLED'] );

// Set some static values
define('MAX_STEPS', 8);
$content['web_theme'] = "default";
$content['user_theme'] = "default";
$configsamplefile = $content['BASEPATH'] . "include/config.sample.php"; 
$content['HeaderDefaultEncoding'] = ENC_ISO_8859_1; // Set Default encoding!  

//ini_set('error_reporting', E_ALL); // DEBUG ENABLE
// ***					*** //

// --- CONTENT Vars
$content['TITLE'] = "LogAnalyzer :: " . $content['LN_INSTALL_TITLE'];
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
$content['TITLE'] = GetAndReplaceLangStr( $content['TITLE'], $content['INSTALL_STEP'] );
$content['INSTALL_TITLETOP'] = GetAndReplaceLangStr( $content['LN_INSTALL_TITLETOP'], $content['BUILDNUMBER'],  $content['INSTALL_STEP'] );
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
				@touch($content['fileperm'][$i]['FILE_NAME']);
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
		$content['errormsg'] = $content['LN_INSTALL_FILEORDIRNOTWRITEABLE'];
	}

	// Check if sample config file is available
	if ( !is_file($configsamplefile) || GetFileLength($configsamplefile) <= 0 )
	{
		$content['NEXT_ENABLED'] = "false";
		$content['RECHECK_ENABLED'] = "true";
		$content['iserror'] = "true";
		$content['errormsg'] = GetAndReplaceLangStr( $content['LN_INSTALL_SAMPLECONFIGMISSING'], $configsamplefile);
	}
	
}
else if ( $content['INSTALL_STEP'] == 3 )
{	
	// --- Read and predefine Database options
	if ( isset($_SESSION['UserDBEnabled']) ) { $content['UserDBEnabled'] = $_SESSION['UserDBEnabled']; } else { $content['UserDBEnabled'] = false; }
	if ( isset($_SESSION['UserDBServer']) ) { $content['UserDBServer'] = $_SESSION['UserDBServer']; } else { $content['UserDBServer'] = "localhost"; }
	if ( isset($_SESSION['UserDBPort']) ) { $content['UserDBPort'] = $_SESSION['UserDBPort']; } else { $content['UserDBPort'] = "3306"; }
	if ( isset($_SESSION['UserDBName']) ) { $content['UserDBName'] = $_SESSION['UserDBName']; } else { $content['UserDBName'] = "loganalyzer"; }
	if ( isset($_SESSION['UserDBPref']) ) { $content['UserDBPref'] = $_SESSION['UserDBPref']; } else { $content['UserDBPref'] = "logcon_"; }
	if ( isset($_SESSION['UserDBUser']) ) { $content['UserDBUser'] = $_SESSION['UserDBUser']; } else { $content['UserDBUser'] = "user"; }
	if ( isset($_SESSION['UserDBPass']) ) { $content['UserDBPass'] = $_SESSION['UserDBPass']; } else { $content['UserDBPass'] = ""; }
	if ( isset($_SESSION['UserDBLoginRequired']) ) { $content['UserDBLoginRequired'] = $_SESSION['UserDBLoginRequired']; } else { $content['UserDBLoginRequired'] = false; }

	// Init Auth Options
	if ( isset($_SESSION['UserDBAuthMode']) ) { $content['UserDBAuthMode'] = $_SESSION['UserDBAuthMode']; } else { $content['UserDBAuthMode'] = USERDB_AUTH_INTERNAL; }
	CreateAuthTypesList($content['UserDBAuthMode']);

	// LDAP related properties
	if ( isset($_SESSION['LDAPServer']) ) { $content['LDAPServer'] = $_SESSION['LDAPServer']; } else { $content['LDAPServer'] = "localhost"; }
	if ( isset($_SESSION['LDAPPort']) ) { $content['LDAPPort'] = $_SESSION['LDAPPort']; } else { $content['LDAPPort'] = "389"; }
	if ( isset($_SESSION['LDAPBaseDN']) ) { $content['LDAPBaseDN'] = $_SESSION['LDAPBaseDN']; } else { $content['LDAPBaseDN'] = "CN=Users,DC=domain,DC=local"; }
	if ( isset($_SESSION['LDAPSearchFilter']) ) { $content['LDAPSearchFilter'] = $_SESSION['LDAPSearchFilter']; } else { $content['LDAPSearchFilter'] = "(objectClass=user)"; }
	if ( isset($_SESSION['LDAPUidAttribute']) ) { $content['LDAPUidAttribute'] = $_SESSION['LDAPUidAttribute']; } else { $content['LDAPUidAttribute'] = "sAMAccountName"; }
	if ( isset($_SESSION['LDAPBindDN']) ) { $content['LDAPBindDN'] = $_SESSION['LDAPBindDN']; } else { $content['LDAPBindDN'] = "CN=Searchuser,CN=Users,DC=domain,DC=local"; }
	if ( isset($_SESSION['LDAPBindPassword']) ) { $content['LDAPBindPassword'] = $_SESSION['LDAPBindPassword']; } else { $content['LDAPBindPassword'] = "Password"; }
	if ( isset($_SESSION['LDAPDefaultAdminUser']) ) { $content['LDAPDefaultAdminUser'] = $_SESSION['LDAPDefaultAdminUser']; } else { $content['LDAPDefaultAdminUser'] = "Administrator"; }
	
	// Set template variables
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
	if ( $content['UserDBLoginRequired'] == 1 )
	{
		$content['UserDBLoginRequired_true'] = "checked";
		$content['UserDBLoginRequired_false'] = "";
	}
	else
	{
		$content['UserDBLoginRequired_true'] = "";
		$content['UserDBLoginRequired_false'] = "checked";
	}
	// ---

	// --- Read and predefine Frontend options
	if ( isset($_SESSION['ViewMessageCharacterLimit']) ) { $content['ViewMessageCharacterLimit'] = $_SESSION['ViewMessageCharacterLimit']; } else { $content['ViewMessageCharacterLimit'] = 80; }
	if ( isset($_SESSION['ViewStringCharacterLimit']) ) { $content['ViewStringCharacterLimit'] = $_SESSION['ViewStringCharacterLimit']; } else { $content['ViewStringCharacterLimit'] = 30; }
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
	if ( isset($_SESSION['EnableIPAddressResolve']) ) { $content['EnableIPAddressResolve'] = $_SESSION['EnableIPAddressResolve']; } else { $content['EnableIPAddressResolve'] = 1; }
	if ( $content['EnableIPAddressResolve'] == 1 )
	{
		$content['EnableIPAddressResolve_true'] = "checked";
		$content['EnableIPAddressResolve_false'] = "";
	}
	else
	{
		$content['EnableIPAddressResolve_true'] = "";
		$content['EnableIPAddressResolve_false'] = "checked";
	}
	// ---
	
	// Disable the bottom next button, as the Form in this step has its own button!
//	$content['NEXT_ENABLED'] = "false";

	// Check for Error Msg
	if ( isset($_GET['errormsg']) )
	{
		$content['iserror'] = "true";
		$content['errormsg'] = urldecode( DB_StripSlahes($_GET['errormsg']) );
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
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_DBUSER'] );

			if ( isset($_POST['UserDBPass']) )
				$_SESSION['UserDBPass'] = DB_RemoveBadChars($_POST['UserDBPass']);
			else
				$_SESSION['UserDBPass'] = "";

			if ( isset($_POST['UserDBLoginRequired']) )
				$_SESSION['UserDBLoginRequired'] = intval(DB_RemoveBadChars($_POST['UserDBLoginRequired']));
			else
				$_SESSION['UserDBLoginRequired'] = false;

			if ( isset($_POST['UserDBAuthMode']) )
				$_SESSION['UserDBAuthMode'] = intval(DB_RemoveBadChars($_POST['UserDBAuthMode']));
			else
				$_SESSION['UserDBAuthMode'] = USERDB_AUTH_INTERNAL;
			

			// LDAP Properties
			if ( $_SESSION['UserDBAuthMode'] == USERDB_AUTH_LDAP )
			{
				if ( isset($_POST['LDAPServer']) )
					$_SESSION['LDAPServer'] = DB_RemoveBadChars($_POST['LDAPServer']);
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPServer'] );
				if ( isset($_POST['LDAPPort']) )
					$_SESSION['LDAPPort'] = intval(DB_RemoveBadChars($_POST['LDAPPort']));
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPPort'] );
				if ( isset($_POST['LDAPBaseDN']) )
					$_SESSION['LDAPBaseDN'] = DB_RemoveBadChars($_POST['LDAPBaseDN']);
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPBaseDN'] );
				if ( isset($_POST['LDAPSearchFilter']) )
					$_SESSION['LDAPSearchFilter'] = DB_RemoveBadChars($_POST['LDAPSearchFilter']);
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPSearchFilter'] );
				if ( isset($_POST['LDAPUidAttribute']) )
					$_SESSION['LDAPUidAttribute'] = DB_RemoveBadChars($_POST['LDAPUidAttribute']);
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPUidAttribute'] );
				if ( isset($_POST['LDAPBindDN']) )
					$_SESSION['LDAPBindDN'] = DB_RemoveBadChars($_POST['LDAPBindDN']);
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPBindDN'] );
				if ( isset($_POST['LDAPBindPassword']) )
					$_SESSION['LDAPBindPassword'] = DB_RemoveBadChars($_POST['LDAPBindPassword']);
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPBindPassword'] );
				if ( isset($_POST['LDAPDefaultAdminUser']) )
					$_SESSION['LDAPDefaultAdminUser'] = DB_RemoveBadChars($_POST['LDAPDefaultAdminUser']);
				else
					RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_LDAPDefaultAdminUser'] );
			}

			// Now Check database connect
			$link_id = mysql_connect( $_SESSION['UserDBServer'], $_SESSION['UserDBUser'], $_SESSION['UserDBPass']);
			if (!$link_id) 
				RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr( $content['LN_INSTALL_ERRORCONNECTFAILED'], $_SESSION['UserDBServer']) . "<br>" . DB_ReturnSimpleErrorMsg() );
			
			// Try to select the DB!
			$db_selected = mysql_select_db($_SESSION['UserDBName'], $link_id);
			if(!$db_selected) 
				RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr( $content['LN_INSTALL_ERRORACCESSDENIED'], $_SESSION['UserDBName']) . "<br>" . DB_ReturnSimpleErrorMsg());
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

	if ( isset($_POST['ViewStringCharacterLimit']) )
	{
		$_SESSION['ViewStringCharacterLimit'] = intval( DB_RemoveBadChars($_POST['ViewStringCharacterLimit']) );
		if ( $_SESSION['ViewStringCharacterLimit'] < 0 )
			$_SESSION['ViewStringCharacterLimit'] = 30; // Fallback default!
	}
	else
		$_SESSION['ViewStringCharacterLimit'] = 30; // Fallback default!

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

	if ( isset($_POST['EnableIPAddressResolve']) )
		$_SESSION['EnableIPAddressResolve'] = intval( DB_RemoveBadChars($_POST['EnableIPAddressResolve']) );
	else
		$_SESSION['EnableIPAddressResolve'] = 1; // Fallback default!

	// ---

	// If UserDB is disabled, skip next step!
	if ( $_SESSION['UserDBEnabled'] == 0 )
		ForwardOneStep();
	else
	{
		if ( $_SESSION['UserDBAuthMode']  == USERDB_AUTH_LDAP )
		{
			// We need the user system now!
			ini_set('error_reporting', E_WARNING); // Enable Warnings!
			InitUserDbSettings();		// We need some DB Settings
			InitUserSystemPhpLogCon();	

			// LDAP Variables
			$content['LDAPServer']			= $_SESSION['LDAPServer']; 
			$content['LDAPPort']			= $_SESSION['LDAPPort'];
			$content['LDAPBindDN']			= $_SESSION['LDAPBindDN'];
			$content['LDAPBindPassword']	= $_SESSION['LDAPBindPassword'];

			// try LDAP Connect!
			$ldapConn = DoLDAPConnect(); 
			if ( $ldapConn ) 
			{
				$bBind = DoLDAPBind($ldapConn); 
				if ( !$bBind ) 
					RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr( $content['LN_LOGIN_LDAP_USERBINDFAILED'], $_SESSION['LDAPBindDN']) );
			}
			else
				RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr( $content['LN_INSTALL_LDAPCONNECTFAILED'], $_SESSION['LDAPServer']) );
		}
	}
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
//		$mycommands = split( ";\n", $totaldbdefs ); DEPRECEATED CALL!
		$mycommands = preg_split('/;\n/', $totaldbdefs, -1, PREG_SPLIT_NO_EMPTY);
		
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

		// Append INSERT Statement for Config Table to set the Database Version ^^!
		$mycommands[count($mycommands)] = "INSERT INTO `" . $_SESSION["UserDBPref"] . "config` (`propname`, `propvalue`, `is_global`) VALUES ('database_installedversion', '" . $content['database_internalversion'] . "', 1)";

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
	else // Skip this step in this case!
		ForwardOneStep();
}
else if ( $content['INSTALL_STEP'] == 6 )
{
	if ( $_SESSION['UserDBEnabled'] == 1 )
	{
		if ( $_SESSION['UserDBAuthMode']  == USERDB_AUTH_INTERNAL )
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
				$content['errormsg'] = urldecode( DB_StripSlahes($_GET['errormsg']) );
			}
		}
		else // USERDB_AUTH_LDAP does not need this steo!
			ForwardOneStep();
	}
	else // NO Database means NO user management, so next step!
		ForwardOneStep();
}
else if ( $content['INSTALL_STEP'] == 7 )
{
	if ( $_SESSION['UserDBEnabled'] == 1 )
	{
		if ( $_SESSION['UserDBAuthMode']  == USERDB_AUTH_INTERNAL )
		{
			if ( isset($_POST['username']) )
				$_SESSION['MAIN_Username'] = DB_RemoveBadChars($_POST['username']);
			else
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_INSTALL_MISSINGUSERNAME'] );

			if ( isset($_POST['password1']) )
				$_SESSION['MAIN_Password1'] = DB_RemoveBadChars($_POST['password1']);
			else
				$_SESSION['MAIN_Password1'] = "";

			if ( isset($_POST['password2']) )
				$_SESSION['MAIN_Password2'] = DB_RemoveBadChars($_POST['password2']);
			else
				$_SESSION['MAIN_Password2'] = "";

			if (	
					strlen($_SESSION['MAIN_Password1']) < 4 ||
					$_SESSION['MAIN_Password1'] != $_SESSION['MAIN_Password2'] 
				)
				RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_INSTALL_PASSWORDNOTMATCH'] );
		}
		else if ( $_SESSION['UserDBAuthMode']  == USERDB_AUTH_LDAP )
		{
			$_SESSION['MAIN_Username'] = $_SESSION['LDAPDefaultAdminUser']; 
			$_SESSION['MAIN_Password1'] = "";
			$_SESSION['MAIN_Password2'] = "";
		}

		// --- Now execute all commands
		ini_set('error_reporting', E_WARNING); // Enable Warnings!
		InitUserDbSettings();		// We need some DB Settings
		InitUserSystemPhpLogCon();	// We need the user system now!

		// Establish DB Connection
		DB_Connect();

		// Everything is fine, lets go create the User!
		CreateUserName( $_SESSION['MAIN_Username'], $_SESSION['MAIN_Password1'], 1 );
		
		// Show User success!
		$content['MAIN_Username'] = $_SESSION['MAIN_Username'];
		$content['createduser'] = true;
	}

	// Init Source Options
	if ( isset($_SESSION['SourceType']) ) { $content['SourceType'] = $_SESSION['SourceType']; } else { $content['SourceType'] = SOURCE_DISK; }
	CreateSourceTypesList($content['SourceType']);
	if ( isset($_SESSION['SourceName']) ) { $content['SourceName'] = $_SESSION['SourceName']; } else { $content['SourceName'] = "My Syslog Source"; }
	
	// Init default View
	if ( isset($_SESSION['SourceViewID']) ) { $content['SourceViewID'] = $_SESSION['SourceViewID']; } else { $content['SourceViewID'] = 'SYSLOG'; }
	foreach ( $content['Views'] as $myView )
	{
		if ( $myView['ID'] == $content['SourceViewID'] )
			$content['Views'][ $myView['ID'] ]['selected'] = "selected";
		else
			$content['Views'][ $myView['ID'] ]['selected'] = "";
	}

	// SOURCE_DISK specific
	if ( isset($_SESSION['SourceLogLineType']) ) { $content['SourceLogLineType'] = $_SESSION['SourceLogLineType']; } else { $content['SourceLogLineType'] = ""; }
	CreateLogLineTypesList($content['SourceLogLineType']);
	if ( isset($_SESSION['SourceDiskFile']) ) { $content['SourceDiskFile'] = $_SESSION['SourceDiskFile']; } else { $content['SourceDiskFile'] = "/var/log/syslog"; }

	// SOURCE_DB specific
	if ( isset($_SESSION['SourceDBType']) ) { $content['SourceDBType'] = $_SESSION['SourceDBType']; } else { $content['SourceDBType'] = DB_MYSQL; }
	CreateDBTypesList($content['SourceDBType']);
	if ( isset($_SESSION['SourceDBTableType']) ) { $content['SourceDBTableType'] = $_SESSION['SourceDBTableType']; } else { $content['SourceDBTableType'] = "monitorware"; }
	CreateDBMappingsList($content['SourceDBTableType']);

	if ( isset($_SESSION['SourceDBName']) ) { $content['SourceDBName'] = $_SESSION['SourceDBName']; } else { $content['SourceDBName'] = "loganalyzer"; }
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
		$content['errormsg'] = urldecode( DB_StripSlahes($_GET['errormsg']) );
	}
}
else if ( $content['INSTALL_STEP'] == 8 )
{
	// --- Write Config File!
	// Read vars
	if ( isset($_POST['SourceType']) )
		$_SESSION['SourceType'] = DB_RemoveBadChars($_POST['SourceType']);
	else
		RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_SOURCETYPE'] );

	if ( isset($_POST['SourceName']) )
		$_SESSION['SourceName'] = DB_RemoveBadChars($_POST['SourceName']);
	else
		RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_NAMEOFTHESOURCE'] );

	if ( isset($_POST['SourceViewID']) )
		$_SESSION['SourceViewID'] = DB_RemoveBadChars($_POST['SourceViewID']);
	else
		RevertOneStep( $content['INSTALL_STEP']-1, $content['LN_CFG_PARAMMISSING'] . $content['LN_CFG_VIEW'] );


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
			RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr($content['LN_INSTALL_FAILEDTOOPENSYSLOGFILE'], $_SESSION['SourceDiskFile']) ); 
	}
	// DB Params
	else if (	$_SESSION['SourceType'] == SOURCE_DB || 
				$_SESSION['SourceType'] == SOURCE_PDO ||
				$_SESSION['SourceType'] == SOURCE_MONGODB )
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

		// Check Database Access!

	}

	// If we reached this point, we have gathered all necessary information to create our configuration file ;)!
	$filebuffer = LoadDataFile($configsamplefile);
	
	// Set helper variables and init user vars if needed!
	if ( isset($_SESSION['UserDBEnabled']) && $_SESSION['UserDBEnabled'] ) { $_SESSION['UserDBEnabled_value'] = "true"; } else { $_SESSION['UserDBEnabled_value'] = "false"; }
	if ( isset($_SESSION['UserDBLoginRequired']) && $_SESSION['UserDBLoginRequired'] ) { $_SESSION['UserDBLoginRequired_value'] = "true"; } else { $_SESSION['UserDBLoginRequired_value'] = "false"; }
	if ( !isset($_SESSION['UserDBServer']))	{ $_SESSION['UserDBServer'] = "localhost"; }
	if ( !isset($_SESSION['UserDBPort']))	{ $_SESSION['UserDBPort'] = "3306"; }
	if ( !isset($_SESSION['UserDBName']))	{ $_SESSION['UserDBName'] = "loganalyzer"; }
	if ( !isset($_SESSION['UserDBPref']))	{ $_SESSION['UserDBPref'] = "logcon_"; }
	if ( !isset($_SESSION['UserDBUser']))	{ $_SESSION['UserDBUser'] = "root"; }
	if ( !isset($_SESSION['UserDBPass']))	{ $_SESSION['UserDBPass'] = ""; }
	if ( !isset($_SESSION['UserDBAuthMode']))	{ $_SESSION['UserDBAuthMode'] = USERDB_AUTH_INTERNAL; }

	// LDAP vars
	if ( !isset($_SESSION['LDAPServer']))		{ $_SESSION['LDAPServer'] = "127.0.0.1"; }
	if ( !isset($_SESSION['LDAPPort']))			{ $_SESSION['LDAPPort'] = "389"; }
	if ( !isset($_SESSION['LDAPBaseDN']))		{ $_SESSION['LDAPBaseDN'] = "CN=Users,DC=domain,DC=local"; }
	if ( !isset($_SESSION['LDAPSearchFilter']))	{ $_SESSION['LDAPSearchFilter'] = "(objectClass=user)"; }
	if ( !isset($_SESSION['LDAPUidAttribute']))	{ $_SESSION['LDAPUidAttribute'] = "sAMAccountName"; }
	if ( !isset($_SESSION['LDAPBindDN']))		{ $_SESSION['LDAPBindDN'] = "CN=Searchuser,CN=Users,DC=domain,DC=local"; }
	if ( !isset($_SESSION['LDAPBindPassword']))	{ $_SESSION['LDAPBindPassword'] = "Password"; }

	// Start replacing existing sample configurations
	$patterns[] = "/\\\$CFG\['ViewMessageCharacterLimit'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['ViewStringCharacterLimit'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['ViewEntriesPerPage'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['ViewEnableDetailPopups'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['EnableIPAddressResolve'\] = [0-9]{1,2};/";
	$patterns[] = "/\\\$CFG\['UserDBEnabled'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBServer'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBPort'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBName'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBPref'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBUser'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBPass'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBLoginRequired'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['UserDBAuthMode'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['LDAPServer'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['LDAPPort'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['LDAPBaseDN'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['LDAPSearchFilter'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['LDAPUidAttribute'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['LDAPBindDN'\] = (.*?);/";
	$patterns[] = "/\\\$CFG\['LDAPBindPassword'\] = (.*?);/";

	$replacements[] = "\$CFG['ViewMessageCharacterLimit'] = " . $_SESSION['ViewMessageCharacterLimit'] . ";";
	$replacements[] = "\$CFG['ViewStringCharacterLimit'] = " . $_SESSION['ViewStringCharacterLimit'] . ";";
	$replacements[] = "\$CFG['ViewEntriesPerPage'] = " . $_SESSION['ViewEntriesPerPage'] . ";";
	$replacements[] = "\$CFG['ViewEnableDetailPopups'] = " . $_SESSION['ViewEnableDetailPopups'] . ";";
	$replacements[] = "\$CFG['EnableIPAddressResolve'] = " . $_SESSION['EnableIPAddressResolve'] . ";";
	$replacements[] = "\$CFG['UserDBEnabled'] = " . $_SESSION['UserDBEnabled_value'] . ";";
	$replacements[] = "\$CFG['UserDBServer'] = '" . $_SESSION['UserDBServer'] . "';";
	$replacements[] = "\$CFG['UserDBPort'] = " . $_SESSION['UserDBPort'] . ";";
	$replacements[] = "\$CFG['UserDBName'] = '" . $_SESSION['UserDBName'] . "';";
	$replacements[] = "\$CFG['UserDBPref'] = '" . $_SESSION['UserDBPref'] . "';";
	$replacements[] = "\$CFG['UserDBUser'] = '" . $_SESSION['UserDBUser'] . "';";
	$replacements[] = "\$CFG['UserDBPass'] = '" . $_SESSION['UserDBPass'] . "';";
	$replacements[] = "\$CFG['UserDBLoginRequired'] = " . $_SESSION['UserDBLoginRequired_value'] . ";";
	$replacements[] = "\$CFG['UserDBAuthMode'] = " . $_SESSION['UserDBAuthMode'] . ";";
	$replacements[] = "\$CFG['LDAPServer'] = '" . $_SESSION['LDAPServer'] . "';";
	$replacements[] = "\$CFG['LDAPPort'] = " . $_SESSION['LDAPPort'] . ";";
	$replacements[] = "\$CFG['LDAPBaseDN'] = '" . $_SESSION['LDAPBaseDN'] . "';";
	$replacements[] = "\$CFG['LDAPSearchFilter'] = '" . $_SESSION['LDAPSearchFilter'] . "';";
	$replacements[] = "\$CFG['LDAPUidAttribute'] = '" . $_SESSION['LDAPUidAttribute'] . "';";
	$replacements[] = "\$CFG['LDAPBindDN'] = '" . $_SESSION['LDAPBindDN'] . "';";
	$replacements[] = "\$CFG['LDAPBindPassword'] = '" . $_SESSION['LDAPBindPassword'] . "';";
	
	//User Database	Options
	if ( isset($_SESSION['UserDBEnabled']) && $_SESSION['UserDBEnabled'] )
	{
		// TODO!
	}

	//Add the first source! 
	$firstsource =	"\$CFG['DefaultSourceID'] = 'Source1';\n\n" . 
					"\$CFG['Sources']['Source1']['ID'] = 'Source1';\n" . 
					"\$CFG['Sources']['Source1']['Name'] = '" . $_SESSION['SourceName'] . "';\n" . 
					"\$CFG['Sources']['Source1']['ViewID'] = '" . $_SESSION['SourceViewID'] . "';\n";
	
	if ( $_SESSION['SourceType'] == SOURCE_DISK ) 
	{
		$firstsource .= "\$CFG['Sources']['Source1']['SourceType'] = SOURCE_DISK;\n" . 
						"\$CFG['Sources']['Source1']['LogLineType'] = '" . $_SESSION['SourceLogLineType'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DiskFile'] = '" . $_SESSION['SourceDiskFile'] . "';\n" . 
						"";
	}
	else if ( $_SESSION['SourceType'] == SOURCE_DB )
	{
		// Need to create the LIST first!
		CreateDBTypesList($_SESSION['SourceDBType']);

		$firstsource .=	"\$CFG['Sources']['Source1']['SourceType'] = SOURCE_DB;\n" . 
						"\$CFG['Sources']['Source1']['DBTableType'] = '" . $_SESSION['SourceDBTableType'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBType'] = " . $content['DBTYPES'][$_SESSION['SourceDBType']]['typeastext'] . ";\n" . 
						"\$CFG['Sources']['Source1']['DBServer'] = '" . $_SESSION['SourceDBServer'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBName'] = '" . $_SESSION['SourceDBName'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBUser'] = '" . $_SESSION['SourceDBUser'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBPassword'] = '" . $_SESSION['SourceDBPassword'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBTableName'] = '" . $_SESSION['SourceDBTableName'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBEnableRowCounting'] = " . $_SESSION['SourceDBEnableRowCounting'] . ";\n" . 
						"";
	}
	else if ( $_SESSION['SourceType'] == SOURCE_PDO )
	{
		// Need to create the LIST first!
		CreateDBTypesList($_SESSION['SourceDBType']);

		$firstsource .=	"\$CFG['Sources']['Source1']['SourceType'] = SOURCE_PDO;\n" . 
						"\$CFG['Sources']['Source1']['DBTableType'] = '" . $_SESSION['SourceDBTableType'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBType'] = " . $content['DBTYPES'][$_SESSION['SourceDBType']]['typeastext'] . ";\n" . 
						"\$CFG['Sources']['Source1']['DBServer'] = '" . $_SESSION['SourceDBServer'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBName'] = '" . $_SESSION['SourceDBName'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBUser'] = '" . $_SESSION['SourceDBUser'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBPassword'] = '" . $_SESSION['SourceDBPassword'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBTableName'] = '" . $_SESSION['SourceDBTableName'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBEnableRowCounting'] = " . $_SESSION['SourceDBEnableRowCounting'] . ";\n" . 
						"";
	}
	else if ( $_SESSION['SourceType'] == SOURCE_MONGODB )
	{
		// Need to create the LIST first!
		CreateDBTypesList($_SESSION['SourceDBType']);

		$firstsource .=	"\$CFG['Sources']['Source1']['SourceType'] = SOURCE_MONGODB;\n" . 
						"\$CFG['Sources']['Source1']['DBTableType'] = '" . $_SESSION['SourceDBTableType'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBServer'] = '" . $_SESSION['SourceDBServer'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBName'] = '" . $_SESSION['SourceDBName'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBUser'] = '" . $_SESSION['SourceDBUser'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBPassword'] = '" . $_SESSION['SourceDBPassword'] . "';\n" . 
						"\$CFG['Sources']['Source1']['DBTableName'] = '" . $_SESSION['SourceDBTableName'] . "';\n" . 
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
		RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr($content['LN_INSTALL_FAILEDCREATECFGFILE'], $content['BASEPATH'] . "config.php") );
	
	fwrite($handle, $filebuffer);
	fflush($handle);
	fclose($handle);
	// --- 

	// --- If UserDB is enabled, we need to convert the settings now 
	if ( $_SESSION['UserDBEnabled'] ) 
	{
		// Fully Initialize LogAnalyzer now!
		InitPhpLogCon();
		InitSourceConfigs();

		// Perform conversion of settings into the database now!
		ConvertCustomSearches();
		ConvertCustomViews();
		ConvertCustomSources();
		ConvertCustomCharts();
		
		// Import General Settings in the last step!
		ConvertGeneralSettings();
	}
	// --- 

}
// --- 



// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "install.html");
$page -> output(); 
// ---

// --- Helper functions
function LoadDataFile($szFileName)
{
	global $content;

	// Lets read the table definitions :)
	$buffer = "";
	$handle = @fopen($szFileName, "r");
	if ($handle === false) 
		RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr($content['LN_INSTALL_FAILEDREADINGFILE'], $szFileName) );
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

function InitUserDbSettings()
{
	global $CFG;

	// Init DB Configs 
	$CFG['UserDBEnabled'] = true;
	$CFG['UserDBServer'] = $_SESSION['UserDBServer'];
	$CFG['UserDBPort'] = $_SESSION['UserDBPort'];
	$CFG['UserDBName'] = $_SESSION['UserDBName'];
	$CFG['UserDBPref'] = $_SESSION['UserDBPref'];
	$CFG['UserDBUser'] = $_SESSION['UserDBUser'];
	$CFG['UserDBPass'] = $_SESSION['UserDBPass'];
	$CFG['UserDBLoginRequired'] = $_SESSION['UserDBLoginRequired'];
	
	// Needed table defs
	define('DB_CONFIG',			$CFG['UserDBPref'] . "config");
	define('DB_USERS',			$CFG['UserDBPref'] . "users");
	define('DB_SEARCHES',		$CFG['UserDBPref'] . "searches");
	define('DB_SOURCES',		$CFG['UserDBPref'] . "sources");
	define('DB_VIEWS',			$CFG['UserDBPref'] . "views");
}
// ---
?>