<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Common needed functions											*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
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

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

// --- Basic Includes
include($gl_root_path . 'include/constants_general.php');
include($gl_root_path . 'include/constants_logstream.php');

if ( is_file($gl_root_path . 'config.php') )
	include($gl_root_path . 'config.php');
else
{
	// Check for installscript!
	if ( !defined('IN_PHPLOGCON_INSTALL') )
		CheckForInstallPhp();
}

include($gl_root_path . 'classes/class_template.php');
include($gl_root_path . 'include/functions_themes.php');
include($gl_root_path . 'include/functions_db.php');
include($gl_root_path . 'include/functions_config.php');
// --- 

// --- Define Basic vars
$RUNMODE = RUNMODE_WEBSERVER;
$DEBUGMODE = DEBUG_INFO;

// --- Disable ARGV setting @webserver!
ini_set( "register_argc_argv", "Off" );
// --- 

// Default language
$LANG_EN = "en";	// Used for fallback
$LANG = "en";		// Default language

// Default Template vars
$content['BUILDNUMBER'] = "2.1.1";
$content['TITLE'] = "PhpLogCon - Release " . $content['BUILDNUMBER'];	// Title of the Page 
$content['BASEPATH'] = $gl_root_path;
$content['EXTRA_METATAGS'] = "";
$content['EXTRA_JAVASCRIPT'] = "";
$content['EXTRA_STYLESHEET'] = "";
// --- 

function InitBasicPhpLogCon()
{
	// Needed to make global
	global $CFG, $gl_root_path, $content;

	// Check RunMode first!
	CheckAndSetRunMode();

	// Get and Set RunTime Informations
	InitRuntimeInformations();

	// Set the default line sep
	SetLineBreakVar();

	// Start the PHP Session
	StartPHPSession();
}

function InitPhpLogConConfigFile()
{
	// Needed to make global
	global $CFG, $gl_root_path, $content;

	if ( file_exists($gl_root_path . 'config.php') && GetFileLength($gl_root_path . 'config.php') > 0 )
	{
		// Include the main config
		include_once($gl_root_path . 'config.php');
		
		// Easier DB Access
		define('DB_CONFIG', $CFG['UserDBPref'] . "config");

		// If DEBUG Mode is enabled, we prepend the UID field into the col list!
		if ( $CFG['MiscShowDebugMsg'] == 1 )
			array_unshift($CFG['Columns'], SYSLOG_UID);

		// Now Copy all entries into content variable
		foreach ($CFG as $key => $value )
			$content[$key] = $value;

		// For MiscShowPageRenderStats
		if ( $CFG['MiscShowPageRenderStats'] == 1 )
		{
			$content['ShowPageRenderStats'] = "true";
			InitPageRenderStats();
		}
	}
	else
	{
		// Check for installscript!
		CheckForInstallPhp();
	}
}

function CheckForInstallPhp()
{
	// Check for installscript!
	if ( file_exists($content['BASEPATH'] . "install.php") ) 
		$strinstallmsg = '<br><br>' 
						. '<center><b>Click <a href="' . $content['BASEPATH'] . 'install.php">here</a> to Install PhpLogCon!</b><br><br>'
//							. 'See the Installation Guides for more Details!<br>'
//							. '<a href="docs/installation.htm" target="_blank">English Installation Guide</a>&nbsp;|&nbsp;'
//							. '<a href="docs/installation_de.htm" target="_blank">German Installation Guide</a><br><br>' 
//							. 'Also take a look to the <a href="docs/readme.htm" target="_blank">Readme</a> for some basics around PhpLogCon!<br>'
						. '</center>';
	else
		$strinstallmsg = "";
	DieWithErrorMsg( 'Error, main configuration file is missing!' . $strinstallmsg );
}

function GetFileLength($szFileName)
{
	if ( is_file($szFileName) )
		return filesize($szFileName);
	else
		return 0;
}

