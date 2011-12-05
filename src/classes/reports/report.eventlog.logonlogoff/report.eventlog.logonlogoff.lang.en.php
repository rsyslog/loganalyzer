<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	*
	* Copyright (C) 2008-2011 Adiscon GmbH.
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
$content['ln_report_logonoff_summary'] = "Summary of Logon/Logoff events";
$content['ln_report_consolidation'] = "Logon / Logoff Events consolidated per User";
$content['ln_report_summary'] = "Report Summary";
$content['ln_report_number'] = "No.";
$content['ln_report_firstevent'] = "First Event";
$content['ln_report_lastevent'] = "Last Event";
$content['ln_report_user'] = "Domain & Username";
$content['ln_report_severity'] = "Type";
$content['ln_report_host'] = "Servername";
$content['ln_report_description'] = "Description";
$content['ln_report_count'] = "Count";
$content['ln_report_maxHosts_displayname'] = "Max hosts";
$content['ln_report_maxHosts_description'] = "The maximum number of hosts which will be displayed.";
$content['ln_report_maxLogOnLogOffsPerHost_displayname'] = "Max Logon/Logoffs per host/user";
$content['ln_report_maxLogOnLogOffsPerHost_description'] = "The maximum number of Logon/Logoff events displayed per host/user.";
$content['ln_report_colorThreshold_displayname'] = "Counter Threshold";
$content['ln_report_colorThreshold_description'] = "If the amount of consolidated events is higher then this threshold, the countfield will be marked red.";
$content['ln_report_'] = "";

?>