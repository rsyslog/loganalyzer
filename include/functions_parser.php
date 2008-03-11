<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Parser functions													*
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

function ParseSyslogHeader($szLogLine)
{
	// Init values
	$syslogDate = "Feb  7 17:56:24";
	$syslogFacility = 16;
	$syslogFacilityText = "kernel";
	$syslogSeverity = 5;
	$syslogSeverityText = "notice";
	$syslogTag = "syslog";
	$syslogHost = "localhost";
	$syslogMsg = $szLogLine;
	$syslogIUT = IUT_Syslog;

	// Parse from logline!
	//SAMPLE: Mar 10 14:45:39 debandre syslogd 1.4.1#18: restart.
	//SAMPLE: Mar 10 14:45:44 debandre anacron[3226]: Job `cron.daily' terminated (mailing output)
	//'<' % text($pri) & '>' & date2text($datereceived, rfc3339) & $hostname & tolower($msg)

SYSLOG_DATE => $syslogDate, 
					array ( SYSLOG_FACILITY, SYSLOG_FACILITY_TEXT, SYSLOG_SEVERITY, SYSLOG_SEVERITY_TEXT, SYSLOG_HOST, SYSLOG_SYSLOGTAG, SYSLOG_MESSAGE, SYSLOG_MESSAGETYPE );

	// return results
	return array ( 
					SYSLOG_DATE => $syslogDate, 
					SYSLOG_FACILITY => $syslogFacility, 
					SYSLOG_FACILITY_TEXT => $syslogFacilityText, 
					SYSLOG_SEVERITY => $syslogSeverity, 
					SYSLOG_SEVERITY_TEXT => $syslogSeverityText, 
					SYSLOG_HOST => $syslogHost, 
					SYSLOG_SYSLOGTAG => $syslogTag, 
					SYSLOG_MESSAGE => $syslogMsg, 
					SYSLOG_MESSAGETRUNSCATED => strlen($syslogMsg) > 100 ? substr($syslogMsg, 0, 100 ) . " ..." : $syslogMsg, 
					SYSLOG_MESSAGETYPE => $syslogIUT
				);
}


?>