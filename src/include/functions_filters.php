<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Filter Helper functions for the frontend							*
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

// --- Perform necessary includes
require_once($gl_root_path . 'include/constants_filters.php');
// --- 

function InitFilterHelpers()
{
	global $content, $filters;

	// Init Default DateMode from SESSION!
	if ( isset($_SESSION['filter_datemode']) ) 
		$filters['filter_datemode'] = intval($_SESSION['filter_datemode']);
	else
		$filters['filter_datemode'] = DATEMODE_ALL;

	// Init TimeFilter Helper Array
	$content['datemodes'][0]['ID'] = DATEMODE_ALL;
	$content['datemodes'][0]['DisplayName'] = $content['LN_DATEMODE_ALL'];
	if ( $filters['filter_datemode'] == DATEMODE_ALL ) { $content['datemodes'][0]['selected'] = "selected"; } else { $content['datemodes'][0]['selected'] = ""; }
	$content['datemodes'][1]['ID'] = DATEMODE_RANGE;
	$content['datemodes'][1]['DisplayName'] = $content['LN_DATEMODE_RANGE'];
	if ( $filters['filter_datemode'] == DATEMODE_RANGE ) { $content['datemodes'][1]['selected'] = "selected"; } else { $content['datemodes'][1]['selected'] = ""; }
	$content['datemodes'][2]['ID'] = DATEMODE_LASTX;
	$content['datemodes'][2]['DisplayName'] = $content['LN_DATEMODE_LASTX'];
	if ( $filters['filter_datemode'] == DATEMODE_LASTX ) { $content['datemodes'][2]['selected'] = "selected"; } else { $content['datemodes'][2]['selected'] = ""; }
	
	// Init Date Range Parameters
	global $currentTime, $currentDay, $currentMonth, $currentYear, $tomorrowTime, $tomorrowDay, $tomorrowMonth, $tomorrowYear; 
	$currentTime = time();
	$currentDay = date("d", $currentTime);
	$currentMonth = date("m", $currentTime);
	$currentYear = date("Y", $currentTime);

	$tomorrowTime = time(); // + 86400; // Add one day!
	$tomorrowDay = date("d", $tomorrowTime);
	$tomorrowMonth = date("m", $tomorrowTime);
	$tomorrowYear = date("Y", $tomorrowTime);

	// Init Year, month and day array!
	for ( $i = $currentYear-5; $i <= $currentYear+5; $i++ )
		$content['years'][] = $i;
	for ( $i = 1; $i <= 12; $i++ )
		$content['months'][] = $i;
	for ( $i = 1; $i <= 31; $i++ )
		$content['days'][] = $i;
	// Init Hour, minute and second array
	for ( $i = 0; $i <= 23; $i++ )
	{
		if ($i < 10) 
			$content['hours'][] = '0' . $i; 
		else
			$content['hours'][] = $i; 
	}
	for ( $i = 0; $i <= 59; $i++ )
	{
		if ($i < 10) 
			$content['minutes'][] = '0' . $i; 
		else
			$content['minutes'][] = $i; 
	}
	for ( $i = 0; $i <= 59; $i++ )
	{
		if ($i < 10) 
			$content['seconds'][] = '0' . $i; 
		else
			$content['seconds'][] = $i; 
	}

	// Init filter_daterange_from_year
	if ( isset($_SESSION['filter_daterange_from_year']) ) 
		$filters['filter_daterange_from_year'] = intval($_SESSION['filter_daterange_from_year']);
	else
		$filters['filter_daterange_from_year'] = $currentYear-1;
	FillDateRangeArray($content['years'], "filter_daterange_from_year_list", "filter_daterange_from_year");

	// Init filter_daterange_from_month
	if ( isset($_SESSION['filter_daterange_from_month']) ) 
		$filters['filter_daterange_from_month'] = intval($_SESSION['filter_daterange_from_month']);
	else
		$filters['filter_daterange_from_month'] = $currentMonth;
	FillDateRangeArray($content['months'], "filter_daterange_from_month_list", "filter_daterange_from_month");

	// Init filter_daterange_from_day
	if ( isset($_SESSION['filter_daterange_from_day']) ) 
		$filters['filter_daterange_from_day'] = intval($_SESSION['filter_daterange_from_day']);
	else
		$filters['filter_daterange_from_day'] = $currentDay;
	FillDateRangeArray($content['days'], "filter_daterange_from_day_list", "filter_daterange_from_day");

	// Init filter_daterange_to_year
	if ( isset($_SESSION['filter_daterange_to_year']) ) 
		$filters['filter_daterange_to_year'] = intval($_SESSION['filter_daterange_to_year']);
	else
		$filters['filter_daterange_to_year'] = $tomorrowYear;
	FillDateRangeArray($content['years'], "filter_daterange_to_year_list", "filter_daterange_to_year");

	// Init filter_daterange_to_month
	if ( isset($_SESSION['filter_daterange_to_month']) ) 
		$filters['filter_daterange_to_month'] = intval($_SESSION['filter_daterange_to_month']);
	else
		$filters['filter_daterange_to_month'] = $tomorrowMonth;
	FillDateRangeArray($content['months'], "filter_daterange_to_month_list", "filter_daterange_to_month");

	// Init filter_daterange_to_day
	if ( isset($_SESSION['filter_daterange_to_day']) ) 
		$filters['filter_daterange_to_day'] = intval($_SESSION['filter_daterange_to_day']);
	else
		$filters['filter_daterange_to_day'] = $tomorrowDay;
	FillDateRangeArray($content['days'], "filter_daterange_to_day_list", "filter_daterange_to_day");

	// Init filter_daterange_from_hour
	if ( isset($_SESSION['filter_daterange_from_hour']) ) 
		$filters['filter_daterange_from_hour'] = intval($_SESSION['filter_daterange_from_hour']);
	else
		$filters['filter_daterange_from_hour'] = 0;
	FillDateRangeArray($content['hours'], "filter_daterange_from_hour_list", "filter_daterange_from_hour");

	// Init filter_daterange_from_minute
	if ( isset($_SESSION['filter_daterange_from_minute']) ) 
		$filters['filter_daterange_from_minute'] = intval($_SESSION['filter_daterange_from_minute']);
	else
		$filters['filter_daterange_from_minute'] = 0;
	FillDateRangeArray($content['minutes'], "filter_daterange_from_minute_list", "filter_daterange_from_minute");

	// Init filter_daterange_from_second
	if ( isset($_SESSION['filter_daterange_from_second']) ) 
		$filters['filter_daterange_from_second'] = intval($_SESSION['filter_daterange_from_second']);
	else
		$filters['filter_daterange_from_second'] = 0;
	FillDateRangeArray($content['seconds'], "filter_daterange_from_second_list", "filter_daterange_from_second");

	// Init filter_daterange_to_hour
	if ( isset($_SESSION['filter_daterange_to_hour']) ) 
		$filters['filter_daterange_to_hour'] = intval($_SESSION['filter_daterange_to_hour']);
	else
		$filters['filter_daterange_to_hour'] = 23;
	FillDateRangeArray($content['hours'], "filter_daterange_to_hour_list", "filter_daterange_to_hour");

	// Init filter_daterange_to_minute
	if ( isset($_SESSION['filter_daterange_to_minute']) ) 
		$filters['filter_daterange_to_minute'] = intval($_SESSION['filter_daterange_to_minute']);
	else
		$filters['filter_daterange_to_minute'] = 59;
	FillDateRangeArray($content['minutes'], "filter_daterange_to_minute_list", "filter_daterange_to_minute");

	// Init filter_daterange_to_second
	if ( isset($_SESSION['filter_daterange_to_second']) ) 
		$filters['filter_daterange_to_second'] = intval($_SESSION['filter_daterange_to_second']);
	else
		$filters['filter_daterange_to_second'] = 59;
	FillDateRangeArray($content['seconds'], "filter_daterange_to_second_list", "filter_daterange_to_second");

	// --- Define LASTX Array

	// Init Default DateMode from SESSION!
	if ( isset($_SESSION['filter_lastx_default']) ) 
		$filters['filter_lastx_default'] = intval($_SESSION['filter_lastx_default']);
	else
		$filters['filter_lastx_default'] = DATE_LASTX_24HOURS;

	$content['filter_daterange_last_x_list'][0]['ID'] = DATE_LASTX_HOUR;
	$content['filter_daterange_last_x_list'][0]['DisplayName'] = $content['LN_DATE_LASTX_HOUR'];
	if ( $filters['filter_lastx_default'] == DATE_LASTX_HOUR ) { $content['filter_daterange_last_x_list'][0]['selected'] = "selected"; } else { $content['filter_daterange_last_x_list'][0]['selected'] = ""; }

	$content['filter_daterange_last_x_list'][1]['ID'] = DATE_LASTX_12HOURS;
	$content['filter_daterange_last_x_list'][1]['DisplayName'] = $content['LN_DATE_LASTX_12HOURS'];
	if ( $filters['filter_lastx_default'] == DATE_LASTX_12HOURS ) { $content['filter_daterange_last_x_list'][1]['selected'] = "selected"; } else { $content['filter_daterange_last_x_list'][1]['selected'] = ""; }

	$content['filter_daterange_last_x_list'][2]['ID'] = DATE_LASTX_24HOURS;
	$content['filter_daterange_last_x_list'][2]['DisplayName'] = $content['LN_DATE_LASTX_24HOURS'];
	if ( $filters['filter_lastx_default'] == DATE_LASTX_24HOURS ) { $content['filter_daterange_last_x_list'][2]['selected'] = "selected"; } else { $content['filter_daterange_last_x_list'][2]['selected'] = ""; }

	$content['filter_daterange_last_x_list'][3]['ID'] = DATE_LASTX_7DAYS;
	$content['filter_daterange_last_x_list'][3]['DisplayName'] = $content['LN_DATE_LASTX_7DAYS'];
	if ( $filters['filter_lastx_default'] == DATE_LASTX_7DAYS ) { $content['filter_daterange_last_x_list'][3]['selected'] = "selected"; } else { $content['filter_daterange_last_x_list'][3]['selected'] = ""; }

	$content['filter_daterange_last_x_list'][4]['ID'] = DATE_LASTX_31DAYS;
	$content['filter_daterange_last_x_list'][4]['DisplayName'] = $content['LN_DATE_LASTX_31DAYS'];
	if ( $filters['filter_lastx_default'] == DATE_LASTX_31DAYS ) { $content['filter_daterange_last_x_list'][4]['selected'] = "selected"; } else { $content['filter_daterange_last_x_list'][4]['selected'] = ""; }
	// ---

	// --- Init Default Syslog Facility from SESSION!
	if ( isset($_SESSION['filter_facility']) ) 
		$filters['filter_facility'] = intval($_SESSION['filter_facility']);
	else
		$filters['filter_facility'] = array ( SYSLOG_KERN, SYSLOG_USER, SYSLOG_MAIL, SYSLOG_DAEMON, SYSLOG_AUTH, SYSLOG_SYSLOG, SYSLOG_LPR, SYSLOG_NEWS, SYSLOG_UUCP, SYSLOG_CRON, SYSLOG_SECURITY, SYSLOG_FTP, SYSLOG_NTP, SYSLOG_LOGAUDIT, SYSLOG_LOGALERT, SYSLOG_CLOCK, SYSLOG_LOCAL0, SYSLOG_LOCAL1, SYSLOG_LOCAL2, SYSLOG_LOCAL3, SYSLOG_LOCAL4, SYSLOG_LOCAL5, SYSLOG_LOCAL6, SYSLOG_LOCAL7 );

	$iCount = count($content['filter_facility_list']);
	for ( $i = 0; $i < $iCount; $i++ )
	{
		if ( in_array($content['filter_facility_list'][$i]["ID"], $filters['filter_facility']) ) 
			$content['filter_facility_list'][$i]["selected"] = "selected"; 
	}
	// --- 

	// --- Init Default Syslog Severity from SESSION!
	if ( isset($_SESSION['filter_severity']) ) 
		$filters['filter_severity'] = intval($_SESSION['filter_severity']);
	else
		$filters['filter_severity'] = array ( SYSLOG_EMERG, SYSLOG_ALERT, SYSLOG_CRIT, SYSLOG_ERR, SYSLOG_WARNING, SYSLOG_NOTICE, SYSLOG_INFO, SYSLOG_DEBUG );

	$iCount = count($content['filter_severity_list']);
	for ( $i = 0; $i < $iCount; $i++ )
	{
		if ( in_array( $content['filter_severity_list'][$i]["ID"], $filters['filter_severity']) ) 
			$content['filter_severity_list'][$i]["selected"] = "selected"; 
	}
	// --- 

	// --- Init Default Message Type from SESSION!
	if ( isset($_SESSION['filter_messagetype']) ) 
		$filters['filter_messagetype'] = intval($_SESSION['filter_messagetype']);
	else
		$filters['filter_messagetype'] = array ( IUT_Syslog, IUT_NT_EventReport, IUT_File_Monitor, IUT_WEBSERVERLOG );

	$iCount = count($content['filter_messagetype_list']);
	for ( $i = 0; $i < $iCount; $i++ )
	{
		if ( in_array( $content['filter_messagetype_list'][$i]["ID"], $filters['filter_messagetype']) ) 
			$content['filter_messagetype_list'][$i]["selected"] = "selected"; 
	}
	// --- 

}

