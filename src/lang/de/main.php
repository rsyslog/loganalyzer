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

$content['LN_HIGHLIGHT'] = "Hightlight >>";
$content['LN_HIGHLIGHT_OFF'] = "Hightlight <<";
$content['LN_HIGHLIGHT_WORDS'] = "Hightlight-W&ouml;rter durch ein  Komma voneinander trennen";

$content['LN_ERROR_NORECORDS'] = "Es wurden keine syslog-Eintr&auml;ge gefunden.";

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

// Install Page
$content['LN_CFG_DBSERVER'] = "Datenbank Host";
$content['LN_CFG_DBPORT'] = "Datenbank Port";
$content['LN_CFG_DBNAME'] = "Datenbank Name";
$content['LN_CFG_DBPREF'] = "Tabellen Pr&auml;fix";
$content['LN_CFG_DBUSER'] = "Datenbank Benutzer";
$content['LN_CFG_DBPASSWORD'] = "Datenbank Passwort";
$content['LN_CFG_PARAMMISSING'] = "Die folgenden Parameter k&ouml;nnen nicht gefunden werden: ";
$content['LN_CFG_SOURCETYPE'] = "Source Type";
$content['LN_CFG_DISKTYPEOPTIONS'] = "Disk Type Options";
$content['LN_CFG_LOGLINETYPE'] = "Logline type";
$content['LN_CFG_SYSLOGFILE'] = "Syslog Datei";
$content['LN_CFG_DATABASETYPEOPTIONS'] = "Datenbank Typ Optionen";
$content['LN_CFG_DBTABLETYPE'] = "Tabellen Typ";
$content['LN_CFG_DBSTORAGEENGINE'] = "Datenbank Typ";
$content['LN_CFG_DBTABLENAME'] = "Datenbank Tabellenname";
$content['LN_CFG_NAMEOFTHESOURCE'] = "Name der Quelle";
$content['LN_CFG_FIRSTSYSLOGSOURCE'] = "Erste Syslog Quelle";

// Details page
$content['LN_DETAILS_FORSYSLOGMSG'] = "Details for the syslog messages with id";
$content['LN_DETAILS_DETAILSFORMSG'] = "Details for message id";
$content['LN_DETAIL_BACKTOLIST'] = "Back to Listview";

?>
