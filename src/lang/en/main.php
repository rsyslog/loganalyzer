<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
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
	*********************************************************************
*/
global $content;

// Global Stuff
$content['LN_MAINTITLE'] = "Main LogAnalyzer";
$content['LN_MAIN_SELECTSTYLE'] = "Select a Style";
$content['LN_GEN_LANGUAGE'] = "Select Language";
$content['LN_GEN_SELECTSOURCE'] = "Select Source";
$content['LN_GEN_MOREPAGES'] = "More than one Page available";
$content['LN_GEN_FIRSTPAGE'] = "First Page";
$content['LN_GEN_LASTPAGE'] = "Last Page";
$content['LN_GEN_NEXTPAGE'] = "Next Page";
$content['LN_GEN_PREVIOUSPAGE'] = "Previous Page";
$content['LN_GEN_RECORDCOUNT'] = "Total records found";
$content['LN_GEN_PAGERSIZE'] = "Records per page";
$content['LN_GEN_PAGE'] = "Page";
$content['LN_GEN_PREDEFINEDSEARCHES'] = "Predefined Searches";
$content['LN_GEN_SOURCE_DISK'] = "Diskfile";
$content['LN_GEN_SOURCE_DB'] = "MYSQL Native";
$content['LN_GEN_SOURCE_PDO'] = "Database (PDO)";
$content['LN_GEN_SOURCE_MONGODB'] = "MongoDB Native";
$content['LN_GEN_RECORDSPERPAGE'] = "records per page";
$content['LN_GEN_PRECONFIGURED'] = "Preconfigured";
$content['LN_GEN_AVAILABLESEARCHES'] = "Available searches";
$content['LN_GEN_DB_MYSQL'] = "Mysql Server";
$content['LN_GEN_DB_MSSQL'] = "Microsoft SQL Server";
$content['LN_GEN_DB_ODBC'] = "ODBC Database Source";
$content['LN_GEN_DB_PGSQL'] = "PostgreSQL";
$content['LN_GEN_DB_OCI'] = "Oracle Call Interface";
$content['LN_GEN_DB_DB2'] = "	IBM DB2";
$content['LN_GEN_DB_FIREBIRD'] = "Firebird/Interbase 6";
$content['LN_GEN_DB_INFORMIX'] = "IBM Informix Dynamic Server";
$content['LN_GEN_DB_SQLITE'] = "SQLite 2";
$content['LN_GEN_SELECTVIEW'] = "Select View";
$content['LN_GEN_CRITERROR_UNKNOWNTYPE'] = "The source type '%1' is not supported by LogAnalyzer yet. This is a critical error, please fix your configuration.";
$content['LN_GEN_ERRORRETURNPREV'] = "Click here to return to the previous page.";
$content['LN_GEN_ERRORDETAILS'] = "Error Details:";
$content['LN_SOURCES_ERROR_WITHINSOURCE'] = "The source '%1' checking returned with an error:<br>%2";
$content['LN_SOURCES_ERROR_EXTRAMSG'] = "Extra Error Details:<br>%1";
$content['LN_ERROR_NORECORDS'] = "No syslog records found";
$content['LN_ERROR_FILE_NOT_FOUND'] = "Syslog file could not be found";
$content['LN_ERROR_FILE_NOT_READABLE'] = "Syslog file is not readable, read access may be denied";
$content['LN_ERROR_UNKNOWN'] = "Unknown or unhandled error occured (Error Code '%1')";
$content['LN_ERROR_FILE_EOF'] = "End of File reached";
$content['LN_ERROR_FILE_BOF'] = "Begin of File reeached";
$content['LN_ERROR_FILE_CANT_CLOSE'] = "Can't close File";
$content['LN_ERROR_UNDEFINED'] = "Undefined Error";
$content['LN_ERROR_EOS'] = "End of stream reached";
$content['LN_ERROR_FILTER_NOT_MATCH'] = "Filter does not match any results";
$content['LN_ERROR_DB_CONNECTFAILED'] = "Connection to the database server failed";
$content['LN_ERROR_DB_CANNOTSELECTDB'] = "Could not find the configured database";
$content['LN_ERROR_DB_QUERYFAILED'] = "Dataquery failed to execute";
$content['LN_ERROR_DB_NOPROPERTIES'] = "No database properties found";
$content['LN_ERROR_DB_INVALIDDBMAPPING'] = "Invalid datafield mappings";
$content['LN_ERROR_DB_INVALIDDBDRIVER'] = "Invalid database driver selected";
$content['LN_ERROR_DB_TABLENOTFOUND'] = "Could not find the configured table, maybe misspelled or the tablenames are case sensitive";
$content['LN_ERROR_DB_DBFIELDNOTFOUND'] = "Database Field mapping for at least one field could not be found.";
$content['LN_GEN_SELECTEXPORT'] = "&gt; Select Exportformat &lt;";
$content['LN_GEN_EXPORT_CVS'] = "CSV (Comma separated)";
$content['LN_GEN_EXPORT_XML'] = "XML";
$content['LN_GEN_EXPORT_PDF'] = "PDF";
$content['LN_GEN_ERROR_EXPORING'] = "Error exporting data";
$content['LN_GEN_ERROR_INVALIDEXPORTTYPE'] = "Invalid Export format selected, or other parameters were wrong.";
$content['LN_GEN_ERROR_SOURCENOTFOUND'] = "The Source with ID '%1' could not be found.";
$content['LN_GEN_MOREINFORMATION'] = "More Information";
$content['LN_FOOTER_PAGERENDERED'] = "Page rendered in";
$content['LN_FOOTER_DBQUERIES'] = "DB queries";
$content['LN_FOOTER_GZIPENABLED'] = "GZIP enabled";
$content['LN_FOOTER_SCRIPTTIMEOUT'] = "Script Timeout";
$content['LN_FOOTER_SECONDS'] = "seconds";
$content['LN_WARNING_LOGSTREAMTITLE'] = "Logstream Warning";
$content['LN_WARNING_LOGSTREAMDISK_TIMEOUT'] = "While reading the logstream, the php script timeout forced me to abort at this point.<br><br> If you want to avoid this, please increase the LogAnalyzer script timeout in your config.php. If the user system is installed, you can do that in Admin center.";
$content['LN_ERROR_FILE_NOMORETIME'] = "No more time for processing left";
$content['LN_WARNING_DBUPGRADE'] = "Database Upgrade required";
$content['LN_WARNING_DBUPGRADE_TEXT'] = "The current installed database version is '%1'.<br>An update to version '%2' is available.";
$content['LN_ERROR_REDIRECTABORTED'] = 'Automatic redirect to the <a href="%1">page</a> was aborted, as an internal error occured. Please see the error details above and contact our support forums if you need assistance.';
$content['LN_DEBUGLEVEL'] = "Debug Level";
$content['LN_DEBUGMESSAGE'] = "Debug Message";
$content['LN_GEN_REPORT_OUTPUT_HTML'] = "HTML Format";
$content['LN_GEN_REPORT_OUTPUT_PDF'] = "PDF Format";
$content['LN_GEN_REPORT_TARGET_STDOUT'] = "Direct Output";
$content['LN_GEN_REPORT_TARGET_FILE'] = "Save into File";
$content['LN_GEN_REPORT_TARGET_EMAIL'] = "Send as Email";
$content['LN_GEN_UNKNOWN'] = "Unknown";
$content['LN_GEN_AUTH_INTERNAL'] = "Internal authentication";
$content['LN_GEN_AUTH_LDAP'] = "LDAP Authentication";

