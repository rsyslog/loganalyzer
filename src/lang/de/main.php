<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-
	* -----------------------------------------------------------------
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
global $content;

// Global Stuff
$content['LN_MAINTITLE'] = "Main PhpLogCon";
$content['LN_MAIN_SELECTSTYLE'] = "Style ausw&auml;hlen";
$content['LN_GEN_LANGUAGE'] = "Sprache ausw&auml;hlen";
$content['LN_GEN_SELECTSOURCE'] = "Quelle ausw&auml;len";
$content['LN_GEN_MOREPAGES'] = "Mehr als eine Seite verf&uuml;gbar";
$content['LN_GEN_FIRSTPAGE'] = "Erste Seite";
$content['LN_GEN_LASTPAGE'] = "Letzte Seite";
$content['LN_GEN_NEXTPAGE'] = "N&auml;chste Seite";
$content['LN_GEN_PREVIOUSPAGE'] = "Vorherige Seite";
$content['LN_GEN_RECORDCOUNT'] = "Alle gefundenen Eintr&auml;ge";
$content['LN_GEN_PAGERSIZE'] = "Eintr&auml;ge pro Seite";
$content['LN_GEN_PAGE'] = "Seite";
$content['LN_GEN_PREDEFINEDSEARCHES'] = "Vordefinierte Suchkriterien";
$content['LN_GEN_SOURCE_DISK'] = "Datei";
$content['LN_GEN_SOURCE_DB'] = "Datenbank";
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
	$content['LN_GEN_CRITERROR_UNKNOWNTYPE'] = "The source type '%1' is not supported by phpLogCon yet. This is a critical error, please fix your configuration.";
	$content['LN_GEN_ERRORRETURNPREV'] = "Click here to return to the previous site.";
	$content['LN_GEN_ERRORDETAILS'] = "Error Details:";

	$content['LN_SOURCES_ERROR_WITHINSOURCE'] = "The source '%1' checking returned with an error: '%2'";
	$content['LN_SOURCES_ERROR_EXTRAMSG'] = "Extra Error Details:<br>%1";
$content['LN_ERROR_NORECORDS'] = "Es wurden keine syslog-Eintr&auml;ge gefunden.";
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
	$content['LN_GEN_EXPORT_CVS'] = "CVS (Comma separated)";
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
	$content['LN_WARNING_LOGSTREAMDISK_TIMEOUT'] = "While reading the logstream, the php script timeout forced me to abort at this point.<br><br> If you want to avoid this, please increase the phpLogCon script timeout in your config.php. If the user system is installed, you can do that in Admin center.";

// Topmenu Entries
$content['LN_MENU_SEARCH'] = "Suchen";
	$content['LN_MENU_SHOWEVENTS'] = "Show Events";
$content['LN_MENU_HELP'] = "Hilfe";
	$content['LN_MENU_SEARCHINKB'] = "Search in Knowledge Base";
$content['LN_MENU_LOGIN'] = "Login";
	$content['LN_MENU_ADMINCENTER'] = "Admin Center";
$content['LN_MENU_LOGOFF'] = "Logoff";
	$content['LN_MENU_LOGGEDINAS'] = "Logged in as";
	$content['LN_MENU_MAXVIEW'] = "Maximize View";
	$content['LN_MENU_NORMALVIEW'] = "Normalize View";
	$content['LN_MENU_STATISTICS'] = "Statistics";


// Index Site
$content['LN_ERROR_INSTALLFILEREMINDER'] = "Warnung! Du hast das Installationsscript 'install.php' noch nicht aus dem phpLogCon Hauptordner entfernt!";
$content['LN_TOP_NUM'] = "No.";
$content['LN_TOP_UID'] = "uID";
$content['LN_GRID_POPUPDETAILS'] = "Details f&uulm;r die Syslog-Meldung mit der ID '%1'";

