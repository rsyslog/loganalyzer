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
	* Translation by Luigi Rosa
	*       [mailto:lrosa@venus.it]
	*
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
$content['LN_MAINTITLE'] = "LogAnalyzer pagina principale";
$content['LN_MAIN_SELECTSTYLE'] = "Stile";
$content['LN_GEN_LANGUAGE'] = "Lingua";
$content['LN_GEN_SELECTSOURCE'] = "Fonte";
$content['LN_GEN_MOREPAGES'] = "&Egrave; disponibile pi&ugrave; di una pagina";
$content['LN_GEN_FIRSTPAGE'] = "Prima pagina";
$content['LN_GEN_LASTPAGE'] = "Ultima pagina";
$content['LN_GEN_NEXTPAGE'] = "Pagina successiva";
$content['LN_GEN_PREVIOUSPAGE'] = "Pagina precedente";
$content['LN_GEN_RECORDCOUNT'] = "Record totali";
$content['LN_GEN_PAGERSIZE'] = "Record per pagina";
$content['LN_GEN_PAGE'] = "Pagina";
$content['LN_GEN_PREDEFINEDSEARCHES'] = "Ricerche predefinite";
$content['LN_GEN_SOURCE_DISK'] = "File su disco";
$content['LN_GEN_SOURCE_DB'] = "MYSQL nativo";
$content['LN_GEN_SOURCE_PDO'] = "Database (PDO)";
$content['LN_GEN_RECORDSPERPAGE'] = "record per pagina";
$content['LN_GEN_PRECONFIGURED'] = "Preconfigurato";
$content['LN_GEN_AVAILABLESEARCHES'] = "Ricerche disponibili";
$content['LN_GEN_DB_MYSQL'] = "Mysql Server";
$content['LN_GEN_DB_MSSQL'] = "Microsoft SQL Server";
$content['LN_GEN_DB_ODBC'] = "Database ODBC";
$content['LN_GEN_DB_PGSQL'] = "PostgreSQL";
$content['LN_GEN_DB_OCI'] = "Oracle Call Interface";
$content['LN_GEN_DB_DB2'] = "   IBM DB2";
$content['LN_GEN_DB_FIREBIRD'] = "Firebird/Interbase 6";
$content['LN_GEN_DB_INFORMIX'] = "IBM Informix Dynamic Server";
$content['LN_GEN_DB_SQLITE'] = "SQLite 2";
$content['LN_GEN_SELECTVIEW'] = "Visualizzaizone";


// Main Index Site
$content['LN_ERROR_INSTALLFILEREMINDER'] = "Attenzione! Non hai ancora rimosso il file 'install.php' dalla directory principale di LogAnalyzer!";
$content['LN_TOP_NUM'] = "Nr.";
$content['LN_TOP_UID'] = "uID";
$content['LN_GRID_POPUPDETAILS'] = "Dettagli del messaggio di syslog con ID '%1'";

$content['LN_SEARCH_USETHISBLA'] = "Compila il form sottostante, qui apparir&agrave; il filtro";
$content['LN_SEARCH_FILTER'] = "Filtro di ricerca:";
$content['LN_SEARCH_ADVANCED'] = "Ricerca avanzata";
$content['LN_SEARCH'] = "Cerca";
$content['LN_SEARCH_RESET'] = "Annulla ricerca";
$content['LN_SEARCH_PERFORMADVANCED'] = "Esegui questa ricerca avanzata";
$content['LN_VIEW_MESSAGECENTERED'] = "Torna alla visualizzazione non filtrata con questo messaggio all'inizio";
$content['LN_VIEW_RELATEDMSG'] = "Visualizza i messaggi di syslog correlati";
$content['LN_VIEW_FILTERFOR'] = "Filtra i messaggi per ";
$content['LN_VIEW_SEARCHFOR'] = "Ricerca online di ";
$content['LN_VIEW_SEARCHFORGOOGLE'] = "Cerca su Google ";
$content['LN_GEN_MESSAGEDETAILS'] = "Dettagli del messaggio";

$content['LN_HIGHLIGHT'] = "Evidenzia >>";
$content['LN_HIGHLIGHT_OFF'] = "Evidenzia <<";
$content['LN_HIGHLIGHT_WORDS'] = "Parole da evidenziare separate da virgola";

$content['LN_AUTORELOAD'] = "Aggiornamento automatico";
$content['LN_AUTORELOAD_DISABLED'] = "Disabilitato";
$content['LN_AUTORELOAD_PRECONFIGURED'] = "Aggiornamento automatico preconfigurato ";
$content['LN_AUTORELOAD_SECONDS'] = "secondi";
$content['LN_AUTORELOAD_MINUTES'] = "minuti";

$content['LN_ERROR_NORECORDS'] = "Nessun record di syslog trovato.";

