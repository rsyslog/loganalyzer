<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Main Configuration File
	*
	* -> Configuration need variables for the Database connection
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
$CFG['UserDBAuthMode'] = USERDB_AUTH_INTERNAL;	// USERDB_AUTH_INTERNAL means LogAnalyzer Internal Auth
												// USERDB_AUTH_LDAP means Auth via LDAP Server

// LDAP Auth options
$CFG['LDAPServer'] = "127.0.0.1";					// LDAP server hostname or IP
$CFG['LDAPPort'] = 389;								// LDAP port, 389 or 636 for SSL
$CFG['LDAPBaseDN'] = 'CN=Users,DC=domain,DC=local';	// Base DN for LDAP Search, this is a typical ActiveDirectory sample
$CFG['LDAPSearchFilter'] = '(objectClass=user)';	// Basic Search filter
$CFG['LDAPUidAttribute'] = "sAMAccountName";		// The LDAP attribute used in the search to find the user, example: uid, cn or sAMAccountName (Active Directory)
													// DN of the privileged user for the search
$CFG['LDAPBindDN'] = 'CN=Searchuser,CN=Users,DC=domain,DC=local'; // "Searchuser" = the privilegied user used to query LDAP Directory
$CFG['LDAPBindPassword'] = 'Password';				// Password of the privilegied user
// --- 

// --- Misc Options
$CFG['MiscShowDebugMsg'] = 0;				// if enabled, you will get additional output on certain places
$CFG['MiscDebugToSyslog'] = 0;				// if enabled, debug messages from LogAnalyzer will be send to syslog on linux, and into the EventLog on Windows
$CFG['MiscShowDebugGridCounter'] = 0;		// Only for debugging purposes, will add a counter column into the grid!
$CFG["MiscShowPageRenderStats"] = 1;		// If enabled, you will see Pagerender Settings
$CFG['MiscEnableGzipCompression'] = 1;		// If enabled, LogAnalyzer will use gzip compression for output, we recommend
											// to have this option enabled, it will highly reduce bandwith usage. 
$CFG['MiscMaxExecutionTime'] = 30;			// LogAnalyzer will try to overwrite the default script timeout with this value during runtime!
											// This can of course only work if LogAnalyzer is allowed to changed the script timeout. 
$CFG['DebugUserLogin'] = 0;					// if enabled, you will see additional informations on failed logins
// --- 

// --- Default Frontend Options 
$CFG['PrependTitle'] = "";					// If set, this	text will be prepended withint the title tag
$CFG['ViewUseTodayYesterday'] = 1;			// If enabled, the date from today and yesterday is displayed as "today" and "yesterday"
$CFG['ViewMessageCharacterLimit'] = 80;		// Default character limit for the message gets trunscated! 0 means NO trunscation.
$CFG['ViewStringCharacterLimit'] = 30;		// Default character limit for all other string type fields before they get trunscated! 0 means NO trunscation.
$CFG['ViewEntriesPerPage'] = 50;			// Default number of syslog entries shown per page
$CFG['ViewEnableDetailPopups'] = 1;			// If enabled, you will see additional Details for each syslog message on mouse over. 
$CFG['ViewDefaultTheme'] = "default";		// This sets the default theme the user is going to see when he opens LogAnalyzer the first time. 
											// Currently only "default" and "dark" are available. 
$CFG['ViewDefaultLanguage'] = "en";			// Sets the default display language
$CFG['ViewEnableAutoReloadSeconds'] = 0;	// If "ViewEnableAutoReloadSeconds" is set to anything higher the 0 (which means disabled), this means auto reload is enabled by default. 

$CFG['SearchCustomButtonCaption'] = "I'd like to feel sad";	// Default caption for the custom fast search button
$CFG['SearchCustomButtonSearch'] = "error";					// Default search string for the custom search button