$content['LN_SEARCH_USETHISBLA'] = "Bitte ber&uuml;cksichtigen Sie bei Ihrer Suche folgenden Kriterien";
$content['LN_SEARCH_FILTER'] = "Suche (Filter):";
$content['LN_SEARCH_ADVANCED'] = "Erweiterte Suche";
$content['LN_SEARCH'] = "Suche";
$content['LN_SEARCH_RESET'] = "Suche zur&uuml;cksetzen";
$content['LN_SEARCH_PERFORMADVANCED'] = "Erweiterte Suche starten";
	$content['LN_VIEW_MESSAGECENTERED'] = "Back to unfiltered view with this message at top";
	$content['LN_VIEW_RELATEDMSG'] = "View related syslog messages";
	$content['LN_VIEW_FILTERFOR'] = "Filter message for ";
	$content['LN_VIEW_SEARCHFOR'] = "Search online for ";
	$content['LN_VIEW_SEARCHFORGOOGLE'] = "Search Google for ";
	$content['LN_GEN_MESSAGEDETAILS'] = "Message Details";
	$content['LN_VIEW_ADDTOFILTER'] = "Add '%1' to filterset";
	$content['LN_VIEW_FILTERFORONLY'] = "Filter for '%1' only";

$content['LN_HIGHLIGHT'] = "Hightlight >>";
$content['LN_HIGHLIGHT_OFF'] = "Hightlight <<";
$content['LN_HIGHLIGHT_WORDS'] = "Hightlight-W&ouml;rter durch ein  Komma voneinander trennen";

$content['LN_AUTORELOAD'] = "Set auto reload";
$content['LN_AUTORELOAD_DISABLED'] = "Auto reload disabled";
$content['LN_AUTORELOAD_PRECONFIGURED'] = "Preconfigured auto reload ";
$content['LN_AUTORELOAD_SECONDS'] = "seconds";
$content['LN_AUTORELOAD_MINUTES'] = "minutes";

// Filter Options
$content['LN_FILTER_DATE'] = "Zeitliche Abgrenzung";
$content['LN_FILTER_DATEMODE'] = "Zeitraum ausw&aumlhlen";
$content['LN_DATEMODE_ALL'] = "Kompletter Zeitraum";
$content['LN_DATEMODE_RANGE'] = "Zeitspanne";
$content['LN_DATEMODE_LASTX'] = "Seit heute, x Uhr";
$content['LN_FILTER_DATEFROM'] = "Zeitraum seit x";
$content['LN_FILTER_DATETO'] = "Zeitraum bis x";
$content['LN_FILTER_DATELASTX'] = "Zeit seit";
$content['LN_FILTER_ADD2SEARCH'] = "Zur Suche hinzuf&uuml;gen";
$content['LN_DATE_LASTX_HOUR'] = "in der letzten Stunde";
$content['LN_DATE_LASTX_12HOURS'] = "in den letzten 12 Stunden";
$content['LN_DATE_LASTX_24HOURS'] = "in den letzten 24 Stunden";
$content['LN_DATE_LASTX_7DAYS'] = "in den letzten 7 Tagen";
$content['LN_DATE_LASTX_31DAYS'] = "in den letzten 31 Tagen";
$content['LN_FILTER_FACILITY'] = "Syslog Kategorie/Facility";
$content['LN_FILTER_SEVERITY'] = "Syslog Dringlichkeit/Severity";
$content['LN_FILTER_OTHERS'] = "Andere Filter";
$content['LN_FILTER_MESSAGE'] = "Syslog Meldungen";
$content['LN_FILTER_SYSLOGTAG'] = "Syslogtag";
$content['LN_FILTER_SOURCE'] = "Quelle (Hostname)";
	$content['LN_FILTER_MESSAGETYPE'] = "Message Type";

// Field Captions
$content['LN_FIELDS_DATE'] = "Datum";
$content['LN_FIELDS_FACILITY'] = "Kategorie/Facility";
$content['LN_FIELDS_SEVERITY'] = "Dringlichkeit/Severity";
$content['LN_FIELDS_HOST'] = "Host";
$content['LN_FIELDS_SYSLOGTAG'] = "Syslogtag";
$content['LN_FIELDS_PROCESSID'] = "Prozess ID";
$content['LN_FIELDS_MESSAGETYPE'] = "Meldungstyp";
$content['LN_FIELDS_UID'] = "uID";
$content['LN_FIELDS_MESSAGE'] = "Meldung";
	$content['LN_FIELDS_EVENTID'] = "Event ID";
	$content['LN_FIELDS_EVENTLOGTYPE'] = "Eventlogtype";
	$content['LN_FIELDS_EVENTSOURCE'] = "Event Source";
	$content['LN_FIELDS_EVENTCATEGORY'] = "Event Category";
	$content['LN_FIELDS_EVENTUSER'] = "Event User";
	$content['LN_FIELDS_WEBLOG_USER'] = "HTTP User";
	$content['LN_FIELDS_WEBLOG_METHOD'] = "Method";
	$content['LN_FIELDS_WEBLOG_URL'] = "URL";
	$content['LN_FIELDS_WEBLOG_QUERYSTRING'] = "Querystring";
	$content['LN_FIELDS_WEBLOG_PVER'] = "Version";
	$content['LN_FIELDS_WEBLOG_STATUS'] = "Status";
	$content['LN_FIELDS_WEBLOG_BYTESSEND'] = "Bytes Send";
	$content['LN_FIELDS_WEBLOG_REFERER'] = "Referer";
	$content['LN_FIELDS_WEBLOG_USERAGENT'] = "User Agent";

