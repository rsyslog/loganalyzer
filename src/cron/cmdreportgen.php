<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Command Report Generator File
	*																	
	* -> This file is meant to run on command line, or via CRON / task Scheduler
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
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './../';

// --- IMPORTANT, read the script filename from argv! 
// Operation argv
if ( isset($_SERVER["argv"][0]) )
	$myscriptname = $_SERVER["argv"][0];
else
	die( "Error, this script can only be run from the command prompt." );

// Extract OS!
$pos = strpos( strtoupper(PHP_OS), "WIN");
if ($pos !== false)	// Running on Windows
{	
	// Extract Global root path from scriptname
	$gl_root_path = substr( $myscriptname, 0, strrpos($myscriptname, "\\")+1 ); 
	$gl_root_path = str_replace("\\", "/", $gl_root_path); 
} 
else 				// Running on LINUX
{
	// Extract Global root path from scriptname
	$gl_root_path = substr( $myscriptname, 0, strrpos($myscriptname, "/")+1 ); 
}

// Remove cron folder as well!
$gl_root_path = str_replace("cron/", "", $gl_root_path); 
// ---

// Now include necessary include files!
include_once($gl_root_path . 'include/functions_common.php');
include_once($gl_root_path . 'include/functions_frontendhelpers.php');
include_once($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
include_once($gl_root_path . 'classes/logstream.class.php');

// Set commandline mode for the script
define('IN_PHPLOGCON_COMMANDLINE', true);
$content['IN_PHPLOGCON_COMMANDLINE'] = true;
InitPhpLogCon();
InitFilterHelpers();	// Helpers for frontend filtering!
InitSourceConfigs();

// Firts of all init List of Reports!
InitReportModules();
// ---

// --- Helper functions
/*
*	Cleans data in desired logstream 
*/
function RunReport()
{
	global $content, $gl_root_path;

	// Get Reference to report!
	$myReport = $content['REPORTS'][ $content['reportid'] ];

	// Get reference to savedreport
	$mySavedReport = $myReport['SAVEDREPORTS'][ $content['savedreportid'] ]; 
	
	// Get Objectreference to report
	$myReportObj = $myReport["ObjRef"];

	// Set SavedReport Settings!
	$myReportObj->InitFromSavedReport($mySavedReport);

	//Debug Output
	PrintHTMLDebugInfo( DEBUG_INFO, "RunReport", GetAndReplaceLangStr($content["LN_CMD_RUNREPORT"], $mySavedReport['customTitle']) );

	// Perform check
	$res = $myReportObj->verifyDataSource();
	if ( $res != SUCCESS ) 
	{
		// Print error and die!
		$szError = GetAndReplaceLangStr( $content['LN_GEN_ERROR_REPORTGENFAILED'], $mySavedReport['customTitle'], GetAndReplaceLangStr( GetErrorMessage($res), $mySavedReport['sourceid']) ); 
		if ( isset($extraErrorDescription) )
			$szError .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
		DieWithErrorMsg( $szError );
	}
	else
	{
		// Call processing part now!
		$res = $myReportObj->startDataProcessing();
		if ( $res != SUCCESS ) 
			DieWithErrorMsg( GetAndReplaceLangStr($content['LN_GEN_ERROR_REPORTGENFAILED'], $mySavedReport['customTitle'], GetErrorMessage($res)) );
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

			// Output the result
			$res = $myReportObj->OutputReport( $page ->result(), $szErrorStr );
			if ( $res == SUCCESS && $myReportObj->GetOutputTarget() != REPORT_TARGET_STDOUT ) 
			{
				//Debug Output
				PrintHTMLDebugInfo( DEBUG_INFO, "RunReport", GetAndReplaceLangStr($content["LN_GEN_SUCCESS_REPORTWASGENERATED_DETAILS"], $szErrorStr) );
			}
			else if ( $res == ERROR ) 
			{
				// Debug Error
				PrintHTMLDebugInfo( DEBUG_ERROR, "RunReport", GetAndReplaceLangStr($content["LN_GEN_ERROR_REPORTFAILEDTOGENERATE"], $szErrorStr) );
			}
			// --- 
		}
	}
}
// --- 


// --- BEGIN Custom Code
	//Additional Includes
	include($gl_root_path . 'include/functions_debugoutput.php');

	// Run into Commandline part now!
	/* Only run if we are in command line mode 
	*	
	*	Possible Operation Types:
	*	runreport		=	To create a report, use this operation type. 
	*						Sample 1: Run report from type "monilog" with savedreportid 3 
	*							php cmdreportgen.php runreport monilog 3
	*
	*/

	// Init DebugHeader
	PrintDebugInfoHeader();

	// --- Now read command line args!
	// Operation argv
	if ( isset($_SERVER["argv"][1]) )
		$operation = $_SERVER["argv"][1];
	else
		DieWithErrorMsg( $content["LN_CMD_NOOP"] );

	// SavedReportID argv
	if ( isset($_SERVER["argv"][2]) )
	{
		// Set to SourceID property!
		$content['reportid'] = $_SERVER["argv"][2];

		// Check if exists
		if ( !isset($content['REPORTS'][ $content['reportid'] ]) )
			DieWithErrorMsg( GetAndReplaceLangStr($content["LN_CMD_REPORTIDNOTFOUND"], $content['reportid']) );

		// Get Reference to report!
		$myReport = $content['REPORTS'][ $content['reportid'] ];
	}
	else
		DieWithErrorMsg( $content["LN_CMD_NOREPORTID"] );

	// SavedReportID argv
	if ( isset($_SERVER["argv"][3]) )
	{
		// Set to SourceID property!
		$content['savedreportid'] = intval( $_SERVER["argv"][3] );

		// Check if exists
		if ( !isset($myReport['SAVEDREPORTS'][ $content['savedreportid'] ]) )
			DieWithErrorMsg( GetAndReplaceLangStr($content["LN_CMD_SAVEDREPORTIDNOTFOUND"], $content['savedreportid']) );
	}
	else
		DieWithErrorMsg( $content["LN_CMD_NOSAVEDREPORTID"] );

	// Run Optional Params first: userid/groupid
	if ( isset($_SERVER["argv"][4]) )
	{
		// Set to SourceID property!
		$tmpvar = $_SERVER["argv"][4];

		if ( strpos($tmpvar, "=") !== false ) 
		{
			$tmparr = explode("=", $tmpvar); 
			if ( $tmparr[0] == "userid" )
			{
				// Set logged in state for LogAnalyzer System
				$_SESSION['SESSION_LOGGEDIN'] = true;
				$content['SESSION_LOGGEDIN'] = true;
				$_SESSION['SESSION_USERID'] = $tmparr[1]; 
				$content['SESSION_USERID'] = $tmparr[1]; 
			}
			else if ( $tmparr[0] == "groupid" )
			{
				$_SESSION['SESSION_GROUPIDS'] = $tmparr[1];
				$content['SESSION_GROUPIDS'] = $tmparr[1];
			}
		}

		// Reload Configured Sources
		LoadSourcesFromDatabase();
		InitSourceConfigs();
	}
	// --- 

	// --- Operation Handling now
	if ( $operation == "runreport" )
	{
		// Create Report
		RunReport();
	}
	// --- 
// --- 

?>