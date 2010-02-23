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

?>