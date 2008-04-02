<?php
	/*
		*********************************************************************
		* Copyright by Adiscon GmbH | 2008!									*
		* -> www.phplogcon.org <-											*
		*																	*
		* Use this script at your own risk!									*
		* -----------------------------------------------------------------	*
		* Main Configuration File											*
		*																	*
		* -> Configuration need variables for the Database connection		*
		*********************************************************************
	*/

	// --- Avoid directly accessing this file! 
	if ( !defined('IN_PHPLOGCON') )
	{
		die('Hacking attempt');
		exit;
	}
	// --- 

	// --- Database options
	$CFG['UseDB'] = false;
	$CFG['DBServer'] = "localhost";
	$CFG['Port'] = 3306;
	$CFG['DBName'] = ""; 
	$CFG['TBPref'] = "logcon_"; 
	$CFG['User'] = "root";
	$CFG['Pass'] = "";
	// --- 
	
	// --- Generic Options
	$CFG['ShowDebugMsg'] = 1;
	$CFG["ShowPageRenderStats"] = 1;						// If enabled, you will see Pagerender Settings
	// --- 

	// --- Default Frontend Options 

	// ---

	// --- Source Options
	$CFG['Sources'][0]['ID'] = "Source1";
	$CFG['Sources'][0]['Name'] = "Syslog Disk File";
	$CFG['Sources'][0]['SourceType'] = SOURCE_DISK;
	$CFG['Sources'][0]['LogLineType'] = "syslog";
	$CFG['Sources'][0]['DiskFile'] = $gl_root_path . "samplelogs/syslog";
	$CFG['Sources'][1]['ID'] = "Source2";
	$CFG['Sources'][1]['Name'] = "Old Syslog Disk File";
	$CFG['Sources'][1]['SourceType'] = SOURCE_DISK;
	$CFG['Sources'][1]['LogLineType'] = "syslog";
	$CFG['Sources'][1]['DiskFile'] = $gl_root_path . "samplelogs/syslog.0";
	$CFG['Sources'][2]['ID'] = "Source3";
	$CFG['Sources'][2]['Name'] = "RSyslog Disk File";
	$CFG['Sources'][2]['SourceType'] = SOURCE_DISK;
	$CFG['Sources'][2]['LogLineType'] = "syslog";
	$CFG['Sources'][2]['DiskFile'] = $gl_root_path . "samplelogs/rsyslog";
	$CFG['Sources'][3]['ID'] = "Source4";
	$CFG['Sources'][3]['Name'] = "WinSyslog Disk File";
	$CFG['Sources'][3]['SourceType'] = SOURCE_DISK;
	$CFG['Sources'][3]['LogLineType'] = "winsyslog";
	$CFG['Sources'][3]['DiskFile'] = $gl_root_path . "samplelogs/winsyslog";
	// --- 
?>