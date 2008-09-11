<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Details File											
	*																	
	* -> Shows Statistic, Charts and more
	*																	
	* All directives are explained within this file
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
	* distribution				
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
include($gl_root_path . 'classes/logstream.class.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!
// ---

// --- CONTENT Vars
// ---

// --- BEGIN Custom Code

if ( isset($content['Charts']) ) 
{
	// This will enable to Stats View 
	$content['statsenabled'] = true;

	// PreProcess Charts Array for display!
	$i = 0; // Help counter!
	foreach ($content['Charts'] as &$myChart )
	{
		// --- Set display name for chart type
		switch($myChart['chart_type'])
		{
			case CHART_CAKE: 
				$myChart['CHART_TYPE_DISPLAYNAME'] = $content['LN_CHART_TYPE_CAKE'];
				break;
			case CHART_BARS_VERTICAL: 
				$myChart['CHART_TYPE_DISPLAYNAME'] = $content['LN_CHART_TYPE_BARS_VERTICAL'];
				break;
			case CHART_BARS_HORIZONTAL: 
				$myChart['CHART_TYPE_DISPLAYNAME'] = $content['LN_CHART_TYPE_BARS_HORIZONTAL'];
				break;
			default: 
				$myChart['CHART_TYPE_DISPLAYNAME'] = $content['LN_GEN_ERROR_INVALIDTYPE'];
				break;
		}
		// --- 

		// --- Set display name for chart field
		if ( isset($myChart['chart_field']) && isset($content[ $fields[$myChart['chart_field']]['FieldCaptionID'] ]) ) 
			$myChart['CHART_FIELD_DISPLAYNAME'] = $content[ $fields[$myChart['chart_field']]['FieldCaptionID'] ];
		else
			$myChart['CHART_FIELD_DISPLAYNAME'] = $myChart['chart_field']; 
		// --- 

		// --- Set showpercent display
		if ( $myChart['showpercent'] == 1 )
			$myChart['showpercent_display'] = "Yes";
		else
			$myChart['showpercent_display'] = "No";
		// --- 

		// --- Set CSS Class
		if ( $i % 2 == 0 )
		{
			$myChart['cssclass'] = "line1";
			$myChart['rowbegin'] = '<tr><td width="50%" valign="top">';
			$myChart['rowend'] = '</td>';
		}
		else
		{
			$myChart['cssclass'] = "line2";
			$myChart['rowbegin'] = '<td width="50%" valign="top">';
			$myChart['rowend'] = '</td></tr>';
		}
		$i++;
		// --- 
	}

}
else
{
	// This will disable to Stats View and show an error message
	$content['statsenabled'] = false;

	// Set error code 
	$content['ISERROR'] = true;
	$content['ERROR_MSG'] = GetErrorMessage(ERROR_CHARTS_NOTCONFIGURED);
}
// --- 

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();

// Append custom title part!
$content['TITLE'] .= " :: " . $content['LN_MENU_STATISTICS'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "statistics.html");
$page -> output(); 
// --- 


?>