function InitPhpLogCon()
{
	// Needed to make global
	global $CFG, $gl_root_path, $content;

	// Init Basics which do not need a database
	InitBasicPhpLogCon();
	
	// Will init the config file!
	InitPhpLogConConfigFile();

	// Establish DB Connection
	if ( $CFG['UserDBEnabled'] )
		DB_Connect();

	// Now load the Page configuration values
	InitConfigurationValues();

	// Now Create Themes List because we haven't the config before!
	CreateThemesList();

	// Create Language List
	CreateLanguageList();

	// Init Predefined Searches List
	CreatePredefinedSearches();

	// --- Enable PHP Debug Mode 
	InitPhpDebugMode();
	// --- 
}

function CreateLogLineTypesList( $selectedType )
{
	global $content;

	// syslog
	$content['LOGLINETYPES']["syslog"]['type'] = "syslog";
	$content['LOGLINETYPES']["syslog"]['DisplayName'] = "Syslog / RSyslog";
	if ( $selectedType == $content['LOGLINETYPES']["syslog"]['type'] ) { $content['LOGLINETYPES']["syslog"]['selected'] = "selected"; } else { $content['LOGLINETYPES']["syslog"]['selected'] = ""; }

	// Adiscon Winsyslog
	$content['LOGLINETYPES']["winsyslog"]['type'] = "winsyslog";
	$content['LOGLINETYPES']["winsyslog"]['DisplayName'] = "Adiscon WinSyslog";
	if ( $selectedType == $content['LOGLINETYPES']["winsyslog"]['type'] ) { $content['LOGLINETYPES']["winsyslog"]['selected'] = "selected"; } else { $content['LOGLINETYPES']["winsyslog"]['selected'] = ""; }
}

function CreateSourceTypesList( $selectedSource )
{
	global $content;

	// SOURCE_DISK
	$content['SOURCETYPES'][SOURCE_DISK]['type'] = SOURCE_DISK;
	$content['SOURCETYPES'][SOURCE_DISK]['DisplayName'] = $content['LN_GEN_SOURCE_DISK'];
	if ( $selectedSource == $content['SOURCETYPES'][SOURCE_DISK]['type'] ) { $content['SOURCETYPES'][SOURCE_DISK]['selected'] = "selected"; } else { $content['SOURCETYPES'][SOURCE_DISK]['selected'] = ""; }

	// SOURCE_DB
	$content['SOURCETYPES'][SOURCE_DB]['type'] = SOURCE_DB;
	$content['SOURCETYPES'][SOURCE_DB]['DisplayName'] = $content['LN_GEN_SOURCE_DB'];
	if ( $selectedSource == $content['SOURCETYPES'][SOURCE_DB]['type'] ) { $content['SOURCETYPES'][SOURCE_DB]['selected'] = "selected"; } else { $content['SOURCETYPES'][SOURCE_DISK]['selected'] = ""; }
}

function CreateDBTypesList( $selectedDBType )
{
	global $content;

	// DB_MYSQL
	$content['DBTYPES'][DB_MYSQL]['type'] = DB_MYSQL;
	$content['DBTYPES'][DB_MYSQL]['DisplayName'] = "Mysql";
	if ( $selectedDBType == $content['DBTYPES'][DB_MYSQL]['type'] ) { $content['DBTYPES'][DB_MYSQL]['selected'] = "selected"; } else { $content['DBTYPES'][DB_MYSQL]['selected'] = ""; }

/* LATER ...
	// DB_MSSQL
	$content['DBTYPES'][DB_MSSQL]['type'] = DB_MSSQL;
	$content['DBTYPES'][DB_MSSQL]['DisplayName'] = "Microsoft SQL Server";
	if ( $selectedDBType == $content['DBTYPES'][DB_MSSQL]['type'] ) { $content['DBTYPES'][DB_MSSQL]['selected'] = "selected"; } else { $content['DBTYPES'][DB_MSSQL]['selected'] = ""; }

	// DB_ODBC
	$content['DBTYPES'][DB_ODBC]['type'] = DB_MSSQL;
	$content['DBTYPES'][DB_ODBC]['DisplayName'] = "ODBC Database Source";
	if ( $selectedDBType == $content['DBTYPES'][DB_ODBC]['type'] ) { $content['DBTYPES'][DB_ODBC]['selected'] = "selected"; } else { $content['DB_ODBC'][DB_MSSQL]['selected'] = ""; }
*/

}