// Topmenu Entries
$content['LN_MENU_SEARCH'] = "Search";
$content['LN_MENU_SHOWEVENTS'] = "Show Events";
$content['LN_MENU_HELP'] = "Help";
	$content['LN_MENU_DOC'] = "Documentation";
	$content['LN_MENU_FORUM'] = "Support Forum";
	$content['LN_MENU_WIKI'] = "LogAnalyzer Wiki";
	$content['LN_MENU_PROSERVICES'] = "Professional Services";
$content['LN_MENU_SEARCHINKB'] = "Search in Knowledge Base";
$content['LN_MENU_LOGIN'] = "Login";
$content['LN_MENU_ADMINCENTER'] = "Admin Center";
$content['LN_MENU_LOGOFF'] = "Logoff";
$content['LN_MENU_LOGGEDINAS'] = "Logged in as";
$content['LN_MENU_MAXVIEW'] = "Maximize View";
$content['LN_MENU_NORMALVIEW'] = "Normalize View";
$content['LN_MENU_STATISTICS'] = "Statistics";
$content['LN_MENU_CLICKTOEXPANDMENU'] = "Click the icon to show the menu";
	$content['LN_MENU_REPORTS'] = "Reports";

// Main Index Site
$content['LN_ERROR_INSTALLFILEREMINDER'] = "Warning! You still have NOT removed the 'install.php' from your LogAnalyzer main directory!";
$content['LN_TOP_NUM'] = "No.";
$content['LN_TOP_UID'] = "uID";
$content['LN_GRID_POPUPDETAILS'] = "Details for Syslogmessage with ID '%1'";

