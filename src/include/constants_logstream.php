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

// --- Define properties names of all know fields 
define('SYSLOG_UID', 'uID');
define('SYSLOG_DATE', 'timereported');
define('SYSLOG_HOST', 'FROMHOST');
define('SYSLOG_MESSAGETYPE', 'IUT');
define('SYSLOG_MESSAGE', 'msg');

// Syslog specific
define('SYSLOG_FACILITY', 'syslogfacility');
define('SYSLOG_SEVERITY', 'syslogseverity');
define('SYSLOG_SYSLOGTAG', 'syslogtag');
define('SYSLOG_PROCESSID', 'procid');

// EventLog specific
define('SYSLOG_EVENT_ID', 'id');
define('SYSLOG_EVENT_LOGTYPE', 'NTEventLogType');
define('SYSLOG_EVENT_SOURCE', 'sourceproc');
define('SYSLOG_EVENT_CATEGORY', 'category');
define('SYSLOG_EVENT_USER', 'user');

// Weblog specific
define('SYSLOG_WEBLOG_USER', 'http_user');
define('SYSLOG_WEBLOG_METHOD', 'http_method');
define('SYSLOG_WEBLOG_URL', 'http_url');
define('SYSLOG_WEBLOG_QUERYSTRING', 'http_querystring');
define('SYSLOG_WEBLOG_PVER', 'http_ver');
define('SYSLOG_WEBLOG_STATUS', 'http_status');
define('SYSLOG_WEBLOG_BYTESSEND', 'http_bytessend');
define('SYSLOG_WEBLOG_REFERER', 'http_referer');
define('SYSLOG_WEBLOG_USERAGENT', 'http_useragent');

// Other fields
define('MISC_SYSTEMID', 'misc_systenid');
define('MISC_CHECKSUM', 'misc_checksum');
// ---

// Define possible FIELD Types
define('FILTER_TYPE_STRING', 0);
define('FILTER_TYPE_NUMBER', 1);
define('FILTER_TYPE_DATE', 2);
define('FILTER_TYPE_BOOL', 3);
define('FILTER_TYPE_UNKNOWN', 99);

// Define possible alignments
define('ALIGN_CENTER', 'center');
define('ALIGN_LEFT', 'left');
define('ALIGN_RIGHT', 'right');

// Defines for Report output types
define('REPORT_OUTPUT_HTML', 'html');
define('REPORT_OUTPUT_PDF', 'pdf');

// Defines for Report output targets
define('REPORT_TARGET_STDOUT', 'stdout');
define('REPORT_TARGET_FILE', 'file');
define('REPORT_TARGET_EMAIL', 'mail');

// Further helper defines for output targets
define('REPORT_TARGET_TYPE', 'type');
define('REPORT_TARGET_FILENAME', 'filename');

// Defines for sorting
define('SORTING_ORDER_ASC', 'asc');
define('SORTING_ORDER_DESC', 'desc');

// --- Predefine fields array!
$fields[SYSLOG_UID]['FieldID'] = SYSLOG_UID;
$fields[SYSLOG_UID]['FieldDefine'] = 'SYSLOG_UID';
$fields[SYSLOG_UID]['FieldCaption'] = 'uID';
$fields[SYSLOG_UID]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_UID]['Sortable'] = false;
$fields[SYSLOG_UID]['DefaultWidth'] = "50";
$fields[SYSLOG_UID]['FieldAlign'] = "center";
$fields[SYSLOG_UID]['SearchOnline'] = false;
$fields[SYSLOG_DATE]['FieldID'] = SYSLOG_DATE;
$fields[SYSLOG_DATE]['FieldDefine'] = 'SYSLOG_DATE';
$fields[SYSLOG_DATE]['FieldCaption'] = 'Date';
$fields[SYSLOG_DATE]['FieldType'] = FILTER_TYPE_DATE;
$fields[SYSLOG_DATE]['Sortable'] = true;
$fields[SYSLOG_DATE]['DefaultWidth'] = "115";
$fields[SYSLOG_DATE]['FieldAlign'] = "center";
$fields[SYSLOG_DATE]['SearchOnline'] = false; 
$fields[SYSLOG_HOST]['FieldID'] = SYSLOG_HOST;
$fields[SYSLOG_HOST]['FieldDefine'] = 'SYSLOG_HOST';
$fields[SYSLOG_HOST]['FieldCaption'] = 'Host';
$fields[SYSLOG_HOST]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_HOST]['Sortable'] = true;
$fields[SYSLOG_HOST]['DefaultWidth'] = "80";
$fields[SYSLOG_HOST]['FieldAlign'] = "left";
$fields[SYSLOG_HOST]['SearchField'] = "source";
$fields[SYSLOG_HOST]['SearchOnline'] = false; 
$fields[SYSLOG_MESSAGETYPE]['FieldID'] = SYSLOG_MESSAGETYPE;
$fields[SYSLOG_MESSAGETYPE]['FieldDefine'] = 'SYSLOG_MESSAGETYPE';
$fields[SYSLOG_MESSAGETYPE]['FieldCaption'] = 'Messagetype';
$fields[SYSLOG_MESSAGETYPE]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_MESSAGETYPE]['Sortable'] = true;
$fields[SYSLOG_MESSAGETYPE]['DefaultWidth'] = "90";
$fields[SYSLOG_MESSAGETYPE]['FieldAlign'] = "center";
$fields[SYSLOG_MESSAGETYPE]['SearchField'] = "messagetype";
$fields[SYSLOG_MESSAGETYPE]['SearchOnline'] = false; 