function CreatePredefinedSearches()
{
	global $CFG, $content;
	if ( isset($CFG['Search']) )
	{
		// Enable predefined searches 
		$content['EnablePredefinedSearches'] = true;
		
		// Loop through all predefined searches!
		foreach ($CFG['Search'] as $mykey => $mySearch)
		{
			// Copy configured searches into content array!
			$content['Search'][$mykey]["ID"] = $mykey;
			$content['Search'][$mykey]["Selected"] = false;

			// --- Set CSS Class
			if ( $mykey % 2 == 0 )
				$content['Search'][$mykey]['cssclass'] = "line1";
			else
				$content['Search'][$mykey]['cssclass'] = "line2";
			// --- 

		}
	}
	else	// Disable predefined searches 
		$content['EnablePredefinedSearches'] = false;
}

function InitPhpDebugMode()
{
	global $content, $CFG;

	// --- Set Global DEBUG Level!
	if ( $CFG['MiscShowDebugMsg'] == 1 )
		ini_set( "error_reporting", E_ALL ); // ALL PHP MESSAGES!
//	else
//		ini_set( "error_reporting", E_ERROR ); // ONLY PHP ERROR'S!
	// --- 
}

function CheckAndSetRunMode()
{
	global $RUNMODE;
	// Set to command line mode if argv is set! 
	if ( !isset($_SERVER["GATEWAY_INTERFACE"]) )
		$RUNMODE = RUNMODE_COMMANDLINE;
}

function InitRuntimeInformations()
{
	global $content;

	// TODO| maybe not needed!
}

function CreateDebugModes()
{
	global $content;

	$content['DBGMODES'][0]['DisplayName'] = STR_DEBUG_ULTRADEBUG;
	if ( $content['parser_debugmode'] == $content['DBGMODES'][0]['DisplayName'] ) { $content['DBGMODES'][0]['selected'] = "selected"; } else { $content['DBGMODES'][0]['selected'] = ""; }
	$content['DBGMODES'][1]['DisplayName'] = STR_DEBUG_DEBUG;
	if ( $content['parser_debugmode'] == $content['DBGMODES'][1]['DisplayName'] ) { $content['DBGMODES'][1]['selected'] = "selected"; } else { $content['DBGMODES'][1]['selected'] = ""; }
	$content['DBGMODES'][2]['DisplayName'] = STR_DEBUG_INFO;
	if ( $content['parser_debugmode'] == $content['DBGMODES'][2]['DisplayName'] ) { $content['DBGMODES'][2]['selected'] = "selected"; } else { $content['DBGMODES'][2]['selected'] = ""; }
	$content['DBGMODES'][3]['DisplayName'] = STR_DEBUG_WARN;
	if ( $content['parser_debugmode'] == $content['DBGMODES'][3]['DisplayName'] ) { $content['DBGMODES'][3]['selected'] = "selected"; } else { $content['DBGMODES'][3]['selected'] = ""; }
	$content['DBGMODES'][4]['DisplayName'] = STR_DEBUG_ERROR;
	if ( $content['parser_debugmode'] == $content['DBGMODES'][4]['DisplayName'] ) { $content['DBGMODES'][4]['selected'] = "selected"; } else { $content['DBGMODES'][4]['selected'] = ""; }
}

