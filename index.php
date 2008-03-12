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
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
// ***					*** //

// --- CONTENT Vars
//if ( isset($content['myserver']) ) 
//	$content['TITLE'] = "PhpLogCon :: Home :: Server '" . $content['myserver']['Name'] . "'";	// Title of the Page 
//else
	$content['TITLE'] = "PhpLogCon :: Home";
// --- 

// --- BEGIN Custom Code
if ( isset($content['Sources'][$currentSourceID]) && $content['Sources'][$currentSourceID]['SourceType'] == SOURCE_DISK )
{
	require_once($gl_root_path . 'classes/enums.class.php');
	require_once($gl_root_path . 'classes/logstream.class.php');
	require_once($gl_root_path . 'classes/logstreamdisk.class.php');
	require_once($gl_root_path . 'include/constants_errors.php');
	require_once($gl_root_path . 'include/constants_logstream.php');


	// Obtain Config Object
	$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

	// Create LogStream Object 
	$stream = $stream_config->LogStreamFactory($stream_config);
	$stream->Open( array ( SYSLOG_DATE, SYSLOG_FACILITY, SYSLOG_FACILITY_TEXT, SYSLOG_SEVERITY, SYSLOG_SEVERITY_TEXT, SYSLOG_HOST, SYSLOG_SYSLOGTAG, SYSLOG_MESSAGE, SYSLOG_MESSAGETYPE ), true);
	
	$uID = -1;
	$counter = 0;

//	$stream->SetReadDirection(EnumReadDirection::Backward);

	while ($stream->ReadNext($uID, $logArray) == SUCCESS && $counter <= 30)
	{
		// Copy Obtained array 
		$content['syslogmessages'][] = $logArray;
		
		// Copy UID
		$content['syslogmessages'][$counter]['UID'] = $uID;

		// Set truncasted message for display
		if ( isset($logArray[SYSLOG_MESSAGE]) )
			$content['syslogmessages'][$counter][SYSLOG_MESSAGETRUNSCATED] = strlen($logArray[SYSLOG_MESSAGE]) > 100 ? substr($logArray[SYSLOG_MESSAGE], 0, 100 ) . " ..." : $logArray[SYSLOG_MESSAGE];
		else
			$content['syslogmessages'][$counter][SYSLOG_MESSAGETRUNSCATED] = "";

		// Increment Counter
		$counter++;
	}

	if ( $stream->ReadNext($uID, $logArray) == SUCCESS ) 
	{
		// Enable Player Pager
		$content['main_pagerenabled'] = "true";
	}


	
	// Close file!
	$stream->Close();
}


// DEBUG, create TESTING DATA!
//$content['syslogmessages'][0] = array ( SYSLOG_DATE => "Feb  7 17:56:24", SYSLOG_FACILITY => 0, SYSLOG_FACILITY_TEXT => "kernel", SYSLOG_SEVERITY => 5, SYSLOG_SEVERITY_TEXT => "notice", SYSLOG_HOST => "localhost", SYSLOG_SYSLOGTAG => "RSyslogTest", SYSLOG_MESSAGE => "Kernel log daemon terminating.", SYSLOG_MESSAGETYPE => IUT_Syslog, );
//$content['syslogmessages'][1] = array ( SYSLOG_DATE => "Feb  6 18:56:24", SYSLOG_FACILITY => 0, SYSLOG_FACILITY_TEXT => "kernel", SYSLOG_SEVERITY => 5, SYSLOG_SEVERITY_TEXT => "notice", SYSLOG_HOST => "localhost", SYSLOG_SYSLOGTAG => "RSyslogTest", SYSLOG_MESSAGE => "Kernel log daemon terminating.", SYSLOG_MESSAGETYPE => IUT_Syslog, );

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