$content['LN_SEARCH_USETHISBLA'] = "Use the form below and your advanced search will appear here";
$content['LN_SEARCH_FILTER'] = "Search (filter):";
$content['LN_SEARCH_ADVANCED'] = "Advanced Search";
$content['LN_SEARCH'] = "Search";
$content['LN_SEARCH_RESET'] = "Reset search";
$content['LN_SEARCH_PERFORMADVANCED'] = "Perform Advanced Search";
$content['LN_VIEW_MESSAGECENTERED'] = "Back to unfiltered view with this message at top";
$content['LN_VIEW_RELATEDMSG'] = "View related syslog messages";
$content['LN_VIEW_FILTERFOR'] = "Filter message for ";
$content['LN_VIEW_SEARCHFOR'] = "Search online for ";
$content['LN_VIEW_SEARCHFORGOOGLE'] = "Search Google for ";
$content['LN_GEN_MESSAGEDETAILS'] = "Message Details";
	$content['LN_VIEW_ADDTOFILTER'] = "Add '%1' to filterset";
	$content['LN_VIEW_EXCLUDEFILTER'] = "Exclude '%1' from filterset";
	$content['LN_VIEW_FILTERFORONLY'] = "Filter for '%1' only";
	$content['LN_VIEW_SHOWALLBUT'] = "Show all except '%1'";
	$content['LN_VIEW_VISITLINK'] = "Open Link '%1' in new window";

$content['LN_HIGHLIGHT'] = "Highlight >>";
$content['LN_HIGHLIGHT_OFF'] = "Highlight <<";
$content['LN_HIGHLIGHT_WORDS'] = "Highlight words comma separated";

$content['LN_AUTORELOAD'] = "Set auto reload";
$content['LN_AUTORELOAD_DISABLED'] = "Auto reload disabled";
$content['LN_AUTORELOAD_PRECONFIGURED'] = "Preconfigured auto reload ";
$content['LN_AUTORELOAD_SECONDS'] = "seconds";
$content['LN_AUTORELOAD_MINUTES'] = "minutes";

// Filter Options
$content['LN_FILTER_DATE'] = "Datetime Range";
$content['LN_FILTER_DATEMODE'] = "Select mode";
$content['LN_DATEMODE_ALL'] = "All time";
$content['LN_DATEMODE_RANGE'] = "Time range";
$content['LN_DATEMODE_LASTX'] = "Time x since today";
$content['LN_FILTER_DATEFROM'] = "Date range from";
$content['LN_FILTER_DATETO'] = "Date range to";
$content['LN_FILTER_TIMEFROM'] = "Time range from";
$content['LN_FILTER_TIMETO'] = "Time range to";
$content['LN_FILTER_DATELASTX'] = "Time since";
$content['LN_FILTER_ADD2SEARCH'] = "Add to search";
$content['LN_DATE_LASTX_HOUR'] = "Last hour";
$content['LN_DATE_LASTX_12HOURS'] = "Last 12 hours";
$content['LN_DATE_LASTX_24HOURS'] = "Last 24 hours";
$content['LN_DATE_LASTX_7DAYS'] = "Last 7 days";
$content['LN_DATE_LASTX_31DAYS'] = "Last 31 days";
$content['LN_FILTER_FACILITY'] = "Syslog Facility";
$content['LN_FILTER_SEVERITY'] = "Syslog Severity";
$content['LN_FILTER_OTHERS'] = "Other Filters";
$content['LN_FILTER_MESSAGE'] = "Syslog Message";
$content['LN_FILTER_SYSLOGTAG'] = "Syslogtag";
$content['LN_FILTER_SOURCE'] = "Source (Hostname)";
$content['LN_FILTER_MESSAGETYPE'] = "Message Type";

