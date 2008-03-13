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
//		$content['datemodes'][0]['ID'] = DATEMODE_ALL;
//		$content['datemodes'][0]['DisplayName'] = $content['LN_DATEMODE_ALL'];
//		if ( $filters['filter_datemode'] == DATEMODE_ALL ) { $content['datemodes'][0]['selected'] = "selected"; } else { $content['datemodes'][0]['selected'] = ""; }
	$content['datemodes'][0]['ID'] = DATEMODE_RANGE;
	$content['datemodes'][0]['DisplayName'] = $content['LN_DATEMODE_RANGE'];
	if ( $filters['filter_datemode'] == DATEMODE_RANGE ) { $content['datemodes'][0]['selected'] = "selected"; } else { $content['datemodes'][0]['selected'] = ""; }
	$content['datemodes'][1]['ID'] = DATEMODE_LASTX;
	$content['datemodes'][1]['DisplayName'] = $content['LN_DATEMODE_LASTX'];
	if ( $filters['filter_datemode'] == DATEMODE_LASTX ) { $content['datemodes'][1]['selected'] = "selected"; } else { $content['datemodes'][1]['selected'] = ""; }
	
	// Init Date Range Parameters
	$currentTime = time();
	$currentDay = date("d", $currentTime);
	$currentMonth = date("m", $currentTime);
	$currentYear = date("Y", $currentTime);
	
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
		$filters['filter_daterange_to_year'] = $currentYear;
	FillDateRangeArray($content['years'], "filter_daterange_to_year_list", "filter_daterange_to_year");

	// Init filter_daterange_to_month
	if ( isset($_SESSION['filter_daterange_to_month']) ) 
		$filters['filter_daterange_to_month'] = intval($_SESSION['filter_daterange_to_month']);
	else
		$filters['filter_daterange_to_month'] = $currentMonth;
	FillDateRangeArray($content['months'], "filter_daterange_to_month_list", "filter_daterange_to_month");

	// Init filter_daterange_to_day
	if ( isset($_SESSION['filter_daterange_to_day']) ) 
		$filters['filter_daterange_to_day'] = intval($_SESSION['filter_daterange_to_day']);
	else
		$filters['filter_daterange_to_day'] = $currentDay;
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

?>