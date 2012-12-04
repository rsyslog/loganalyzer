<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Maintain and read Source Configurations							*
	*																	*
	* -> Configuration need variables for the Database connection		*
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

// --- Perform necessary includes
require_once($gl_root_path . 'classes/logstreamconfig.class.php');
// --- 

function InitSource(&$mysource)
{
	global $CFG, $content, $gl_root_path, $currentSourceID;

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

		if ( !isset($mysource['MsgParserList']) )
		{
			$CFG['Sources'][$iSourceID]['MsgParserList'] = null;
			$content['Sources'][$iSourceID]['MsgParserList'] = null;
		}

		if ( !isset($mysource['MsgNormalize']) )
		{
			$CFG['Sources'][$iSourceID]['MsgNormalize'] = 0;
			$content['Sources'][$iSourceID]['MsgNormalize'] = 0;
		}

		if ( !isset($mysource['MsgSkipUnparseable']) )
		{
			$CFG['Sources'][$iSourceID]['MsgSkipUnparseable'] = 0;
			$content['Sources'][$iSourceID]['MsgSkipUnparseable'] = 0;
		}

		if ( !isset($mysource['Description']) )
		{
			$CFG['Sources'][$iSourceID]['Description'] = "";
			$content['Sources'][$iSourceID]['Description'] = "";
		}

		if ( !isset($mysource['defaultfilter']) )
		{
			$CFG['Sources'][$iSourceID]['defaultfilter'] = "";
			$content['Sources'][$iSourceID]['defaultfilter'] = "";
		}
		// ---

		// Set default view id to source
		$tmpVar = GetConfigSetting("DefaultViewsID", "", CFGLEVEL_USER);
		$szDefaultViewID = strlen($tmpVar) > 0 ? $tmpVar : "SYSLOG";

		if ( isset($_SESSION[$iSourceID . "-View"]) ) 
		{
			// check if view is valid
			$UserSessionViewID = $_SESSION[$iSourceID . "-View"];

			if ( isset($content['Views'][$UserSessionViewID]) ) 
			{
				// Overwrite configured view!
				$content['Sources'][$iSourceID]['ViewID'] = $_SESSION[$iSourceID . "-View"];
			}
			else
				$content['Sources'][$iSourceID]['ViewID'] = $szDefaultViewID;
		}
		else
		{
			if ( isset($mysource['ViewID']) && strlen($mysource['ViewID']) > 0 && isset($content['Views'][ $mysource['ViewID'] ]) )
				// Set to configured Source ViewID
				$content['Sources'][$iSourceID]['ViewID'] = $mysource['ViewID'];
			else
				// Not configured, maybe old legacy cfg. Set default view.
				$content['Sources'][$iSourceID]['ViewID'] = $szDefaultViewID;
		}

		// Only for the display box
		$content['Sources'][$iSourceID]['selected'] = ""; 
		
		// Create Config instance!
		if ( $mysource['SourceType'] == SOURCE_DISK )
		{
			// Perform necessary include
			require_once($gl_root_path . 'classes/logstreamconfigdisk.class.php');
			$mysource['ObjRef'] = new LogStreamConfigDisk();
			$mysource['ObjRef']->SetFileName( $mysource['DiskFile'] );
			$mysource['ObjRef']->LineParserType = $mysource['LogLineType'];
		}
		else if ( $mysource['SourceType'] == SOURCE_DB )
		{
			// Perform necessary include
			require_once($gl_root_path . 'classes/logstreamconfigdb.class.php');

			$mysource['ObjRef'] = new LogStreamConfigDB();
			$mysource['ObjRef']->DBServer = $mysource['DBServer'];
			$mysource['ObjRef']->DBName = $mysource['DBName'];
			// Workaround a little bug from the installer script
			if ( isset($mysource['DBType']) )
				$mysource['ObjRef']->DBType = $mysource['DBType'];
			else
				$mysource['ObjRef']->DBType = DB_MYSQL;

			$mysource['ObjRef']->DBTableName = $mysource['DBTableName'];
			
			// Legacy handling for tabletype!
			if ( isset($mysource['DBTableType']) && strtolower($mysource['DBTableType']) == "winsyslog" )
				$mysource['ObjRef']->DBTableType = "monitorware"; // Convert to MonitorWare!
			else
				$mysource['ObjRef']->DBTableType = strtolower($mysource['DBTableType']);

			// Optional parameters!
			if ( isset($mysource['DBPort']) ) { $mysource['ObjRef']->DBPort = $mysource['DBPort']; }
			if ( isset($mysource['DBUser']) ) { $mysource['ObjRef']->DBUser = $mysource['DBUser']; }
			if ( isset($mysource['DBPassword']) ) { $mysource['ObjRef']->DBPassword = $mysource['DBPassword']; }
			if ( isset($mysource['DBEnableRowCounting']) ) { $mysource['ObjRef']->DBEnableRowCounting = $mysource['DBEnableRowCounting']; }
			if ( isset($mysource['DBRecordsPerQuery']) ) { $mysource['ObjRef']->RecordsPerQuery = $mysource['DBRecordsPerQuery']; }
		}
		else if ( $mysource['SourceType'] == SOURCE_PDO )
		{
			// Perform necessary include
			require_once($gl_root_path . 'classes/logstreamconfigpdo.class.php');

			$mysource['ObjRef'] = new LogStreamConfigPDO();
			$mysource['ObjRef']->DBServer = $mysource['DBServer'];
			$mysource['ObjRef']->DBName = $mysource['DBName'];
			$mysource['ObjRef']->DBType = $mysource['DBType'];
			$mysource['ObjRef']->DBTableName = $mysource['DBTableName'];
			$mysource['ObjRef']->DBTableType = strtolower($mysource['DBTableType']);

			// Optional parameters!
			if ( isset($mysource['DBPort']) ) { $mysource['ObjRef']->DBPort = $mysource['DBPort']; }
			if ( isset($mysource['DBUser']) ) { $mysource['ObjRef']->DBUser = $mysource['DBUser']; }
			if ( isset($mysource['DBPassword']) ) { $mysource['ObjRef']->DBPassword = $mysource['DBPassword']; }
			if ( isset($mysource['DBEnableRowCounting']) ) { $mysource['ObjRef']->DBEnableRowCounting = $mysource['DBEnableRowCounting']; }
		}
		else if ( $mysource['SourceType'] == SOURCE_MONGODB)
		{
			// Perform necessary include
			require_once($gl_root_path . 'classes/logstreamconfigmongodb.class.php');

			$mysource['ObjRef'] = new LogStreamConfigMongoDB();
			$mysource['ObjRef']->DBServer = $mysource['DBServer'];
			$mysource['ObjRef']->DBName = $mysource['DBName'];
			$mysource['ObjRef']->DBCollection = $mysource['DBTableName'];
			$mysource['ObjRef']->DBTableType = strtolower($mysource['DBTableType']);

			// Optional parameters!
			if ( isset($mysource['DBPort']) ) { $mysource['ObjRef']->DBPort = $mysource['DBPort']; }
			if ( isset($mysource['DBUser']) ) { $mysource['ObjRef']->DBUser = $mysource['DBUser']; }
			if ( isset($mysource['DBPassword']) ) { $mysource['ObjRef']->DBPassword = $mysource['DBPassword']; }
//			if ( isset($mysource['DBEnableRowCounting']) ) { $mysource['ObjRef']->DBEnableRowCounting = $mysource['DBEnableRowCounting']; }
		}
		else
		{	
			// UNKNOWN, remove config entry!
			unset($content['Sources'][$iSourceID]);

			// Output Debug Warning only!
			OutputDebugMessage( GetAndReplaceLangStr($content['LN_GEN_CRITERROR_UNKNOWNTYPE'], $mysource['SourceType']), DEBUG_ERROR);
			// DieWithFriendlyErrorMsg( GetAndReplaceLangStr($content['LN_GEN_CRITERROR_UNKNOWNTYPE'], $mysource['SourceType']) );
			return ERROR; 
		}

		// Set generic configuration options
		$mysource['ObjRef']->_pageCount = GetConfigSetting("ViewEntriesPerPage", 50);

		if ( isset($mysource['MsgParserList']) ) 
			$mysource['ObjRef']->SetMsgParserList( $mysource['MsgParserList'] );
		if ( isset($mysource['MsgNormalize']) )
			$mysource['ObjRef']->SetMsgNormalize( $mysource['MsgNormalize'] );
		if ( isset($mysource['MsgSkipUnparseable']) )
			$mysource['ObjRef']->SetSkipUnparseable( $mysource['MsgSkipUnparseable'] );
		if ( isset($mysource['defaultfilter']) )
			$mysource['ObjRef']->SetDefaultfilter( $mysource['defaultfilter'] );

		// Set default SourceID here!
		if ( isset($content['Sources'][$iSourceID]) && !isset($currentSourceID) ) 
			$currentSourceID = $iSourceID;

		// Copy Object REF into CFG and content Array as well!
		$content['Sources'][$iSourceID]['ObjRef'] = $mysource['ObjRef'];
		$CFG['Sources'][$iSourceID]['ObjRef'] = $mysource['ObjRef']; 
	}
}