// Install Page
$content['LN_CFG_DBSERVER'] = "Database Host";
$content['LN_CFG_DBPORT'] = "Database Port";
$content['LN_CFG_DBNAME'] = "Database Name";
$content['LN_CFG_DBPREF'] = "Table prefix";
$content['LN_CFG_DBUSER'] = "Database User";
$content['LN_CFG_DBPASSWORD'] = "Database Password";
$content['LN_CFG_PARAMMISSING'] = "The following parameter were missing: ";
$content['LN_CFG_SOURCETYPE'] = "Source Type";
$content['LN_CFG_DISKTYPEOPTIONS'] = "Disk Type Options";
$content['LN_CFG_LOGLINETYPE'] = "Logline type";
$content['LN_CFG_SYSLOGFILE'] = "Syslog file";
$content['LN_CFG_DATABASETYPEOPTIONS'] = "Database Type Options";
$content['LN_CFG_DBTABLETYPE'] = "Table type";
$content['LN_CFG_DBSTORAGEENGINE'] = "Database Storage Engine";
$content['LN_CFG_DBTABLENAME'] = "Database Tablename";
$content['LN_CFG_NAMEOFTHESOURCE'] = "Name of the Source";
$content['LN_CFG_FIRSTSYSLOGSOURCE'] = "First Syslog Source";
$content['LN_CFG_DBROWCOUNTING'] = "Enable Row Counting";
$content['LN_CFG_VIEW'] = "Select View";
$content['LN_CFG_DBUSERLOGINREQUIRED'] = "Require user to be logged in";
$content['LN_CFG_MSGPARSERS'] = "Message Parsers (comma seperated)";
$content['LN_CFG_NORMALIZEMSG'] = "Normalize Message within Parsers";
$content['LN_CFG_SKIPUNPARSEABLE'] = "Skip unparseable messages (Only works if msgparsers are configured!)";
$content['LN_CFG_DBRECORDSPERQUERY'] = "Recordcount for database queries";
$content['LN_CFG_LDAPServer'] = "LDAP Server Hostname/IP";
$content['LN_CFG_LDAPPort'] = "LDAP Port, default 389 (636 for SSL)";
$content['LN_CFG_LDAPBaseDN'] = "Base DN for LDAP Search";
$content['LN_CFG_LDAPSearchFilter'] = "Basic Search filter";
$content['LN_CFG_LDAPUidAttribute'] = "LDAP Username attribute";
$content['LN_CFG_LDAPBindDN'] = "Privilegied user used to LDAP queries";
$content['LN_CFG_LDAPBindPassword'] = "Password of the privilegied user";
$content['LN_CFG_LDAPDefaultAdminUser'] = "Default administrative LDAP Username";
$content['LN_CFG_AUTHTYPE'] = "Authentication method";
$content['LN_GEN_AUTH_LDAP_OPTIONS'] = "LDAP Authentication Options";

// Details page
$content['LN_DETAILS_FORSYSLOGMSG'] = "Details for the syslog messages with id";
$content['LN_DETAILS_DETAILSFORMSG'] = "Details for message id";
$content['LN_DETAIL_BACKTOLIST'] = "Back to Listview";
$content['LN_DETAIL_DYNAMIC_FIELDS'] = "Dynamic fields";


// Login Site
$content['LN_LOGIN_DESCRIPTION'] = "Use this form to login into LogAnalyzer. ";
$content['LN_LOGIN_TITLE'] = "Login";
$content['LN_LOGIN_USERNAME'] = "Username";
$content['LN_LOGIN_PASSWORD'] = "Password";
$content['LN_LOGIN_SAVEASCOOKIE'] = "Stay logged on";
$content['LN_LOGIN_ERRWRONGPASSWORD'] = "Wrong username or password!";
$content['LN_LOGIN_USERPASSMISSING'] = "Username or password not given";
$content['LN_LOGIN_LDAP_USERNOTFOUND'] = "User '%1' could not be found";
$content['LN_LOGIN_LDAP_USERCOULDNOTLOGIN'] = "Could not login user '%1', LDAP error: %2";
$content['LN_LOGIN_LDAP_PASSWORDFAIL'] = "User '%1' could not login with the given password";
$content['LN_LOGIN_LDAP_SERVERFAILED'] = "Failed to connect to LDAP Server '%1'";
$content['LN_LOGIN_LDAP_USERBINDFAILED'] = "Could not bind with the Search user DN '%1'";


