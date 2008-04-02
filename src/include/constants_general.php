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

// --- 
define('UID_UNKNOWN', -1);
// --- 

// --- Syslog specific defines!
define('SYSLOG_KERN', 0);
define('SYSLOG_USER', 1);
define('SYSLOG_MAIL', 2);
define('SYSLOG_DAEMON', 3);
define('SYSLOG_AUTH', 4);
define('SYSLOG_SYSLOG', 5);
define('SYSLOG_LPR', 6);
define('SYSLOG_NEWS', 7);
define('SYSLOG_UUCP', 8);
define('SYSLOG_CRON', 9);
define('SYSLOG_LOCAL0', 16);
define('SYSLOG_LOCAL1', 17);
define('SYSLOG_LOCAL2', 18);
define('SYSLOG_LOCAL3', 19);
define('SYSLOG_LOCAL4', 20);
define('SYSLOG_LOCAL5', 21);
define('SYSLOG_LOCAL6', 22);
define('SYSLOG_LOCAL7', 23);

define('SYSLOG_EMERG', 0);
define('SYSLOG_ALERT', 1);
define('SYSLOG_CRIT', 2);
define('SYSLOG_ERR', 3);
define('SYSLOG_WARNING', 4);
define('SYSLOG_NOTICE', 5);
define('SYSLOG_INFO', 6);
define('SYSLOG_DEBUG', 7);
// --- 

?>