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