// Syslog specific
$fields[SYSLOG_FACILITY]['FieldID'] = SYSLOG_FACILITY;
$fields[SYSLOG_FACILITY]['FieldDefine'] = 'SYSLOG_FACILITY';
$fields[SYSLOG_FACILITY]['FieldCaption'] = 'Facility';
$fields[SYSLOG_FACILITY]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_FACILITY]['Sortable'] = true;
$fields[SYSLOG_FACILITY]['DefaultWidth'] = "50";
$fields[SYSLOG_FACILITY]['FieldAlign'] = "center";
$fields[SYSLOG_FACILITY]['SearchField'] = "facility";
$fields[SYSLOG_FACILITY]['SearchOnline'] = true; 
$fields[SYSLOG_SEVERITY]['FieldID'] = SYSLOG_SEVERITY;
$fields[SYSLOG_SEVERITY]['FieldDefine'] = 'SYSLOG_SEVERITY';
$fields[SYSLOG_SEVERITY]['FieldCaption'] = 'Severity';
$fields[SYSLOG_SEVERITY]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_SEVERITY]['Sortable'] = true;
$fields[SYSLOG_SEVERITY]['DefaultWidth'] = "50";
$fields[SYSLOG_SEVERITY]['FieldAlign'] = "center";
$fields[SYSLOG_SEVERITY]['SearchField'] = "severity";
$fields[SYSLOG_SEVERITY]['SearchOnline'] = true; 
$fields[SYSLOG_SYSLOGTAG]['FieldID'] = SYSLOG_SYSLOGTAG;
$fields[SYSLOG_SYSLOGTAG]['FieldDefine'] = 'SYSLOG_SYSLOGTAG';
$fields[SYSLOG_SYSLOGTAG]['FieldCaption'] = 'Syslogtag';
$fields[SYSLOG_SYSLOGTAG]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_SYSLOGTAG]['Sortable'] = true;
$fields[SYSLOG_SYSLOGTAG]['DefaultWidth'] = "85";
$fields[SYSLOG_SYSLOGTAG]['FieldAlign'] = "left";
$fields[SYSLOG_SYSLOGTAG]['SearchField'] = "syslogtag";
$fields[SYSLOG_SYSLOGTAG]['SearchOnline'] = true; 
$fields[SYSLOG_PROCESSID]['FieldID'] = SYSLOG_PROCESSID;
$fields[SYSLOG_PROCESSID]['FieldDefine'] = 'SYSLOG_PROCESSID';
$fields[SYSLOG_PROCESSID]['FieldCaption'] = 'ProcessID';
$fields[SYSLOG_PROCESSID]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_PROCESSID]['Sortable'] = true;
$fields[SYSLOG_PROCESSID]['DefaultWidth'] = "65";
$fields[SYSLOG_PROCESSID]['FieldAlign'] = "center";
$fields[SYSLOG_PROCESSID]['SearchField'] = "processid";
$fields[SYSLOG_PROCESSID]['SearchOnline'] = false; 

