<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* -> Stuff which has to be static and predefined					*
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

// --- Some custom defines
define('RUNMODE_COMMANDLINE', 1);
define('RUNMODE_WEBSERVER', 2);

define('DEBUG_ULTRADEBUG', 5);
define('DEBUG_DEBUG', 4);
define('DEBUG_INFO', 3);
define('DEBUG_WARN', 2);
define('DEBUG_ERROR', 1);
define('DEBUG_ERROR_WTF', 0);

define('STR_DEBUG_ULTRADEBUG', "UltraDebug");
define('STR_DEBUG_DEBUG', "Debug");
define('STR_DEBUG_INFO', "Information");
define('STR_DEBUG_WARN', "Warning");
define('STR_DEBUG_ERROR', "Error");
define('STR_DEBUG_ERROR_WTF', "WTF OMFG");

// --- Source Type defines
define('SOURCE_DISK', '1');
define('SOURCE_MYSQLDB', '2');

// --- 
?>