// Install Page
$content['LN_CFG_DBSERVER'] = "Datenbank Host";
$content['LN_CFG_DBPORT'] = "Datenbank Port";
$content['LN_CFG_DBNAME'] = "Datenbank Name";
$content['LN_CFG_DBPREF'] = "Tabellen Pr&auml;fix";
$content['LN_CFG_DBUSER'] = "Datenbank Benutzer";
$content['LN_CFG_DBPASSWORD'] = "Datenbank Passwort";
$content['LN_CFG_PARAMMISSING'] = "Die folgenden Parameter k&ouml;nnen nicht gefunden werden: ";
$content['LN_CFG_SOURCETYPE'] = "Quell-Typ";
$content['LN_CFG_DISKTYPEOPTIONS'] = "Disk-Typ Optionen";
$content['LN_CFG_LOGLINETYPE'] = "Logzeilentyp";
$content['LN_CFG_SYSLOGFILE'] = "Syslog Datei";
$content['LN_CFG_DATABASETYPEOPTIONS'] = "Datenbank Typ Optionen";
$content['LN_CFG_DBTABLETYPE'] = "Tabellen Typ";
$content['LN_CFG_DBSTORAGEENGINE'] = "Datenbank Typ";
$content['LN_CFG_DBTABLENAME'] = "Datenbank Tabellenname";
$content['LN_CFG_NAMEOFTHESOURCE'] = "Name der Quelle";
$content['LN_CFG_FIRSTSYSLOGSOURCE'] = "Erste Syslog Quelle";
	$content['LN_CFG_VIEW'] = "Select View";
	$content['LN_CFG_DBUSERLOGINREQUIRED'] = "Require user to be logged in";
	$content['LN_CFG_MSGPARSERS'] = "Message Parsers (comma seperated)";
	$content['LN_CFG_NORMALIZEMSG'] = "Normalize Message within Parsers";

