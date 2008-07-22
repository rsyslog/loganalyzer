<?php
/*
*********************************************************************
* -> www.phplogcon.org <-											*
* -----------------------------------------------------------------	*
* Maintain and read Source Configurations							*
*																	*
* -> Configuration need variables for the Database connection		*
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
* distribution.
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
require_once($gl_root_path . 'classes/logstreamconfig.class.php');
// --- 

function InitSourceConfigs()
{
	global $CFG, $content, $currentSourceID, $gl_root_path;

	// Init Source Configs!
	if ( isset($CFG['Sources']) )
	{	
		$iCount = count($CFG['Sources']);
		foreach( $CFG['Sources'] as &$mysource )
		{
			if ( isset($mysource['SourceType']) ) 
			{
				// Set Array Index, TODO: Check for invalid characters!
				$iSourceID = $mysource['ID'];
				
				// --- Set defaults if not set!
				if ( !isset($mysource['LogLineType']) ) 
				{
					$CFG['Sources'][$iSourceID]['LogLineType'] = "syslog";
					$content['Sources'][$iSourceID]['LogLineType'] = "syslog";
				}

				if ( !isset($mysource['userid']) )
				{
					$CFG['Sources'][$iSourceID]['userid'] = null;
					$content['Sources'][$iSourceID]['userid'] = null;
				}
				if ( !isset($mysource['groupid']) )
				{
					$CFG['Sources'][$iSourceID]['groupid'] = null;
					$content['Sources'][$iSourceID]['groupid'] = null;
				}
				// ---

				// Set different view if necessary
				if ( isset($_SESSION[$iSourceID . "-View"]) ) 
				{
					// Overwrite configured view!
					$content['Sources'][$iSourceID]['ViewID'] = $_SESSION[$iSourceID . "-View"];
				}
				else
				{
					if ( isset($mysource['ViewID']) )
						// Set to configured Source ViewID
						$content['Sources'][$iSourceID]['ViewID'] = $mysource['ViewID'];
					else
						// Not configured, maybe old legacy cfg. Set default view.
						$content['Sources'][$iSourceID]['ViewID'] = strlen($CFG['DefaultViewsID']) > 0 ? $CFG['DefaultViewsID'] : "SYSLOG";

				}

				// Only for the display box
				$content['Sources'][$iSourceID]['selected'] = ""; 
				
				// Create Config instance!
				if ( $mysource['SourceType'] == SOURCE_DISK )
				{
					// Perform necessary include
					require_once($gl_root_path . 'classes/logstreamconfigdisk.class.php');
					$content['Sources'][$iSourceID]['ObjRef'] = new LogStreamConfigDisk();
					$content['Sources'][$iSourceID]['ObjRef']->FileName = $mysource['DiskFile'];
					$content['Sources'][$iSourceID]['ObjRef']->LineParserType = $mysource['LogLineType'];
				}
				else if ( $mysource['SourceType'] == SOURCE_DB )
				{
					// Perform necessary include
					require_once($gl_root_path . 'classes/logstreamconfigdb.class.php');

					$content['Sources'][$iSourceID]['ObjRef'] = new LogStreamConfigDB();
					$content['Sources'][$iSourceID]['ObjRef']->DBServer = $mysource['DBServer'];
					$content['Sources'][$iSourceID]['ObjRef']->DBName = $mysource['DBName'];
					// Workaround a little bug from the installer script
					if ( isset($mysource['DBType']) )
						$content['Sources'][$iSourceID]['ObjRef']->DBType = $mysource['DBType'];
					else
						$content['Sources'][$iSourceID]['ObjRef']->DBType = DB_MYSQL;

					$content['Sources'][$iSourceID]['ObjRef']->DBTableName = $mysource['DBTableName'];
					
					// Legacy handling for tabletype!
					if ( isset($mysource['DBTableType']) && strtolower($mysource['DBTableType']) == "winsyslog" )
						$content['Sources'][$iSourceID]['ObjRef']->DBTableType = "monitorware"; // Convert to MonitorWare!
					else
						$content['Sources'][$iSourceID]['ObjRef']->DBTableType = strtolower($mysource['DBTableType']);

					// Optional parameters!
					if ( isset($mysource['DBPort']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBPort = $mysource['DBPort']; }
					if ( isset($mysource['DBUser']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBUser = $mysource['DBUser']; }
					if ( isset($mysource['DBPassword']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBPassword = $mysource['DBPassword']; }
					if ( isset($mysource['DBEnableRowCounting']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBEnableRowCounting = $mysource['DBEnableRowCounting']; }
				}
				else if ( $mysource['SourceType'] == SOURCE_PDO )
				{
					// Perform necessary include
					require_once($gl_root_path . 'classes/logstreamconfigpdo.class.php');

					$content['Sources'][$iSourceID]['ObjRef'] = new LogStreamConfigPDO();
					$content['Sources'][$iSourceID]['ObjRef']->DBServer = $mysource['DBServer'];
					$content['Sources'][$iSourceID]['ObjRef']->DBName = $mysource['DBName'];
					$content['Sources'][$iSourceID]['ObjRef']->DBType = $mysource['DBType'];
					$content['Sources'][$iSourceID]['ObjRef']->DBTableName = $mysource['DBTableName'];
					$content['Sources'][$iSourceID]['ObjRef']->DBTableType = strtolower($mysource['DBTableType']);

					// Optional parameters!
					if ( isset($mysource['DBPort']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBPort = $mysource['DBPort']; }
					if ( isset($mysource['DBUser']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBUser = $mysource['DBUser']; }
					if ( isset($mysource['DBPassword']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBPassword = $mysource['DBPassword']; }
					if ( isset($mysource['DBEnableRowCounting']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBEnableRowCounting = $mysource['DBEnableRowCounting']; }
				}
				else
				{	
					// UNKNOWN, remove config entry!
					unset($content['Sources'][$iSourceID]);

					// Output CRITICAL WARNING
					DieWithFriendlyErrorMsg( GetAndReplaceLangStr($content['LN_GEN_CRITERROR_UNKNOWNTYPE'], $mysource['SourceType']) );
				}
				
				// Set generic configuration options
				$content['Sources'][$iSourceID]['ObjRef']->_pageCount = $CFG['ViewEntriesPerPage'];

				// Set default SourceID here!
				if ( isset($content['Sources'][$iSourceID]) && !isset($currentSourceID) ) 
					$currentSourceID = $iSourceID;
			}
		}
	}

	// Read SourceID from GET Querystring
	if ( isset($_GET['sourceid']) && isset($content['Sources'][$_GET['sourceid']]) )
	{
		$currentSourceID = $_GET['sourceid'];
		$_SESSION['currentSourceID'] = $currentSourceID;
	}
	else
	{
		// Set Source from session if available!
		if ( isset($_SESSION['currentSourceID']) && isset($content['Sources'][$_SESSION['currentSourceID']]) )
			$currentSourceID = $_SESSION['currentSourceID'];
		else
		{
			if ( isset($CFG['DefaultSourceID']) && isset($content['Sources'][ $CFG['DefaultSourceID'] ]) ) 
				// Set Source to preconfigured sourceID!
				$_SESSION['currentSourceID'] = $CFG['DefaultSourceID'];
			else
				// No Source stored in session, then to so now!
				$_SESSION['currentSourceID'] = $currentSourceID;
		}
	}
	
	// Set for the selection box in the header
	$content['Sources'][$currentSourceID]['selected'] = "selected";

	// --- Additional handling needed for the current view!
	global $currentViewID;
	$currentViewID = $content['Sources'][$currentSourceID]['ViewID'];

	// Set selected state for correct View, for selection box ^^
	$content['Views'][ $currentViewID ]['selected'] = "selected";

	// If DEBUG Mode is enabled, we prepend the UID field into the col list!
	if ( $CFG['MiscShowDebugMsg'] == 1 && isset($content['Views'][$currentViewID]) )
		array_unshift( $content['Views'][$currentViewID]['Columns'], SYSLOG_UID);
	// ---
}

/*
*	This function Inits preconfigured Views. 
*/
function InitViewConfigs()
{
	global $CFG, $content, $currentViewID;
	
	// Predefined phpLogCon Views 
	$CFG['Views']['SYSLOG']= array( 
									'ID' =>			"SYSLOG", 
									'DisplayName' =>"Syslog Fields", 
									'Columns' =>	array ( SYSLOG_DATE, SYSLOG_FACILITY, SYSLOG_SEVERITY, SYSLOG_HOST, SYSLOG_SYSLOGTAG, SYSLOG_PROCESSID, SYSLOG_MESSAGETYPE, SYSLOG_MESSAGE ), 
									'userid' =>		null, 
									'groupid' =>	null, 
								   );
	$CFG['Views']['EVTRPT']= array( 
									'ID' =>			"EVTRPT", 
									'DisplayName' =>"EventLog Fields", 
									'Columns' =>	array ( SYSLOG_DATE, SYSLOG_HOST, SYSLOG_SEVERITY, SYSLOG_EVENT_LOGTYPE, SYSLOG_EVENT_SOURCE, SYSLOG_EVENT_ID, SYSLOG_EVENT_USER, SYSLOG_MESSAGE ), 
									'userid' =>		null, 
									'groupid' =>	null, 
								   );
	
	// Set default of 'DefaultViewsID'
	$CFG['DefaultViewsID'] = "SYSLOG";

	// Loop through views now and copy into content array!
	foreach ( $CFG['Views'] as $key => $view )
		$content['Views'][$key] = $view;
}

