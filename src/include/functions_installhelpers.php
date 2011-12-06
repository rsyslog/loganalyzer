<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Installer needed functions
	*																	
	* -> 		Functions in this file are only used by the installer 
	*			and converter script. 
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

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

// --- BEGIN Installer Helper Functions --- 
function ImportDataFile($szFileName)
{
	global $content, $totaldbdefs;

	// Lets read the table definitions :)
	$handle = @fopen($szFileName, "r");
	if ($handle === false) 
		RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr($content['LN_INSTALL_ERRORREADINGDBFILE'], $szFileName) );
	else
	{
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);

			$pos = strpos($buffer, "--");
			if ($pos === false)
				$totaldbdefs .= $buffer; 
			else if ( $pos > 2 && strlen( trim($buffer) ) > 1 )
				$totaldbdefs .= $buffer; 
		}
	   fclose($handle);
	}
}

function RevertOneStep($stepback, $errormsg)
{
	header("Location: " . STEPSCRIPTNAME . "?step=" . $stepback . "&errormsg=" . urlencode($errormsg) );
	exit;
}

function ForwardOneStep()
{
	global $content; 

	header("Location: " . STEPSCRIPTNAME . "?step=" . ($content['INSTALL_STEP']+1) );
	exit;
}

function ConvertGeneralSettings()
{
	global $content; 

	// Only call the same function as in admin index!
	SaveGeneralSettingsIntoDB(true);
}

/*
*	Convert Custom searches into DB
*/
function ConvertCustomSearches()
{
	global $CFG, $content;
	
	// Insert all searches into the DB!
	foreach($CFG['Search'] as $searchid => &$mySearch)
	{
		// New Entry
		$result = DB_Query("INSERT INTO  " . DB_SEARCHES . " (DisplayName, SearchQuery) VALUES ( '" . PrepareValueForDB($mySearch['DisplayName']) . "', '" . PrepareValueForDB($mySearch['SearchQuery']) . "')");
		$mySearch['DBID'] = DB_ReturnLastInsertID($result);
		DB_FreeQuery($result);

	}
}