// Details page
$content['LN_DETAILS_FORSYSLOGMSG'] = "Details für syslog-Nachrichten mit der ID";
$content['LN_DETAILS_DETAILSFORMSG'] = "Details für Nachrichten-ID";
$content['LN_DETAIL_BACKTOLIST'] = "Back to Listview";

	// Login Site
	$content['LN_LOGIN_DESCRIPTION'] = "Use this form to login into phpLogCon. ";
	$content['LN_LOGIN_TITLE'] = "Login";
	$content['LN_LOGIN_USERNAME'] = "Username";
	$content['LN_LOGIN_PASSWORD'] = "Password";
	$content['LN_LOGIN_SAVEASCOOKIE'] = "Stay logged on";
	$content['LN_LOGIN_ERRWRONGPASSWORD'] = "Wrong username or password!";
	$content['LN_LOGIN_USERPASSMISSING'] = "Username or password not given";

	// Install Site
	$content['LN_INSTALL_TITLETOP'] = "Installing phpLogCon Version %1 - Step %2";
	$content['LN_INSTALL_TITLE'] = "Installer Step %1";
	$content['LN_INSTALL_ERRORINSTALLED'] = 'phpLogCon is already configured!<br><br> If you want to reconfigure phpLogCon, either delete the current <B>config.php</B> or replace it with an empty file.<br><br>Click <A HREF="index.php">here</A> to return to pgpLogCon start page.';
	$content['LN_INSTALL_FILEORDIRNOTWRITEABLE'] = "At least one file or directory (or more) is not writeable, please check the file permissions (chmod 666)!";
	$content['LN_INSTALL_SAMPLECONFIGMISSING'] = "The sample configuration file '%1' is missing. You have not fully uploaded phplogcon.";
	$content['LN_INSTALL_ERRORCONNECTFAILED'] = "Database connect to '%1' failed! Please check Servername, Port, User and Password!";
	$content['LN_INSTALL_ERRORACCESSDENIED'] = "Cannot use the database  '%1'! If the database does not exists, create it or check user access permissions!";
	$content['LN_INSTALL_ERRORINVALIDDBFILE'] = "Error, invalid Database definition file (to short!), the file name is '%1'! Please check if the file was correctly uploaded.";
	$content['LN_INSTALL_ERRORINSQLCOMMANDS'] = "Error, invalid Database definition file (no sql statements found!), the file name is '%1'!<br> Please check if the file was not correctly uploaded, or contact the phpLogCon forums for assistance!";
	$content['LN_INSTALL_MISSINGUSERNAME'] = "Username needs to be specified";
	$content['LN_INSTALL_PASSWORDNOTMATCH'] = "Either the password does not match or is to short!";
	$content['LN_INSTALL_FAILEDTOOPENSYSLOGFILE'] = "Failed to open the syslog file '%1'! Check if the file exists and phplogcon has sufficient rights to it<br>";
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
	$content['LN_INSTALL_STEP1_TEXT'] = 'Before you start installing phpLogCon, the Installer setup has to check a few things first.<br>You may have to correct some file permissions first. <br><br>Click on <input type="submit" value="Next"> to start the Test!';
	$content['LN_INSTALL_STEP2_TEXT'] = "The following file permissions have been checked. Verify the results below! <br>You may use the <B>configure.sh</B> script from the <B>contrib</B> folder to set the permissions for you.";
	$content['LN_INSTALL_STEP3_TEXT'] = "In this step, you configure the basic configurations for phpLogCon.";
	$content['LN_INSTALL_STEP4_TEXT'] = 'If you reached this step, the database connection has been successfully verified!<br><br> The next step will be to create the necessary database tables used by the phpLogCon User System. This might take a while!<br> <b>WARNING</b>, if you have an existing phpLogCon installation in this database with the same tableprefix, all your data will be <b>OVERWRITTEN</b>! Make sure you are using a fresh database, or you want to overwrite your old phpLogCon database. <br><br><b>Click on <input type="submit" value="Next"> to start the creation of the tables</b>';
	$content['LN_INSTALL_STEP5_TEXT'] = "Tables have been created. Check the List below for possible Error's";
	$content['LN_INSTALL_STEP6_TEXT'] = "You are now about to create the initial phpLogCon User Account.<br> This will be the first administrative user, which will be needed to login into phpLogCon and access the Admin Center!";
	$content['LN_INSTALL_STEP8_TEXT'] = 'Congratulations! You have successfully installed phpLogCon :)! <br><br>Click <a href="index.php">here</a> to go to your installation.';
	$content['LN_INSTALL_PROGRESS'] = "Install Progress: ";
	$content['LN_INSTALL_FRONTEND'] = "Frontend Options";
	$content['LN_INSTALL_NUMOFSYSLOGS'] = "Number of syslog messages per page";
	$content['LN_INSTALL_MSGCHARLIMIT'] = "Message character limit for the main view";
	$content['LN_INSTALL_SHOWDETAILPOP'] = "Show message details popup";
	$content['LN_INSTALL_AUTORESOLVIP'] = "Automatically resolved IP Addresses (inline)";
	$content['LN_INSTALL_USERDBOPTIONS'] = "User Database Options";
	$content['LN_INSTALL_ENABLEUSERDB'] = "Enable User Database";
	$content['LN_INSTALL_SUCCESSSTATEMENTS'] = "Successfully executed statements:";
	$content['LN_INSTALL_FAILEDSTATEMENTS'] = "Failed statements:";
	$content['LN_INSTALL_STEP5_TEXT_NEXT'] = "You can now proceed to the <B>next</B> step adding the first phpLogCon Admin User!";
	$content['LN_INSTALL_STEP5_TEXT_FAILED'] = "At least one statement failed,see error reasons below";
	$content['LN_INSTALL_ERRORMSG'] = "Error Message";
	$content['LN_INSTALL_SQLSTATEMENT'] = "SQL Statement";
	$content['LN_INSTALL_CREATEUSER'] = "Create User Account";
	$content['LN_INSTALL_PASSWORD'] = "Password";
	$content['LN_INSTALL_PASSWORDREPEAT'] = "Repeat Password";
	$content['LN_INSTALL_SUCCESSCREATED'] = "Successfully created User";
	$content['LN_INSTALL_RECHECK'] = "ReCheck";
	$content['LN_INSTALL_FINISH'] = "Finish!";
	$content['LN_INSTALL_'] = "";

	// Converter Site
	$content['LN_CONVERT_TITLE'] = "Configuration Converter Step %1";
	$content['LN_CONVERT_NOTALLOWED'] = "Login";
	$content['LN_CONVERT_ERRORINSTALLED'] = 'phpLogCon is not allowed to convert your settings into the user database.<br><br> If you want to convert your convert your settings, add the variable following into your config.php: <br><b>$CFG[\'UserDBConvertAllowed\'] = true;</b><br><br> Click <A HREF="index.php">here</A> to return to pgpLogCon start page.';
	$content['LN_CONVERT_STEP1'] = "Step 1 - Informations";
	$content['LN_CONVERT_STEP2'] = "Step 2 - Create Tables";
	$content['LN_CONVERT_STEP3'] = "Step 3 - Check SQL Results";
	$content['LN_CONVERT_STEP4'] = "Step 4 - Creating the Main Useraccount";
	$content['LN_CONVERT_STEP5'] = "Step 5 - Import Settings into UserDB";
	$content['LN_CONVERT_TITLETOP'] = "Converting phpLogCon configuration settings - Step ";
	$content['LN_CONVERT_STEP1_TEXT'] = 'This script allows you to import your existing configuration from the <b>config.php</b> file. This includes frontend settings, data sources, custom views and custom searches. Do only perform this conversion if you did install phpLogCon without the UserDB System, and decided to enable it now. <br><br><b>ANY EXISTING INSTANCE OF A USERDB WILL BE OVERWRITTEN!</b><br><br><input type="submit" value="Click here"> to start the first conversion step!';
	$content['LN_CONVERT_STEP2_TEXT'] = 'The database connection has been successfully verified! <br><br>The next step will be to create the necessary database tables for the phpLogCon User System. This might take a while! <br><b>WARNING</b>, if you have an existing phpLogCon installation in this database with the same tableprefix, all your data will be <b>OVERWRITTEN</b>!<br> Make sure you are using a fresh database, or you want to overwrite your old phpLogCon database.<br><br><b>Click on <input type="submit" value="Next"> to start the creation of the tables</b>';
	$content['LN_CONVERT_STEP5_TEXT'] = '<input type="submit" value="Click here"> to start the last step of the conversion. In this step, your existing configuration from the <b>config.php</b> will be imported into the database.';
	$content['LN_CONVERT_STEP6'] = "Step 8 - Done";
	$content['LN_CONVERT_STEP6_TEXT'] = 'Congratulations! You have successfully converted your existing phpLogCon installation :)!<br><br>Important! Don\'t forget to REMOVE THE VARIABLES <b>$CFG[\'UserDBConvertAllowed\'] = true;</b> from your config.php file! <br><br>You can click <a href="index.php">here</a> to get to your phpLogConinstallation.';
	$content['LN_CONVERT_PROCESS'] = "Conversion Progress:";
	$content['LN_CONVERT_ERROR_SOURCEIMPORT'] = "Critical Error while importing the sources into the database, the SourceType '%1' is not supported by this phpLogCon Version.";

// Stats Site
	$content['LN_STATS_CHARTTITLE'] = "Top %1 '%2' sorted by messagecount";
	$content['LN_STATS_COUNTBY'] = "Messagecount by '%1'";
	$content['LN_STATS_GRAPH'] = "Graph";
	$content['LN_STATS_TOPRECORDS'] = "Maxrecords: %1";
	$content['LN_STATS_GENERATEDAT'] = "Generated at: %1";
	$content['LN_GEN_ERROR_INVALIDFIELD'] = "Invalid fieldname";
	$content['LN_GEN_ERROR_MISSINGCHARTFIELD'] = "Missing fieldname";
	$content['LN_GEN_ERROR_INVALIDTYPE'] = "Invalid or unknown chart type";
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

?>