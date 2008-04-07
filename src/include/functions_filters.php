<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Filter Helper functions for the frontend							*
	*																	*
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
	global $CFG, $content, $filters;

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
	$currentTime = time();
	$currentDay = date("d", $currentTime);
	$currentMonth = date("m", $currentTime);
	$currentYear = date("Y", $currentTime);

	$tomorrowTime = time() + 86400; // Add one day!
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

	// Init Default Syslog Facility from SESSION!
	if ( isset($_SESSION['filter_facility']) ) 
		$filters['filter_facility'] = intval($_SESSION['filter_facility']);
	else
		$filters['filter_facility'] = array ( SYSLOG_KERN, SYSLOG_USER, SYSLOG_MAIL, SYSLOG_DAEMON, SYSLOG_AUTH, SYSLOG_SYSLOG, SYSLOG_LPR, SYSLOG_NEWS, SYSLOG_UUCP, SYSLOG_CRON, SYSLOG_LOCAL0, SYSLOG_LOCAL1, SYSLOG_LOCAL2, SYSLOG_LOCAL3, SYSLOG_LOCAL4, SYSLOG_LOCAL5, SYSLOG_LOCAL6, SYSLOG_LOCAL7 );
//		$filters['filter_facility'] = SYSLOG_LOCAL0;



	// Init Facility LIST
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_KERN, "DisplayName" => "KERN", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_USER, "DisplayName" => "USER", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_MAIL, "DisplayName" => "MAIL", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_DAEMON, "DisplayName" => "DAEMON", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_AUTH, "DisplayName" => "AUTH", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_SYSLOG, "DisplayName" => "SYSLOG", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LPR, "DisplayName" => "LPR", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_NEWS, "DisplayName" => "NEWS", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_UUCP, "DisplayName" => "UUCP", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_CRON, "DisplayName" => "CRON", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL0, "DisplayName" => "LOCAL0", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL1, "DisplayName" => "LOCAL1", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL2, "DisplayName" => "LOCAL2", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL3, "DisplayName" => "LOCAL3", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL4, "DisplayName" => "LOCAL4", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL5, "DisplayName" => "LOCAL5", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL6, "DisplayName" => "LOCAL6", "selected" => "" );
	$content['filter_facility_list'][] = array( "ID" => SYSLOG_LOCAL7, "DisplayName" => "LOCAL7", "selected" => "" );


	$iCount = count($content['filter_facility_list']);
	for ( $i = 0; $i < $iCount; $i++ )
	{
//		echo $content['filter_facility_list'][$i]["ID"] . "-" . $filters['filter_facility'] . "<br>";
		if ( in_array($content['filter_facility_list'][$i]["ID"], $filters['filter_facility']) ) 
			$content['filter_facility_list'][$i]["selected"] = "selected"; 
	}

	// Init Default Syslog Severity from SESSION!
	if ( isset($_SESSION['filter_severity']) ) 
		$filters['filter_severity'] = intval($_SESSION['filter_severity']);
	else
		$filters['filter_severity'] = array ( SYSLOG_EMERG, SYSLOG_ALERT, SYSLOG_CRIT, SYSLOG_ERR, SYSLOG_WARNING, SYSLOG_NOTICE, SYSLOG_INFO, SYSLOG_DEBUG );
//		$filters['filter_severity'] = SYSLOG_NOTICE;

	// Init Severity LIST
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_EMERG, "DisplayName" => "EMERG", "selected" => "" );
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_ALERT, "DisplayName" => "ALERT", "selected" => "" );
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_CRIT, "DisplayName" => "CRIT", "selected" => "" );
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_ERR, "DisplayName" => "ERR", "selected" => "" );
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_WARNING, "DisplayName" => "WARNING", "selected" => "" );
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_NOTICE, "DisplayName" => "NOTICE", "selected" => "" );
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_INFO, "DisplayName" => "INFO", "selected" => "" );
	$content['filter_severity_list'][] = array( "ID" => SYSLOG_DEBUG, "DisplayName" => "DEBUG", "selected" => "" );

	$iCount = count($content['filter_severity_list']);
	for ( $i = 0; $i < $iCount; $i++ )
	{
		if ( in_array( $content['filter_severity_list'][$i]["ID"], $filters['filter_severity']) ) 
			$content['filter_severity_list'][$i]["selected"] = "selected"; 
	}

}

function FillDateRangeArray($sourcearray, $szArrayListName, $szFilterName) // $content['years'], "filter_daterange_from_year_list", "filter_daterange_from_year")
{
	global $CFG, $content, $filters;
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
	return "Unknown Facility";
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
	return "Unknown Severity";
}

function GetTimeStampFromTimeString($szTimeString)
{
	//Sample: 2008-4-1T00:00:00
	if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$/", $szTimeString, $out) )
	{
		// return new timestamp
		return mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
	}
	else
	{
		//WTF?
		die ("WTF GetTimeStampFromTimeString " . $szTimeString);
		return 0;
	}
}

?>