function InitFrontEndVariables()
{
	global $content;

	$content['MENU_FOLDER_OPEN'] = "image=" . $content['BASEPATH'] . "images/icons/folder_closed.png";
	$content['MENU_FOLDER_CLOSED'] = "overimage=" . $content['BASEPATH'] . "images/icons/folder.png";
	$content['MENU_HOMEPAGE'] = "image=" . $content['BASEPATH'] . "images/icons/home.png";
	$content['MENU_LINK'] = "image=" . $content['BASEPATH'] . "images/icons/link.png";
	$content['MENU_PREFERENCES'] = "image=" . $content['BASEPATH'] . "images/icons/preferences.png";
	$content['MENU_ADMINENTRY'] = "image=" . $content['BASEPATH'] . "images/icons/star_blue.png";
	$content['MENU_ADMINLOGOFF'] = "image=" . $content['BASEPATH'] . "images/icons/exit.png";
	$content['MENU_ADMINUSERS'] = "image=" . $content['BASEPATH'] . "images/icons/businessmen.png";
	$content['MENU_SEARCH'] = "image=" . $content['BASEPATH'] . "images/icons/view.png";
	$content['MENU_SELECTION_DISABLED'] = "image=" . $content['BASEPATH'] . "images/icons/selection.png";
	$content['MENU_SELECTION_ENABLED'] = "image=" . $content['BASEPATH'] . "images/icons/selection_delete.png";

	$content['MENU_PAGER_BEGIN'] = $content['BASEPATH'] . "images/icons/media_beginning.png";
	$content['MENU_PAGER_PREVIOUS'] = $content['BASEPATH'] . "images/icons/media_rewind.png";
	$content['MENU_PAGER_NEXT'] = $content['BASEPATH'] . "images/icons/media_fast_forward.png";
	$content['MENU_PAGER_END'] = $content['BASEPATH'] . "images/icons/media_end.png";
	$content['MENU_NAV_LEFT'] = $content['BASEPATH'] . "images/icons/navigate_left.png";
	$content['MENU_NAV_RIGHT'] = $content['BASEPATH'] . "images/icons/navigate_right.png";
	$content['MENU_NAV_CLOSE'] = $content['BASEPATH'] . "images/icons/navigate_close.png";
	$content['MENU_NAV_OPEN'] = $content['BASEPATH'] . "images/icons/navigate_open.png";

	$content['MENU_PAGER_BEGIN_GREY'] = $content['BASEPATH'] . "images/icons/grey/media_beginning.png";
	$content['MENU_PAGER_PREVIOUS_GREY'] = $content['BASEPATH'] . "images/icons/grey/media_rewind.png";
	$content['MENU_PAGER_NEXT_GREY'] = $content['BASEPATH'] . "images/icons/grey/media_fast_forward.png";
	$content['MENU_PAGER_END_GREY'] = $content['BASEPATH'] . "images/icons/grey/media_end.png";
}

// Lang Helper for Strings with ONE variable
function GetAndReplaceLangStr( $strlang, $param1 = "", $param2 = "", $param3 = "", $param4 = "", $param5 = "" )
{
	$strfinal = str_replace ( "%1", $param1, $strlang );
	if ( strlen($param2) > 0 )
		$strfinal = str_replace ( "%1", $param2, $strfinal );
	if ( strlen($param3) > 0 )
		$strfinal = str_replace ( "%1", $param3, $strfinal );
	if ( strlen($param4) > 0 )
		$strfinal = str_replace ( "%1", $param4, $strfinal );
	if ( strlen($param5) > 0 )
		$strfinal = str_replace ( "%1", $param5, $strfinal );
	
	// And return
	return $strfinal;
}