/*
*	This function Inits preconfigured Views. 
*/
function AppendLegacyColumns()
{
	global $CFG, $content;

	// Init View from legacy Columns 
	$CFG['Views']['LEGACY']= array( 
									'ID' =>			"LEGACY", 
									'DisplayName' =>"Legacy Columns Configuration", 
									'Columns' =>	$CFG['Columns'], 
								   );
	
	// set default to legacy of no default view is specified!
	if ( !isset($CFG['DefaultViewsID']) || strlen($CFG['DefaultViewsID']) <= 0 )
		$CFG['DefaultViewsID'] = "LEGACY";
}

function InitPhpLogConConfigFile($bHandleMissing = true)
{
	// Needed to make global
	global $CFG, $gl_root_path, $content;

	if ( file_exists($gl_root_path . 'config.php') && GetFileLength($gl_root_path . 'config.php') > 0 )
	{
		// Include the main config
		include_once($gl_root_path . 'config.php');
		
		// Easier DB Access
		define('DB_CONFIG',			$CFG['UserDBPref'] . "config");
		define('DB_GROUPS',			$CFG['UserDBPref'] . "groups");
		define('DB_GROUPMEMBERS',	$CFG['UserDBPref'] . "groupmembers");
		define('DB_SEARCHES',		$CFG['UserDBPref'] . "searches");
		define('DB_SOURCES',		$CFG['UserDBPref'] . "sources");
		define('DB_USERS',			$CFG['UserDBPref'] . "users");
		define('DB_VIEWS',			$CFG['UserDBPref'] . "views");

		// Legacy support for old columns definition format!
		if ( isset($CFG['Columns']) && is_array($CFG['Columns']) )
			AppendLegacyColumns();

		// --- Now Copy all entries into content variable
		foreach ($CFG as $key => $value )
			$content[$key] = $value;
		// --- 

		// For MiscShowPageRenderStats
		if ( $CFG['MiscShowPageRenderStats'] == 1 )
		{
			$content['ShowPageRenderStats'] = "true";
			InitPageRenderStats();
		}
		
		// return result
		return true;
	}
	else
	{
		// if handled ourselfe, we die in CheckForInstallPhp.
		if ( $bHandleMissing == true )
		{
			// Check for installscript!
			CheckForInstallPhp();
		}
		else
			return false;
	}
}