// EventLog specific
$fields[SYSLOG_EVENT_ID]['FieldID'] = SYSLOG_EVENT_ID;
$fields[SYSLOG_EVENT_ID]['FieldDefine'] = 'SYSLOG_EVENT_ID';
$fields[SYSLOG_EVENT_ID]['FieldCaption'] = 'Event ID';
$fields[SYSLOG_EVENT_ID]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_EVENT_ID]['Sortable'] = true;
$fields[SYSLOG_EVENT_ID]['DefaultWidth'] = "65";
$fields[SYSLOG_EVENT_ID]['FieldAlign'] = "center";
$fields[SYSLOG_EVENT_ID]['SearchField'] = "eventid";
$fields[SYSLOG_EVENT_ID]['SearchOnline'] = true; 
$fields[SYSLOG_EVENT_LOGTYPE]['FieldID'] = SYSLOG_EVENT_LOGTYPE;
$fields[SYSLOG_EVENT_LOGTYPE]['FieldDefine'] = 'SYSLOG_EVENT_LOGTYPE';
$fields[SYSLOG_EVENT_LOGTYPE]['FieldCaption'] = 'Eventlog Type';
$fields[SYSLOG_EVENT_LOGTYPE]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_EVENT_LOGTYPE]['Sortable'] = true;
$fields[SYSLOG_EVENT_LOGTYPE]['DefaultWidth'] = "100";
$fields[SYSLOG_EVENT_LOGTYPE]['FieldAlign'] = "left";
$fields[SYSLOG_EVENT_LOGTYPE]['SearchField'] = "eventlogtype";
$fields[SYSLOG_EVENT_LOGTYPE]['SearchOnline'] = true; 
$fields[SYSLOG_EVENT_SOURCE]['FieldID'] = SYSLOG_EVENT_SOURCE;
$fields[SYSLOG_EVENT_SOURCE]['FieldDefine'] = 'SYSLOG_EVENT_SOURCE';
$fields[SYSLOG_EVENT_SOURCE]['FieldCaption'] = 'Event Source';
$fields[SYSLOG_EVENT_SOURCE]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_EVENT_SOURCE]['Sortable'] = true;
$fields[SYSLOG_EVENT_SOURCE]['DefaultWidth'] = "100";
$fields[SYSLOG_EVENT_SOURCE]['FieldAlign'] = "left";
$fields[SYSLOG_EVENT_SOURCE]['SearchField'] = "eventlogsource";
$fields[SYSLOG_EVENT_SOURCE]['SearchOnline'] = true; 
$fields[SYSLOG_EVENT_CATEGORY]['FieldID'] = SYSLOG_EVENT_CATEGORY;
$fields[SYSLOG_EVENT_CATEGORY]['FieldDefine'] = 'SYSLOG_EVENT_CATEGORY';
$fields[SYSLOG_EVENT_CATEGORY]['FieldCaption'] = 'Event Category';
$fields[SYSLOG_EVENT_CATEGORY]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_EVENT_CATEGORY]['Sortable'] = true;
$fields[SYSLOG_EVENT_CATEGORY]['DefaultWidth'] = "50";
$fields[SYSLOG_EVENT_CATEGORY]['FieldAlign'] = "center";
$fields[SYSLOG_EVENT_CATEGORY]['SearchField'] = "eventcategory";
$fields[SYSLOG_EVENT_CATEGORY]['SearchOnline'] = false; 
$fields[SYSLOG_EVENT_USER]['FieldID'] = SYSLOG_EVENT_USER;
$fields[SYSLOG_EVENT_USER]['FieldDefine'] = 'SYSLOG_EVENT_USER';
$fields[SYSLOG_EVENT_USER]['FieldCaption'] = 'Event User';
$fields[SYSLOG_EVENT_USER]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_EVENT_USER]['Sortable'] = true;
$fields[SYSLOG_EVENT_USER]['DefaultWidth'] = "85";
$fields[SYSLOG_EVENT_USER]['FieldAlign'] = "left";
$fields[SYSLOG_EVENT_USER]['SearchField'] = "eventuser";
$fields[SYSLOG_EVENT_USER]['SearchOnline'] = false; 

