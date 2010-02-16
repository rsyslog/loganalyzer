<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Sources Admin File											
	*																	
	* -> This file is meant for command line maintenance 
	*																	
	* All directives are explained within this file
	*
	* Copyright (C) 2008 Adiscon GmbH.
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

// Now include necessary include files!
include_once($gl_root_path . 'include/functions_common.php');
include_once($gl_root_path . 'include/functions_frontendhelpers.php');
//include_once($gl_root_path . 'include/functions_debugoutput.php');

// Set commandline mode for the script
define('IN_PHPLOGCON_COMMANDLINE', true);
$content['IN_PHPLOGCON_COMMANDLINE'] = true;
InitPhpLogCon();
InitSourceConfigs();

// Init admin langauge file now!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/admin.php' );
// ***					*** //

// --- Helper functions
/*
*	Cleans data in desired logstream 
*/
function CleanData($optParam1, $optParam2, $optParam3, $optParam4)
{
	global $content, $gl_root_path;

	// Get Source reference!
	$mySource = $content['Sources'][ $content['SOURCEID'] ];

	// Check Source Type
	if ( $mySource['SourceType'] == SOURCE_DB || $mySource['SourceType'] == SOURCE_PDO ) 
	{
		// Include LogStream facility
		include($gl_root_path . 'classes/logstream.class.php');
		
		//Debug Output
		PrintHTMLDebugInfo( DEBUG_INFO, "CleanData", GetAndReplaceLangStr($content["LN_CMD_CLEANINGDATAFOR"], $mySource['Name']) );

		// Create LogStream Object 
		$stream = $mySource['ObjRef']->LogStreamFactory($mySource['ObjRef']);
		$res = $stream->Verify();
		if ( $res == SUCCESS ) 
		{
			// Gather Database Stats
			$content['ROWCOUNT'] = $stream->GetLogStreamTotalRowCount();
			if ( isset($content['ROWCOUNT']) )
			{
				//Debug Output
				PrintHTMLDebugInfo( DEBUG_INFO, "CleanData", GetAndReplaceLangStr($content["LN_CMD_ROWSFOUND"], $content['ROWCOUNT'], $mySource['Name']) );

				if ( $optParam1 != NULL )
				{
					if		( $optParam1 == "all" ) 
					{
						$timestamp = 0;
					}
					else if ( $optParam1 == "olderthan" && $optParam2 != NULL ) 
					{
						// Take current time and subtract Seconds
						$nSecondsSubtract = intval($optParam2);
						$timestamp = time() - $nSecondsSubtract;
					}
					else if ( $optParam1 == "date" && $optParam2 != NULL && $optParam3 != NULL && $optParam4 != NULL ) 
					{
						// Generate Timestamp
						$timestamp = mktime( 0, 0, 0, intval($optParam2), intval($optParam3), intval($optParam4) );
					}
					else
						// Print error and die!
						DieWithErrorMsg( $content["LN_CMD_WRONGSUBOPORMISSING"] );

					// Continue with delete only if $timestamp is set!
					if ( isset($timestamp) ) 
					{
						//Debug Output
						PrintHTMLDebugInfo( DEBUG_INFO, "CleanData", GetAndReplaceLangStr($content["LN_CMD_DELETINGOLDERTHEN"], date("Y-m-d", $timestamp) ) );

						// Now perform the data cleanup!
						$content['affectedrows'] = $stream->CleanupLogdataByDate($timestamp);

						if ( isset($content['affectedrows']) )
						{
							//Debug Output
							PrintHTMLDebugInfo( DEBUG_INFO, "CleanData", GetAndReplaceLangStr($content["LN_CMD_DELETEDROWS"], $content['affectedrows']) );
						}
						else
							// Print error and die!
							DieWithErrorMsg( GetAndReplaceLangStr($content["LN_CMD_FAILEDTOCLEANDATA"], $mySource['Name']) );
					}
				}
				else
					// Print error and die!
					DieWithErrorMsg( $content["LN_CMD_SUBPARAM1MISSING"] );
			}
			else
				// Print error and die!
				DieWithErrorMsg( GetAndReplaceLangStr($content["LN_CMD_COULDNOTGETROWCOUNT"], $mySource['Name']) );
		}
		else
		{
			// Print error and die!
			$szErroMsg = GetAndReplaceLangStr($content["LN_SOURCES_ERROR_WITHINSOURCE"],$mySource['Name'], GetErrorMessage($res));
			if ( isset($extraErrorDescription) )
				$szErroMsg .= "\r\n\r\n" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
			DieWithErrorMsg( $szErroMsg );
		}
	}
	else
		// Print error and die!
		DieWithErrorMsg( GetAndReplaceLangStr($content["LN_SOURCES_ERROR_NOCLEARSUPPORT"], $content['SOURCEID']) );
}
// --- 


// --- BEGIN Custom Code
	//Additional Includes
	include($gl_root_path . 'include/functions_debugoutput.php');

	// Run into Commandline part now!
	/* Only run if we are in command line mode 
	*	
	*	Possible Operation Types:
	*	cleandata		=	If you want to clear data from a logstream source, you can use the operation type. 
	*						Be carefull using this option, any deletion process cannot be undone!
	*						Sample 1: Delete all data in the logstream with id 2
	*							php maintenance.php cleandata 2 all
	*						Sample 2: Delete all data older then 60 seconds in the logstream with id 2
	*							php maintenance.php cleandata 2 olderthan 60
	*						Sample 3: Delete all data before 2008-11-18 in the logstream with id 2
	*							php maintenance.php cleandata 2 date 11 18 2008 
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

	// SourceID argv
	if ( isset($_SERVER["argv"][2]) )
	{
		// Set to SourceID property!
		$content['SOURCEID'] = intval( $_SERVER["argv"][2] );

		// Check if exists
		if ( !isset($content['Sources'][ $content['SOURCEID'] ]) )
			DieWithErrorMsg( GetAndReplaceLangStr($content["LN_CMD_LOGSTREAMNOTFOUND"], $content['SOURCEID']) );
	}
	else
		DieWithErrorMsg( $content["LN_CMD_NOLOGSTREAM"] );


	// First Optional Parameter
	if ( isset($_SERVER["argv"][3]) )
		$optparam1 = $_SERVER["argv"][3];
	else
		$optparam1 = NULL;

	// Second Optional Parameter
	if ( isset($_SERVER["argv"][4]) )
		$optparam2 = $_SERVER["argv"][4];
	else
		$optparam2 = NULL;

	// Third Optional Parameter
	if ( isset($_SERVER["argv"][5]) )
		$optParam3 = $_SERVER["argv"][5];
	else
		$optParam3 = NULL;

	// fourth Optional Parameter
	if ( isset($_SERVER["argv"][6]) )
		$optParam4 = $_SERVER["argv"][6];
	else
		$optParam4 = NULL;
	// --- 

	// --- Operation Handling now
	if ( $operation == "cleandata" )
	{
		// Run Parser only
		CleanData($optparam1, $optparam2, $optParam3, $optParam4);
	}
	// --- 
// --- 

?>