/*
*	Helper function to load configured Searches from the database
*/
function LoadSearchesFromDatabase()
{
	// Needed to make global
	global $CFG, $content;

	// --- Create SQL Query
	// Create Where for USERID
	if ( isset($content['SESSION_LOGGEDIN']) && $content['SESSION_LOGGEDIN'] )
		$szWhereUser = " OR " . DB_SEARCHES . ".userid = " . $content['SESSION_USERID'] . " ";
	else
		$szWhereUser = "";

	if ( isset($content['SESSION_GROUPIDS']) )
		$szGroupWhere = " OR " . DB_SEARCHES . ".groupid IN (" . $content['SESSION_GROUPIDS'] . ")";
	else
		$szGroupWhere = "";
	$sqlquery = " SELECT " . 
				DB_SEARCHES . ".ID, " . 
				DB_SEARCHES . ".DisplayName, " . 
				DB_SEARCHES . ".SearchQuery, " . 
				DB_SEARCHES . ".userid, " .
				DB_SEARCHES . ".groupid, " .
				DB_USERS . ".username, " .
				DB_GROUPS . ".groupname " .
				" FROM " . DB_SEARCHES . 
				" LEFT OUTER JOIN (" . DB_USERS . ") ON (" . DB_SEARCHES . ".userid=" . DB_USERS . ".ID ) " . 
				" LEFT OUTER JOIN (" . DB_GROUPS . ") ON (" . DB_SEARCHES . ".groupid=" . DB_GROUPS . ".ID ) " . 
				" WHERE (" . DB_SEARCHES . ".userid IS NULL AND " . DB_SEARCHES . ".groupid IS NULL) " . 
				$szWhereUser . 
				$szGroupWhere . 
				" ORDER BY " . DB_SEARCHES . ".userid, " . DB_SEARCHES . ".groupid, " . DB_SEARCHES . ".DisplayName";
	// ---

	// Get Searches from DB now!
	$result = DB_Query($sqlquery);
	$myrows = DB_GetAllRows($result, true);
	if ( isset($myrows ) && count($myrows) > 0 )
	{
		// Overwrite Search Array with Database one
		$CFG['Search'] = $myrows;
		$content['Search'] = $myrows;
	}
}

