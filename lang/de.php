<?php

/*#### #### #### #### #### #### #### #### #### #### 

phpLogCon - A Web Interface to Log Data.
Copyright (C) 2003  Adiscon GmbH

This program is free software); you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation); either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY); without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program); if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

If you have questions about phpLogCon in general, please email info@adiscon.com. To learn more about phpLogCon, please visit 
http://www.phplogcon.com.

This Project was intiated and is maintened by Rainer Gerhards <rgerhards@hq.adiscon.com>. See AUTHORS to learn who helped make 
it become a reality.

*/#### #### #### #### #### #### #### #### #### #### 

/*
* German language file for phpLogCon
*/

define('_MSG001', 'Willkommen bei phpLogCon! Bittle loggen Sie sich zuerst mit Ihrem benutzernamen und Passwort ein');
define('_MSGUsrnam', 'Benutzername');
define('_MSGpas', 'Passwort');
define('_MSGLogSuc', 'Login erfolgreich');
define('_MSGWel', 'Willkommen bei phpLogCon');
define('_MSGChoOpt', '! Wählen sie unter den Optionen aus dem obrigen Menü aus');
define('_MSGQuiInf', 'Kurz-Info (Unter Berücksichtigung der Filtereinstellungen)');
define('_MSGTop5', 'Top fünf Logs (Unter Berücksichtigung der Filtereinstellungen)');
define('_MSGNoData', 'Keine Daten gefunden');
define('_MSGDate', 'Datum');
define('_MSGFac', 'Facility');
define('_MSGMsg', 'Nachricht');
define('_MSGAccDen', 'Zugang nicht gestattet');
define('_MSGFalLog', 'Sie sind kein registrierter Benutzer oder Sie haben ein falsches Passwort eingegeben');
define('_MSGBac2Ind', 'Zurück zum Index');
define('_MSGSesExp', 'Session abgelaufen');
define('_MSGSesExpQue', 'Session abgelaufen. Haben Sie vielleicht das letzte mal vergessen sich auszuloggen');
define('_MSGReLog', 'Zurück zum Index um sich neu neu einzuloggen');
define('_MSGShwEvn', 'Zeige Events');
define('_MSGNoDBCon', 'Verbindung zum Datenbank-Server fehlgeschlagen');
define('_MSGChDB', 'Auswahl der Datenbank fehlgeschlagen');
define('_MSGInvQur', 'Ungültige Abfrage');
define('_MSGNoRes', 'Kein gültiges Datenbank-Ergebnis');
define('_MSGNoDBHan', 'Kein gültiger Datenbank-Verbindungs-Handle');
define('_MSGLogout', 'LogOut');
define('_MSGSrcExp', 'Nach Ausdruck suchen');
define('_MSGSrc', 'Suche');
define('_MSGColExp', 'Ausdruck färben');
define('_MSGinCol', ' in dieser Farbe: ');
define('_MSGBrw', 'Durchsuchen');
define('_MSGFilConf', 'Filter Konfiguration');
define('_MSGUsrConf', 'Benutzer Konfiguration');
define('_MSGBscSet', 'Generelle Einstellungen');
define('_MSGConSet', 'Verbindungs Einstellungen');
define('_MSGConMod', 'Verbindungsmodus');
define('_MSGFilCon', 'Filter Bedingungen');
define('_MSGEvnDat', 'Event Datum');
define('_MSGOrdBy', 'Sortieren nach');
define('_MSGRef', 'Aktualisierung');
define('_MSGInfUI', 'InfoEinheit');
define('_MSGOth', 'Andere');
define('_MSGPri', 'Priorität');
define('_MSGFilSet', 'Filter Einstellungen');
define('_MSGUsrSet', 'Benutzer Einstellungen');
define('_MSGFilOpt', 'Filter Optionen');
define('_MSGSwiEvnMan', 'Event Datum manuell auswählen');
define('_MSGSwiEvnPre', 'Event Datum vordefiniert auswählen');
define('_MSGShwEvnDet', 'Zeige Event Details');
define('_MSGBck', 'zurück');
define('_MSGEvnID', 'EventID');
define('_MSGClickBrw', ' (Klick um die MonitorWare Datenbank zu durchsuchen) :: (Oder durchsuche ');
define('_MSGEvnCat', 'EventKategorie');
define('_MSGEvnUsr', 'EventBenutzer');
define('_MSGFrmHos', 'VonHost');
define('_MSGNTSev', 'NTSeverity');
define('_MSGRecAt', 'EmpfangenAm');
define('_MSGDevRep', 'VomGerätGemeldeteZeit');
define('_MSGImp', 'Wichtigkeit');
define('_MSGEvn', 'Event');
define('_MSGTo', 'bis');
define('_MSGFrm', 'von');
define('_MSGLogPg', 'Logs pro Seite');
define('_MSGHom', 'Startseite');
define('_MSGHlp', 'Hilfe');
define('_MSGFOpt', 'Filter Optionen');
define('_MSGUOpt', 'Benutzer Optionen');
define('_MSGEvnLogTyp', 'EventLogArt');
define('_MSGEvnSrc', 'EventQuelle');
define('_MSG2dy', 'heute');
define('_MSGYester', 'nur gestern');
define('_MSGThsH', 'aktuelle Stunde');
define('_MSGLstH', 'letzte Stunde');
define('_MSGL2stH', 'letzten 2 Stunden');
define('_MSGL5stH', 'letzten 5 Stunden');
define('_MSGL12stH', 'letzten 12 Stunden');
define('_MSGL2d', 'letzten 2 Tage');
define('_MSGL3d', 'letzten 3 Tage');
define('_MSGLw', 'letzte Woche');
define('_MSGFacDat', 'Facility und Datum');
define('_MSGPriDat', 'Priorität und Datum');
define('_MSGNoRef', 'nicht aktualisieren');
define('_MSGE10s', 'alle 10 sek');
define('_MSGE30s', 'alle 30 sek');
define('_MSGEm', 'jede min');
define('_MSGE2m', 'alle 2 min');
define('_MSGE15m', 'alle 15 min');
define('_MSGEn', 'Englisch');
define('_MSGDe', 'Deutsch');
define('_MSGFav', 'Favoriten');
define('_MSGDel', 'Löschen');
define('_MSGNoFav', 'Keine Favoriten gefunden');
define('_MSGNewFav', 'Neuer Favorit');
define('_MSGSiten', 'Seitenname');
define('_MSGAdd', 'Hinzufügen');
define('_MSGChg', 'Ändern');
define('_MSGEnv', 'Umgebung');
define('_MSGUsrInt', 'Benutzer Maske');
define('_MSGUEna', 'Aktiviert');
define('_MSGUDsa', 'Deaktiviert');
define('_MSGNamInvChr', 'Name und/oder Passwort enthielten ungültige Zeichen');
define('_MSGSitInvChr', 'Seitenname und/oder Adresse enthielten ungültige Zeichen');
define('_MSGESec', 'jede Sekunde');
define('_MSGE5Sec', 'alle 5 Sek');
define('_MSGE20Sec', 'alle 20 Sek');
define('_MSGRed', 'Rot');
define('_MSGBlue', 'Blau');
define('_MSGGreen', 'Grün');
define('_MSGYel', 'Gelb');
define('_MSGOra', 'Orange');
define('_MSGFilHost', 'Suchen nach IP/Computer');
define('_MSGSearchMsg', 'Nachricht muss folgendes enthalten');
define('_MSGDisIU', 'Zeige Info Einheiten');
define('_MSGEnbQF', 'Anzeigen von Quick-Filtern');
define('_MSGSty', 'Aussehen');
define('_MSGHost', 'Computer');
define('_MSGPRI0', 'EMERGENCY');
define('_MSGPRI1', 'ALERT');
define('_MSGPRI2', 'CRITICAL');
define('_MSGPRI3', 'ERROR');
define('_MSGPRI4', 'WARNING');
define('_MSGPRI5', 'NOTICE');
define('_MSGPRI6', 'INFO');
define('_MSGPRI7', 'DEBUG');
define('_MSGNumSLE', 'Anzahl der Syslog Events');
define('_MSGNumERE', 'Anzahl der EventReporter Events');
define('_MSGNoMsg', '[Keine Nachricht vorhanden]');
define('_MSGMenInf1', '- Sie befinden sich zur Zeit im ');
define('_MSGMenInf2', ' Modus auf ');
define('_MSGMenInf3', '. Datenbank: ');
define('_MSGLang', 'Sprache:');
define('_MSGStyle', 'Stylesheet:');
define('_MSGAddInfo', 'Zusätzliche Informationen:');
define('_MSGDebug1', 'Debug:');
define('_MSGDebug2', 'Zeige Debug Ausgaben');
define('_MSGSave', 'Speicher/- Ladeoptionen:');
define('_MSGFilSave1', 'Filter Einstellungen:');
define('_MSGFilSave2', 'Filter Einstellungen in Datenbank speichern und beim Einloggen auslesen');
define('_MSGDBOpt', 'Datenbankoptionen:');
define('_MSGUTC1', 'UTC-Zeit:');
define('_MSGUTC2', 'Wenn Ihr Datenbank-Server keine UTC-Zeit verwendet, entfernen Sie das Häckchen!');
define('_MSGSavCook', 'Login behalten (Cookie)?');
