<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Main Index File													*
	*																	*
	* -> Loads the main PhpLogCon Site									*
	*																	*
	* All directives are explained within this file						*
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');

InitPhpLogCon();
InitFrontEndDefaults();	// Only in WebFrontEnd
// ***					*** //

// --- CONTENT Vars
//if ( isset($content['myserver']) ) 
//	$content['TITLE'] = "PhpLogCon :: Home :: Server '" . $content['myserver']['Name'] . "'";	// Title of the Page 
//else
	$content['TITLE'] = "PhpLogCon :: Home";
// --- 

// --- BEGIN Custom Code

// DEBUG, create TESTING DATA!
$content['syslogmessages'][0] = array ( SYSLOG_DATE => "Feb  7 17:56:24", SYSLOG_FACILITY => 0, SYSLOG_FACILITY_TEXT => "kernel", SYSLOG_SEVERITY => 5, SYSLOG_SEVERITY_TEXT => "notice", SYSLOG_HOST => "localhost", SYSLOG_SYSLOGTAG => "RSyslogTest", SYSLOG_MESSAGE => "Kernel log daemon terminating.", SYSLOG_MESSAGETYPE => IUT_Syslog, );
$content['syslogmessages'][1] = array ( SYSLOG_DATE => "Feb  6 18:56:24", SYSLOG_FACILITY => 0, SYSLOG_FACILITY_TEXT => "kernel", SYSLOG_SEVERITY => 5, SYSLOG_SEVERITY_TEXT => "notice", SYSLOG_HOST => "localhost", SYSLOG_SYSLOGTAG => "RSyslogTest", SYSLOG_MESSAGE => "Kernel log daemon terminating.", SYSLOG_MESSAGETYPE => IUT_Syslog, );

if ( isset($content['syslogmessages']) && count($content['syslogmessages']) > 0 )
{
	// This will enable to Main SyslogView
	$content['syslogmessagesenabled'] = "true";

	for($i = 0; $i < count($content['syslogmessages']); $i++)
	{
		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$content['syslogmessages'][$i]['cssclass'] = "line1";
		else
			$content['syslogmessages'][$i]['cssclass'] = "line2";
		// --- 
	}
}
// --- 

// --- Parsen and Output
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

InitTemplateParser();
$page -> parser($content, "index.html");
$page -> output(); 
// --- 

?>