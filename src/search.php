<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Main Index File
	*
	* -> Loads the main LogAnalyzer Site
	*
	* All directives are explained within this file
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

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd

// Init Langauge first!
// IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

// Helpers for frontend filtering!
InitFilterHelpers();	
// ***					*** //

// --- Extra Javascript?
$content['EXTRA_JAVASCRIPT'] = "<script type='text/javascript' src='" . $content['BASEPATH'] . "js/searchhelpers.js'></script>";
// --- 

// --- CONTENT Vars

// Init Sorting variables
$content['searchstr'] = "";

// ---

// --- BEGIN Custom Code
if ( (isset($_POST['search']) || isset($_GET['search'])) )
{
	// Copy search over
	if		( isset($_POST['search']) )
		$mysearch = $_POST['search'];
	else if ( isset($_GET['search']) )
		$mysearch = $_GET['search'];

	// Evaluate search now
	if ( $mysearch == $content['LN_SEARCH_PERFORMADVANCED']) 
	{
		if ( isset($_GET['filter_datemode']) )
		{
			$filters['filter_datemode'] = intval($_GET['filter_datemode']);
			if ( $filters['filter_datemode'] == DATEMODE_RANGE )
			{
				// Read range values 
				if ( isset($_GET['filter_daterange_from_year']) ) 
					$filters['filter_daterange_from_year'] = intval($_GET['filter_daterange_from_year']);
				if ( isset($_GET['filter_daterange_from_month']) ) 
					$filters['filter_daterange_from_month'] = intval($_GET['filter_daterange_from_month']);
				if ( isset($_GET['filter_daterange_from_day']) ) 
					$filters['filter_daterange_from_day'] = intval($_GET['filter_daterange_from_day']);
				if ( isset($_GET['filter_daterange_to_year']) ) 
					$filters['filter_daterange_to_year'] = intval($_GET['filter_daterange_to_year']);
				if ( isset($_GET['filter_daterange_to_month']) ) 
					$filters['filter_daterange_to_month'] = intval($_GET['filter_daterange_to_month']);
				if ( isset($_GET['filter_daterange_to_day']) ) 
					$filters['filter_daterange_to_day'] = intval($_GET['filter_daterange_to_day']);

				// Read range values and prepend leading zeroes for values < 10
				if ( isset($_GET['filter_daterange_from_hour']) )
				{
					$filters['filter_daterange_from_hour'] = intval($_GET['filter_daterange_from_hour']);
					if ($filters['filter_daterange_from_hour'] < 10)
					    $filters['filter_daterange_from_hour'] = '0' . $filters['filter_daterange_from_hour'];
				}
				if ( isset($_GET['filter_daterange_from_minute']) )
				{
					$filters['filter_daterange_from_minute'] = intval($_GET['filter_daterange_from_minute']);
					if ($filters['filter_daterange_from_minute'] < 10)
					    $filters['filter_daterange_from_minute'] = '0' . $filters['filter_daterange_from_minute'];
				}
				if ( isset($_GET['filter_daterange_from_second']) )
				{
					$filters['filter_daterange_from_second'] = intval($_GET['filter_daterange_from_second']);
					if ($filters['filter_daterange_from_second'] < 10)
					    $filters['filter_daterange_from_second'] = '0' . $filters['filter_daterange_from_second'];
				}
				if ( isset($_GET['filter_daterange_to_hour']) )
				{
					$filters['filter_daterange_to_hour'] = intval($_GET['filter_daterange_to_hour']);
					if ($filters['filter_daterange_to_hour'] < 10)
					    $filters['filter_daterange_to_hour'] = '0' . $filters['filter_daterange_to_hour']; 
				}
				if ( isset($_GET['filter_daterange_to_minute']) )
				{
					$filters['filter_daterange_to_minute'] = intval($_GET['filter_daterange_to_minute']);
					if ($filters['filter_daterange_to_minute'] < 10)
					    $filters['filter_daterange_to_minute'] = '0' . $filters['filter_daterange_to_minute'];
				}
				if ( isset($_GET['filter_daterange_to_second']) )
				{
					$filters['filter_daterange_to_second'] = intval($_GET['filter_daterange_to_second']);
					if ($filters['filter_daterange_to_second'] < 10)
					    $filters['filter_daterange_to_second'] = '0' . $filters['filter_daterange_to_second']; 
				}
				
				// Append to searchstring
				$content['searchstr'] .= "datefrom:" .	$filters['filter_daterange_from_year'] . "-" . 
														$filters['filter_daterange_from_month'] . "-" . 
														$filters['filter_daterange_from_day'] . "T" . 
														$filters['filter_daterange_from_hour'] . ":" . 
														$filters['filter_daterange_from_minute'] . ":" . 
														$filters['filter_daterange_from_second'] . " ";

				$content['searchstr'] .= "dateto:" .	$filters['filter_daterange_to_year'] . "-" . 
														$filters['filter_daterange_to_month'] . "-" . 
														$filters['filter_daterange_to_day'] . "T" . 
														$filters['filter_daterange_to_hour'] . ":" . 
														$filters['filter_daterange_to_minute'] . ":" . 
														$filters['filter_daterange_to_second'] . " ";

			}
			else if ( $filters['filter_datemode'] == DATEMODE_LASTX )
			{
				if ( isset($_GET['filter_daterange_last_x']) ) 
				{
					$filters['filter_daterange_last_x'] = intval($_GET['filter_daterange_last_x']);
					$content['searchstr'] .= "datelastx:" .	$filters['filter_daterange_last_x'] . " ";
				}
			}
		}

		if ( isset($_GET['filter_facility']) && count($_GET['filter_facility']) < count($content['filter_facility_list']) ) // If we have more elements as in the filter list array, this means all are enabled
		{
			$tmpStr = "";
			foreach ($_GET['filter_facility'] as $tmpfacility) 
			{
				if ( strlen($tmpStr) > 0 )
					$tmpStr .= ",";
				$tmpStr .= $tmpfacility;  
			}
			$content['searchstr'] .= "facility:" . $tmpStr . " ";
		}

		if ( isset($_GET['filter_severity']) && count($_GET['filter_severity']) < count($content['filter_severity_list']) ) // If we have more elements as in the filter list array, this means all are enabled
		{
			$tmpStr = "";
			foreach ($_GET['filter_severity'] as $tmpfacility) 
			{
				if ( strlen($tmpStr) > 0 )
					$tmpStr .= ",";
				$tmpStr .= $tmpfacility;  
			}
			$content['searchstr'] .= "severity:" . $tmpStr . " ";
		}

		if ( isset($_GET['filter_messagetype']) && count($_GET['filter_messagetype']) < count($content['filter_messagetype_list']) ) // If we have more elements as in the filter list array, this means all are enabled
		{
			$tmpStr = "";
			foreach ($_GET['filter_messagetype'] as $tmpmsgtype) 
			{
				if ( strlen($tmpStr) > 0 )
					$tmpStr .= ",";
				$tmpStr .= $tmpmsgtype;  
			}
			$content['searchstr'] .= "messagetype:" . $tmpStr . " ";
		}
		

		// Spaces need to be converted!
		if ( isset($_GET['filter_syslogtag']) && strlen($_GET['filter_syslogtag']) > 0 )
		{
			if ( strpos($_GET['filter_syslogtag'], " ") === false)
				$content['searchstr'] .= "syslogtag:" . $_GET['filter_syslogtag'] . " ";
			else
				$content['searchstr'] .= "syslogtag:" . str_replace(" ", ",", $_GET['filter_syslogtag']) . " ";
		}
		
		// Spaces need to be converted!
		if ( isset($_GET['filter_source']) && strlen($_GET['filter_source']) > 0 )
		{
			if ( strpos($_GET['filter_source'], " ") === false)
				$content['searchstr'] .= "source:" . $_GET['filter_source'] . " ";
			else
				$content['searchstr'] .= "source:" . str_replace(" ", ",", $_GET['filter_source']) . " ";
		}
		
		// Message is just appended
		if ( isset($_GET['filter_message']) && strlen($_GET['filter_message']) > 0 )
			$content['searchstr'] .= $_GET['filter_message'];
	}
	
	// Append sourceid if needed
	if ( isset($_GET['sourceid']) && isset($content['Sources'][ $_GET['sourceid'] ]) )
		$sourceidstr = "&sourceid=" . $_GET['sourceid'];
	else
		$sourceidstr = "";

	// Redirect to the index page now!
	RedirectPage( "index.php?filter=" . urlencode( trim($content['searchstr']) ) . "&search=Search" . $sourceidstr);
}
// --- 

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();

// Append custom title part!
$content['TITLE'] .= " :: Search";
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "search.html");
$page -> output(); 
// --- 

?>