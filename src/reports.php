<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Search Admin File											
	*																	
	* -> Helps administrating report modules 
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
	* distribution				
	* 
	* Adiscon LogAnalyzer is also available under a commercial license.
	* For details, contact info@adiscon.com or visit
	* http://loganalyzer.adiscon.com/commercial
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');
include($gl_root_path . 'include/functions_reports.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!
// --- 

// --- BEGIN Custom Code

// Firts of all init List of Reports!
InitReportModules();

if ( isset($content['REPORTS']) ) 
{
	// This will enable to Stats View 
	$content['reportsenabled'] = true;

	$i = 0; // Help counter!
	foreach ($content['REPORTS'] as &$myReport )
	{
		// Set if help link is enabled
		if ( strlen($myReport['ReportHelpArticle']) > 0 ) 
			$myReport['ReportHelpEnabled'] = true;
		else
			$myReport['ReportHelpEnabled'] = false;

		// check for custom fields
		if ( $myReport['NeedsInit'] ) // && count($myReport['CustomFieldsList']) > 0 ) 
		{
			// Needs custom fields!
			$myReport['EnableNeedsInit'] = true;

			if ( $myReport['Initialized'] ) 
			{
				$myReport['InitEnabled'] = false;
				$myReport['DeleteEnabled'] = true;
			}
			else
			{
				$myReport['InitEnabled'] = true;
				$myReport['DeleteEnabled'] = false;
			}
		}

		// --- Set CSS Class
		if ( $i % 2 == 0 )
		{
			$myReport['cssclass'] = "line1";
			$myReport['rowbegin'] = '<tr><td width="50%" valign="top">';
			$myReport['rowend'] = '</td>';
		}
		else
		{
			$myReport['cssclass'] = "line2";
			$myReport['rowbegin'] = '<td width="50%" valign="top">';
			$myReport['rowend'] = '</td></tr>';
		}
		$i++;
		// --- 

		// --- Check for saved reports!
		if ( isset($myReport['SAVEDREPORTS']) && count($myReport['SAVEDREPORTS']) > 0 )
		{
			$myReport['HASSAVEDREPORTS'] = "true";
			$myReport['SavedReportRowSpan'] = ( count($myReport['SAVEDREPORTS']) + 1);

			$j = 0; // Help counter!
			foreach ($myReport['SAVEDREPORTS']  as &$mySavedReport )
			{
				// --- Set CSS Class
				if ( $j % 2 == 0 )
					$mySavedReport['srcssclass'] = "line1";
				else
					$mySavedReport['srcssclass'] = "line2";
				$j++;
				// --- 
			}
		}
		// ---
	}
}
else
{
	$content['LISTREPORTS'] = "false";
	$content['ISERROR'] = true;
	$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_NOREPORTS']; 
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
// Append custom title part!
$content['TITLE'] .= " :: " . $content['LN_MENU_REPORTS'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "reports.html");
$page -> output(); 
// --- 
?>