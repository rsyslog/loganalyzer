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
$content['LN_MAINTITLE'] = "Hauptseite LogAnalyzer";
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
$content['LN_GEN_RECORDSPERPAGE'] = "Zeilen pro Seite";
$content['LN_GEN_PRECONFIGURED'] = "Vorkonfiguriert";
$content['LN_GEN_AVAILABLESEARCHES'] = "Verf&uuml;gbare Suchabfragen";
$content['LN_GEN_DB_MYSQL'] = "MySQL Server";
$content['LN_GEN_DB_MSSQL'] = "Microsoft SQL Server";
$content['LN_GEN_DB_ODBC'] = "ODBC Datenbank Quelle";
$content['LN_GEN_DB_PGSQL'] = "PostgreSQL";
$content['LN_GEN_DB_OCI'] = "Oracle Call Interface";
$content['LN_GEN_DB_DB2'] = "	IBM DB2";
$content['LN_GEN_DB_FIREBIRD'] = "Firebird/Interbase 6";
$content['LN_GEN_DB_INFORMIX'] = "IBM Informix Dynamic Server";
$content['LN_GEN_DB_SQLITE'] = "SQLite 2";
$content['LN_GEN_SELECTVIEW'] = "Anzeige ausw&auml;hlen";
$content['LN_GEN_CRITERROR_UNKNOWNTYPE'] = "Der Quell-Typ '%1' wird aktuell von LogAnalyzer nicht unterst&uum;tzt. Dies ist ein kritischer Fehler, bitte passen Sie Ihre Konfiguration an.";
$content['LN_GEN_ERRORRETURNPREV'] = "Bitte klicken Sie hier, um auf die vorhergehende Seite zur&uuml;ckzukehren.";
$content['LN_GEN_ERRORDETAILS'] = "Fehler Details:";
$content['LN_SOURCES_ERROR_WITHINSOURCE'] = "Die &Uuml;berpr&uuml;fung der Quelle '%1' endete mit dem Fehler: '%2'";
$content['LN_SOURCES_ERROR_EXTRAMSG'] = "Extra Fehler Details:<br>%1";
$content['LN_ERROR_NORECORDS'] = "Es wurden keine syslog-Eintr&auml;ge gefunden.";
$content['LN_ERROR_FILE_NOT_FOUND'] = "Die Syslog Datei konnte nicht gefunden werden";
$content['LN_ERROR_FILE_NOT_READABLE'] = "Die Syslog Datei ist nicht lesbar, lesender Zugriff ist evtl. nicht gestattet";
$content['LN_ERROR_UNKNOWN'] = "Ein unbekannter oder unerwarteter Fehler ist aufgetreten. (Fehler Code '%1')";
$content['LN_ERROR_FILE_EOF'] = "Ende der Datei erreicht";
$content['LN_ERROR_FILE_BOF'] = "Anfang der Datei erreicht";
$content['LN_ERROR_FILE_CANT_CLOSE'] = "Das Schliessen der Datei ist nicht m&ouml;glich";
$content['LN_ERROR_UNDEFINED'] = "Unerwarteter Fehler";
$content['LN_ERROR_EOS'] = "Ende des Datenstroms erreicht";
$content['LN_ERROR_FILTER_NOT_MATCH'] = "Der Filter ergab keine &Uuml;bereinstimmung im Ergbenis";
$content['LN_ERROR_DB_CONNECTFAILED'] = "Die Verbindung zum Datenbank Server ist fehlgeschlagen";
$content['LN_ERROR_DB_CANNOTSELECTDB'] = "Die in der Konfiguration angegeben Datenbank konnte nicht gefunden werden";
$content['LN_ERROR_DB_QUERYFAILED'] = "Die Datenbank-Abfrage konnte nicht ausgef&uuml;hrt werden";
$content['LN_ERROR_DB_NOPROPERTIES'] = "Keine Einstellungen zur Datenbank gefunden";
$content['LN_ERROR_DB_INVALIDDBMAPPING'] = "Ung&uuml;ltige Datenfeld Zuordnung";
$content['LN_ERROR_DB_INVALIDDBDRIVER'] = "Ung&uuml;ltiger Datenbank Treiber ausgew&auml;hlt";
$content['LN_ERROR_DB_TABLENOTFOUND'] = "Die angegebene Tabelle konnte nicht gefunden werden, evtl. ist der Eintrag falsch geschrieben oder Gross- und Kleinschreibung wurden nicht beachtet.";
$content['LN_ERROR_DB_DBFIELDNOTFOUND'] = "Die Datenbankfeldzuordnung ist fehlerhaft, es konnte mindestens ein Feld nicht gefunden werden.";
$content['LN_GEN_SELECTEXPORT'] = "&gt; Exportformat ausw&auml;hlen &lt;";
$content['LN_GEN_EXPORT_CVS'] = "CSV (Komma unterteilt)";
$content['LN_GEN_EXPORT_XML'] = "XML";
$content['LN_GEN_EXPORT_PDF'] = "PDF";
$content['LN_GEN_ERROR_EXPORING'] = "Fehler beim exportieren der Daten";
$content['LN_GEN_ERROR_INVALIDEXPORTTYPE'] = "Ung&uuml;ltiges Export Format ausgew&auml;htl, oder andere Parameter sind ung&uuml;ltig.";
$content['LN_GEN_ERROR_SOURCENOTFOUND'] = "Die Quelle mit der ID '%1' konnte nicht gefunden werden.";
$content['LN_GEN_MOREINFORMATION'] = "Mehr Informationen";
$content['LN_FOOTER_PAGERENDERED'] = "Siete gerenderet in";
$content['LN_FOOTER_DBQUERIES'] = "DB Abfragen";
$content['LN_FOOTER_GZIPENABLED'] = "GZIP erm&ouml;glichen";
$content['LN_FOOTER_SCRIPTTIMEOUT'] = "Max. Skript Laufzeit";
$content['LN_FOOTER_SECONDS'] = "Sekunden";
$content['LN_WARNING_LOGSTREAMTITLE'] = "Log-Datenstrom Warnung";
$content['LN_WARNING_LOGSTREAMDISK_TIMEOUT'] = "Beim lesen des Log-Datenstroms, hat das PHP-Skript die max. Laufzeit erreicht und wurde abgebrochen.<br><br> Falls Sie dennoch die Ausf&uuml;hrung erm&ouml;glichen wollen, bitte erh&ouml;hen Sie die max. LogAnalyzer Skript Laufzeit in Ihrer config.php. Falls das Benutzersystem installiert ist, k&ouml;nnen Sie dies im Bereich Administration einstellen.";
$content['LN_WARNING_DBUPGRADE'] = "Datenbank Upgrade erforderlich";
$content['LN_WARNING_DBUPGRADE_TEXT'] = "Die aktuell installierte Datenbankversion ist '%1'.<br>Ein Update auf Version '%2' ist verf&uuml;gbar.";
$content['LN_ERROR_REDIRECTABORTED'] = 'Automatiche R&uuml;ckkehr zur Seite <a href="%1">page</a> wurde abgebrochen, da ein interner Fehler aufgetrete ist. Bitte beachten Sie die weiteren Informationen zu diesem Fehler &uuml;ber dieser Meldung und/oder nehmen Sie Kontakt zum Support-Forum auf, falls Sie Hilfe ben&ouml;tigen.';
$content['LN_DEBUGLEVEL'] = "Debug Level";
$content['LN_DEBUGMESSAGE'] = "Debug Meldung";
$content['LN_GEN_REPORT_OUTPUT_HTML'] = "HTML Format";
$content['LN_GEN_REPORT_OUTPUT_PDF'] = "PDF Format";
$content['LN_GEN_UNKNOWN'] = "Unbekannt";