// Filter Options
$content['LN_FILTER_DATE'] = "Intervallo data/ora";
$content['LN_FILTER_DATEMODE'] = "Seleziona il tipo di intervallo";
$content['LN_DATEMODE_ALL'] = "Qualsiasi data/ora";
$content['LN_DATEMODE_RANGE'] = "Intervallo orario";
$content['LN_DATEMODE_LASTX'] = "Ultimi x ore/giorni";
$content['LN_FILTER_DATEFROM'] = "Dalla data";
$content['LN_FILTER_DATETO'] = "Alla data";
$content['LN_FILTER_TIMEFROM'] = "Time range from";
$content['LN_FILTER_TIMETO'] = "Time range to";
$content['LN_FILTER_DATELASTX'] = "Ultime ore oppure ultimi giorni";
$content['LN_FILTER_ADD2SEARCH'] = "Aggiungi alla ricerca";
$content['LN_DATE_LASTX_HOUR'] = "Ultima ora";
$content['LN_DATE_LASTX_12HOURS'] = "Ultime 12 ore";
$content['LN_DATE_LASTX_24HOURS'] = "Ultime 24 ore";
$content['LN_DATE_LASTX_7DAYS'] = "Ultimi 7 giorni";
$content['LN_DATE_LASTX_31DAYS'] = "Ultimi 31 giorni";
$content['LN_FILTER_FACILITY'] = "Facility syslog";
$content['LN_FILTER_SEVERITY'] = "Severit&agrave; syslog";
$content['LN_FILTER_OTHERS'] = "Altri filtri";
$content['LN_FILTER_MESSAGE'] = "messaggio syslog";
$content['LN_FILTER_SYSLOGTAG'] = "Tag syslog";
$content['LN_FILTER_SOURCE'] = "Fonte (nome host)";
$content['LN_FILTER_MESSAGETYPE'] = "Tipo del messaggio";

// Field Captions
$content['LN_FIELDS_DATE'] = "Data";
$content['LN_FIELDS_FACILITY'] = "Facility";
$content['LN_FIELDS_SEVERITY'] = "iSeverit&agrave;";
$content['LN_FIELDS_HOST'] = "Host";
$content['LN_FIELDS_SYSLOGTAG'] = "Tag syslog";
$content['LN_FIELDS_PROCESSID'] = "ID processo";
$content['LN_FIELDS_MESSAGETYPE'] = "Tipo messaggio";
$content['LN_FIELDS_UID'] = "uID";
$content['LN_FIELDS_MESSAGE'] = "Messaggio";
$content['LN_FIELDS_EVENTID'] = "ID evento";
$content['LN_FIELDS_EVENTLOGTYPE'] = "Tipo log eventi";
$content['LN_FIELDS_EVENTSOURCE'] = "Fonte";
$content['LN_FIELDS_EVENTCATEGORY'] = "Categoria";
$content['LN_FIELDS_EVENTUSER'] = "Utente";

// Install Page
$content['LN_CFG_DBSERVER'] = "Host";
$content['LN_CFG_DBPORT'] = "Porta";
$content['LN_CFG_DBNAME'] = "Nome del database";
$content['LN_CFG_DBPREF'] = "Prefisso della tabella";
$content['LN_CFG_DBUSER'] = "Utente";
$content['LN_CFG_DBPASSWORD'] = "Password";
$content['LN_CFG_PARAMMISSING'] = "Mancano questi parametri: ";
$content['LN_CFG_SOURCETYPE'] = "Tipo della fonte";
$content['LN_CFG_DISKTYPEOPTIONS'] = "Opzioni per il disco";
$content['LN_CFG_LOGLINETYPE'] = "Tipo del log";
$content['LN_CFG_SYSLOGFILE'] = "File di syslog";
$content['LN_CFG_DATABASETYPEOPTIONS'] = "Opzioni del database";
$content['LN_CFG_DBTABLETYPE'] = "Tipo della tabella";
$content['LN_CFG_DBSTORAGEENGINE'] = "Motore di archiviazione su database";
$content['LN_CFG_DBTABLENAME'] = "Nome della tabella";
$content['LN_CFG_NAMEOFTHESOURCE'] = "Nome della fonte";
$content['LN_CFG_FIRSTSYSLOGSOURCE'] = "Prima fonte di syslog";
$content['LN_CFG_DBROWCOUNTING'] = "Abilita il conteggio delle righe";
$content['LN_CFG_VIEW'] = "Seleziona il tipo di vista";

// Details page
$content['LN_DETAILS_FORSYSLOGMSG'] = "Dettagli dei messaggi di syslog con id";
$content['LN_DETAILS_DETAILSFORMSG'] = "Dettaglio del messaggio con id";
$content['LN_DETAIL_BACKTOLIST'] = "Torna all'elenco";

// Login Site
$content['LN_LOGIN_DESCRIPTION'] = "Use this form to login into LogAnalyzer. ";
$content['LN_LOGIN_TITLE'] = "Login";
$content['LN_LOGIN_USERNAME'] = "Username";
$content['LN_LOGIN_PASSWORD'] = "Password";
$content['LN_LOGIN_SAVEASCOOKIE'] = "Stay logged on";
$content['LN_LOGIN_ERRWRONGPASSWORD'] = "Wrong username or password!";
$content['LN_LOGIN_USERPASSMISSING'] = "Username or password not given";

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

$content['LN_CMD_RUNREPORT'] = "Generating saved report '%1'";
$content['LN_CMD_REPORTIDNOTFOUND'] = "Invalid Report ID '%1'";
$content['LN_CMD_SAVEDREPORTIDNOTFOUND'] = "Invalid SavedReport ID '%1'";
$content['LN_CMD_NOREPORTID'] = "Missing Report ID";
$content['LN_CMD_NOSAVEDREPORTID'] = "Missing SavedReport ID";
$content['LN_CMD_NOCMDPROMPT'] = "Error, this script can only be run from the command prompt.";

?>