/*
*	This function reads and generates a list of available message parsers
*/
function InitMessageParsers()
{
	global $content, $gl_root_path;

	$szDirectory = $gl_root_path . 'classes/msgparsers/'; // msgparser.' . $szParser . '.class.php';
	$aFiles = list_files($szDirectory, true); 
	if ( isset($aFiles) && count($aFiles) > 0 )
	{
		foreach( $aFiles as $myFile ) 
		{
			// Check if file is valid msg parser!
			if ( preg_match("/msgparser\.(.*?)\.class\.php$/", $myFile, $out ) )
			{
				// Set ParserID!
				$myParserID = $out[1]; 

				// Check if parser file include exists
				$szIncludeFile = $szDirectory . $myFile; 
				if ( file_exists($szIncludeFile) )
				{
					// Try to include
					if ( include_once($szIncludeFile) )
					{
						// Set ParserClassName
						$szParserClass = "MsgParser_" . $myParserID; 
///						echo $szParserClass . "<br>";
						
						// Create Instance and get properties
						$tmpParser = new $szParserClass(); // Create an instance
						$szParserName = $tmpParser->_ClassName; 
						$szParserDescription = $tmpParser->_ClassDescription;
						$szParserHelpArticle = $tmpParser->_ClassHelpArticle;
						

						// check for required fields!
						if ( $tmpParser->_ClassRequiredFields != null && count($tmpParser->_ClassRequiredFields) > 0 ) 
						{
							$bCustomFields = true;
							$aCustomFieldList = $tmpParser->_ClassRequiredFields; 
//							print_r ( $aCustomFieldList );
						}
						else
						{
							$bCustomFields = false;
							$aCustomFieldList = null;
						}

						// Add entry to msg parser list!
						$content['PARSERS'][$myParserID] = array (
														"ID" => $myParserID, 
														"DisplayName" => $szParserName, 
														"Description" => $szParserDescription, 
														"CustomFields" => $bCustomFields, 
														"CustomFieldsList" => $aCustomFieldList, 
														"ParserHelpArticle" => $szParserHelpArticle, 
														);
					}
					else
					{
						// DEBUG ERROR
						OutputDebugMessage("InitMessageParsers: Failed including msgparser file '" . $szIncludeFile . "' with error: '" . $php_errormsg . "'", DEBUG_ERROR);
					}
				}
				else
				{
					// DEBUG ERROR
					OutputDebugMessage("InitMessageParsers: MsgParserfile '" . $szIncludeFile . "' does not exist!", DEBUG_ERROR);
				}
			}
		}
	}
}