// Install Site
$content['LN_INSTALL_TITLETOP'] = "Installing LogAnalyzer Version %1 - Step %2";
$content['LN_INSTALL_TITLE'] = "Installer Step %1";
$content['LN_INSTALL_ERRORINSTALLED'] = 'LogAnalyzer is already configured!<br><br> If you want to reconfigure LogAnalyzer, either delete the current <B>config.php</B> or replace it with an empty file.<br><br>Click <A HREF="index.php">here</A> to return to pgpLogCon start page.';
$content['LN_INSTALL_FILEORDIRNOTWRITEABLE'] = "At least one file or directory (or more) is not writeable, please check the file permissions (chmod 666)!";
$content['LN_INSTALL_SAMPLECONFIGMISSING'] = "The sample configuration file '%1' is missing. You have not fully uploaded LogAnalyzer.";
$content['LN_INSTALL_ERRORCONNECTFAILED'] = "Database connect to '%1' failed! Please check Servername, Port, User and Password!";
$content['LN_INSTALL_ERRORACCESSDENIED'] = "Cannot use the database  '%1'! If the database does not exists, create it or check user access permissions!";
$content['LN_INSTALL_ERRORINVALIDDBFILE'] = "Error, invalid Database definition file (to short!), the file name is '%1'! Please check if the file was correctly uploaded.";
$content['LN_INSTALL_ERRORINSQLCOMMANDS'] = "Error, invalid Database definition file (no sql statements found!), the file name is '%1'!<br> Please check if the file was not correctly uploaded, or contact the LogAnalyzer forums for assistance!";
$content['LN_INSTALL_MISSINGUSERNAME'] = "Username needs to be specified";
$content['LN_INSTALL_PASSWORDNOTMATCH'] = "Either the password does not match or is to short!";
$content['LN_INSTALL_FAILEDTOOPENSYSLOGFILE'] = "Failed to open the syslog file '%1'! Check if the file exists and LogAnalyzer has sufficient rights to it<br>";
$content['LN_INSTALL_FAILEDCREATECFGFILE'] = "Coult not create the configuration file in '%1'! Please verify the file permissions!";
$content['LN_INSTALL_FAILEDREADINGFILE'] = "Error reading the file '%1'! Please verify if the file exists!";
$content['LN_INSTALL_ERRORREADINGDBFILE'] = "Error reading the default database definition file in '%1'! Please verify  if the file exists!";
$content['LN_INSTALL_STEP1'] = "Step 1 - Prerequisites";
$content['LN_INSTALL_STEP2'] = "Step 2 - Verify File Permissions";
$content['LN_INSTALL_STEP3'] = "Step 3 - Basic Configuration";
$content['LN_INSTALL_STEP4'] = "Step 4 - Create Tables";
$content['LN_INSTALL_STEP5'] = "Step 5 - Check SQL Results";
$content['LN_INSTALL_STEP6'] = "Step 6 - Creating the Main Useraccount";
$content['LN_INSTALL_STEP7'] = "Step 7 - Create the first source for syslog messages";
$content['LN_INSTALL_STEP8'] = "Step 8 - Done";
$content['LN_INSTALL_STEP1_TEXT'] = 'Before you start installing LogAnalyzer, the Installer setup has to check a few things first.<br>You may have to correct some file permissions first. <br><br>Click on <input type="submit" value="Next"> to start the Test!';
$content['LN_INSTALL_STEP2_TEXT'] = "The following file permissions have been checked. Verify the results below! <br>You may use the <B>configure.sh</B> script from the <B>contrib</B> folder to set the permissions for you.";
$content['LN_INSTALL_STEP3_TEXT'] = "In this step, you configure the basic configurations for LogAnalyzer.";
$content['LN_INSTALL_STEP4_TEXT'] = 'If you reached this step, the database connection has been successfully verified!<br><br> The next step will be to create the necessary database tables used by the LogAnalyzer User System. This might take a while!<br> <b>WARNING</b>, if you have an existing LogAnalyzer installation in this database with the same tableprefix, all your data will be <b>OVERWRITTEN</b>! Make sure you are using a fresh database, or you want to overwrite your old LogAnalyzer database. <br><br><b>Click on <input type="submit" value="Next"> to start the creation of the tables</b>';
$content['LN_INSTALL_STEP5_TEXT'] = "Tables have been created. Check the List below for possible Error's";
$content['LN_INSTALL_STEP6_TEXT'] = "You are now about to create the initial LogAnalyzer User Account.<br> This will be the first administrative user, which will be needed to login into LogAnalyzer and access the Admin Center!";
$content['LN_INSTALL_STEP8_TEXT'] = 'Congratulations! You have successfully installed LogAnalyzer :)! <br><br>Click <a href="index.php">here</a> to go to your installation.';
$content['LN_INSTALL_PROGRESS'] = "Install Progress: ";
$content['LN_INSTALL_FRONTEND'] = "Frontend Options";
$content['LN_INSTALL_NUMOFSYSLOGS'] = "Number of syslog messages per page";
$content['LN_INSTALL_MSGCHARLIMIT'] = "Message character limit for the main view";
$content['LN_INSTALL_STRCHARLIMIT'] = "Character display limit for all string type fields";
$content['LN_INSTALL_SHOWDETAILPOP'] = "Show message details popup";
$content['LN_INSTALL_AUTORESOLVIP'] = "Automatically resolved IP Addresses (inline)";
$content['LN_INSTALL_USERDBOPTIONS'] = "User Database Options";
$content['LN_INSTALL_ENABLEUSERDB'] = "Enable User Database";
$content['LN_INSTALL_SUCCESSSTATEMENTS'] = "Successfully executed statements:";
$content['LN_INSTALL_FAILEDSTATEMENTS'] = "Failed statements:";
$content['LN_INSTALL_STEP5_TEXT_NEXT'] = "You can now proceed to the <B>next</B> step adding the first LogAnalyzer Admin User!";
$content['LN_INSTALL_STEP5_TEXT_FAILED'] = "At least one statement failed,see error reasons below";
$content['LN_INSTALL_ERRORMSG'] = "Error Message";
$content['LN_INSTALL_SQLSTATEMENT'] = "SQL Statement";
$content['LN_INSTALL_CREATEUSER'] = "Create User Account";
$content['LN_INSTALL_PASSWORD'] = "Password";
$content['LN_INSTALL_PASSWORDREPEAT'] = "Repeat Password";
$content['LN_INSTALL_SUCCESSCREATED'] = "Successfully created User";
$content['LN_INSTALL_RECHECK'] = "ReCheck";
$content['LN_INSTALL_FINISH'] = "Finish!";
$content['LN_INSTALL_LDAPCONNECTFAILED'] = "Failed to connect to your LDAP Server '%1'.";
$content['LN_INSTALL_'] = "";