// Weblogfile specific
$fields[SYSLOG_WEBLOG_USER]['FieldID'] = SYSLOG_WEBLOG_USER;
$fields[SYSLOG_WEBLOG_USER]['FieldDefine'] = 'SYSLOG_WEBLOG_USER';
$fields[SYSLOG_WEBLOG_USER]['FieldCaption'] = 'HTTP User';
$fields[SYSLOG_WEBLOG_USER]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_WEBLOG_USER]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_USER]['DefaultWidth'] = "75";
$fields[SYSLOG_WEBLOG_USER]['FieldAlign'] = "left";
$fields[SYSLOG_WEBLOG_USER]['SearchField'] = SYSLOG_WEBLOG_USER;
$fields[SYSLOG_WEBLOG_USER]['SearchOnline'] = false; 
$fields[SYSLOG_WEBLOG_METHOD]['FieldID'] = SYSLOG_WEBLOG_METHOD;
$fields[SYSLOG_WEBLOG_METHOD]['FieldDefine'] = 'SYSLOG_WEBLOG_METHOD';
$fields[SYSLOG_WEBLOG_METHOD]['FieldCaption'] = 'Method';
$fields[SYSLOG_WEBLOG_METHOD]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_WEBLOG_METHOD]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_METHOD]['DefaultWidth'] = "50";
$fields[SYSLOG_WEBLOG_METHOD]['FieldAlign'] = "center";
$fields[SYSLOG_WEBLOG_METHOD]['SearchField'] = SYSLOG_WEBLOG_METHOD;
$fields[SYSLOG_WEBLOG_METHOD]['SearchOnline'] = false; 
$fields[SYSLOG_WEBLOG_URL]['FieldID'] = SYSLOG_WEBLOG_URL;
$fields[SYSLOG_WEBLOG_URL]['FieldDefine'] = 'SYSLOG_WEBLOG_URL';
$fields[SYSLOG_WEBLOG_URL]['FieldCaption'] = 'URL';
$fields[SYSLOG_WEBLOG_URL]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_WEBLOG_URL]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_URL]['DefaultWidth'] = "200";
$fields[SYSLOG_WEBLOG_URL]['FieldAlign'] = "left";
$fields[SYSLOG_WEBLOG_URL]['SearchField'] = SYSLOG_WEBLOG_URL;
$fields[SYSLOG_WEBLOG_URL]['SearchOnline'] = false; 
$fields[SYSLOG_WEBLOG_QUERYSTRING]['FieldID'] = SYSLOG_WEBLOG_QUERYSTRING;
$fields[SYSLOG_WEBLOG_QUERYSTRING]['FieldDefine'] = 'SYSLOG_WEBLOG_QUERYSTRING';
$fields[SYSLOG_WEBLOG_QUERYSTRING]['FieldCaption'] = 'Querystring';
$fields[SYSLOG_WEBLOG_QUERYSTRING]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_WEBLOG_QUERYSTRING]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_QUERYSTRING]['DefaultWidth'] = "200";
$fields[SYSLOG_WEBLOG_QUERYSTRING]['FieldAlign'] = "left";
$fields[SYSLOG_WEBLOG_QUERYSTRING]['SearchField'] = SYSLOG_WEBLOG_QUERYSTRING;
$fields[SYSLOG_WEBLOG_QUERYSTRING]['SearchOnline'] = false; 
$fields[SYSLOG_WEBLOG_PVER]['FieldID'] = SYSLOG_WEBLOG_PVER;
$fields[SYSLOG_WEBLOG_PVER]['FieldDefine'] = 'SYSLOG_WEBLOG_PVER';
$fields[SYSLOG_WEBLOG_PVER]['FieldCaption'] = 'Version';
$fields[SYSLOG_WEBLOG_PVER]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_WEBLOG_PVER]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_PVER]['DefaultWidth'] = "50";
$fields[SYSLOG_WEBLOG_PVER]['FieldAlign'] = "center";
$fields[SYSLOG_WEBLOG_PVER]['SearchField'] = SYSLOG_WEBLOG_PVER;
$fields[SYSLOG_WEBLOG_PVER]['SearchOnline'] = false; 
$fields[SYSLOG_WEBLOG_STATUS]['FieldID'] = SYSLOG_WEBLOG_STATUS;
$fields[SYSLOG_WEBLOG_STATUS]['FieldDefine'] = 'SYSLOG_WEBLOG_STATUS';
$fields[SYSLOG_WEBLOG_STATUS]['FieldCaption'] = 'Status';
$fields[SYSLOG_WEBLOG_STATUS]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_WEBLOG_STATUS]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_STATUS]['DefaultWidth'] = "50";
$fields[SYSLOG_WEBLOG_STATUS]['FieldAlign'] = "center";
$fields[SYSLOG_WEBLOG_STATUS]['SearchField'] = SYSLOG_WEBLOG_STATUS;
$fields[SYSLOG_WEBLOG_STATUS]['SearchOnline'] = false; 
$fields[SYSLOG_WEBLOG_BYTESSEND]['FieldID'] = SYSLOG_WEBLOG_BYTESSEND;
$fields[SYSLOG_WEBLOG_BYTESSEND]['FieldDefine'] = 'SYSLOG_WEBLOG_BYTESSEND';
$fields[SYSLOG_WEBLOG_BYTESSEND]['FieldCaption'] = 'Bytes Send';
$fields[SYSLOG_WEBLOG_BYTESSEND]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_WEBLOG_BYTESSEND]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_BYTESSEND]['DefaultWidth'] = "75";
$fields[SYSLOG_WEBLOG_BYTESSEND]['FieldAlign'] = "left";
$fields[SYSLOG_WEBLOG_BYTESSEND]['SearchField'] = SYSLOG_WEBLOG_BYTESSEND;
$fields[SYSLOG_WEBLOG_BYTESSEND]['SearchOnline'] = false; 
$fields[SYSLOG_WEBLOG_REFERER]['FieldID'] = SYSLOG_WEBLOG_REFERER;
$fields[SYSLOG_WEBLOG_REFERER]['FieldDefine'] = 'SYSLOG_WEBLOG_REFERER';
$fields[SYSLOG_WEBLOG_REFERER]['FieldCaption'] = 'Referer';
$fields[SYSLOG_WEBLOG_REFERER]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_WEBLOG_REFERER]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_REFERER]['DefaultWidth'] = "200";
$fields[SYSLOG_WEBLOG_REFERER]['FieldAlign'] = "left";
$fields[SYSLOG_WEBLOG_REFERER]['SearchField'] = SYSLOG_WEBLOG_REFERER;
$fields[SYSLOG_WEBLOG_REFERER]['SearchOnline'] = true; 
$fields[SYSLOG_WEBLOG_USERAGENT]['FieldID'] = SYSLOG_WEBLOG_USERAGENT;
$fields[SYSLOG_WEBLOG_USERAGENT]['FieldDefine'] = 'SYSLOG_WEBLOG_USERAGENT';
$fields[SYSLOG_WEBLOG_USERAGENT]['FieldCaption'] = 'User Agent';
$fields[SYSLOG_WEBLOG_USERAGENT]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_WEBLOG_USERAGENT]['Sortable'] = false;
$fields[SYSLOG_WEBLOG_USERAGENT]['DefaultWidth'] = "100";
$fields[SYSLOG_WEBLOG_USERAGENT]['FieldAlign'] = "left";
$fields[SYSLOG_WEBLOG_USERAGENT]['SearchField'] = SYSLOG_WEBLOG_USERAGENT;
$fields[SYSLOG_WEBLOG_USERAGENT]['SearchOnline'] = true; 