/*
*	This function generates a list of available reports modules and custom reports
*/
function InitReportModules($szRootPath = "")
{
	global $content, $gl_root_path;
	
	// Check for parameter
	if ( strlen($szRootPath) == 0 ) 
		$szRootPath = $gl_root_path; 
	$szDirectory = $szRootPath . 'classes/reports/'; 
	$aFiles = list_files($szDirectory, true); 
	if ( isset($aFiles) && count($aFiles) > 0 )
	{
		foreach( $aFiles as $myFile ) 
		{
			// Check if file is valid msg parser!
			if ( preg_match("/report\.(.*?)\.(.*?)\.class\.php$/", $myFile, $out ) )
			{
				// Set ParserID!
				$myReportCat = $out[1]; 
				$myReportID = $out[2]; 

				// Check if parser file include exists
				$szIncludeFile = $szDirectory . $myFile; 
				if ( file_exists($szIncludeFile) )
				{
					// Try to include
					if ( include_once($szIncludeFile) )
					{
						// Set ParserClassName
						$szReportClass = "Report_" . $myReportID; 
						
						// Create Instance and get properties
						$tmpReport = new $szReportClass(); // Create an instance
						$szReportName = $tmpReport->_reportTitle;
						$szReportDescription = $tmpReport->_reportDescription;
						$szReportVersion= $tmpReport->_reportVersion;
						$szReportHelpArticle = $tmpReport->_reportHelpArticle;
						$bNeedsInit = $tmpReport->_reportNeedsInit;
						$bInitialized = $tmpReport->_reportInitialized;
						$aRequiredFieldsList = $tmpReport->GetRequiredProperties();
						
/*
						// check for required fields!
						if ( $tmpReport->_ClassRequiredFields != null && count($tmpParser->_ClassRequiredFields) > 0 ) 
						{
							$bCustomFields = true;
							$aCustomFieldList = $tmpParser->_ClassRequiredFields; 
//							print_r ( $aCustomFieldList );
						}
						else
						{
							$bCustomFields = false;
							$aCustomFieldList = null;
						}
						*/

						// Add entry to report modules list!
						$content['REPORTS'][$myReportID] = array (
														"ID" => $myReportID, 
														"Category" => $myReportCat, 
														"DisplayName" => $szReportName, 
														"Description" => $szReportDescription, 
														"ReportVersion" => $szReportVersion, 
														"ReportHelpArticle" => $szReportHelpArticle, 
														"NeedsInit" => $bNeedsInit, 
														"Initialized" => $bInitialized, 
														"ObjRef" => $tmpReport, 
//														"CustomFields" => $bCustomFields, 
														"RequiredFieldsList" => $aRequiredFieldsList, 
														);

						// --- Now Search and populate savedReports | but only if DB Version is 9 or higher.
						if ( $content['database_installedversion'] >= 9 )
						{
							// --- Create SQL Query
							$sqlquery = " SELECT " . 
										DB_SAVEDREPORTS . ".ID as SavedReportID, " . 
										DB_SAVEDREPORTS . ".sourceid, " . 
										DB_SAVEDREPORTS . ".customTitle, " . 
										DB_SAVEDREPORTS . ".customComment, " . 
										DB_SAVEDREPORTS . ".filterString, " . 
										DB_SAVEDREPORTS . ".customFilters, " . 
										DB_SAVEDREPORTS . ".outputFormat, " . 
										DB_SAVEDREPORTS . ".outputTarget, " . 
										DB_SAVEDREPORTS . ".outputTargetDetails, " . 
										DB_SAVEDREPORTS . ".scheduleSettings " . 
										" FROM " . DB_SAVEDREPORTS . 
										" WHERE " . DB_SAVEDREPORTS . ".reportid = '" . $myReportID . "' " .  
										" ORDER BY " . DB_SAVEDREPORTS . ".customTitle";

							// Get Views from DB now!
							$result = DB_Query($sqlquery);
							$myrows = DB_GetAllRows($result, true);
							if ( isset($myrows) && count($myrows) > 0 )
							{
								// Set to true!
								$content['REPORTS'][$myReportID]['HASSAVEDREPORTS'] = true;

								// Add all savedreports
								foreach ($myrows as &$mySavedReport)
								{
									// Set default properties if not set!
									if (!isset($mySavedReport['outputTarget']) || strlen($mySavedReport['outputTarget']) <= 0 )
										$mySavedReport['outputTarget'] = REPORT_TARGET_STDOUT; 

									// Add saved report into global array
									$content['REPORTS'][$myReportID]['SAVEDREPORTS'][ $mySavedReport['SavedReportID'] ] = $mySavedReport; 
								}
							}

						}
						// ---
					}
					else
					{
						// DEBUG ERROR
						OutputDebugMessage("InitReportModules: Failed including report file '" . $szIncludeFile . "' with error: '" . $php_errormsg . "'", DEBUG_ERROR);
					}
				}
				else
				{
					// DEBUG ERROR
					OutputDebugMessage("InitReportModules: Reportfile '" . $szIncludeFile . "' does not exist!", DEBUG_ERROR);
				}
			}
		}
	}

	// TODO: compare update report modules registered in database


}