// Topmenu Entries
$content['LN_MENU_SEARCH'] = "Suchen";
$content['LN_MENU_SHOWEVENTS'] = "Meldungen";
$content['LN_MENU_HELP'] = "Hilfe";
$content['LN_MENU_DOC'] = "Dokumentation";
$content['LN_MENU_FORUM'] = "Support Forum";
$content['LN_MENU_WIKI'] = "LogAnalyzer Wiki";
$content['LN_MENU_PROSERVICES'] = "Professionelle Unterst&uuml;tzung";
$content['LN_MENU_SEARCHINKB'] = "Suche in der Wissensdatenbank";
$content['LN_MENU_LOGIN'] = "Anmeldung";
$content['LN_MENU_ADMINCENTER'] = "Administration";
$content['LN_MENU_LOGOFF'] = "Abmeldung";
$content['LN_MENU_LOGGEDINAS'] = "Angemeldet als ";
$content['LN_MENU_MAXVIEW'] = "Anzeige maximieren";
$content['LN_MENU_NORMALVIEW'] = "Standard Anzeige";
$content['LN_MENU_STATISTICS'] = "Statistiken";
$content['LN_MENU_CLICKTOEXPANDMENU'] = "Klicken Sie das Icon um das Men&uuml; anzuzeigen";


// Index Site
$content['LN_ERROR_INSTALLFILEREMINDER'] = "Warnung! Sie haben das Installationsskript 'install.php' noch nicht aus dem LogAnalyzer Hauptordner entfernt!";
$content['LN_TOP_NUM'] = "No.";
$content['LN_TOP_UID'] = "uID";
$content['LN_GRID_POPUPDETAILS'] = "Details f&uulm;r die Syslog-Meldung mit der ID '%1'";

