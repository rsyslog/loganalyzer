<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Main Configuration File
	*
	* -> Configuration need variables for the Database connection
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

// --- Database options
$CFG['UserDBEnabled'] = false;
$CFG['UserDBServer'] = "localhost";
$CFG['UserDBPort'] = 3306;
$CFG['UserDBName'] = ""; 
$CFG['UserDBPref'] = "logcon_"; 
$CFG['UserDBUser'] = "root";
$CFG['UserDBPass'] = "";
// --- 

// --- Misc Options
$CFG['MiscShowDebugMsg'] = 1;				// if enabled, you will get additional output on certain places
$CFG["MiscShowPageRenderStats"] = 1;		// If enabled, you will see Pagerender Settings
// --- 

// --- Default Frontend Options 
$CFG['ViewUseTodayYesterday'] = 1;			// If enabled, the date from today and yesterday is displayed as "today" and "yesterday"
$CFG['ViewMessageCharacterLimit'] = 80;		// Default character limit for the message gets trunscated.
$CFG['ViewEntriesPerPage'] = 50;			// Default number of syslog entries shown per page
$CFG['ViewEnableDetailPopups'] = 1;			// If enabled, you will see additional Details for each syslog message on mouse over. 

$CFG['SearchCustomButtonCaption'] = "I'd like to feel sad";	// Default caption for the custom fast search button
$CFG['SearchCustomButtonSearch'] = "error";					// Default search string for the custom search button
// ---

// --- Define which fields you want to see 
//$CFG['ShowMessage'] = true;					// If enabled, the Message column will be appended to the columns list.
$CFG['Columns'][] = SYSLOG_DATE;
$CFG['Columns'][] = SYSLOG_FACILITY;
$CFG['Columns'][] = SYSLOG_SEVERITY;
$CFG['Columns'][] = SYSLOG_HOST;
$CFG['Columns'][] = SYSLOG_SYSLOGTAG;
$CFG['Columns'][] = SYSLOG_MESSAGETYPE;
$CFG['Columns'][] = SYSLOG_MESSAGE;
// ---

// --- Source Options
$CFG['Sources'][Source1]['ID'] = "Source1";
$CFG['Sources'][Source1]['Name'] = "Syslog Disk File";
$CFG['Sources'][Source1]['SourceType'] = SOURCE_DISK;
$CFG['Sources'][Source1]['LogLineType'] = "syslog";
$CFG['Sources'][Source1]['DiskFile'] = $gl_root_path . "samplelogs/syslog";

$CFG['Sources'][Source2]['ID'] = "Source2";
$CFG['Sources'][Source2]['Name'] = "Old Syslog Disk File";
$CFG['Sources'][Source2]['SourceType'] = SOURCE_DISK;
$CFG['Sources'][Source2]['LogLineType'] = "syslog";
$CFG['Sources'][Source2]['DiskFile'] = $gl_root_path . "samplelogs/syslog.0";

$CFG['Sources'][Source3]['ID'] = "Source3";
$CFG['Sources'][Source3]['Name'] = "RSyslog Disk File";
$CFG['Sources'][Source3]['SourceType'] = SOURCE_DISK;
$CFG['Sources'][Source3]['LogLineType'] = "syslog";
$CFG['Sources'][Source3]['DiskFile'] = $gl_root_path . "samplelogs/rsyslog";

$CFG['Sources'][Source4]['ID'] = "Source4";
$CFG['Sources'][Source4]['Name'] = "WinSyslog Disk File";
$CFG['Sources'][Source4]['SourceType'] = SOURCE_DISK;
$CFG['Sources'][Source4]['LogLineType'] = "winsyslog";
$CFG['Sources'][Source4]['DiskFile'] = $gl_root_path . "samplelogs/winsyslog";

$CFG['Sources'][Source5]['ID'] = "Source5";
$CFG['Sources'][Source5]['Name'] = "WinSyslog DB";
$CFG['Sources'][Source5]['SourceType'] = SOURCE_DB;
$CFG['Sources'][Source5]['DBTableType'] = "winsyslog";
$CFG['Sources'][Source5]['DBType'] = DB_MYSQL;
$CFG['Sources'][Source5]['DBServer'] = "127.0.0.1";
$CFG['Sources'][Source5]['DBName'] = "phplogcon";
$CFG['Sources'][Source5]['DBUser'] = "root";
$CFG['Sources'][Source5]['DBPassword'] = "";
$CFG['Sources'][Source5]['DBTableName'] = "systemevents";
// --- 
?>