/*
*	Init Source configs
*/
function InitSourceConfigs()
{
	global $CFG, $content, $currentSourceID;

	// Init Source Configs!
	if ( isset($CFG['Sources']) )
	{	
		foreach( $CFG['Sources'] as &$mysource )
		{
			// Init each source using this function!
			InitSource($mysource);
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
			$tmpVar = GetConfigSetting("DefaultSourceID", "", CFGLEVEL_USER);
			if ( isset($content['Sources'][ $tmpVar ]) ) 
				// Set Source to preconfigured sourceID!
				$_SESSION['currentSourceID'] = $tmpVar;
			else
				// No Source stored in session, then to so now!
				$_SESSION['currentSourceID'] = $currentSourceID;
		}
	}
	
	// Set for the selection box in the header
	$content['Sources'][$currentSourceID]['selected'] = "selected";

	// Set Description properties!
	if ( isset($content['Sources'][$currentSourceID]['Description']) && strlen($content['Sources'][$currentSourceID]['Description']) > 0 ) 
	{
		$content['SourceDescriptionEnabled'] = true;
		$content['SourceDescription'] = $content['Sources'][$currentSourceID]['Description']; 
	}
	

	// --- Additional handling needed for the current view!
	global $currentViewID;
	$currentViewID = $content['Sources'][$currentSourceID]['ViewID'];

	// Set selected state for correct View, for selection box ^^
	$content['Views'][ $currentViewID ]['selected'] = "selected";

	// If DEBUG Mode is enabled, we prepend the UID field into the col list!
	
	if ( GetConfigSetting("MiscShowDebugMsg", 0, CFGLEVEL_USER) == 1 && isset($content['Views'][$currentViewID]) )
		array_unshift( $content['Views'][$currentViewID]['Columns'], SYSLOG_UID);
	// ---
}