// Converter Site
$content['LN_CONVERT_TITLE'] = "Configuration Converter Step %1";
$content['LN_CONVERT_NOTALLOWED'] = "Login";
$content['LN_CONVERT_ERRORINSTALLED'] = 'LogAnalyzer is not allowed to convert your settings into the user database.<br><br> If you want to convert your convert your settings, add the variable following into your config.php: <br><b>$CFG[\'UserDBConvertAllowed\'] = true;</b><br><br> Click <A HREF="index.php">here</A> to return to pgpLogCon start page.';
$content['LN_CONVERT_STEP1'] = "Step 1 - Informations";
$content['LN_CONVERT_STEP2'] = "Step 2 - Create Tables";
$content['LN_CONVERT_STEP3'] = "Step 3 - Check SQL Results";
$content['LN_CONVERT_STEP4'] = "Step 4 - Creating the Main Useraccount";
$content['LN_CONVERT_STEP5'] = "Step 5 - Import Settings into UserDB";
$content['LN_CONVERT_TITLETOP'] = "Converting LogAnalyzer configuration settings - Step ";
$content['LN_CONVERT_STEP1_TEXT'] = 'This script allows you to import your existing configuration from the <b>config.php</b> file. This includes frontend settings, data sources, custom views and custom searches. Do only perform this conversion if you did install LogAnalyzer without the UserDB System, and decided to enable it now. <br><br><b>ANY EXISTING INSTANCE OF A USERDB WILL BE OVERWRITTEN!</b><br><br><input type="submit" value="Click here"> to start the first conversion step!';
$content['LN_CONVERT_STEP2_TEXT'] = 'The database connection has been successfully verified! <br><br>The next step will be to create the necessary database tables for the LogAnalyzer User System. This might take a while! <br><b>WARNING</b>, if you have an existing LogAnalyzer installation in this database with the same tableprefix, all your data will be <b>OVERWRITTEN</b>!<br> Make sure you are using a fresh database, or you want to overwrite your old LogAnalyzer database.<br><br><b>Click on <input type="submit" value="Next"> to start the creation of the tables</b>';
$content['LN_CONVERT_STEP5_TEXT'] = '<input type="submit" value="Click here"> to start the last step of the conversion. In this step, your existing configuration from the <b>config.php</b> will be imported into the database.';
$content['LN_CONVERT_STEP6'] = "Step 8 - Done";
$content['LN_CONVERT_STEP6_TEXT'] = 'Congratulations! You have successfully converted your existing LogAnalyzer installation :)!<br><br>Important! Don\'t forget to REMOVE THE VARIABLES <b>$CFG[\'UserDBConvertAllowed\'] = true;</b> from your config.php file! <br><br>You can click <a href="index.php">here</a> to get to your LogAnalyzerinstallation.';
$content['LN_CONVERT_PROCESS'] = "Conversion Progress:";
$content['LN_CONVERT_ERROR_SOURCEIMPORT'] = "Critical Error while importing the sources into the database, the SourceType '%1' is not supported by this LogAnalyzer Version.";

