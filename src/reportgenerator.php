<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Report Generator Code File											
	*																	
	* -> This file will create reports based on their saved values. 
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
if ( !defined('IN_PHPLOGCON') )
	define('IN_PHPLOGCON', true);
$gl_root_path = './';

// Now include necessary include files!
include_once($gl_root_path . 'include/functions_common.php');
include_once($gl_root_path . 'include/functions_frontendhelpers.php');
include_once($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
include_once($gl_root_path . 'classes/logstream.class.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!

// Firts of all init List of Reports!
InitReportModules();
// ---

// --- READ CONTENT Vars
$content['error_occured'] = false;

if ( isset($_GET['op']) ) 
	$content['op'] = DB_RemoveBadChars($_GET['op']);
else
{
	$content['error_occured'] = "error";
	$content['error_details'] = $content['LN_GEN_ERROR_INVALIDOP'];
}

if ( isset($_GET['id']) ) 
	$content['reportid'] = DB_RemoveBadChars($_GET['id']);
else
{
	$content['error_occured'] = "error";
	$content['error_details'] = $content['LN_GEN_ERROR_INVALIDREPORTID'];
}

if ( isset($_GET['savedreportid']) ) 
{
	// read and verify value
	$content['savedreportid'] = intval($_GET['savedreportid']);
}
else
{
	$content['error_occured'] = "error";
	$content['error_details'] = $content['LN_GEN_ERROR_MISSINGSAVEDREPORTID'];
}

/*
if ( isset($_GET['width']) ) 
{
	$content['chart_width'] = intval($_GET['width']);
	
	// Limit Chart Size for now
	if		( $content['chart_width'] < 100 ) 
		$content['chart_width'] = 100;
	else if	( $content['chart_width'] > 1000 ) 
		$content['chart_width'] = 1000;
}
else
	$content['chart_width'] = 100;

if ( isset($_GET['byfield']) )
{
	if ( isset($fields[ $_GET['byfield'] ]) )
	{
		$content['chart_field'] = $_GET['byfield'];
		$content['chart_fieldtype'] = $fields[ $content['chart_field'] ]['FieldType'];
	}
	else
	{
		$content['error_occured'] = true;
		$content['error_details'] = $content['LN_GEN_ERROR_INVALIDFIELD'];
	}
}
else
{
	$content['error_occured'] = true;
	$content['error_details'] = $content['LN_GEN_ERROR_MISSINGCHARTFIELD'];
}

if ( isset($_GET['maxrecords']) ) 
{
	// read and verify value
	$content['maxrecords'] = intval($_GET['maxrecords']);
	if ( $content['maxrecords'] < 2 || $content['maxrecords'] > 100 ) 
		$content['maxrecords'] = 10;
}
else
	$content['maxrecords'] = 10;

if ( isset($_GET['showpercent']) ) 
{
	// read and verify value
	$content['showpercent'] = intval($_GET['showpercent']);
	if ( $content['showpercent'] >= 1 ) 
		$content['showpercent'] = 1;
	else
		$content['showpercent'] = 0;
}
else
	$content['showpercent'] = 0;
*/

// ---

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
// --- END CREATE TITLE


// --- BEGIN Custom Code

// Get data and print on the image!
if ( !$content['error_occured'] )
{
	// Check if report exists
	if ( isset($content['REPORTS'][ $content['reportid'] ]) )
	{
		// Get Reference to parser!
		$myReport = $content['REPORTS'][ $content['reportid'] ];

		// Now check if the saved report is available
		if ( isset($myReport['SAVEDREPORTS']) && count($myReport['SAVEDREPORTS']) > 0 )
		{
			// Init SavedReport reference
			$mySavedReport = null; 

			// Saved reports available, search for our one!
			foreach ($myReport['SAVEDREPORTS']  as &$tmpSavedReport )
			{
				if ( $tmpSavedReport['SavedReportID'] == $content['savedreportid'] ) 
				{
					// Copy reference and break loop
					$mySavedReport = $tmpSavedReport; 
					break;
				}
			}

			// Found reference to saved report, no process
			if ( $mySavedReport != null ) 
			{
				// Get Objectreference to report
				$myReportObj = $myReport["ObjRef"];

				// Set SavedReport Settings!
				$myReportObj->InitFromSavedReport($mySavedReport);

				// Perform check
				$res = $myReportObj->verifyDataSource();
				if ( $res != SUCCESS ) 
				{
					$content['error_occured'] = true;
					$content['error_details'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_ERRORCHECKINGSOURCE'], GetAndReplaceLangStr( GetErrorMessage($res), $mySavedReport['sourceid']) );
					if ( isset($extraErrorDescription) )
						$content['error_details'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
				}
				else
				{
					// Call processing part now!
					$res = $myReportObj->startDataProcessing();
					if ( $res != SUCCESS ) 
					{
						$content['error_occured'] = true;
						$content['error_details'] = GetAndReplaceLangStr( $content['LN_GEN_ERROR_REPORTGENFAILED'], $mySavedReport['customTitle'], GetErrorMessage($res) );
					}
					else
					{
						// --- Perform report output

						// Init IncludePath
						$reportIncludePath = $myReportObj->GetReportIncludePath(); 

						// Include Custom language file if available
						$myReportObj->InitReportLanguageFile($reportIncludePath); 
						
						// Init template Parser
						$page = new Template();
						$page -> set_path ( $reportIncludePath );

						// Parse template
						$page -> parser($content, $myReportObj->GetBaseFileName());

						// Output to browser 
						$myReportObj->OutputReport( $page ->result() );
						//$page->output(); 
						// --- 
					}
				}
			}
			else
			{
				$content['error_occured'] = true;
				$content['error_details'] = $content['LN_GEN_ERROR_MISSINGSAVEDREPORTID'];
			}
		}
	}
	else
	{
		$content['error_occured'] = true;
		$content['error_details'] = $content['LN_GEN_ERROR_MISSINGSAVEDREPORTID'];
	}
}

// Output error if necessary
if ( $content['error_occured'] )
{
//	$content['TITLE'] = InitPageTitle();
	$content['TITLE'] .= " :: " . $content['LN_GEN_ERROR_WHILEREPORTGEN'];

	InitTemplateParser();
	$page -> parser($content, "reportgenerator.html");
	$page -> output(); 
}
// --- 


?>