function InitConfigurationValues()
{
	global $content, $CFG, $LANG, $gl_root_path;

	// If Database is enabled, try to read from database!
	if ( $CFG['UserDBEnabled'] )
	{
		$result = DB_Query("SELECT * FROM " . DB_CONFIG);
		$rows = DB_GetAllRows($result, true, true);

		if ( isset($rows ) )
		{
			for($i = 0; $i < count($rows); $i++)
				$content[ $rows[$i]['name'] ] = $rows[$i]['value'];
		}
		// General defaults 
		// --- Language Handling
		if ( !isset($content['gen_lang']) ) { $content['gen_lang'] = $CFG['ViewDefaultLanguage'] /*"en"*/; }
		
		// --- PHP Debug Mode
		if ( !isset($content['gen_phpdebug']) ) { $content['gen_phpdebug'] = "no"; }
		// --- 

		// Database Version Checker! 
		if ( $content['database_internalversion'] > $content['database_installedversion'] )
		{	
			// Database is out of date, we need to upgrade
			$content['database_forcedatabaseupdate'] = "yes"; 
		}
	}
	else
	{
		// --- Set Defaults...
		// Language Handling
		if ( isset($_SESSION['CUSTOM_LANG']) && VerifyLanguage($_SESSION['CUSTOM_LANG']) )
		{
			$content['user_lang'] = $_SESSION['CUSTOM_LANG'];
			$LANG = $content['user_lang'];
		}
		else if ( isset($content['gen_lang']) && VerifyLanguage($content['gen_lang']))
		{
			$content['user_lang'] = $content['gen_lang'];
			$LANG = $content['user_lang'];
		}
		else	// Failsave!
		{
			$content['user_lang'] = $CFG['ViewDefaultLanguage'] /*"en"*/;
			$LANG = $content['user_lang'];
			$content['gen_lang'] = $content['user_lang'];
		}
	}

	// Theme Handling
	if ( !isset($content['web_theme']) ) { $content['web_theme'] = $CFG['ViewDefaultTheme'] /*"default"*/; }
	if ( isset($_SESSION['CUSTOM_THEME']) && VerifyTheme($_SESSION['CUSTOM_THEME']) )
		$content['user_theme'] = $_SESSION['CUSTOM_THEME'];
	else
		$content['user_theme'] = $content['web_theme'];

	//Init Theme About Info ^^
	InitThemeAbout($content['user_theme']);
	// ---

	// Init main langauge file now!
	IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

	// Init other things which are needed
	InitFrontEndVariables();
}

function SetDebugModeFromString( $facility )
{
	global $DEBUGMODE;

	switch ( $facility )
	{
		case STR_DEBUG_ULTRADEBUG:
			$DEBUGMODE = DEBUG_ULTRADEBUG;
			break;
		case STR_DEBUG_DEBUG:
			$DEBUGMODE = DEBUG_DEBUG;
			break;
		case STR_DEBUG_INFO:
			$DEBUGMODE = DEBUG_INFO;
			break;
		case STR_DEBUG_WARN:
			$DEBUGMODE = DEBUG_WARN;
			break;
		case STR_DEBUG_ERROR:
			$DEBUGMODE = DEBUG_ERROR;
			break;
	}
}


function InitPageRenderStats()
{
	global $gl_starttime, $querycount;
	$gl_starttime = microtime_float();
	$querycount = 0;
}

function FinishPageRenderStats( &$mycontent)
{
	global $gl_starttime, $querycount;

	$endtime = microtime_float();
	$mycontent['PAGERENDERTIME'] = number_format($endtime - $gl_starttime, 4, '.', '');
	$mycontent['TOTALQUERIES'] = $querycount;
}

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

function SetLineBreakVar()
{
	// Used for some functions
	global $RUNMODE, $linesep;

	if		( $RUNMODE == RUNMODE_COMMANDLINE )
		$linesep = "\r\n";
	else if	( $RUNMODE == RUNMODE_WEBSERVER )
		$linesep = "<br>";
}

function CheckUrlOrIP($ip) 
{
	$long = ip2long($ip); 
	if ( $long == -1 ) 
		return false; 
	else
		return true; 
}

function DieWithErrorMsg( $szerrmsg )
{
	global $content;
	print("<html><head><link rel=\"stylesheet\" href=\"" . $gl_root_path . "admin/css/admin.css\" type=\"text/css\"></head><body>");
	print("<table width=\"600\" align=\"center\" class=\"with_border\"><tr><td><center><H3><font color='red'>Critical Error occured</font></H3><br></center>");
	print("<B>Errordetails:</B><BR>" .  $szerrmsg);
	print("</td></tr></table>");

	exit;
}

function DieWithFriendlyErrorMsg( $szerrmsg )
{
	//TODO: Make with template
	print("<html><body>");
	print("<center><H3><font color='red'>Error occured</font></H3><br></center>");
	print("<B>Errordetails:</B><BR>" .  $szerrmsg);
	exit;
}