// Stats Site
	$content['LN_STATS_CHARTTITLE'] = "Top %1 '%2' sorted by messagecount";
	$content['LN_STATS_COUNTBY'] = "Messagecount by '%1'";
	$content['LN_STATS_OTHERS'] = "All Others";
	$content['LN_STATS_TOPRECORDS'] = "Maxrecords: %1";
	$content['LN_STATS_GENERATEDAT'] = "Generated at: %1";
//	$content['LN_STATS_COUNTBYSYSLOGTAG'] = "Messagecount by SyslogTag";
	$content['LN_STATS_GRAPH'] = "Graph";
	$content['LN_GEN_ERROR_INVALIDFIELD'] = "Invalid fieldname";
	$content['LN_GEN_ERROR_MISSINGCHARTFIELD'] = "Missing fieldname";
	$content['LN_GEN_ERROR_INVALIDTYPE'] = "Invalid or unknown chart type.";
	$content['LN_ERROR_CHARTS_NOTCONFIGURED'] = "There are no charts configured at all.";
	$content['LN_CHART_TYPE'] = "Chart type";
	$content['LN_CHART_WIDTH'] = "Chart width";
	$content['LN_CHART_FIELD'] = "Chart field";
	$content['LN_CHART_MAXRECORDS'] = "Top records count";
	$content['LN_CHART_SHOWPERCENT'] = "Show percentage data";
	$content['LN_CHART_TYPE_CAKE'] = "Cake (Pie)";
	$content['LN_CHART_TYPE_BARS_VERTICAL'] = "Bars vertical";
	$content['LN_CHART_TYPE_BARS_HORIZONTAL'] = "Bars horizontal";
	$content['LN_STATS_WARNINGDISPLAY'] = "Generating graphics on large data sources currently is very time consuming. This will be addressed in later versions. If processing takes too long, please simply cancel the request.";

// asktheoracle site
$content['LN_ORACLE_TITLE'] = "Asking the oracle for '%1'";
$content['LN_ORACLE_HELP_FOR'] = "These are the links the oracle got for you";
$content['LN_ORACLE_HELP_TEXT'] = "<br><h3>You asked the oracle to find more information about the '%1' value '%2'.</h3>
<p align=\"left\">This pages enables you do a a search over multiple log sources. %3
<br>The overall idea is to make it easy to find information about a specific subject in all places where it may exist.
</p>
<p align=\"left\">A useful use case may be a hack attempt you see in a web log. Click on the attacker's IP, which brings up this search page here. Now you can both lookup information about the IP range as well as check your other logs (e.g. firewall or mail) if they contain information about the attacker. We hope that this facilitates your analysis process.
</p>
";
$content['LN_ORACLE_HELP_TEXT_EXTERNAL'] = "It also enables you to perform canned searches over some external databases";
$content['LN_ORACLE_HELP_DETAIL'] = "Link matrix for the '%1' value '%2'";
$content['LN_ORACLE_SEARCH'] = "Search"; // in '%1' Field";
$content['LN_ORACLE_SOURCENAME'] = "Source name";
$content['LN_ORACLE_FIELD'] = "Field";
$content['LN_ORACLE_ONLINESEARCH'] = "Online Search";
$content['LN_ORACLE_WHOIS'] = "WHOIS Lookup for '%1' value '%2'";