$content['LN_SEARCH_USETHISBLA'] = "Bitte ber&uuml;cksichtigen Sie bei Ihrer Suche folgende Kriterien";
$content['LN_SEARCH_FILTER'] = "Suche (Filter):";
$content['LN_SEARCH_ADVANCED'] = "Erweiterte Suche";
$content['LN_SEARCH'] = "Suche";
$content['LN_SEARCH_RESET'] = "Suche zur&uuml;cksetzen";
$content['LN_SEARCH_PERFORMADVANCED'] = "Erweiterte Suche starten";
$content['LN_VIEW_MESSAGECENTERED'] = "Zur&uuml;ck zur ungefilterten Ansicht, mit dieser Meldung als erster";
$content['LN_VIEW_RELATEDMSG'] = "Anzeige vorheriger Syslog Meldungen ";
$content['LN_VIEW_FILTERFOR'] = "Filtere Meldungen nach ";
$content['LN_VIEW_SEARCHFOR'] = "Suche online nach ";
$content['LN_VIEW_SEARCHFORGOOGLE'] = "Durchsuche Google nach ";
$content['LN_GEN_MESSAGEDETAILS'] = "Meldungsdetails";
$content['LN_VIEW_ADDTOFILTER'] = "F&uuml; '%1' zur Filterliste hinzu";
$content['LN_VIEW_EXCLUDEFILTER'] = "Entferne '%1' von der Filterliste";
$content['LN_VIEW_FILTERFORONLY'] = "Filtere nur nach '%1'";
$content['LN_VIEW_SHOWALLBUT'] = "Anzeige aller Meldungen, ausgenommen '%1'";
$content['LN_VIEW_VISITLINK'] = "&Ouml;ffne Link '%1' in neuem Fenster";

$content['LN_HIGHLIGHT'] = "Hervorhebung >>";
$content['LN_HIGHLIGHT_OFF'] = "Hervorhebung <<";
$content['LN_HIGHLIGHT_WORDS'] = "Hervorgehobene W&ouml;rter durch ein Komma voneinander trennen";

$content['LN_AUTORELOAD'] = "Auto. neu laden";
$content['LN_AUTORELOAD_DISABLED'] = "Auto. neu laden deaktiviert";
$content['LN_AUTORELOAD_PRECONFIGURED'] = "Konfiguriere auto. neu laden ";
$content['LN_AUTORELOAD_SECONDS'] = "Sekunden";
$content['LN_AUTORELOAD_MINUTES'] = "Minuten";

// Filter Options
$content['LN_FILTER_DATE'] = "Zeitliche Abgrenzung";
$content['LN_FILTER_DATEMODE'] = "Zeitraum ausw&auml;hlen";
$content['LN_DATEMODE_ALL'] = "Kompletter Zeitraum";
$content['LN_DATEMODE_RANGE'] = "Zeitspanne";
$content['LN_DATEMODE_LASTX'] = "Seit heute, x Uhr";
$content['LN_FILTER_DATEFROM'] = "Zeitraum seit x";
$content['LN_FILTER_DATETO'] = "Zeitraum bis x";
$content['LN_FILTER_TIMEFROM'] = "Time range from";
$content['LN_FILTER_TIMETO'] = "Time range to";
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
$content['LN_FILTER_MESSAGETYPE'] = "Meldungs Typ";

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
$content['LN_CFG_VIEW'] = "Anzeige ausw&auml;hlen";
$content['LN_CFG_DBUSERLOGINREQUIRED'] = "Erfordert eine Benutzer-Anmeldung";
$content['LN_CFG_MSGPARSERS'] = "Meldungs Parser (Komma getrent)";
$content['LN_CFG_NORMALIZEMSG'] = "Standard Meldunganzeige mit Parser";
$content['LN_CFG_SKIPUNPARSEABLE'] = "&Uuml;berspringe nicht lesbare Meldungen (Nur m&ouml;glich, wenn ein Parsen konfiguriert wurde!)";
$content['LN_CFG_DBRECORDSPERQUERY'] = "Anzahl der Datenbankabfragen";

// Details page
$content['LN_DETAILS_FORSYSLOGMSG'] = "Details f&uuml;r Syslog-Nachrichten mit der ID";
$content['LN_DETAILS_DETAILSFORMSG'] = "Details f&uuml;r Nachrichten-ID";
$content['LN_DETAIL_BACKTOLIST'] = "Zur&uuml;ck zur Listenansicht";