function LoadViewsFromDatabase()
{
	// Needed to make global
	global $CFG, $content;

	// --- Create SQL Query
	// Create Where for USERID
	if ( isset($content['SESSION_LOGGEDIN']) && $content['SESSION_LOGGEDIN'] )
		$szWhereUser = " OR " . DB_VIEWS . ".userid = " . $content['SESSION_USERID'] . " ";
	else
		$szWhereUser = "";

	if ( isset($content['SESSION_GROUPIDS']) )
		$szGroupWhere = " OR " . DB_VIEWS . ".groupid IN (" . $content['SESSION_GROUPIDS'] . ")";
	else
		$szGroupWhere = "";
	$sqlquery = " SELECT " . 
				DB_VIEWS . ".ID, " . 
				DB_VIEWS . ".DisplayName, " . 
				DB_VIEWS . ".Columns, " . 
				DB_VIEWS . ".userid, " .
				DB_VIEWS . ".groupid, " .
				DB_USERS . ".username, " .
				DB_GROUPS . ".groupname " .
				" FROM " . DB_VIEWS . 
				" LEFT OUTER JOIN (" . DB_USERS . ") ON (" . DB_VIEWS . ".userid=" . DB_USERS . ".ID ) " . 
				" LEFT OUTER JOIN (" . DB_GROUPS . ") ON (" . DB_VIEWS . ".groupid=" . DB_GROUPS . ".ID ) " . 
				" WHERE (" . DB_VIEWS . ".userid IS NULL AND " . DB_VIEWS . ".groupid IS NULL) " . 
				$szWhereUser . 
				$szGroupWhere . 
				" ORDER BY " . DB_VIEWS . ".userid, " . DB_VIEWS . ".groupid, " . DB_VIEWS . ".DisplayName";
	// ---

	// Get Views from DB now!
	$result = DB_Query($sqlquery);
	$myrows = DB_GetAllRows($result, true);
	if ( isset($myrows) && count($myrows) > 0 )
	{
		// Overwrite existing Views array
		unset($CFG['Views']);
//		print_r ( $myrows );
//		exit;


		// ReINIT Views Array
		InitViewConfigs();
		
		// Unpack the Columns and append to Views Array
		foreach ($myrows as &$myView )
		{
			// Split into array
			$myView['Columns'] = explode( ",", $myView['Columns'] );
			
			// remove spaces
			foreach ($myView['Columns'] as &$myCol )
				$myCol = trim($myCol);
			
			// Append to Views Array
			$CFG['Views'][ $myView['ID'] ] = $myView;
		}

		// Merge into existing Views Array!
//		$CFG['Views'] = array_merge ( $CFG['Views'], $myrows );
		$content['Views'] = $CFG['Views'];
	}
}

function LoadSourcesFromDatabase()
{
	// Needed to make global
	global $CFG, $content;

	// --- Create SQL Query
	// Create Where for USERID
	if ( isset($content['SESSION_LOGGEDIN']) && $content['SESSION_LOGGEDIN'] )
		$szWhereUser = " OR " . DB_SOURCES . ".userid = " . $content['SESSION_USERID'] . " ";
	else
		$szWhereUser = "";

	if ( isset($content['SESSION_GROUPIDS']) )
		$szGroupWhere = " OR " . DB_SOURCES . ".groupid IN (" . $content['SESSION_GROUPIDS'] . ")";
	else
		$szGroupWhere = "";
	$sqlquery = " SELECT " . 
				DB_SOURCES . ".*, " . 
				DB_USERS . ".username, " .
				DB_GROUPS . ".groupname " .
				" FROM " . DB_SOURCES . 
				" LEFT OUTER JOIN (" . DB_USERS . ") ON (" . DB_SOURCES . ".userid=" . DB_USERS . ".ID ) " . 
				" LEFT OUTER JOIN (" . DB_GROUPS . ") ON (" . DB_SOURCES . ".groupid=" . DB_GROUPS . ".ID ) " . 
				" WHERE (" . DB_SOURCES . ".userid IS NULL AND " . DB_SOURCES . ".groupid IS NULL) " . 
				$szWhereUser . 
				$szGroupWhere . 
				" ORDER BY " . DB_SOURCES . ".userid, " . DB_SOURCES . ".groupid, " . DB_SOURCES . ".Name";
	// ---
	// Get Sources from DB now!
	$result = DB_Query($sqlquery);
	$myrows = DB_GetAllRows($result, true);
	if ( isset($myrows) && count($myrows) > 0 )
	{
		// Overwrite existing Views array
		unset($CFG['Sources']);
		
		// Append to Source Array
		foreach ($myrows as &$mySource )
		{
			// Append to Source Array
			$CFG['Sources'][ $mySource['ID'] ] = $mySource; //['ID'];
		}
		
		// Copy to content array!
		$content['Sources'] = $CFG['Sources'];
	}

}

?>