// Misc fields
$fields[MISC_SYSTEMID]['FieldID'] = MISC_SYSTEMID;
$fields[MISC_SYSTEMID]['FieldDefine'] = 'MISC_SYSTEMID';
$fields[MISC_SYSTEMID]['FieldCaption'] = 'SystemID';
$fields[MISC_SYSTEMID]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[MISC_SYSTEMID]['Sortable'] = false;
$fields[MISC_SYSTEMID]['DefaultWidth'] = "50";
$fields[MISC_SYSTEMID]['FieldAlign'] = "center";
$fields[MISC_SYSTEMID]['SearchField'] = MISC_SYSTEMID;
$fields[MISC_SYSTEMID]['SearchOnline'] = false; 
$fields[MISC_CHECKSUM]['FieldID'] = MISC_CHECKSUM;
$fields[MISC_CHECKSUM]['FieldDefine'] = 'MISC_CHECKSUM';
$fields[MISC_CHECKSUM]['FieldCaption'] = 'Checksum';
$fields[MISC_CHECKSUM]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[MISC_CHECKSUM]['Sortable'] = false;
$fields[MISC_CHECKSUM]['DefaultWidth'] = "50";
$fields[MISC_CHECKSUM]['FieldAlign'] = "center";
$fields[MISC_CHECKSUM]['SearchField'] = MISC_CHECKSUM;
$fields[MISC_CHECKSUM]['SearchOnline'] = false; 