function GetStringWithHTMLCodes($myStr)
{
	// Replace all special characters with valid html representations
	return htmlentities($myStr);
}

function InitTemplateParser()
{
	global $page, $gl_root_path;
	// -----------------------------------------------
	// Create Template Object and set some variables for the templates
	// -----------------------------------------------
	$page = new Template();
	$page -> set_path ( $gl_root_path . "templates/" );
}

function VerifyLanguage( $mylang ) 
{ 
	global $gl_root_path;

	if ( is_dir( $gl_root_path . 'lang/' . $mylang ) )
		return true;
	else
		return false;
}

function IncludeLanguageFile( $langfile ) 
{
	global $LANG, $LANG_EN; 

	if ( file_exists( $langfile ) )
		include( $langfile );
	else
	{
		$langfile = str_replace( $LANG, $LANG_EN, $langfile );
		include( $langfile );
	}
}

function RedirectPage( $newpage )
{
	header("Location: $newpage");
	exit;
}

function RedirectResult( $szMsg, $newpage )
{
	header("Location: result.php?msg=" . urlencode($szMsg) . "&redir=" . urlencode($newpage));
	exit;
}

/*
*	GetEventTime
*
*	Helper function to parse and obtain a valid EventTime Array from the input string.
*	Return value: EventTime Array!
*
*/
function GetEventTime($szTimStr)
{
	// Sample: Mar 10 14:45:44
	if ( preg_match("/(...) ([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
	{
		// RFC 3164 typical timestamp
		$eventtime[EVTIME_TIMESTAMP] = mktime($out[3], $out[4], $out[5], GetMonthFromString($out[1]), $out[2]);
		$eventtime[EVTIME_TIMEZONE] = date_default_timezone_get(); // WTF TODO!
		$eventtime[EVTIME_MICROSECONDS] = 0;

//			echo gmdate(DATE_RFC822, $eventtime[EVTIME_TIMESTAMP]) . "<br>";
//			print_r ( $eventtime );
//			exit;
	}
	// Sample: 2008-04-02T11:12:32+02:00
	else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})\+([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
	{
		// RFC 3164 typical timestamp
		$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
		$eventtime[EVTIME_TIMEZONE] = $out[7]; 
		$eventtime[EVTIME_MICROSECONDS] = 0;
	}
	// Sample: 2008-04-02T11:12:32.380449+02:00
	else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})\.([0-9]{1,6})\+([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
	{
		// RFC 3164 typical timestamp
		$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
		$eventtime[EVTIME_TIMEZONE] = $out[8]; 
		$eventtime[EVTIME_MICROSECONDS] = $out[7];
	}
	// Sample: 2008-04-02,15:19:06
	else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2}),([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
	{
		// RFC 3164 typical timestamp
		$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
		$eventtime[EVTIME_TIMEZONE] = date_default_timezone_get(); // WTF TODO!
		$eventtime[EVTIME_MICROSECONDS] = 0;
	}
	// Sample: 2008-02-19 12:52:37
	else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
	{
		// RFC 3164 typical timestamp
		$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
		$eventtime[EVTIME_TIMEZONE] = date_default_timezone_get(); // WTF TODO!
		$eventtime[EVTIME_MICROSECONDS] = 0;
	}
	// Sample: 2007-4-18T00:00:00
	else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
	{
		// RFC 3164 typical timestamp
		$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
		$eventtime[EVTIME_TIMEZONE] = date_default_timezone_get(); // WTF TODO!
		$eventtime[EVTIME_MICROSECONDS] = 0;
	}
	else
	{
		die ("wtf GetEventTime unparsable time - " . $szTimStr );
	}

	// return result!
	return $eventtime;
}

/*
*	GetMonthFromString
*	
*	Simple Helper function to obtain the numeric represantation of the month
*/
function GetMonthFromString($szMonth)
{
	switch($szMonth)
	{
		case "Jan":
			return 1;
		case "Feb":
			return 2;
		case "Mar":
			return 3;
		case "Apr":
			return 4;
		case "May":
			return 5;
		case "Jun":
			return 6;
		case "Jul":
			return 7;
		case "Aug":
			return 8;
		case "Sep":
			return 9;
		case "Oct":
			return 10;
		case "Nov":
			return 11;
		case "Dez":
			return 12;
	}
}


// --- BEGIN Usermanagement Function --- 
function StartPHPSession()
{
	global $RUNMODE;
	if ( $RUNMODE == RUNMODE_WEBSERVER )
	{
		// This will start the session
		if (session_id() == "")
			session_start();

		if ( !isset($_SESSION['SESSION_STARTED']) )
			$_SESSION['SESSION_STARTED'] = "true";
	}
}

function CheckForUserLogin( $isloginpage, $isUpgradePage = false )
{
	global $content; 

	if ( isset($_SESSION['SESSION_LOGGEDIN']) )
	{
		if ( !$_SESSION['SESSION_LOGGEDIN'] ) 
			RedirectToUserLogin();
		else
		{
			$content['SESSION_LOGGEDIN'] = "true";
			$content['SESSION_USERNAME'] = $_SESSION['SESSION_USERNAME'];
		}

		// New, Check for database Version and may redirect to updatepage!
		if (	isset($content['database_forcedatabaseupdate']) && 
				$content['database_forcedatabaseupdate'] == "yes" && 
				$isUpgradePage == false 
			)
				RedirectToDatabaseUpgrade();
	}
	else
	{
		if ( $isloginpage == false )
			RedirectToUserLogin();
	}

}

function CreateUserName( $username, $password, $access_level )
{
	$md5pass = md5($password);
	$result = DB_Query("SELECT username FROM " . STATS_USERS . " WHERE username = '" . $username . "'");
	$rows = DB_GetAllRows($result, true);
	if ( isset($rows) )
	{
		DieWithFriendlyErrorMsg( "User $username already exists!" );

		// User not created!
		return false;
	}
	else
	{
		// Create User
		$result = DB_Query("INSERT INTO " . STATS_USERS . " (username, password, access_level) VALUES ('$username', '$md5pass', $access_level)");
		DB_FreeQuery($result);

		// Success
		return true;
	}
}

function CheckUserLogin( $username, $password )
{
	global $content, $CFG;

	// TODO: SessionTime and AccessLevel check

	$md5pass = md5($password);
	$sqlselect = "SELECT access_level FROM " . STATS_USERS . " WHERE username = '" . $username . "' and password = '" . $md5pass . "'";
	$result = DB_Query($sqlselect);
	$rows = DB_GetAllRows($result, true);
	if ( isset($rows) )
	{
		$_SESSION['SESSION_LOGGEDIN'] = true;
		$_SESSION['SESSION_USERNAME'] = $username;
		$_SESSION['SESSION_ACCESSLEVEL'] = $rows[0]['access_level'];
		
		$content['SESSION_LOGGEDIN'] = "true";
		$content['SESSION_USERNAME'] = $username;

		// Success !
		return true;
	}
	else
	{
		if ( $CFG['MiscShowDebugMsg'] == 1 )
			DieWithFriendlyErrorMsg( "Debug Error: Could not login user '" . $username . "' <br><br><B>Sessionarray</B> <pre>" . var_export($_SESSION, true) . "</pre><br><B>SQL Statement</B>: " . $sqlselect );
		
		// Default return false
		return false;
	}
}

function DoLogOff()
{
	global $content;

	unset( $_SESSION['SESSION_LOGGEDIN'] );
	unset( $_SESSION['SESSION_USERNAME'] );
	unset( $_SESSION['SESSION_ACCESSLEVEL'] );

	// Redir to Index Page
	RedirectPage( "index.php");
}

function RedirectToUserLogin()
{
	// TODO Referer
	header("Location: login.php?referer=" . $_SERVER['PHP_SELF']);
	exit;
}

function RedirectToDatabaseUpgrade()
{
	// TODO Referer
	header("Location: upgrade.php"); // ?referer=" . $_SERVER['PHP_SELF']);
	exit;
}
// --- END Usermanagement Function --- 


?>
