<?php

/*#### #### #### #### #### #### #### #### #### ####
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2004-2005  Adiscon GmbH

Version 1.1

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; 
if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, 
MA  02111-1307, USA.

If you have questions about phpLogCon in general, please email info@adiscon.com. 
To learn more about phpLogCon, please visit http://www.phplogcon.com.

This Project was intiated and is maintened by Rainer Gerhards <rgerhards@hq.adiscon.com>. 
See AUTHORS to learn who helped make it become a reality.

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
define('_MSGTop5', 'Letzte fünf Logs (Unter Berücksichtigung der Filtereinstellungen)');
define('_MSGNoData', 'Keine Daten gefunden');
define('_MSGDate', 'Datum');
define('_MSGFac', 'Facility');
define('_MSGMsg', 'Nachricht');
define('_MSGSysLogTag', 'SysLogTag');
define('_MSGOccuren', 'Vorkommen');
define('_MSGAccDen', 'Zugang nicht gestattet');
define('_MSGFalLog', 'Sie sind kein registrierter Benutzer oder Sie haben ein falsches Passwort eingegeben');
define('_MSGBac2Ind', 'Zurück zum Index');
define('_MSGSesExp', 'Session abgelaufen');
define('_MSGSesExpQue', 'Session abgelaufen. Haben Sie vielleicht das letzte mal vergessen sich auszuloggen');
define('_MSGReLog', 'Zurück zum Index um sich neu neu einzuloggen');
define('_MSGShwEvn', 'Zeige Events');
define('_MSGShwSlt', 'Zeige SysLogTags');
define('_MSGShwSLog', 'Zeige SysLog');
define('_MSGNoDBCon', 'Verbindung zum Datenbank-Server fehlgeschlagen');
define('_MSGChDB', 'Auswahl der Datenbank fehlgeschlagen');
define('_MSGInvQur', 'Ungültige Abfrage');
define('_MSGNoDBHan', 'Kein gültiger Datenbank-Verbindungs-Handle');
define('_MSGLogout', 'LogOut');
define('_MSGSrcExp', 'Nach Ausdruck suchen');
define('_MSGSrc', 'Suche');
define('_MSGColExp', 'Ausdruck färben');
define('_MSGinCol', ' in dieser Farbe: ');
define('_MSGBrw', 'Durchsuchen');
define('_MSGFilConf', 'Allgemeine Filter Konfiguration');
define('_MSGSltFil', 'SysLogTag Filter Konfiguration');
define('_MSGUsrConf', 'Benutzer Konfiguration');
define('_MSGBscSet', 'Generelle Einstellungen');
define('_MSGConSet', 'Verbindungs Einstellungen');
define('_MSGConMod', 'Verbindungsmodus');
define('_MSGFilCon', 'Filter Bedingungen');
define('_MSGEvnDat', 'Event Datum');
define('_MSGOrdBy', 'Sortieren nach');
define('_MSGTagSort', 'Aufsteigend oder absteigend sortieren');
define('_MSGRef', 'Aktualisierung');
define('_MSGInfUI', 'InfoEinheit');
define('_MSGOth', 'Andere');
define('_MSGPri', 'Severity');
define('_MSGFilSet', 'Filter Einstellungen');
define('_MSGUsrSet', 'Benutzer Einstellungen');
define('_MSGFilOpt', 'Quick Filter Optionen');
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
define('_MSGPriDat', 'Severity und Datum');
define('_MSGNoRef', 'nicht aktualisieren');
define('_MSGE10s', 'alle 10 sek');
define('_MSGE30s', 'alle 30 sek');
define('_MSGEm', 'jede min');
define('_MSGE2m', 'alle 2 min');
define('_MSGE15m', 'alle 15 min');
define('_MSGEn', 'Englisch');
define('_MSGDe', 'Deutsch');
define('_MSGFav', 'Favoriten (Zum Aufrufen, auswählen):');
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
define('_MSGAscend', 'Aufsteigend');
define('_MSGDescend', 'Absteigend');
define('_MSGEnbQF', 'Quick-Filter auswählen');
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
define('_MSGAnd', 'und');
define('_MSGApply', 'Filter anwenden');
define('_MSGShow', 'Show');
define('_MSGMethSlt', 'SysLogTags');
define('_MSGMethHost', 'SysLogTags auf Hosts beziehend');
define('_MSGInstDir', 'Das \'install\' Verzeichnis existiert noch! Wenn Sie phplogcon schon komnfiguriert haben, löschen oder benennen Sie dieses Verzeichnis um. Dies verursacht sonst ein hohes sicherheitsrisiko! Anderfalls klicken Sie <a href="install/install.php">HIER</A> um die Installation von PHPLogCon zu starten.');

define('_InsWelc1', 'Willkommen zur Installation von phpLogCon, dem Logging-WebInterface.');
define('_InsWelc2', 'Die folgenden Schritte werden Sie durch die Installation begleiten und Ihnen bei der korrekten Installation und Konfiguration von phpLogCon helfen.');
define('_InsWelc3', 'Anmerkung: Felder mit einem ');
define('_InsWelc4', 'ROTEN *');
define('_InsWelc5', ' müssen in jdem Fall ausgefüllt werden!');
define('_InsDbIns1', 'Zuerst müssen wir Ihre Datenbank-Struktur überprüfen, da phpLogCon einige Tabellen benötigt. Wenn die Tabellen nicht existieren, wird phpLogCon sie anlegen.');
define('_InsDbIns2', 'Hierfür benötigt die phpLogCon Installation ein paar Informationen zu Ihrem Datenbank-Server:');
define('_InsDbIns3', 'Datenbank Einstellungen');
define('_InsDbInsCon', 'Verbindungstyp');
define('_InsDbInsConNa', 'Native');
define('_InsDbInsApp', 'Datenbank-Applikation');
define('_InsDbInsPort', 'Bei Standard, leer lassen');
define('_InsDbInsUsr', 'Benutzer (Benutzer muss \'INSERT\' und \'CREATE\' Rechte haben!)');
define('_InsPass', 'Passwort');
define('_InsPassRe', 'Password wiederholen');
define('_InsDbInsName', 'Datenbank/DSN Name');
define('_InsDbInsTime', 'Datenbank Zeitformat');
define('_InsPlcIns1', 'Nun müssen wir ein paar Einstellungen vornehmen, sauber und optimiert läuft.');
define('_InsPlcIns2', 'Anmerkung: Wenn Sie gewählt haben, dass das User Interface nicht installiert werden soll, können Sie es durch SQL-Scripte nachträglich installieren! Für Hilfe schauen Sie ins Manual.');
define('_InsPlcIns3', 'phpLogCon Allgemeine Einstellungen');
define('_InsPlcInsLang', 'Standard Sprache');
define('_InsLangEn', 'Englisch');
define('_InsLangDe', 'Deutsch');
define('_InsPlcInsUi', 'User Interface installieren');
define('_InsPlcInsUiCrUsr', 'Einen Benutzer anlegen');
define('_InsPlcIns4', 'Hier können Sie einen Benutzer für das User Interface anlegen. Wenn Sie bereits Benutzer in Ihrer Datenbank haben oder das User Interface nicht installieren möchten, lassen Sie diese Felder leer!');
define('_InsPlcInsUiName', 'Benutzername');
define('_InsPlcInsUiDisName', 'Anzeigename');
define('_InsPlcInsUiLang', 'Gewünschte Sprache');
define('_InsPer1', 'Überprüfe Benutzer Eingaben...');
define('_InsPerDone', 'Erledigt!');
define('_InsPer2', 'Erstelle benötigte Tabellen...');
define('_InsPer3', 'Füge Daten in die Tabellen ein...');
define('_InsPer4', 'Erstelle Ihre Konfigurationsdatei (config.php)...');
define('_InsPer5', 'Alle Aufgaben wurden erfolgreich abgeschlossen!');
define('_InsPer6', 'Herzlichen Glückwunsch! Sie haben erfolgreich phpLogCon installiert!');
define('_InsPer7', 'Eine Dtei namens \'config.php\' liegt im Hauptverzeichnis von phpLogCon. In dieser Datei sind alle Informationen, die sie zuvor eingegeben haben, gespeichert! Sie können diese Datei jederzeit Ihren Bedürfnissen anpassen.');
define('_InsPer8', 'Wechseln Sie zu \'index.php\' im root Verzeichnis um die Arbeit mit phpLogCon zu starten!');
define('_InsPer9', 'Vergessen Sie nicht den kompletten Ordner \'install/\' zu löschen!');
define('_InsPer10', 'Diese Datein können für einen DoS auf Ihr phpLogCon genutzt werden!');
define('_InsPer11', 'Nach Löschen des Ordners können Sie zum ');
define('_InsPer12', 'Index wecheln!');
