<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Report Generator Code File											
	*																	
	* -> This file will create reports based on their saved values. 
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
$content['report_success'] = false;

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
// ---

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
// --- END CREATE TITLE


// --- BEGIN Custom Code

// Do not BLOCK other Site Calls
WriteClosePHPSession();

// Get data and print on the image!
if ( !$content['error_occured'] )
{
	// Check if report exists
	if ( isset($content['REPORTS'][ $content['reportid'] ]) )
	{
		// Get Reference to report!
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
					// Init IncludePath
					$reportIncludePath = $myReportObj->GetReportIncludePath(); 

					// Include Custom language file if available
					$myReportObj->InitReportLanguageFile($reportIncludePath); 

					// Now start the processing part!
					$res = $myReportObj->startDataProcessing();
					if ( $res != SUCCESS ) 
					{
						$content['error_occured'] = true;
						$content['error_details'] = GetAndReplaceLangStr( $content['LN_GEN_ERROR_REPORTGENFAILED'], $mySavedReport['customTitle'], GetErrorMessage($res) );
						if ( isset($extraErrorDescription) )
							$content['error_details'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
					}
					else
					{
						// --- Perform report output
						// Init template Parser
						$page = new Template();
						$page -> set_path ( $reportIncludePath );

						// Parse template
						$page -> parser($content, $myReportObj->GetBaseFileName());

						// Output the result
						$res = $myReportObj->OutputReport( $page ->result(), $szErrorStr );
						if ( $res == SUCCESS && $myReportObj->GetOutputTarget() != REPORT_TARGET_STDOUT ) 
						{
							// Output wasn't STDOUT, so we need to display what happened to the user
							$content['report_success'] = true;
							$content['error_details'] = GetAndReplaceLangStr($content["LN_GEN_SUCCESS_REPORTWASGENERATED_DETAILS"], $szErrorStr); 
						}
						else if ( $res == ERROR ) 
						{
							// Output failed, display what happened to the user
							$content['error_occured'] = true;
							$content['error_details'] = GetAndReplaceLangStr($content["LN_GEN_ERROR_REPORTFAILEDTOGENERATE"], $szErrorStr); 
							if ( isset($extraErrorDescription) )
								$content['error_details'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
						}
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
if ( $content['error_occured'] || $content['report_success'] )
{
	if ( $content['error_occured'] ) 
		$content['TITLE'] .= " :: " . $content['LN_GEN_ERROR_WHILEREPORTGEN'];
	else
		$content['TITLE'] .= " :: " . $content['LN_GEN_SUCCESS_WHILEREPORTGEN'];

	// Create template Parser and output results
	InitTemplateParser();
	$page -> parser($content, "reportgenerator.html");
	$page -> output(); 
}
// --- 


?>