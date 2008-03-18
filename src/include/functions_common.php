<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Common needed functions											*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
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
include($gl_root_path . 'config.php');

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
$content['BUILDNUMBER'] = "0.1.101";
$content['TITLE'] = "PhpLogCon - Release " . $content['BUILDNUMBER'];	// Title of the Page 
$content['BASEPATH'] = $gl_root_path;
$content['EXTRA_METATAGS'] = "";
$content['EXTRA_JAVASCRIPT'] = "";
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
		define('DB_CONFIG', $CFG['TBPref'] . "config");

		// For ShowPageRenderStats
		if ( $CFG['ShowPageRenderStats'] == 1 )
		{
			$content['ShowPageRenderStats'] = "true";
			InitPageRenderStats();
		}
	}
	else
	{
		// Check for installscript!
		if ( file_exists($content['BASEPATH'] . "install.php") ) 
			$strinstallmsg = '<br><br>' 
							. '<center><b>Click <a href="' . $content['BASEPATH'] . 'install.php">here</a> to Install PhpLogCon!</b><br><br>'
							. 'See the Installation Guides for more Details!<br>'
							. '<a href="docs/installation.htm" target="_blank">English Installation Guide</a>&nbsp;|&nbsp;'
							. '<a href="docs/installation_de.htm" target="_blank">German Installation Guide</a><br><br>' 
							. 'Also take a look to the <a href="docs/readme.htm" target="_blank">Readme</a> for some basics around PhpLogCon!<br>'
							. '</center>';
		else
			$strinstallmsg = "";
		DieWithErrorMsg( 'Error, main configuration file is missing!' . $strinstallmsg );
	}
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
	if ( $CFG['UseDB'] )
		DB_Connect();

	// Now load the Page configuration values
	InitConfigurationValues();

	// Now Create Themes List because we haven't the config before!
	CreateThemesList();

	// Create Language List
	CreateLanguageList();

	// --- Enable PHP Debug Mode 
	InitPhpDebugMode();
	// --- 
}

function InitPhpDebugMode()
{
	global $content;

	// --- Set Global DEBUG Level!

// HARDCODED !!!
	$content['gen_phpdebug'] = "yes";
	
	if ( $content['gen_phpdebug'] == "yes" )
		ini_set( "error_reporting", E_ALL ); // ALL PHP MESSAGES!
	else
		ini_set( "error_reporting", E_ERROR ); // ONLY PHP ERROR'S!
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
	global $content, $LANG;

	$result = DB_Query("SELECT * FROM " . STATS_CONFIG);
	$rows = DB_GetAllRows($result, true, true);

	// If Database is enabled, try to read from database!
	if ( $CFG['UseDB'] )
	{
		if ( isset($rows ) )
		{
			for($i = 0; $i < count($rows); $i++)
				$content[ $rows[$i]['name'] ] = $rows[$i]['value'];
		}
		// General defaults 
		// --- Language Handling
		if ( !isset($content['gen_lang']) ) { $content['gen_lang'] = "en"; }
		if ( VerifyLanguage($content['gen_lang']) )
			$LANG = $content['gen_lang'];
		else
		{
			// Fallback!
			$LANG = "en";
			$content['gen_lang'] = "en";
		}
		
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

	// --- Set Defaults...
	// Language Handling
	if ( isset($_SESSION['CUSTOM_LANG']) && VerifyLanguage($_SESSION['CUSTOM_LANG']) )
	{
		$content['user_lang'] = $_SESSION['CUSTOM_LANG'];
		$LANG = $content['user_lang'];
	}
	else if ( isset($content['gen_lang']) )
		$content['user_lang'] = $content['gen_lang'];
	else	// Failsave!
		$content['user_lang'] = "en";

	// Theme Handling
	if ( !isset($content['web_theme']) ) { $content['web_theme'] = "default"; }
	if ( isset($_SESSION['CUSTOM_THEME']) && VerifyTheme($_SESSION['CUSTOM_THEME']) )
		$content['user_theme'] = $_SESSION['CUSTOM_THEME'];
	else
		$content['user_theme'] = $content['web_theme'];
	// ---

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
	global $RUNMODE, $content;
	if		( $RUNMODE == RUNMODE_COMMANDLINE )
	{
		print("\n\n\t\tCritical Error occured\n");
		print("\t\tErrordetails:\t" . $szerrmsg . "\n");
		print("\t\tTerminating now!\n");
	}
	else if	( $RUNMODE == RUNMODE_WEBSERVER )
	{
		print("<html><head><link rel=\"stylesheet\" href=\"" . $content['BASEPATH'] . "admin/css/admin.css\" type=\"text/css\"></head><body>");
		print("<table width=\"600\" align=\"center\" class=\"with_border\"><tr><td><center><H3><font color='red'>Critical Error occured</font></H3><br></center>");
		print("<B>Errordetails:</B><BR>" .  $szerrmsg);
		print("</td></tr></table>");
	}
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
		if ( $CFG['ShowDebugMsg'] == 1 )
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