// Message is the last element, this order is important for the Detail page for now!
$fields[SYSLOG_MESSAGE]['FieldID'] = SYSLOG_MESSAGE;
$fields[SYSLOG_MESSAGE]['FieldDefine'] = 'SYSLOG_MESSAGE';
$fields[SYSLOG_MESSAGE]['FieldCaption'] = 'Message';
$fields[SYSLOG_MESSAGE]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_MESSAGE]['Sortable'] = false;
$fields[SYSLOG_MESSAGE]['DefaultWidth'] = "100%";
$fields[SYSLOG_MESSAGE]['FieldAlign'] = "left";
// $fields[SYSLOG_MESSAGE]['SearchField'] = "";
$fields[SYSLOG_MESSAGE]['SearchField'] = SYSLOG_MESSAGE;
$fields[SYSLOG_MESSAGE]['SearchOnline'] = false; 
// --- 

// --- Define default Database field mappings!
$dbmapping['monitorware']['ID'] = "monitorware";
$dbmapping['monitorware']['DisplayName'] = "MonitorWare";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_UID] = "ID";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_DATE] = "DeviceReportedTime";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_HOST] = "FromHost";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_MESSAGETYPE] = "InfoUnitID";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_MESSAGE] = "Message";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_FACILITY] = "Facility";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_SEVERITY] = "Priority";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_SYSLOGTAG] = "SysLogTag";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_PROCESSID] = "ProcessID";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_EVENT_ID] = "EventID";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_EVENT_LOGTYPE] = "EventLogType";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_EVENT_SOURCE] = "EventSource";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_EVENT_CATEGORY] = "EventCategory";
$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_EVENT_USER] = "EventUser";
$dbmapping['monitorware']['DBMAPPINGS'][MISC_SYSTEMID] = "SystemID";
$dbmapping['monitorware']['DBMAPPINGS'][MISC_CHECKSUM] = "Checksum";
//$dbmapping['monitorware']['DBMAPPINGS'][SYSLOG_PROCESSID] = "ProcessID";

$dbmapping['syslogng']['ID'] = "syslogng";
$dbmapping['syslogng']['DisplayName'] = "SyslogNG";
$dbmapping['syslogng']['DBMAPPINGS'][SYSLOG_UID] = "seq";
$dbmapping['syslogng']['DBMAPPINGS'][SYSLOG_DATE] = "datetime";
$dbmapping['syslogng']['DBMAPPINGS'][SYSLOG_HOST] = "host";
$dbmapping['syslogng']['DBMAPPINGS'][SYSLOG_MESSAGE] = "msg";
//NOT POSSIBLE YET $dbmapping['syslogng'][SYSLOG_FACILITY] = "Facility";
//NOT POSSIBLE YET $dbmapping['syslogng'][SYSLOG_SEVERITY] = "Priority";
$dbmapping['syslogng']['DBMAPPINGS'][SYSLOG_SYSLOGTAG] = "tag";
$dbmapping['syslogng']['DBMAPPINGS'][SYSLOG_PROCESSID] = "program";

$dbmapping['mongodb']['ID'] = "mongodb";
$dbmapping['mongodb']['DisplayName'] = "MongoDB";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_UID] = "_id";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_DATE] = "time";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_HOST] = "sys";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_MESSAGE] = "msg";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_FACILITY] = "syslog_fac";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_SEVERITY] = "syslog_sever";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_SYSLOGTAG] = "procid"; // not using syslog_tag because of PID in it
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_PROCESSID] = "pid";
$dbmapping['mongodb']['DBMAPPINGS'][MISC_CHECKSUM] = "Checksum";
$dbmapping['mongodb']['DBMAPPINGS'][SYSLOG_EVENT_LOGTYPE] = "nteventlogtype";

// Convert all fieldnames to lowercase to avoid problems with case sensitive array keys later 
foreach( $dbmapping as &$myMapping ) 
{
	foreach( $myMapping['DBMAPPINGS'] as &$myField ) 
		$myField = strtolower($myField);
}

// --- 

// EventTime Constants
define('EVTIME_TIMESTAMP', '0');
define('EVTIME_TIMEZONE', '1');
define('EVTIME_MICROSECONDS', '2');

?>