function FillDateRangeArray($sourcearray, $szArrayListName, $szFilterName) // $content['years'], "filter_daterange_from_year_list", "filter_daterange_from_year")
{
	global $content, $filters;
	$iCount = count($sourcearray);

	for ( $i = 0; $i < $iCount; $i++)
	{
		$content[$szArrayListName][$i]['value'] = $sourcearray[$i];
		if ( $filters[$szFilterName]  == $sourcearray[$i] ) 
			$content[$szArrayListName][$i]['selected'] = "selected";
		else
			$content[$szArrayListName][$i]['selected'] = "";
	}
}

function GetFacilityDisplayName( $nFacilityID )
{
	global $content;

	foreach( $content['filter_facility_list'] as $myfacility )
	{
		if ( $myfacility['ID'] == $nFacilityID )
			return $myfacility['DisplayName'];
	}

	// Default 
	return "Unknown Facility($nFacilityID)";
}

function GetSeverityDisplayName( $nSeverityID )
{
	global $content;

	foreach( $content['filter_severity_list'] as $myseverity )
	{
		if ( $myseverity['ID'] == $nSeverityID )
			return $myseverity['DisplayName'];
	}

	// Default 
	return "Unknown Severity($nSeverityID)";
}

function GetMessageTypeDisplayName( $nMsgTypeID )
{
	global $content;

	foreach( $content['filter_messagetype_list'] as $mymsgtype )
	{
		if ( $mymsgtype['ID'] == $nMsgTypeID )
			return $mymsgtype['DisplayName'];
	}

	// Default 
	return "Unknown MessageType($nMsgTypeID)";
}


function GetTimeStampFromTimeString($szTimeString)
{
	//Sample: 2008-4-1T00:00:00
	if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$/", $szTimeString, $out) )
	{
		// return new timestamp
		return mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
	}
	//Sample: 2008-04-01
	else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})$/", $szTimeString, $out) )
	{
		// return new timestamp
		return mktime(0,0,0, $out[2], $out[3], $out[1]);
	}
	else
	{
		OutputDebugMessage("Unparseable Time in GetTimeStampFromTimeString - '" . $szTimeString . "'", DEBUG_WARN);
		return $szTimeString;
	}
}

function GetDateTimeDetailsFromTimeString($szTimeString, &$second, &$minute, &$hour, &$day, &$month, &$year)
{
	//Sample: 2008-4-1T00:00:00
	if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$/", $szTimeString, $out) )
	{
		// Assign parameters
		$second = $out[6]; 
		$minute = $out[5]; 
		$hour = $out[4]; 
		$day = $out[3]; 
		$month = $out[2]; 
		$year = $out[1]; 
		
		// Success!
		return true;
	}
	else
		// Failed
		return false;
}

?>