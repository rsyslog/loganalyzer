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

// --- UserDB options
/*	If UserDB is enabled, all options will and have to be configured in the database. 
*	All Options below the UserDB options here will not be used, unless a setting
*	is missing in the database. 
*/
$CFG['UserDBEnabled'] = false;
$CFG['UserDBServer'] = "";
$CFG['UserDBPort'] = 3306;
$CFG['UserDBName'] = ""; 
$CFG['UserDBPref'] = ""; 
$CFG['UserDBUser'] = "";
$CFG['UserDBPass'] = "";
$CFG['UserDBLoginRequired'] = false;
// --- 

// --- Misc Options
$CFG['MiscShowDebugMsg'] = 0;				// if enabled, you will get additional output on certain places
$CFG['MiscShowDebugGridCounter'] = 0;		// Only for debugging purposes, will add a counter column into the grid!
$CFG["MiscShowPageRenderStats"] = 1;		// If enabled, you will see Pagerender Settings
$CFG['MiscEnableGzipCompression'] = 1;		// If enabled, phplogcon will use gzip compression for output, we recommend
											// to have this option enabled, it will highly reduce bandwith usage. 
$CFG['DebugUserLogin'] = 0;					// if enabled, you will see additional informations on failed logins
// --- 

// --- Default Frontend Options 
$CFG['PrependTitle'] = "";					// If set, this	text will be prepended withint the title tag
$CFG['ViewUseTodayYesterday'] = 1;			// If enabled, the date from today and yesterday is displayed as "today" and "yesterday"
$CFG['ViewMessageCharacterLimit'] = 80;		// Default character limit for the message gets trunscated.
$CFG['ViewEntriesPerPage'] = 50;			// Default number of syslog entries shown per page
$CFG['ViewEnableDetailPopups'] = 1;			// If enabled, you will see additional Details for each syslog message on mouse over. 
$CFG['ViewDefaultTheme'] = "default";		// This sets the default theme the user is going to see when he opens phplogcon the first time. 
											// Currently only "default" and "dark" are available. 
$CFG['ViewDefaultLanguage'] = "en";			// Sets the default display language
$CFG['ViewEnableAutoReloadSeconds'] = 0;	// If "ViewEnableAutoReloadSeconds" is set to anything higher the 0 (which means disabled), this means auto reload is enabled by default. 

$CFG['SearchCustomButtonCaption'] = "I'd like to feel sad";	// Default caption for the custom fast search button
$CFG['SearchCustomButtonSearch'] = "error";					// Default search string for the custom search button

$CFG['EnableIPAddressResolve'] = 1;			// If enabled, IP Addresses inline messages are automatically resolved and the result is added in brackets {} behind the IP Address
// ---

// --- Define which fields you want to see 
//$CFG['ShowMessage'] = true;					// If enabled, the Message column will be appended to the columns list.
//Eventlog based fields: $CFG['Columns'] = array ( SYSLOG_DATE, SYSLOG_HOST, SYSLOG_EVENT_LOGTYPE, SYSLOG_EVENT_SOURCE, /*SYSLOG_EVENT_CATEGORY, */SYSLOG_EVENT_ID, SYSLOG_MESSAGE );
//$CFG['Columns'] = array ( SYSLOG_DATE, SYSLOG_FACILITY, SYSLOG_SEVERITY, SYSLOG_HOST, SYSLOG_SYSLOGTAG, SYSLOG_MESSAGETYPE, SYSLOG_MESSAGE );
$CFG['DefaultViewsID'] = "";
// ---

// --- Predefined Searches! 
$CFG['Search'][] = array ( "DisplayName" => "Syslog Warnings and Errors", "SearchQuery" => "filter=severity%3A0%2C1%2C2%2C3%2C4&search=Search" );
$CFG['Search'][] = array ( "DisplayName" => "Syslog Errors", "SearchQuery" => "filter=severity%3A0%2C1%2C2%2C3&search=Search" );
$CFG['Search'][] = array ( "DisplayName" => "All messages from the last hour", "SearchQuery" => "filter=datelastx%3A1&search=Search" );
$CFG['Search'][] = array ( "DisplayName" => "All messages from last 12 hours", "SearchQuery" => "filter=datelastx%3A2&search=Search" );
$CFG['Search'][] = array ( "DisplayName" => "All messages from last 24 hours", "SearchQuery" => "filter=datelastx%3A3&search=Search" );
$CFG['Search'][] = array ( "DisplayName" => "All messages from last 7 days", "SearchQuery" => "filter=datelastx%3A4&search=Search" );
$CFG['Search'][] = array ( "DisplayName" => "All messages from last 31 days", "SearchQuery" => "filter=datelastx%3A5&search=Search" );
// $CFG['Search'][] = array ( "DisplayName" => "", "SearchQuery" => "" );
// ---

// --- Source Options
/* Example for DiskType Source:
	$CFG['Sources']['Source1']['ID'] = "Source1";
	$CFG['Sources']['Source1']['Name'] = "Syslog Disk File";
	$CFG['Sources']['Source1']['SourceType'] = SOURCE_DISK;
	$CFG['Sources']['Source1']['LogLineType'] = "syslog";
	$CFG['Sources']['Source1']['DiskFile'] = "/var/log/syslog";
	$CFG['Sources']['Source1']['ViewID'] = "SYSLOG";

	$CFG['Sources']['Source2']['ID'] = "Source5";
	$CFG['Sources']['Source2']['Name'] = "WinSyslog DB";
	$CFG['Sources']['Source2']['SourceType'] = SOURCE_DB;
	$CFG['Sources']['Source2']['DBTableType'] = "winsyslog";
	$CFG['Sources']['Source2']['DBType'] = DB_MYSQL;
	$CFG['Sources']['Source2']['DBServer'] = "localhost";
	$CFG['Sources']['Source2']['DBName'] = "phplogcon";
	$CFG['Sources']['Source2']['DBUser'] = "root";
	$CFG['Sources']['Source2']['DBPassword'] = "";
	$CFG['Sources']['Source2']['DBTableName'] = "systemevents";
	$CFG['Sources']['Source2']['ViewID'] = "SYSLOG";
*/

// --- %Insert Source Here%
// --- 

?>