$CFG['EnableContextLinks'] = 1;				// if enabled, context links within the messages will automatically be created and added. Set this to 0 to disable all context links. 
$CFG['EnableIPAddressResolve'] = 1;			// If enabled, IP Addresses inline messages are automatically resolved and the result is added in brackets {} behind the IP Address
$CFG['SuppressDuplicatedMessages'] = 0;		// If enabled, duplicated messages will be suppressed in the main display. 
$CFG['TreatNotFoundFiltersAsTrue'] = 0;		// If you filter / search for messages, and the fields you are filtering for is not found, the filter result is treaten as TRUE! 
$CFG['PopupMenuTimeout'] = 3000;			// This variable defines the default timeout value for popup menus in milliseconds. (those menus which popup when you click on the value of a field.
$CFG['PhplogconLogoUrl'] = "";				// Put an Url to a custom toplogo you want to use.
$CFG['InlineOnlineSearchIcons'] = 1;		// Show online search icons
$CFG['UseProxyServerForRemoteQueries'] = "";// If empty no proxy server will be used. If set to a proxy server url like 127.0.0.1:8080, LogAnalyzer will use this server for url queries like the updatecheck. 
$CFG['HeaderDefaultEncoding'] = ENC_ISO_8859_1;	// Set default character encoding
// ---

// --- Custom HTML Code 
$CFG['InjectHtmlHeader'] = "";				// Use this variable to inject custom html into the html <head> area!
$CFG['InjectBodyHeader'] = "";				// Use this variable to inject custom html into the begin of the <body> area!
$CFG['InjectBodyFooter'] = "";				// Use this variable to inject custom html into the end of the <body> area!
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

// --- Predefined Charts!
$CFG['Charts'][] = array ( "DisplayName" => "Top Hosts", "chart_type" => CHART_BARS_HORIZONTAL, "chart_width" => 400, "chart_field" => SYSLOG_HOST, "maxrecords" => 10, "showpercent" => 0, "chart_enabled" => 1 );
$CFG['Charts'][] = array ( "DisplayName" => "SyslogTags", "chart_type" => CHART_CAKE, "chart_width" => 400, "chart_field" => SYSLOG_SYSLOGTAG, "maxrecords" => 10, "showpercent" => 0, "chart_enabled" => 1 );
$CFG['Charts'][] = array ( "DisplayName" => "Severity Occurences", "chart_type" => CHART_BARS_VERTICAL, "chart_width" => 400, "chart_field" => SYSLOG_SEVERITY, "maxrecords" => 10, "showpercent" => 1, "chart_enabled" => 1 );
$CFG['Charts'][] = array ( "DisplayName" => "Usage by Day", "chart_type" => CHART_CAKE, "chart_width" => 400, "chart_field" => SYSLOG_DATE, "maxrecords" => 10, "showpercent" => 1, "chart_enabled" => 1 );
// ---

// --- Configure allowed directories for File base logstream sources
$CFG['DiskAllowed'][] = "/var/log/"; 
// ---

// --- Source Options
/* Example for DiskType Source:
	$CFG['Sources']['Source1']['ID'] = "Source1";
	$CFG['Sources']['Source1']['Name'] = "Syslog Disk File";
	$CFG['Sources']['Source1']['Description'] = "More details you want to see about this source";
	$CFG['Sources']['Source1']['SourceType'] = SOURCE_DISK;
	$CFG['Sources']['Source1']['LogLineType'] = "syslog";
	$CFG['Sources']['Source1']['MsgParserList'] = "";
	$CFG['Sources']['Source1']['MsgNormalize'] = 0;
	$CFG['Sources']['Source1']['DiskFile'] = "/var/log/syslog";
	$CFG['Sources']['Source1']['ViewID'] = "SYSLOG";

	$CFG['Sources']['Source2']['ID'] = "Source5";
	$CFG['Sources']['Source2']['Name'] = "WinSyslog DB";
	$CFG['Sources']['Source1']['Description'] = "";
	$CFG['Sources']['Source2']['SourceType'] = SOURCE_DB;
	$CFG['Sources']['Source1']['MsgParserList'] = "";
	$CFG['Sources']['Source2']['DBTableType'] = "winsyslog";
	$CFG['Sources']['Source2']['DBType'] = DB_MYSQL;
	$CFG['Sources']['Source2']['DBServer'] = "localhost";
	$CFG['Sources']['Source2']['DBName'] = "loganalyzer";
	$CFG['Sources']['Source2']['DBUser'] = "root";
	$CFG['Sources']['Source2']['DBPassword'] = "";
	$CFG['Sources']['Source2']['DBTableName'] = "systemevents";
	$CFG['Sources']['Source2']['ViewID'] = "SYSLOG";
*/

// --- %Insert Source Here%
// --- 

?>