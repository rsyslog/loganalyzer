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

// --- Config Level defines
define('CFGLEVEL_GLOBAL', 0);
define('CFGLEVEL_GROUP', 1);
define('CFGLEVEL_USER', 2);
// --- 

// --- Source Type defines
define('SOURCE_DISK', '1');
define('SOURCE_DB', '2');
define('SOURCE_PDO', '3');
define('SOURCE_MONGODB', '4');
// --- 

// --- Exportformat defines
define('EXPORT_CVS', 'CVS');
define('EXPORT_XML', 'XML');
// --- 

// --- GFX Chart Types
define('CHART_CAKE', 1);
define('CHART_BARS_VERTICAL', 2);
define('CHART_BARS_HORIZONTAL', 3);

define('CHARTDATA_NAME', 'NAME');
define('CHARTDATA_COUNT', 'COUNT');
// --- 

// --- 
define('UID_UNKNOWN', -1);
// --- 

// --- Define possible database types
define('DB_MYSQL', 0);
define('DB_MSSQL', 1);
define('DB_ODBC', 2);
define('DB_PGSQL', 3);
define('DB_OCI', 4);
define('DB_DB2', 5);
define('DB_FIREBIRD', 6);
define('DB_INFORMIX', 7);
define('DB_SQLITE', 8);
// --- 

// --- Define supported AUTH Methods
define('USERDB_AUTH_INTERNAL', 0);
define('USERDB_AUTH_LDAP', 1);
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
define('SYSLOG_SECURITY', 10);
define('SYSLOG_FTP', 11);
define('SYSLOG_NTP', 12);
define('SYSLOG_LOGAUDIT', 13);
define('SYSLOG_LOGALERT', 14);
define('SYSLOG_CLOCK', 15);
define('SYSLOG_LOCAL0', 16);
define('SYSLOG_LOCAL1', 17);
define('SYSLOG_LOCAL2', 18);
define('SYSLOG_LOCAL3', 19);
define('SYSLOG_LOCAL4', 20);
define('SYSLOG_LOCAL5', 21);
define('SYSLOG_LOCAL6', 22);
define('SYSLOG_LOCAL7', 23);
$facility_colors[SYSLOG_KERN] = "#F1BEA7";
$facility_colors[SYSLOG_USER] = "#F1D0A7";
$facility_colors[SYSLOG_MAIL] = "#F1E3A7";
$facility_colors[SYSLOG_DAEMON] = "#E5F1A7";
$facility_colors[SYSLOG_AUTH] = "#D3F1A7";
$facility_colors[SYSLOG_SYSLOG] = "#C1F1A7";
$facility_colors[SYSLOG_LPR] = "#A7F1D6";
$facility_colors[SYSLOG_NEWS] = "#A7F1E8";
$facility_colors[SYSLOG_UUCP] = "#A7E1F1";
$facility_colors[SYSLOG_CRON] = "#A7C8F1";
$facility_colors[SYSLOG_SECURITY] = "#F2ECD8";
$facility_colors[SYSLOG_FTP] = "#ECE3C4";
$facility_colors[SYSLOG_NTP] = "#E7DAB1";
$facility_colors[SYSLOG_LOGAUDIT] = "#F2D8E2";
$facility_colors[SYSLOG_LOGALERT] = "#ECC4D3";
$facility_colors[SYSLOG_CLOCK] = "#E7B1C5";
$facility_colors[SYSLOG_LOCAL0] = "#F2F2F2";
$facility_colors[SYSLOG_LOCAL1] = "#E4E5E6";
$facility_colors[SYSLOG_LOCAL2] = "#D6D9DA";
$facility_colors[SYSLOG_LOCAL3] = "#C9CDCF";
$facility_colors[SYSLOG_LOCAL4] = "#BEC2C4";
$facility_colors[SYSLOG_LOCAL5] = "#B1B6B9";
$facility_colors[SYSLOG_LOCAL6] = "#A3AAAD";
$facility_colors[SYSLOG_LOCAL7] = "#969DA1";

