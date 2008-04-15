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

// Define properties names of all know fields 
define('SYSLOG_UID', 'uID');
define('SYSLOG_DATE', 'timereported');
define('SYSLOG_DATE_FORMATED', 'timereported_formatted');
define('SYSLOG_FACILITY', 'syslogfacility');
define('SYSLOG_FACILITY_TEXT', 'syslogfacility-text');
define('SYSLOG_SEVERITY', 'syslogseverity');
define('SYSLOG_SEVERITY_TEXT','syslogseverity-text');
define('SYSLOG_HOST', 'FROMHOST');
define('SYSLOG_SYSLOGTAG', 'syslogtag');
define('SYSLOG_MESSAGE', 'msg');
define('SYSLOG_MESSAGETRUNSCATED', 'msgtrunscated');
define('SYSLOG_MESSAGETYPE', 'IUT');
define('SYSLOG_PROCESSID', 'procid');

// Defines which kind of field types we have
define('FILTER_TYPE_STRING', 0);
define('FILTER_TYPE_NUMBER', 1);
define('FILTER_TYPE_DATE', 2);

// Predefine fields array!

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
$fields[SYSLOG_DATE]['DefaultWidth'] = "110";
$fields[SYSLOG_DATE]['FieldAlign'] = "center";
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
$fields[SYSLOG_HOST]['FieldID'] = SYSLOG_HOST;
$fields[SYSLOG_HOST]['FieldCaptionID'] = 'LN_FIELDS_HOST';
$fields[SYSLOG_HOST]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_HOST]['Sortable'] = true;
$fields[SYSLOG_HOST]['DefaultWidth'] = "65";
$fields[SYSLOG_HOST]['FieldAlign'] = "center";
$fields[SYSLOG_SYSLOGTAG]['FieldID'] = SYSLOG_SYSLOGTAG;
$fields[SYSLOG_SYSLOGTAG]['FieldCaptionID'] = 'LN_FIELDS_SYSLOGTAG';
$fields[SYSLOG_SYSLOGTAG]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_SYSLOGTAG]['Sortable'] = true;
$fields[SYSLOG_SYSLOGTAG]['DefaultWidth'] = "70";
$fields[SYSLOG_SYSLOGTAG]['FieldAlign'] = "center";
$fields[SYSLOG_MESSAGETYPE]['FieldID'] = SYSLOG_MESSAGETYPE;
$fields[SYSLOG_MESSAGETYPE]['FieldCaptionID'] = 'LN_FIELDS_MESSAGETYPE';
$fields[SYSLOG_MESSAGETYPE]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_MESSAGETYPE]['Sortable'] = true;
$fields[SYSLOG_MESSAGETYPE]['DefaultWidth'] = "90";
$fields[SYSLOG_MESSAGETYPE]['FieldAlign'] = "center";
$fields[SYSLOG_PROCESSID]['FieldID'] = SYSLOG_PROCESSID;
$fields[SYSLOG_PROCESSID]['FieldCaptionID'] = 'LN_FIELDS_PROCESSID';
$fields[SYSLOG_PROCESSID]['FieldType'] = FILTER_TYPE_NUMBER;
$fields[SYSLOG_PROCESSID]['Sortable'] = true;
$fields[SYSLOG_PROCESSID]['DefaultWidth'] = "65";
$fields[SYSLOG_PROCESSID]['FieldAlign'] = "center";
$fields[SYSLOG_MESSAGE]['FieldID'] = SYSLOG_MESSAGE;
$fields[SYSLOG_MESSAGE]['FieldCaptionID'] = 'LN_FIELDS_MESSAGE';
$fields[SYSLOG_MESSAGE]['FieldType'] = FILTER_TYPE_STRING;
$fields[SYSLOG_MESSAGE]['Sortable'] = false;
$fields[SYSLOG_MESSAGE]['DefaultWidth'] = "100%";
$fields[SYSLOG_MESSAGE]['FieldAlign'] = "left";


// MonitorWare InfoUnit Defines
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
define('IUT_AliveMonECHO', '1999998');
define('IUT_MIAP_Receiver', '1999999');

// EventTime Constants
define('EVTIME_TIMESTAMP', '0');
define('EVTIME_TIMEZONE', '1');
define('EVTIME_MICROSECONDS', '2');

?>