/*
*	Convert Custom Charts into DB
*/
function ConvertCustomCharts()
{
	global $CFG, $content;
	
	// Insert all searches into the DB!
	foreach($CFG['Charts'] as $chartid => &$myChart)
	{
		// New Entry
		$result = DB_Query("INSERT INTO  " . DB_CHARTS . " (DisplayName, chart_enabled, chart_type, chart_width, chart_field, maxrecords, showpercent) 
							VALUES ( 
									'" . PrepareValueForDB($myChart['DisplayName']) . "', 
									" . intval($myChart['chart_enabled']) . ", 
									" . intval($myChart['chart_type']) . ", 
									" . intval($myChart['chart_width']) . ", 
									'" . PrepareValueForDB($myChart['chart_field']) . "', 
									" . intval($myChart['maxrecords']) . ", 
									" . intval($myChart['showpercent']) . "
									)");
		$myChart['DBID'] = DB_ReturnLastInsertID($result);
		DB_FreeQuery($result);
	}
}

/*
*	Convert Custom Views into DB
*/
function ConvertCustomViews()
{
	global $CFG, $content;
	
	// Insert all searches into the DB!
	foreach($CFG['Views'] as $viewid => &$myView)
	{
		if ( is_numeric($viewid) || $viewid == "LEGACY" )
		{
			// Create Columns String
			foreach ($myView['Columns'] as $myCol )
			{
				if ( isset($myView['ColumnsAsString']) ) 
					$myView['ColumnsAsString'] .= ", " . $myCol;
				else
					$myView['ColumnsAsString'] = $myCol;
			}

			// New Entry
			$result = DB_Query("INSERT INTO  " . DB_VIEWS . " (DisplayName, Columns) VALUES ( '" . PrepareValueForDB($myView['DisplayName']) . "', '" . PrepareValueForDB($myView['ColumnsAsString']) . "')");
			$myView['DBID'] = DB_ReturnLastInsertID($result);
			DB_FreeQuery($result);
		}
	}

	// --- Check and set DefaultViewID!
	if ( 
			(isset($content['DefaultViewsID']) && strlen($content['DefaultViewsID']) > 0) 
				&&
			(isset($CFG['Views'][$content['DefaultViewsID']]['DBID']))
		)
	{
		// Copy the new DefaultViewID back!
		$content['DefaultViewsID'] = $CFG['Views'][$content['DefaultViewsID']]['DBID'];
		$CFG['DefaultViewsID'] = $content['DefaultViewsID'];
	}
	// ---
}

function ConvertCustomSources()
{
	global $CFG, $content;
	
	// Insert all searches into the DB!
	foreach($CFG['Sources'] as $sourceid => &$mySource)
	{
		// Correct VIEWID!
		if ( isset($mySource['ViewID']) )
		{
			if ( isset($CFG['Views'][$mySource['ViewID']]['DBID']) )
				$mySource['ViewID'] = $CFG['Views'][$mySource['ViewID']]['DBID'];
		} 
		else
			$mySource['ViewID'] = ""; // Set empty default

		// Add New Entry
		if ( $mySource['SourceType'] == SOURCE_DISK ) 
		{
			$result = DB_Query("INSERT INTO  " . DB_SOURCES . " (Name, Description, SourceType, MsgParserList, MsgNormalize, ViewID, LogLineType, DiskFile) VALUES ( " . 
				"'" . PrepareValueForDB($mySource['Name']) . "', " . 
				"'" . PrepareValueForDB($mySource['Description']) . "', " . 
				" " . PrepareValueForDB($mySource['SourceType']) . " , " . 
				"'" . PrepareValueForDB($mySource['MsgParserList']) . "', " . 
				" " . PrepareValueForDB($mySource['MsgNormalize']) . " , " . 
				"'" . PrepareValueForDB($mySource['ViewID']) . "', " . 
				"'" . PrepareValueForDB($mySource['LogLineType']) . "', " . 
				"'" . PrepareValueForDB($mySource['DiskFile']) . "'" . 
				")");
		}
		else if ( $mySource['SourceType'] == SOURCE_DB || $mySource['SourceType'] == SOURCE_PDO ) 
		{
			// Set Default for number fields
			if ( !isset($mySource['DBEnableRowCounting']) ) 
				$mySource['DBEnableRowCounting'] = 0;
			else // Force to number
				$mySource['DBEnableRowCounting'] = intval($mySource['DBEnableRowCounting']);
			if ( !isset($mySource['DBType']) ) 
				$mySource['DBType'] = DB_MYSQL;

			// Perform the insert
			$result = DB_Query("INSERT INTO  " . DB_SOURCES . " (Name, Description, SourceType, MsgParserList, MsgNormalize, ViewID, DBTableType, DBType, DBServer, DBName, DBUser, DBPassword, DBTableName, DBEnableRowCounting) VALUES ( " . 
				"'" . PrepareValueForDB($mySource['Name']) . "', " . 
				"'" . PrepareValueForDB($mySource['Description']) . "', " . 
				" " . PrepareValueForDB($mySource['SourceType']) . " , " . 
				"'" . PrepareValueForDB($mySource['MsgParserList']) . "', " . 
				" " . PrepareValueForDB($mySource['MsgNormalize']) . " , " . 
				"'" . PrepareValueForDB($mySource['ViewID']) . "', " . 
				"'" . PrepareValueForDB($mySource['DBTableType']) . "', " . 
				" " . PrepareValueForDB($mySource['DBType']) . " , " . 
				"'" . PrepareValueForDB($mySource['DBServer']) . "', " . 
				"'" . PrepareValueForDB($mySource['DBName']) . "', " . 
				"'" . PrepareValueForDB($mySource['DBUser']) . "', " . 
				"'" . PrepareValueForDB($mySource['DBPassword']) . "', " . 
				"'" . PrepareValueForDB($mySource['DBTableName']) . "', " . 
				" " . PrepareValueForDB($mySource['DBEnableRowCounting']) . " " . 
				")");
		}
		else
			DieWithFriendlyErrorMsg( GetAndReplaceLangStr($content['LN_CONVERT_ERROR_SOURCEIMPORT'], $mySource['SourceType']) );
		
		// Copy DBID!
		$mySource['DBID'] = DB_ReturnLastInsertID($result);
		DB_FreeQuery($result);
	}

	// --- Check and set DefaultSourceID!
	if ( 
			(isset($content['DefaultSourceID']) && strlen($content['DefaultSourceID']) > 0) 
				&&
			(isset($CFG['Sources'][$content['DefaultSourceID']]['DBID']))
		)
	{
		// Copy the new DefaultSourceID back!
		$content['DefaultSourceID'] = $CFG['Sources'][$content['DefaultSourceID']]['DBID'];
		$CFG['DefaultSourceID'] = $content['DefaultSourceID'];
	}
	// ---
}
// --- 

?>