// Login Site
$content['LN_LOGIN_DESCRIPTION'] = "Bitte geben Sie Ihren Benutzernamen und Ihr dazugeh&ouml;riges Passwort ein, um sich bei LogAnalyzer anzumelden. ";
$content['LN_LOGIN_TITLE'] = "Anmeldung";
$content['LN_LOGIN_USERNAME'] = "Benutzername";
$content['LN_LOGIN_PASSWORD'] = "Passwort";
$content['LN_LOGIN_SAVEASCOOKIE'] = "Angemeldet bleiben";
$content['LN_LOGIN_ERRWRONGPASSWORD'] = "Falscher Benutzername oder falsches Passwort!";
$content['LN_LOGIN_USERPASSMISSING'] = "Benutzername und/oder Passwort wurden nicht eingegeben!";

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
$content['LN_CONVERT_STEP6_TEXT'] = 'Congratulations! You have successfully converted your existing LogAnalyzer installation :)!<br><br>Important! Don\'t forget to REMOVE THE VARIABLES <b>$CFG[\'UserDBConvertAllowed\'] = true;</b> from your config.php file! <br><br>You can click <a href="index.php">here</a> to get to your LogAnalyzer installation.';
$content['LN_CONVERT_PROCESS'] = "Conversion Progress:";
$content['LN_CONVERT_ERROR_SOURCEIMPORT'] = "Critical Error while importing the sources into the database, the SourceType '%1' is not supported by this LogAnalyzer Version.";

// Stats Site
$content['LN_STATS_CHARTTITLE'] = "Top %1 '%2' sortiert nach Meldungsanzahl";
$content['LN_STATS_COUNTBY'] = "Meldungsanzahl '%1'";
$content['LN_STATS_GRAPH'] = "Grafik";
$content['LN_STATS_TOPRECORDS'] = "Max. Anzahl: %1";
$content['LN_STATS_GENERATEDAT'] = "Erstellumgsdatum: %1";
$content['LN_GEN_ERROR_INVALIDFIELD'] = "Ung&uuml;tiger Feldname";
$content['LN_GEN_ERROR_MISSINGCHARTFIELD'] = "Fehlender Feldname";
$content['LN_GEN_ERROR_INVALIDTYPE'] = "Ung&uuml;ltiger oder unbekannter Chart Typ";
$content['LN_ERROR_CHARTS_NOTCONFIGURED'] = "Es wurden kein Chart konfiguriert.";
$content['LN_CHART_TYPE'] = "Chart Typ";
$content['LN_CHART_WIDTH'] = "Chart Breite";
$content['LN_CHART_FIELD'] = "Chart Felder";
$content['LN_CHART_MAXRECORDS'] = "Top Anzahl Summe";
$content['LN_CHART_SHOWPERCENT'] = "Prozentuale Anzeige";
$content['LN_CHART_TYPE_CAKE'] = "Kuchen (Pie)";
$content['LN_CHART_TYPE_BARS_VERTICAL'] = "Balken vertikal";
$content['LN_CHART_TYPE_BARS_HORIZONTAL'] = "Balken horizontal";
$content['LN_STATS_WARNINGDISPLAY'] = "Das Erstellen von Grafiken &uuml;ber eine grosse Anzahl von Datens&auml;tzen kann sehr Prozessortlastig sein.<br>Dies wird in nachfolgenden Versionen noch weiter optimiert werden.<br>Falls die Erstellung der Grafiken zu viel Prozessortzeit in Anspruch nehmen sollte, bitte brechen Sie die Erstellung einfach ab.";

// asktheoracle site
$content['LN_ORACLE_TITLE'] = "Fragen Sie das Orakel nach '%1'";
$content['LN_ORACLE_HELP_FOR'] = "Das sind die Links welche das Orakel f&uuml;r Sie ermittelt hat";
$content['LN_ORACLE_HELP_TEXT'] = "<br><h3>Sie haben das Orakel nach mehr Informationen &uuml;ber '%1' - '%2' gefragt.</h3>
<p align=\"left\">Diese Seite erm&ouml;glicht es Ihnen eine Suche &uuml;ber verschiedene Log-Quellen zu starten. %3
<br>Die Idee ist es, einfach nach Informationen &uuml;ber spezifizierte Angaben allerorts zu suchen, egal wo diese vorkommen.
</p>
";
$content['LN_ORACLE_HELP_TEXT_EXTERNAL'] = "Es wird eine Suche &uuml;ber externe Datenquellen erm&ouml;glichti.";
$content['LN_ORACLE_HELP_DETAIL'] = "Link &Uuml;bersicht f&uuml;r '%1' - '%2'";
$content['LN_ORACLE_SEARCH'] = "Suche"; // in '%1' Field";
$content['LN_ORACLE_SOURCENAME'] = "Quellen Name";
$content['LN_ORACLE_FIELD'] = "Feld";
$content['LN_ORACLE_ONLINESEARCH'] = "Online Suche";
$content['LN_ORACLE_WHOIS'] = "WHOIS Abfrage f&uuml;r '%1' - '%2'";

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