/*
*	This function Inits preconfigured Views. 
*/
function InitViewConfigs()
{
	global $CFG, $content, $currentViewID;
	
	// Predefined LogAnalyzer Views 
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
	$CFG['Views']['WEBLOG']= array( 
									'ID' =>			"WEBLOG", 
									'DisplayName' =>"Webserver Fields", 
									'Columns' =>	array ( SYSLOG_DATE, SYSLOG_HOST, SYSLOG_WEBLOG_URL, SYSLOG_WEBLOG_USERAGENT, SYSLOG_WEBLOG_STATUS, SYSLOG_WEBLOG_BYTESSEND, SYSLOG_MESSAGE ), 
									'userid' =>		null, 
									'groupid' =>	null, 
								   );
	
	// Set default of 'DefaultViewsID' only if not set already!
	if ( !isset($CFG['DefaultViewsID']) ) 
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
	$tmpVar = GetConfigSetting("DefaultViewsID", "", CFGLEVEL_USER);
	if ( strlen($tmpVar) <= 0 )
		$CFG['DefaultViewsID'] = "LEGACY";
}

function InitPhpLogConConfigFile($bHandleMissing = true)
{
	// Needed to make global
	global $CFG, $gl_root_path, $content;

	// Bugfix for race conditions, clear file stats cache!
	clearstatcache();

	if ( file_exists($gl_root_path . 'config.php') && GetFileLength($gl_root_path . 'config.php') > 0 )
	{
		// Include the main config
		include_once($gl_root_path . 'config.php');
		
		// Easier DB Access
		$tblPref = GetConfigSetting("UserDBPref", "logcon");
		define('DB_CONFIG',			$tblPref . "config");
		define('DB_GROUPS',			$tblPref . "groups");
		define('DB_GROUPMEMBERS',	$tblPref . "groupmembers");
		define('DB_FIELDS',			$tblPref . "fields");
		define('DB_SEARCHES',		$tblPref . "searches");
		define('DB_SOURCES',		$tblPref . "sources");
		define('DB_USERS',			$tblPref . "users");
		define('DB_VIEWS',			$tblPref . "views");
		define('DB_CHARTS',			$tblPref . "charts");
		define('DB_MAPPINGS',		$tblPref . "dbmappings");
		define('DB_SAVEDREPORTS',	$tblPref . "savedreports");

		// Legacy support for old columns definition format!
		if ( isset($CFG['Columns']) && is_array($CFG['Columns']) )
			AppendLegacyColumns();

		// --- Now Copy all entries into content variable
		foreach ($CFG as $key => $value )
			$content[$key] = $value;
		// --- 

		// For MiscShowPageRenderStats
		if ( GetConfigSetting("MiscShowPageRenderStats", 1) )
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
*	Helper function to load configured dbmappings from the database
*/
function InitDiskAllowedSources()
{
	global $CFG, $content;

	// Init Source Configs!
	if ( isset($CFG['DiskAllowed']) )
	{	
		// Copy Array to content array
		$content['DiskAllowed'] = $CFG['DiskAllowed']; 
	}
	else
	{
		// Set default 
		$content['DiskAllowed'][] = "/var/log/"; 
	}
}


/*
*	Helper function to load configured dbmappings from the database
*/
function LoadDBMappingsFromDatabase()
{
	// Needed to make global
	global $dbmapping, $content, $fields;

	// Abort reading fields if the database version is below version 8!, because prior v8, there were no dbmappings table
	if ( $content['database_installedversion'] < 8 )
		return;

	// --- Preprocess fields in loop 
	foreach ($dbmapping as &$myMapping )
	{
		// Set Field to be internal!
		$myMapping['IsInternalMapping'] = true;
		$myMapping['MappingFromDB'] = false;
	}
	// ---

	// --- Create SQL Query
	$sqlquery = " SELECT " . 
				DB_MAPPINGS . ".ID, " . 
				DB_MAPPINGS . ".DisplayName, " . 
				DB_MAPPINGS . ".Mappings " . 
				" FROM " . DB_MAPPINGS . 
				" ORDER BY " . DB_MAPPINGS . ".DisplayName";

	// Get Views from DB now!
	$result = DB_Query($sqlquery);
	$myrows = DB_GetAllRows($result, true);
	if ( isset($myrows) && count($myrows) > 0 )
	{
		// Unpack the Columns and append to Views Array
		foreach ($myrows as &$myMappings)
		{
			// Split into array
			$tmpMappings = explode( ",", $myMappings['Mappings'] );
			
			//Loop through mappings
			foreach ($tmpMappings as &$myMapping )
			{
				// Split subvalues
				$tmpMapping = explode( "=>", $myMapping );

				// check if field is valid
				$fieldId = trim($tmpMapping[0]);
				if ( isset($fields[$fieldId]) ) 
				{
					// Assign mappings
					$myMappings['DBMAPPINGS'][$fieldId] = trim($tmpMapping[1]);
				}
			}

			// Add Mapping to array
			$dbmapping[ $myMappings['ID'] ] = $myMappings;

			// Set FromDB to true
			$dbmapping[ $myMappings['ID'] ]['MappingFromDB'] = true;
		}
	}
	// ---
}


/*
*	Helper function to load configured fields from the database
*/
function LoadFieldsFromDatabase()
{
	// Needed to make global
	global $fields, $content;

	// Abort reading fields if the database version is below version 5!, because prior v5, there were no fields table
	if ( $content['database_installedversion'] < 5 )
		return;

	// --- Preprocess fields in loop 
	foreach ($fields as &$myField )
	{
		// Set Field to be internal!
		$myField['IsInternalField'] = true;
		$myField['FieldFromDB'] = false;
		
		// Set some other defaults!
		if ( !isset($myField['Trunscate']) ) 
			$myField['Trunscate'] = 30;
		if ( !isset($myField['SearchOnline']) ) 
			$myField['SearchOnline'] = false;
		if ( !isset($myField['SearchField']) ) 
			$myField['SearchField'] = $myField['FieldID'];
		
	}
	// ---

	// --- Create SQL Query
	$sqlquery = " SELECT " . 
				DB_FIELDS . ".FieldID, " . 
				DB_FIELDS . ".FieldDefine, " . 
				DB_FIELDS . ".FieldCaption, " . 
				DB_FIELDS . ".FieldType, " . 
				DB_FIELDS . ".FieldAlign, " . 
				DB_FIELDS . ".SearchField, " . 
				DB_FIELDS . ".DefaultWidth, " . 
				DB_FIELDS . ".SearchOnline, " . 
				DB_FIELDS . ".Trunscate, " . 
				DB_FIELDS . ".Sortable " .
				" FROM " . DB_FIELDS . 
				" ORDER BY " . DB_FIELDS . ".FieldCaption";
	// ---

	// Get Searches from DB now!
	$result = DB_Query($sqlquery);
	$myrows = DB_GetAllRows($result, true);
	if ( isset($myrows ) && count($myrows) > 0 )
	{
		// Loop through all data rows 
		foreach ($myrows as &$myField )
		{
			// Read and Set from db!
			$fieldId = $myField['FieldID'];
			$fieldDefine = $myField['FieldDefine'];
			
			// Set define needed in certain code places!
			if ( !defined($fieldDefine) ) 
			{
				define($fieldDefine, $fieldId);
				$fields[$fieldId]['IsInternalField'] = false;
			}
			
			// Copy values
			$fields[$fieldId]['FieldID'] = $myField['FieldID'];
			$fields[$fieldId]['FieldDefine'] = $myField['FieldDefine'];
			$fields[$fieldId]['FieldCaption'] = $myField['FieldCaption'];
			$fields[$fieldId]['FieldType'] = $myField['FieldType'];
			$fields[$fieldId]['FieldAlign'] = $myField['FieldAlign'];
			$fields[$fieldId]['SearchField'] = $myField['SearchField'];
			$fields[$fieldId]['DefaultWidth'] = $myField['DefaultWidth'];
			$fields[$fieldId]['SearchOnline'] = $myField['SearchOnline'];
			$fields[$fieldId]['Trunscate'] = $myField['Trunscate'];
			$fields[$fieldId]['Sortable'] = $myField['Sortable'];

			// Set FromDB to true
			$fields[$fieldId]['FieldFromDB'] = true;
		}

//		print_r ( $fields );
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
		// Overwrite existing Charts array
		unset($CFG['Search']);
		
		// Loop through all data rows 
		foreach ($myrows as &$mySearch )
		{
			// Append to Chart Array
			$CFG['Search'][ $mySearch['ID'] ] = $mySearch;
		}
		
		// Copy to content array!
		$content['Search'] = $CFG['Search'];

//		// Overwrite Search Array with Database one
//		$CFG['Search'] = $myrows;
//		$content['Search'] = $myrows;
	}
}

/*
*	Helper function to load configured Searches from the database
*/
function LoadChartsFromDatabase()
{
	// Needed to make global
	global $CFG, $content;

	// Abort reading charts if the database version is below 3, because prior v3, there were no charts table
	if ( $content['database_installedversion'] < 3 )
		return;

	// Add new fields depending on DB Version!

	// --- Create SQL Query
	// Create Where for USERID
	if ( isset($content['SESSION_LOGGEDIN']) && $content['SESSION_LOGGEDIN'] )
		$szWhereUser = " OR " . DB_CHARTS . ".userid = " . $content['SESSION_USERID'] . " ";
	else
		$szWhereUser = "";

	if ( isset($content['SESSION_GROUPIDS']) )
		$szGroupWhere = " OR " . DB_CHARTS . ".groupid IN (" . $content['SESSION_GROUPIDS'] . ")";
	else
		$szGroupWhere = "";
	$sqlquery = " SELECT " . 
				DB_CHARTS . ".ID, " . 
				DB_CHARTS . ".DisplayName, " . 
				DB_CHARTS . ".chart_enabled, " . 
				DB_CHARTS . ".chart_type, " . 
				DB_CHARTS . ".chart_width, " . 
				DB_CHARTS . ".chart_field, " . 
				DB_CHARTS . ".chart_defaultfilter, " . 
				DB_CHARTS . ".maxrecords, " . 
				DB_CHARTS . ".showpercent, " . 
				DB_CHARTS . ".userid, " .
				DB_CHARTS . ".groupid, " .
				DB_USERS . ".username, " .
				DB_GROUPS . ".groupname " .
				" FROM " . DB_CHARTS . 
				" LEFT OUTER JOIN (" . DB_USERS . ") ON (" . DB_CHARTS . ".userid=" . DB_USERS . ".ID ) " . 
				" LEFT OUTER JOIN (" . DB_GROUPS . ") ON (" . DB_CHARTS . ".groupid=" . DB_GROUPS . ".ID ) " . 
				" WHERE (" . DB_CHARTS . ".userid IS NULL AND " . DB_CHARTS . ".groupid IS NULL) " . 
				$szWhereUser . 
				$szGroupWhere . 
				" ORDER BY " . DB_CHARTS . ".userid, " . DB_CHARTS . ".groupid, " . DB_CHARTS . ".DisplayName";
	// ---

	// Get Searches from DB now!
	$result = DB_Query($sqlquery);
	$myrows = DB_GetAllRows($result, true);
	if ( isset($myrows ) && count($myrows) > 0 )
	{
		// Overwrite existing Charts array
		unset($CFG['Charts']);
		
		// Loop through all data rows 
		foreach ($myrows as &$myChart )
		{
			// Append to Chart Array
			$CFG['Charts'][ $myChart['ID'] ] = $myChart;
		}
		
		// Copy to content array!
		$content['Charts'] = $CFG['Charts'];
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
		// Overwrite existing Sources array
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