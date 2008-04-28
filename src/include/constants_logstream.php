<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* -> Stuff which has to be static and predefined					*
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
// ---

// Defines which kind of field types we have
define('FILTER_TYPE_STRING', 0);
define('FILTER_TYPE_NUMBER', 1);
define('FILTER_TYPE_DATE', 2);
define('FILTER_TYPE_UNKNOWN', 99);

// Define possible database types
define('DB_MYSQL', 0);
define('DB_MSSQL', 1);
define('DB_ODBC', 2);

// --- Predefine fields array!
$fields[SYSLOG_UID]['FieldID'] = SYSLOG_UID;
$fields[SYSLOG_UID]['FieldCaptionID'] = 'LN_FIELDS_UID';
$fields[SYSLOG_UID]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_UID]['Sortable'] = false;
$fields[SYSLOG_UID]['DefaultWidth'] = "50";
$fields[SYSLOG_UID]['FieldAlign'] = "center";
$fields[SYSLOG_DATE]['FieldID'] = SYSLOG_DATE;
$fields[SYSLOG_DATE]['FieldCaptionID'] = 'LN_FIELDS_DATE';
$fields[SYSLOG_DATE]['FieldType'] = FILTER_TYPE_DATE;
$fields[SYSLOG_DATE]['Sortable'] = true;
$fields[SYSLOG_DATE]['DefaultWidth'] = "115";
$fields[SYSLOG_DATE]['FieldAlign'] = "center";
$fields[SYSLOG_HOST]['FieldID'] = SYSLOG_HOST;
$fields[SYSLOG_HOST]['FieldCaptionID'] = 'LN_FIELDS_HOST';
$fields[SYSLOG_HOST]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_HOST]['Sortable'] = true;
$fields[SYSLOG_HOST]['DefaultWidth'] = "80";
$fields[SYSLOG_HOST]['FieldAlign'] = "center";
$fields[SYSLOG_MESSAGETYPE]['FieldID'] = SYSLOG_MESSAGETYPE;
$fields[SYSLOG_MESSAGETYPE]['FieldCaptionID'] = 'LN_FIELDS_MESSAGETYPE';
$fields[SYSLOG_MESSAGETYPE]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_MESSAGETYPE]['Sortable'] = true;
$fields[SYSLOG_MESSAGETYPE]['DefaultWidth'] = "90";
$fields[SYSLOG_MESSAGETYPE]['FieldAlign'] = "center";

// Syslog specific
$fields[SYSLOG_FACILITY]['FieldID'] = SYSLOG_FACILITY;
$fields[SYSLOG_FACILITY]['FieldCaptionID'] = 'LN_FIELDS_FACILITY';
$fields[SYSLOG_FACILITY]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_FACILITY]['Sortable'] = true;
$fields[SYSLOG_FACILITY]['DefaultWidth'] = "50";
$fields[SYSLOG_FACILITY]['FieldAlign'] = "center";
$fields[SYSLOG_SEVERITY]['FieldID'] = SYSLOG_SEVERITY;
$fields[SYSLOG_SEVERITY]['FieldCaptionID'] = 'LN_FIELDS_SEVERITY';
$fields[SYSLOG_SEVERITY]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_SEVERITY]['Sortable'] = true;
$fields[SYSLOG_SEVERITY]['DefaultWidth'] = "50";
$fields[SYSLOG_SEVERITY]['FieldAlign'] = "center";
$fields[SYSLOG_SYSLOGTAG]['FieldID'] = SYSLOG_SYSLOGTAG;
$fields[SYSLOG_SYSLOGTAG]['FieldCaptionID'] = 'LN_FIELDS_SYSLOGTAG';
$fields[SYSLOG_SYSLOGTAG]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_SYSLOGTAG]['Sortable'] = true;
$fields[SYSLOG_SYSLOGTAG]['DefaultWidth'] = "85";
$fields[SYSLOG_SYSLOGTAG]['FieldAlign'] = "center";
$fields[SYSLOG_PROCESSID]['FieldID'] = SYSLOG_PROCESSID;
$fields[SYSLOG_PROCESSID]['FieldCaptionID'] = 'LN_FIELDS_PROCESSID';
$fields[SYSLOG_PROCESSID]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_PROCESSID]['Sortable'] = true;
$fields[SYSLOG_PROCESSID]['DefaultWidth'] = "65";
$fields[SYSLOG_PROCESSID]['FieldAlign'] = "center";

// TODO! EventLog specific

// Message is the last element, this order is important for the Detail page for now!
$fields[SYSLOG_MESSAGE]['FieldID'] = SYSLOG_MESSAGE;
$fields[SYSLOG_MESSAGE]['FieldCaptionID'] = 'LN_FIELDS_MESSAGE';
$fields[SYSLOG_MESSAGE]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_MESSAGE]['Sortable'] = false;
$fields[SYSLOG_MESSAGE]['DefaultWidth'] = "100%";
$fields[SYSLOG_MESSAGE]['FieldAlign'] = "left";

// --- 

// --- Define default Database field mappings!
$dbmapping['monitorware'][SYSLOG_UID] = "ID";
$dbmapping['monitorware'][SYSLOG_DATE] = "DeviceReportedTime";
$dbmapping['monitorware'][SYSLOG_HOST] = "FromHost";
$dbmapping['monitorware'][SYSLOG_MESSAGETYPE] = "InfoUnitID";
$dbmapping['monitorware'][SYSLOG_MESSAGE] = "Message";
$dbmapping['monitorware'][SYSLOG_FACILITY] = "Facility";
$dbmapping['monitorware'][SYSLOG_SEVERITY] = "Priority";
$dbmapping['monitorware'][SYSLOG_SYSLOGTAG] = "SysLogTag";
$dbmapping['monitorware'][SYSLOG_EVENT_ID] = "EventID";
$dbmapping['monitorware'][SYSLOG_EVENT_LOGTYPE] = "EventLogType";
$dbmapping['monitorware'][SYSLOG_EVENT_SOURCE] = "EventSource";
$dbmapping['monitorware'][SYSLOG_EVENT_CATEGORY] = "EventCategory";
$dbmapping['monitorware'][SYSLOG_EVENT_USER] = "EventUser";

$dbmapping['syslogng'][SYSLOG_UID] = "seq";
$dbmapping['syslogng'][SYSLOG_DATE] = "datetime";
$dbmapping['syslogng'][SYSLOG_HOST] = "host";
$dbmapping['syslogng'][SYSLOG_MESSAGE] = "msg";
//TODO $dbmapping['syslogng'][SYSLOG_FACILITY] = "Facility";
//TODO $dbmapping['syslogng'][SYSLOG_SEVERITY] = "Priority"
$dbmapping['syslogng'][SYSLOG_SYSLOGTAG] = "tag";
// --- 

// EventTime Constants
define('EVTIME_TIMESTAMP', '0');
define('EVTIME_TIMEZONE', '1');
define('EVTIME_MICROSECONDS', '2');

?>
