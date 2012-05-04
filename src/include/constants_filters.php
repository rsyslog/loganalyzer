<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* -> Stuff which has to be static and predefined					*
	*																	*
	* All directives are explained within this file						*
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
	* distribution.
	* 
	* Adiscon LogAnalyzer is also available under a commercial license.
	* For details, contact info@adiscon.com or visit
	* http://loganalyzer.adiscon.com/commercial
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
define('DATEMODE_ALL', 1);
define('DATEMODE_RANGE', 2);
define('DATEMODE_LASTX', 3);
define('DATEMODE_RANGE_FROM', 4);
define('DATEMODE_RANGE_TO', 5);
define('DATEMODE_RANGE_DATE', 6);

define('DATE_LASTX_HOUR', 1);
define('DATE_LASTX_12HOURS', 2);
define('DATE_LASTX_24HOURS', 3);
define('DATE_LASTX_7DAYS', 4);
define('DATE_LASTX_31DAYS', 5);
// --- 


// Helper constants needed for parsing filters
define('FILTER_TMP_KEY', 0);
define('FILTER_TMP_VALUE', 1);
define('FILTER_TMP_MODE', 2);
define('FILTER_DATEMODE', 'datemode');
define('FILTER_TYPE', 'filtertype');
define('FILTER_DATEMODENAME', 'datemodename');
define('FILTER_VALUE', 'value');
define('FILTER_MODE', 'filtermode');
define('FILTER_MODE_INCLUDE', 1);
define('FILTER_MODE_EXCLUDE', 2);
define('FILTER_MODE_SEARCHFULL', 4);
define('FILTER_MODE_SEARCHREGEX', 8);

// --- Init Facility LIST
$content['filter_facility_list'][] = array( "ID" => SYSLOG_KERN, "DisplayName" => "KERN", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_USER, "DisplayName" => "USER", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_MAIL, "DisplayName" => "MAIL", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_DAEMON, "DisplayName" => "DAEMON", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_AUTH, "DisplayName" => "AUTH", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_SYSLOG, "DisplayName" => "SYSLOG", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LPR, "DisplayName" => "LPR", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_NEWS, "DisplayName" => "NEWS", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_UUCP, "DisplayName" => "UUCP", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_CRON, "DisplayName" => "CRON", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_SECURITY, "DisplayName" => "SECURITY", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_FTP, "DisplayName" => "FTP", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_NTP, "DisplayName" => "NTP", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOGAUDIT, "DisplayName" => "LOGAUDIT", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOGALERT, "DisplayName" => "LOGALERT", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_CLOCK, "DisplayName" => "CLOCK", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL0, "DisplayName" => "LOCAL0", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL1, "DisplayName" => "LOCAL1", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL2, "DisplayName" => "LOCAL2", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL3, "DisplayName" => "LOCAL3", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL4, "DisplayName" => "LOCAL4", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL5, "DisplayName" => "LOCAL5", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL6, "DisplayName" => "LOCAL6", "selected" => "" );
$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL7, "DisplayName" => "LOCAL7", "selected" => "" );
// --- 

// Init Severity LIST
$content['filter_severity_list'][] = array( "ID" => SYSLOG_EMERG, "DisplayName" => "EMERG", "selected" => "" );
$content['filter_severity_list'][] = array( "ID" => SYSLOG_ALERT, "DisplayName" => "ALERT", "selected" => "" );
$content['filter_severity_list'][] = array( "ID" => SYSLOG_CRIT, "DisplayName" => "CRIT", "selected" => "" );
$content['filter_severity_list'][] = array( "ID" => SYSLOG_ERR, "DisplayName" => "ERR", "selected" => "" );
$content['filter_severity_list'][] = array( "ID" => SYSLOG_WARNING, "DisplayName" => "WARNING", "selected" => "" );
$content['filter_severity_list'][] = array( "ID" => SYSLOG_NOTICE, "DisplayName" => "NOTICE", "selected" => "" );
$content['filter_severity_list'][] = array( "ID" => SYSLOG_INFO, "DisplayName" => "INFO", "selected" => "" );
$content['filter_severity_list'][] = array( "ID" => SYSLOG_DEBUG, "DisplayName" => "DEBUG", "selected" => "" );
// --- 

// Init MessageType LIST
//$content['filter_messagetype_list'][] = array( "ID" => IUT_Unknown, "DisplayName" => "Unknown", "selected" => "" );
$content['filter_messagetype_list'][] = array( "ID" => IUT_Syslog, "DisplayName" => "Syslog", "selected" => "" );
$content['filter_messagetype_list'][] = array( "ID" => IUT_NT_EventReport, "DisplayName" => "WinEventLog", "selected" => "" );
$content['filter_messagetype_list'][] = array( "ID" => IUT_File_Monitor, "DisplayName" => "File Monitor", "selected" => "" );
$content['filter_messagetype_list'][] = array( "ID" => IUT_WEBSERVERLOG, "DisplayName" => "Webserver Logfile", "selected" => "" );

?>