define('SYSLOG_EMERG', 0);
define('SYSLOG_ALERT', 1);
define('SYSLOG_CRIT', 2);
define('SYSLOG_ERR', 3);
define('SYSLOG_WARNING', 4);
define('SYSLOG_NOTICE', 5);
define('SYSLOG_INFO', 6);
define('SYSLOG_DEBUG', 7);
$severity_colors[SYSLOG_EMERG] = "#840A15";
$severity_colors[SYSLOG_ALERT] = "#BA0716";
$severity_colors[SYSLOG_CRIT] = "#CE0819";
$severity_colors[SYSLOG_ERR] = "#FF0A1F";
$severity_colors[SYSLOG_WARNING] = "#EF8200";
$severity_colors[SYSLOG_NOTICE] = "#14AD42";
$severity_colors[SYSLOG_INFO] = "#0C9C91";
$severity_colors[SYSLOG_DEBUG] = "#119BDE";
// --- 

// --- MonitorWare InfoUnit Defines | Messagetypes
define('IUT_Unknown', '0');
define('IUT_Syslog', '1');
define('IUT_Heartbeat', '2');
define('IUT_NT_EventReport', '3');
define('IUT_SNMP_Trap', '4');
define('IUT_File_Monitor', '5');
define('IUT_PingProbe', '8');
define('IUT_Port_Probe', '9');
define('IUT_NTService_Monitor', '10');
define('IUT_DiskSpace_Monitor', '11');
define('IUT_DB_Monitor', '12');
define('IUT_Serial_Monitor', '13');
define('IUT_CPU_Monitor', '14');
define('IUT_AliveMonRequest', '16');
define('IUT_SMTPProbe', '17');
define('IUT_FTPProbe', '18');
define('IUT_HTTPProbe', '19');
define('IUT_POP3Probe', '20');
define('IUT_IMAPProbe', '21');
define('IUT_NNTPProbe', '22');
define('IUT_WEVTMONV2', '23');
define('IUT_SMTPLISTENER', '24');
define('IUT_WEBSERVERLOG', '10001');
$msgtype_colors[IUT_Unknown] = "#D0FBDC";
$msgtype_colors[IUT_Syslog] = "#D0FBF1";
$msgtype_colors[IUT_Heartbeat] = "#D0EEFB";
$msgtype_colors[IUT_NT_EventReport] = "#D0E5FB";
$msgtype_colors[IUT_SNMP_Trap] = "#D0DBFB";
$msgtype_colors[IUT_File_Monitor] = "#DAD0FB";
$msgtype_colors[IUT_PingProbe] = "#E0D0FB";
$msgtype_colors[IUT_Port_Probe] = "#F6D0FB";
$msgtype_colors[IUT_NTService_Monitor] = "#FBD0E7";
$msgtype_colors[IUT_DiskSpace_Monitor] = "#FBD0D3";
$msgtype_colors[IUT_DB_Monitor] = "#FBD8D0";
$msgtype_colors[IUT_Serial_Monitor] = "#FBE0D0";
$msgtype_colors[IUT_CPU_Monitor] = "#FBEBD0";
$msgtype_colors[IUT_AliveMonRequest] = "#FBF6D0";
$msgtype_colors[IUT_SMTPProbe] = "#F5FBD0";
$msgtype_colors[IUT_FTPProbe] = "#EBFBD0";
$msgtype_colors[IUT_HTTPProbe] = "#E1FBD0";
$msgtype_colors[IUT_POP3Probe] = "#D0FBD4";
$msgtype_colors[IUT_IMAPProbe] = "#D0FBE8";
$msgtype_colors[IUT_NNTPProbe] = "#D0F7FB";
$msgtype_colors[IUT_WEVTMONV2] = "#CCE4D2";
$msgtype_colors[IUT_SMTPLISTENER] = "#CCE4DE";
$msgtype_colors[IUT_WEBSERVERLOG] = "#E1FBD0";
// --- 

// Supported Encodings
define('ENC_ISO_8859_1', "ISO-8859-1"); 
define('ENC_UTF8', "utf-8"); 
$encodings[ENC_ISO_8859_1] = array("ID" => ENC_ISO_8859_1); 
$encodings[ENC_UTF8] = array("ID" => ENC_UTF8); 

?>