// Report Strings
$content['LN_GEN_ERROR_INVALIDOP'] = "Invalid or missing operation type";
$content['LN_GEN_ERROR_INVALIDREPORTID'] = "Invalid or missing report id";
$content['LN_GEN_ERROR_MISSINGSAVEDREPORTID'] = "Invalid or missing savedreport id";
$content['LN_GEN_ERROR_REPORTGENFAILED'] = "Failed generating report '%1' with the following error reason: %2";
$content['LN_GEN_ERROR_WHILEREPORTGEN'] = "Error occured while generating report"; 
$content['LN_GEN_ERROR_REPORT_NODATA'] = "No data found for report generation"; 
$content['LN_GEN_ALL_OTHER_EVENTS'] = "All other events";
$content['LN_REPORT_FOOTER_ENDERED'] = "Report rendered in";
$content['LN_REPORT_FILTERS'] = "List of used filters";
$content['LN_REPORT_FILTERTYPE_DATE'] = "Date";
$content['LN_REPORT_FILTERTYPE_NUMBER'] = "Number";
$content['LN_REPORT_FILTERTYPE_STRING'] = "String";
$content['LN_GEN_SUCCESS_WHILEREPORTGEN'] = "Report was successfully generated";
$content['LN_GEN_ERROR_REPORTFAILEDTOGENERATE'] = "Failed to generate report, error details: %1";
$content['LN_GEN_SUCCESS_REPORTWASGENERATED_DETAILS'] = "Successfully generated report: %1";
$content['LN_ERROR_PATH_NOT_ALLOWED'] = "The file is not located in the allowed directories list (By default /var/log is allowed only)."; 
$content['LN_ERROR_PATH_NOT_ALLOWED_EXTRA'] = "The file '%1' is not located in one of these directories: '%2'"; 

$content['LN_CMD_RUNREPORT'] = "Generating saved report '%1'";
$content['LN_CMD_REPORTIDNOTFOUND'] = "Invalid Report ID '%1'";
$content['LN_CMD_SAVEDREPORTIDNOTFOUND'] = "Invalid SavedReport ID '%1'";
$content['LN_CMD_NOREPORTID'] = "Missing Report ID";
$content['LN_CMD_NOSAVEDREPORTID'] = "Missing SavedReport ID";
$content['LN_CMD_NOCMDPROMPT'] = "Error, this script can only be run from the command prompt.";
$content['LN_REPORT_GENERATEDTIME'] = "Report generated at: ";

$content['LN_REPORT_ACTIONS'] = "Run Report Actions";
$content['LN_REPORTS_CAT'] = "Report Category";
$content['LN_REPORTS_ID'] = "Report ID";
$content['LN_REPORTS_NAME'] = "Report Name";
$content['LN_REPORTS_DESCRIPTION'] = "Report Description";
$content['LN_REPORTS_HELP'] = "Help";
$content['LN_REPORTS_HELP_CLICK'] = "Click here for a detailed report description";
$content['LN_REPORTS_INFO'] = "Show more Information";
$content['LN_REPORTS_SAVEDREPORTS'] = "Saved reports";
$content['LN_REPORTS_ADMIN'] = "Administrate Reports";
$content['LN_REPORTMENU_LIST'] = "List installed Reports";
$content['LN_REPORTMENU_ONLINELIST'] = "All Available Reports";
$content['LN_REPORTS_INFORMATION'] = "This page shows a list of installed and available reports including saved report configurations.
<br/>To run a report, click on the buttons right to the Saved Reports.
<br/>Attention! Generating reports can be very time consuming depending on the size of your database.
";
$content['LN_REPORTS_CHECKLOGSTREAMSOURCE'] = "Verify Logstream optimization";


?>