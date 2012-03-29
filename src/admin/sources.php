<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Sources Admin File											
	*																	
	* -> Helps administrating LogAnalyzer datasources
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

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Set PAGE to be ADMINPAGE!
define('IS_ADMINPAGE', true);
$content['IS_ADMINPAGE'] = true;
InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!

// Init admin langauge file now!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/admin.php' );
// --- 

// --- Deny if User is READONLY!
if ( !isset($_SESSION['SESSION_ISREADONLY']) || $_SESSION['SESSION_ISREADONLY'] == 1 )
{
	if (	isset($_POST['op']) ||
			(
				isset($_GET['op']) && 
				(
					$_GET['op'] == "add" || 
					$_GET['op'] == "delete" || 
					$_GET['op'] == "cleardata"
				)
			)	
		)
		DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_READONLY'] );
}
// --- 

// --- BEGIN Custom Code

// --- Set Helpervariable for non-ADMIN users
if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
	$content['READONLY_ISUSERONLY'] = "disabled"; 
else
	$content['READONLY_ISUSERONLY'] = ""; 
// --- 

if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "add") 
	{
		// Set Mode to add
		$content['ISEDITORNEWSOURCE'] = "true";
		$content['SOURCE_FORMACTION'] = "addnewsource";
		$content['SOURCE_SENDBUTTON'] = $content['LN_SOURCES_ADD'];
		
		//PreInit these values 
		$content['Name'] = "";
		$content['Description'] = "";
		$content['SourceType'] = SOURCE_DISK;
		CreateSourceTypesList($content['SourceType']);
		$content['MsgParserList'] = "";
		$content['MsgNormalize'] = 0;
		$content['MsgSkipUnparseable'] = 0;
		$content['defaultfilter'] = ""; 
		$content['CHECKED_ISNORMALIZEMSG'] = "";
		$content['CHECKED_ISSKIPUNPARSEABLE'] = "";

		// Init View List!
		$content['SourceViewID'] = 'SYSLOG';
		$content['VIEWS'] = $content['Views'];
		foreach ( $content['VIEWS'] as $myView )
		{
			if ( $myView['ID'] == $content['SourceViewID'] )
				$content['VIEWS'][ $myView['ID'] ]['selected'] = "selected";
			else
				$content['VIEWS'][ $myView['ID'] ]['selected'] = "";
		}

		// SOURCE_DISK specific
		$content['SourceLogLineType'] = ""; 
		CreateLogLineTypesList($content['SourceLogLineType']);
		$content['SourceDiskFile'] = "/var/log/syslog";

		// SOURCE_DB specific
		$content['SourceDBType'] = DB_MYSQL;
		CreateDBTypesList($content['SourceDBType']);
		$content['SourceDBTableType'] = "monitorware";
		CreateDBMappingsList($content['SourceDBTableType']);

		$content['SourceDBName'] = "loganalyzer";
		$content['SourceDBServer'] = "localhost";
		$content['SourceDBTableName'] = "systemevents";
		$content['SourceDBUser'] = "user";
		$content['SourceDBPassword'] = "";
		$content['SourceDBEnableRowCounting'] = "false";
		$content['SourceDBEnableRowCounting_true'] = "";
		$content['SourceDBEnableRowCounting_false'] = "checked";
		$content['SourceDBRecordsPerQuery'] = "100";

		// General stuff
		$content['userid'] = null;
		$content['CHECKED_ISUSERONLY'] = "";
		$content['SOURCEID'] = "";

		// --- Can only create a USER source!
		if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
		{
			$content['userid'] = $content['SESSION_USERID']; 
			$content['CHECKED_ISUSERONLY'] = "checked"; 
		}
		// --- 
		
		// --- Check if groups are available
		$content['SUBGROUPS'] = GetGroupsForSelectfield();
		if ( is_array($content['SUBGROUPS']) )
			$content['ISGROUPSAVAILABLE'] = true;
		else
			$content['ISGROUPSAVAILABLE'] = false;
	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWSOURCE'] = "true";
		$content['SOURCE_FORMACTION'] = "editsource";
		$content['SOURCE_SENDBUTTON'] = $content['LN_SOURCES_EDIT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['SOURCEID'] = DB_RemoveBadChars($_GET['id']);

			// Check if exists
			if ( is_numeric($content['SOURCEID']) && isset($content['Sources'][ $content['SOURCEID'] ]) )
			{
				// Get Source reference
				$mysource = $content['Sources'][ $content['SOURCEID'] ];

				// Copy basic properties
				$content['Name'] = $mysource['Name'];
				$content['Description'] = $mysource['Description'];
				$content['SourceType'] = $mysource['SourceType'];
				CreateSourceTypesList($content['SourceType']);
				$content['MsgParserList'] = $mysource['MsgParserList'];
				$content['MsgNormalize'] = $mysource['MsgNormalize'];
				if ( $mysource['MsgNormalize'] == 1 )
					$content['CHECKED_ISNORMALIZEMSG'] = "checked";
				else
					$content['CHECKED_ISNORMALIZEMSG'] = "";

				$content['MsgSkipUnparseable'] = $mysource['MsgSkipUnparseable'];
				if ( $mysource['MsgSkipUnparseable'] == 1 )
					$content['CHECKED_ISSKIPUNPARSEABLE'] = "checked";
				else
					$content['CHECKED_ISSKIPUNPARSEABLE'] = "";
				$content['defaultfilter'] = $mysource['defaultfilter'];

				// Init View List!
				$content['SourceViewID'] = $mysource['ViewID'];
				$content['VIEWS'] = $content['Views'];
				foreach ( $content['VIEWS'] as $myView )
				{
					if ( $myView['ID'] == $content['SourceViewID'] )
						$content['VIEWS'][ $myView['ID'] ]['selected'] = "selected";
					else
						$content['VIEWS'][ $myView['ID'] ]['selected'] = "";
				}

				// SOURCE_DISK specific
				$content['SourceLogLineType'] = $mysource['LogLineType']; 
				CreateLogLineTypesList($content['SourceLogLineType']);
				$content['SourceDiskFile'] = $mysource['DiskFile'];

				// SOURCE_DB specific
				$content['SourceDBType'] = $mysource['DBType'];
				CreateDBTypesList($content['SourceDBType']);
				$content['SourceDBTableType'] = $mysource['DBTableType'];
				CreateDBMappingsList($content['SourceDBTableType']);

				$content['SourceDBName'] = $mysource['DBName'];
				$content['SourceDBServer'] = $mysource['DBServer'];
				$content['SourceDBTableName'] = $mysource['DBTableName'];
				$content['SourceDBUser'] = $mysource['DBUser'];
				$content['SourceDBPassword'] = $mysource['DBPassword'];
				$content['SourceDBEnableRowCounting'] = $mysource['DBEnableRowCounting'];
				if ( $content['SourceDBEnableRowCounting'] == 1 )
				{
					$content['SourceDBEnableRowCounting_true'] = "checked";
					$content['SourceDBEnableRowCounting_false'] = "";
				}
				else
				{
					$content['SourceDBEnableRowCounting_true'] = "";
					$content['SourceDBEnableRowCounting_false'] = "checked";
				}
				$content['SourceDBRecordsPerQuery'] = $mysource['DBRecordsPerQuery'];

				// Set UserID if set!
				$content['userid'] = $mysource['userid'];
				if ( $content['userid'] != null )
					$content['CHECKED_ISUSERONLY'] = "checked";
				else
					$content['CHECKED_ISUSERONLY'] = "";

				// --- Can only EDIT own views!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 && $content['userid'] == NULL ) 
					DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_NOTALLOWEDTOEDIT'] );
				// --- 
				
				// --- Check if groups are available
				$content['SUBGROUPS'] = GetGroupsForSelectfield();
				if ( is_array($content['SUBGROUPS']) )
				{
					// Process All Groups
					for($i = 0; $i < count($content['SUBGROUPS']); $i++)
					{
						if ( $mysource['groupid'] != null && $content['SUBGROUPS'][$i]['mygroupid'] == $mysource['groupid'] )
							$content['SUBGROUPS'][$i]['group_selected'] = "selected";
						else
							$content['SUBGROUPS'][$i]['group_selected'] = "";
					}

					// Enable Group Selection
					$content['ISGROUPSAVAILABLE'] = true;
				}
				else
					$content['ISGROUPSAVAILABLE'] = false;
				// ---
			}
			else
			{
				$content['ISEDITORNEWSOURCE'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] =  $content['LN_SOURCES_ERROR_INVALIDORNOTFOUNDID'];
			}
		}
		else
		{
			$content['ISEDITORNEWSEARCH'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] =  $content['LN_SEARCH_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "delete") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['SOURCEID'] = DB_RemoveBadChars($_GET['id']);

			// Get SourceInfo
			$result = DB_Query("SELECT Name FROM " . DB_SOURCES . " WHERE ID = " . $content['SOURCEID'] ); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['Name']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_IDNOTFOUND'], $content['SOURCEID'] ); 
			}

			// --- Ask for deletion first!
			if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_SOURCES_WARNDELETESEARCH'], $myrow['Name'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---

			// do the delete!
			$result = DB_Query( "DELETE FROM " . DB_SOURCES . " WHERE ID = " . $content['SOURCEID'] );
			if ($result == FALSE)
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_DELSOURCE'], $content['SOURCEID'] ); 
			}
			else
				DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_HASBEENDEL'], $myrow['Name'] ) , "sources.php" );
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_SOURCES_ERROR_INVALIDORNOTFOUNDID'];
		}
	}
	else if ($_GET['op'] == "cleardata") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['SOURCEID'] = DB_RemoveBadChars($_GET['id']);
		}

		// Check If source is available
		if ( !isset($content['Sources'][ $content['SOURCEID'] ]) )
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_IDNOTFOUND'], $content['SOURCEID'] ); 
		}
		else
		{
			// Include LogStream facility
			include($gl_root_path . 'classes/logstream.class.php');
			
			// --- Init the source
			$tmpSource = $content['Sources'][ $content['SOURCEID'] ];

			// Copy some default properties
			$content['DisplayName'] = $tmpSource['Name'];
			$content['SourceType'] = $tmpSource['SourceType'];
			CreateSourceTypesList($content['SourceType']);
			$content['SourceTypeName'] = $content['SOURCETYPES'][ $content['SourceType'] ]['DisplayName'];

			// Fix Filename manually for FILE LOGSTREAM!
			if (	$content['SourceType'] == SOURCE_DB || 
					$content['SourceType'] == SOURCE_PDO || 
					$content['SourceType'] == SOURCE_MONGODB ) 
			{
				// Create LogStream Object 
				$stream = $tmpSource['ObjRef']->LogStreamFactory($tmpSource['ObjRef']);

				// Verify if datasource is available
				$res = $stream->Verify();
				if ( $res != SUCCESS ) 
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_WITHINSOURCE'], $tmpSource['Name'], GetErrorMessage($res) );
					if ( isset($extraErrorDescription) )
						$content['ERROR_MSG'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
				}
				else
				{
					// Display Stats
					$content['ISCLEARDATA'] = true;

					// Gather Database Stats
					$content['ROWCOUNT'] = $stream->GetLogStreamTotalRowCount();
					if ( isset($content['ROWCOUNT']) )
					{
						// Check for suboperations
						if ( isset($_POST['subop']) )
						{
							if		( $_POST['subop'] == "all" ) 
							{
								$timestamp = 0;
							}
							else if ( $_POST['subop'] == "since" && isset($_POST['olderthan']) ) 
							{
								// Take current time and subtract Seconds
								$nSecondsSubtract = $_POST['olderthan'];
								$timestamp = time() - $nSecondsSubtract;
							}
							else if ( $_POST['subop'] == "date" && isset($_POST['olderdate_year']) && isset($_POST['olderdate_month']) && isset($_POST['olderdate_day']) ) 
							{
								// Generate Timestamp
								$timestamp = mktime( 0, 0, 0, intval($_POST['olderdate_month']), intval($_POST['olderdate_day']), intval($_POST['olderdate_year']) );
							}
							// Continue with delete only inif wherequery is set!
							if ( isset($timestamp) ) 
							{
								// --- Ask for deletion first!
								if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
								{
									// This will print an additional secure check which the user needs to confirm and exit the script execution.
									PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_SOURCES_WARNDELETEDATA'], $content['DisplayName'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
								}
								// ---

								// Now perform the data cleanup!
								$content['affectedrows'] = $stream->CleanupLogdataByDate($timestamp);

								if ( !isset($content['affectedrows']) )
								{
									$content['ISERROR'] = true;
									$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_DELDATA'], $content['SOURCEID'] ); 
								}
								else
								{
									// Do the final redirect
									RedirectResult( GetAndReplaceLangStr( $content['LN_SOURCES_HASBEENDELDATA'], $content['DisplayName'], $content['affectedrows'] ) , "sources.php" );
								}
							}
							else
							{
								$content['ISERROR'] = true;
								$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_INVALIDCLEANUP'], $content['DisplayName'] ); 
							}
						}
						else
						{
							// Allow Deleting by Date
							$content['DELETE_ALLOWDETAIL'] = true;

							// Create Lists
							CreateOlderThanList( 3600 );
							CreateOlderDateFields();
						}

					}
					else 
						$content['ROWCOUNT'] = "Unknown";
				}
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_NOCLEARSUPPORT'], $content['SOURCEID'] ); 
			}
		}
	}
	else if ($_GET['op'] == "dbstats") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['SOURCEID'] = DB_RemoveBadChars($_GET['id']);
		}

		// Check If source is available
		if ( !isset($content['Sources'][ $content['SOURCEID'] ]) )
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_IDNOTFOUND'], $content['SOURCEID'] ); 
		}
		else
		{
			// Include LogStream facility
			include($gl_root_path . 'classes/logstream.class.php');
			
			// --- Init the source
			$tmpSource = $content['Sources'][ $content['SOURCEID'] ];

			// Copy some default properties
			$content['DisplayName'] = $tmpSource['Name'];
			$content['Description'] = $tmpSource['Description'];
			$content['SourceType'] = $tmpSource['SourceType'];
			CreateSourceTypesList($content['SourceType']);
			$content['SourceTypeName'] = $content['SOURCETYPES'][ $content['SourceType'] ]['DisplayName'];

			// Fix Filename manually for FILE LOGSTREAM!
			if ( $content['SourceType'] == SOURCE_DISK ) 
			{
				$tmpSource['DiskFile'] = CheckAndPrependRootPath(DB_StripSlahes($tmpSource['DiskFile']));
				$tmpSource['ObjRef']->FileName = $tmpSource['DiskFile'];
			}

			// Create LogStream Object 
			$stream = $tmpSource['ObjRef']->LogStreamFactory($tmpSource['ObjRef']);
			$res = $stream->Verify();
			if ( $res != SUCCESS ) 
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_WITHINSOURCE'], $tmpSource['Name'], GetErrorMessage($res) );
				if ( isset($extraErrorDescription) )
					$content['ERROR_MSG'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
			}
			else
			{
				// Gather Database Stats
				$content['STATS'] = $stream->GetLogStreamStats();
				if ( isset($content['STATS']) )
				{
					// Display Stats
					$content['ISSTATS'] = true;

					foreach( $content['STATS'] as &$myStats )
					{
						$i = 0;
						foreach( $myStats['STATSDATA'] as &$myStatsData )
						{
							// --- Set CSS Class
							if ( $i % 2 == 0 )
								$myStatsData['cssclass'] = "line1";
							else
								$myStatsData['cssclass'] = "line2";
							$i++;
							// --- 
						}
					}

				}
				else
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_NOSTATSDATA'], $content['SOURCEID'] ); 
				}
//				print_r ( $content['STATS'] );
			}
			// ---
		}
	}
}

if ( isset($_POST['op']) )
{
	// Read parameters first!
	if ( isset($_POST['id']) ) { $content['SOURCEID'] = intval(DB_RemoveBadChars($_POST['id'])); } else {$content['SOURCEID'] = -1; }
	if ( isset($_POST['Name']) ) { $content['Name'] = DB_RemoveBadChars($_POST['Name']); } else {$content['Name'] = ""; }
	if ( isset($_POST['Description']) ) { $content['Description'] = DB_RemoveBadChars($_POST['Description']); } else {$content['Description'] = ""; }
	if ( isset($_POST['SourceType']) ) { $content['SourceType'] = DB_RemoveBadChars($_POST['SourceType']); }
	if ( isset($_POST['MsgParserList']) ) { $content['MsgParserList'] = DB_RemoveBadChars($_POST['MsgParserList']); }
	if ( isset($_POST['MsgNormalize']) ) { $content['MsgNormalize'] = intval(DB_RemoveBadChars($_POST['MsgNormalize'])); } else {$content['MsgNormalize'] = 0; }
	if ( isset($_POST['MsgSkipUnparseable']) ) { $content['MsgSkipUnparseable'] = intval(DB_RemoveBadChars($_POST['MsgSkipUnparseable'])); } else {$content['MsgSkipUnparseable'] = 0; }
	if ( isset($_POST['SourceViewID']) ) { $content['SourceViewID'] = DB_RemoveBadChars($_POST['SourceViewID']); }
	if ( isset($_POST['defaultfilter']) ) { $content['defaultfilter'] = DB_RemoveBadChars($_POST['defaultfilter']); }

	if ( isset($content['SourceType']) )
	{
		// Disk Params
		if ( $content['SourceType'] == SOURCE_DISK ) 
		{
			if ( isset($_POST['SourceLogLineType']) ) { $content['SourceLogLineType'] = DB_RemoveBadChars($_POST['SourceLogLineType']); }
			if ( isset($_POST['SourceDiskFile']) ) { $content['SourceDiskFile'] = DB_RemoveBadChars($_POST['SourceDiskFile']); }
		}
		// DB Params
		else if (	$content['SourceType'] == SOURCE_DB || 
					$content['SourceType'] == SOURCE_PDO || 
					$content['SourceType'] == SOURCE_MONGODB 
			) 
		{
			if ( isset($_POST['SourceDBType']) ) { $content['SourceDBType'] = DB_RemoveBadChars($_POST['SourceDBType']); }
			if ( isset($_POST['SourceDBName']) ) { $content['SourceDBName'] = DB_RemoveBadChars($_POST['SourceDBName']); }
			if ( isset($_POST['SourceDBTableType']) ) { $content['SourceDBTableType'] = DB_RemoveBadChars($_POST['SourceDBTableType']); }
			if ( isset($_POST['SourceDBServer']) ) { $content['SourceDBServer'] = DB_RemoveBadChars($_POST['SourceDBServer']); }
			if ( isset($_POST['SourceDBTableName']) ) { $content['SourceDBTableName'] = DB_RemoveBadChars($_POST['SourceDBTableName']); }
			if ( isset($_POST['SourceDBUser']) ) { $content['SourceDBUser'] = DB_RemoveBadChars($_POST['SourceDBUser']); }
			if ( isset($_POST['SourceDBRecordsPerQuery']) ) { $content['SourceDBRecordsPerQuery'] = DB_RemoveBadChars($_POST['SourceDBRecordsPerQuery']); }
			if ( isset($_POST['SourceDBPassword']) ) { $content['SourceDBPassword'] = DB_RemoveBadChars($_POST['SourceDBPassword']); } else {$content['SourceDBPassword'] = ""; }
			if ( isset($_POST['SourceDBEnableRowCounting']) ) {	$content['SourceDBEnableRowCounting'] = DB_RemoveBadChars($_POST['SourceDBEnableRowCounting']); }
			// Extra Check for this property
			if ( $content['SourceDBEnableRowCounting'] != "true" )
				$content['SourceDBEnableRowCounting'] = "false";
		}
	}

	// User & Group handeled specially
	if ( isset ($_POST['isuseronly']) ) 
	{ 
		$content['userid'] = $content['SESSION_USERID']; 
		$content['groupid'] = "null"; // Either user or group not both!
	} 
	else 
	{
		// --- Can only create a USER source!
		if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
		{
			$content['userid'] = $content['SESSION_USERID']; 
			$content['groupid'] = "null"; 
		}
		else
		{
			$content['userid'] = "null"; 
			if ( isset ($_POST['groupid']) && $_POST['groupid'] != -1 ) 
				$content['groupid'] = intval($_POST['groupid']); 
			else 
				$content['groupid'] = "null";
		}
	}

	// --- Check mandotary values
	if ( $content['Name'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_NAMEOFTHESOURCE'] );
	}
	else if ( !isset($content['SourceType']) ) 
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_SOURCETYPE'] );
	}
	else if ( !isset($content['SourceViewID']) ) 
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_VIEW'] );
	}
	else
	{
		// Disk Params
		if ( $content['SourceType'] == SOURCE_DISK ) 
		{
			if ( !isset($content['SourceLogLineType']) ) 
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_LOGLINETYPE'] );
			}
			else if ( !isset($content['SourceDiskFile']) )
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_SYSLOGFILE'] );
			}
			// Check if file is accessable!
			else 
			{
				// Get plain filename for testing!
				$content['SourceDiskFileTesting'] = CheckAndPrependRootPath(DB_StripSlahes($content['SourceDiskFile']));
				/*
				// Take as it is if rootpath!
				if (
						( ($pos = strpos($content['SourceDiskFileTesting'], "/")) !== FALSE && $pos == 0) ||
						( ($pos = strpos($content['SourceDiskFileTesting'], "\\\\")) !== FALSE && $pos == 0) ||
						( ($pos = strpos($content['SourceDiskFileTesting'], ":\\")) !== FALSE ) ||
						( ($pos = strpos($content['SourceDiskFileTesting'], ":/")) !== FALSE )
					)
				{
					// Nothing really todo
					true;
				}
				else // prepend basepath!
					$content['SourceDiskFileTesting'] = $gl_root_path . $content['SourceDiskFileTesting'];
				*/
			}
		}
		// DB Params
		else if (	$content['SourceType'] == SOURCE_DB || 
					$content['SourceType'] == SOURCE_PDO ||
					$content['SourceType'] == SOURCE_MONGODB 
			) 
		{
			if ( !isset($content['SourceDBType']) ) 
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_DATABASETYPEOPTIONS'] );
			}
			else if ( !isset($content['SourceDBName']) )
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_DBNAME'] );
			}
			else if ( !isset($content['SourceDBTableType']) )
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_DBTABLETYPE'] );
			}
			else if ( !isset($content['SourceDBServer']) )
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_DBSERVER'] );
			}
			else if ( !isset($content['SourceDBTableName']) )
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_DBTABLENAME'] );
			}
			else if ( !isset($content['SourceDBUser']) )
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_MISSINGPARAM'], $content['LN_CFG_DBUSER'] );
			}
			else if ( !is_numeric($content['SourceDBRecordsPerQuery']) )
			{ 
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_INVALIDVALUE'], $content['LN_CFG_DBRECORDSPERQUERY'] );
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_UNKNOWNSOURCE'], $content['SourceDBType'] );
		}

		// --- Verify the Source and report and error if needed!

		// Include LogStream facility
		include($gl_root_path . 'classes/logstream.class.php');

		// First create a tmp source array
		$tmpSource['ID']				= $content['SOURCEID'];
		$tmpSource['Name']				= $content['Name'];
		$tmpSource['Description']		= $content['Description'];
		$tmpSource['SourceType']		= $content['SourceType'];
		$tmpSource['MsgParserList']		= $content['MsgParserList'];
		$tmpSource['MsgNormalize']		= $content['MsgNormalize'];
		$tmpSource['MsgSkipUnparseable']= $content['MsgSkipUnparseable'];
		$tmpSource['defaultfilter']		= $content['defaultfilter'];
		$tmpSource['ViewID']			= $content['SourceViewID'];
		if ( $tmpSource['SourceType'] == SOURCE_DISK ) 
		{
			$tmpSource['LogLineType']	= $content['SourceLogLineType'];
			$tmpSource['DiskFile']		= $content['SourceDiskFileTesting']; // use SourceDiskFileTesting rather then SourceDiskFile as it is corrected
		}
		// DB Params
		else if (	$tmpSource['SourceType'] == SOURCE_DB || 
					$tmpSource['SourceType'] == SOURCE_PDO || 
					$tmpSource['SourceType'] == SOURCE_MONGODB 
				) 
		{
			$tmpSource['DBType']				= DB_StripSlahes($content['SourceDBType']);
			$tmpSource['DBName']				= DB_StripSlahes($content['SourceDBName']);
			$tmpSource['DBTableType']			= DB_StripSlahes($content['SourceDBTableType']);
			$tmpSource['DBServer']				= DB_StripSlahes($content['SourceDBServer']);
			$tmpSource['DBTableName']			= DB_StripSlahes($content['SourceDBTableName']);
			$tmpSource['DBUser']				= DB_StripSlahes($content['SourceDBUser']);
			$tmpSource['DBPassword']			= DB_StripSlahes($content['SourceDBPassword']);
			$tmpSource['DBEnableRowCounting']	= $content['SourceDBEnableRowCounting'];
			$tmpSource['DBRecordsPerQuery']		= $content['SourceDBRecordsPerQuery'];
			$tmpSource['userid']		= $content['userid'];
			$tmpSource['groupid']		= $content['groupid'];
		}

		// Init the source
		InitSource($tmpSource);

		// Create LogStream Object 
		$stream = $tmpSource['ObjRef']->LogStreamFactory($tmpSource['ObjRef']);
		$res = $stream->Verify();
		if ( $res != SUCCESS ) 
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_WITHINSOURCE'], $tmpSource['Name'], GetErrorMessage($res) );

			if ( isset($extraErrorDescription) )
				$content['ERROR_MSG'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
		}
		// ---
	}

	// --- Now ADD/EDIT do the processing!
	if ( !isset($content['ISERROR']) ) 
	{	
		// Everything was alright, so we go to the next step!
		if ( $_POST['op'] == "addnewsource" )
		{
			// Add custom search now!
			if ( $content['SourceType'] == SOURCE_DISK ) 
			{
				$sqlquery = "INSERT INTO " . DB_SOURCES . " (Name, Description, SourceType, MsgParserList, MsgNormalize, MsgSkipUnparseable, defaultfilter, ViewID, LogLineType, DiskFile, userid, groupid) 
				VALUES ('" . $content['Name'] . "', 
						'" . $content['Description'] . "',
						" . $content['SourceType'] . ", 
						'" . $content['MsgParserList'] . "',
						" . $content['MsgNormalize'] . ", 
						" . $content['MsgSkipUnparseable'] . ", 
						'" . $content['defaultfilter'] . "',
						'" . $content['SourceViewID'] . "',
						'" . $content['SourceLogLineType'] . "',
						'" . $content['SourceDiskFile'] . "',
						" . $content['userid'] . ", 
						" . $content['groupid'] . " 
						)";
			}
			else if (	$content['SourceType'] == SOURCE_DB || 
						$content['SourceType'] == SOURCE_PDO ||
						$content['SourceType'] == SOURCE_MONGODB
				) 
			{
				$sqlquery = "INSERT INTO " . DB_SOURCES . " (Name, Description, SourceType, MsgParserList, MsgNormalize, MsgSkipUnparseable, defaultfilter, ViewID, DBTableType, DBType, DBServer, DBName, DBUser, DBPassword, DBTableName, DBEnableRowCounting, DBRecordsPerQuery, userid, groupid) 
				VALUES ('" . $content['Name'] . "', 
						'" . $content['Description'] . "',
						" . $content['SourceType'] . ", 
						'" . $content['MsgParserList'] . "', 
						" . $content['MsgNormalize'] . ", 
						" . $content['MsgSkipUnparseable'] . ", 
						'" . $content['defaultfilter'] . "',
						'" . $content['SourceViewID'] . "',
						'" . $content['SourceDBTableType'] . "',
						" . $content['SourceDBType'] . ",
						'" . $content['SourceDBServer'] . "',
						'" . $content['SourceDBName'] . "',
						'" . $content['SourceDBUser'] . "',
						'" . $content['SourceDBPassword'] . "',
						'" . $content['SourceDBTableName'] . "',
						" . $content['SourceDBEnableRowCounting'] . ",
						" . $content['SourceDBRecordsPerQuery'] . ",
						" . $content['userid'] . ", 
						" . $content['groupid'] . " 
						)";
			}

			$result = DB_Query($sqlquery);
			DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_SOURCE_HASBEENADDED'], DB_StripSlahes($content['Name']) ) , "sources.php" );
		}
		else if ( $_POST['op'] == "editsource" )
		{
			$result = DB_Query("SELECT ID FROM " . DB_SOURCES . " WHERE ID = " . $content['SOURCEID']);
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['ID']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_IDNOTFOUND'], $content['SOURCEID'] ); 
			}
			else
			{
				// Edit the Search Entry now!
				if ( $content['SourceType'] == SOURCE_DISK ) 
				{
					$sqlquery =	"UPDATE " . DB_SOURCES . " SET 
									Name = '" . $content['Name'] . "', 
									Description = '" . $content['Description'] . "', 
									SourceType = " . $content['SourceType'] . ", 
									MsgParserList = '" . $content['MsgParserList'] . "', 
									MsgNormalize = " . $content['MsgNormalize'] . ", 
									MsgSkipUnparseable = " . $content['MsgSkipUnparseable'] . ", 
									defaultfilter = '" . $content['defaultfilter'] . "', 
									ViewID = '" . $content['SourceViewID'] . "', 
									LogLineType = '" . $content['SourceLogLineType'] . "', 
									DiskFile = '" . $content['SourceDiskFile'] . "', 
									userid = " . $content['userid'] . ", 
									groupid = " . $content['groupid'] . "
									WHERE ID = " . $content['SOURCEID'];
				}
				else if (	$content['SourceType'] == SOURCE_DB || 
							$content['SourceType'] == SOURCE_PDO || 
							$content['SourceType'] == SOURCE_MONGODB 
					) 
				{
					$sqlquery =	"UPDATE " . DB_SOURCES . " SET 
									Name = '" . $content['Name'] . "', 
									Description = '" . $content['Description'] . "', 
									SourceType = " . $content['SourceType'] . ", 
									MsgParserList = '" . $content['MsgParserList'] . "', 
									MsgNormalize = " . $content['MsgNormalize'] . ", 
									MsgSkipUnparseable = " . $content['MsgSkipUnparseable'] . ", 
									defaultfilter = '" . $content['defaultfilter'] . "', 
									ViewID = '" . $content['SourceViewID'] . "', 
									DBTableType = '" . $content['SourceDBTableType'] . "', 
									DBType = " . $content['SourceDBType'] . ", 
									DBServer = '" . $content['SourceDBServer'] . "', 
									DBName = '" . $content['SourceDBName'] . "', 
									DBUser = '" . $content['SourceDBUser'] . "', 
									DBPassword = '" . $content['SourceDBPassword'] . "', 
									DBTableName = '" . $content['SourceDBTableName'] . "', 
									DBEnableRowCounting = " . $content['SourceDBEnableRowCounting'] . ", 
									DBRecordsPerQuery = " . $content['SourceDBRecordsPerQuery'] . ", 
									userid = " . $content['userid'] . ", 
									groupid = " . $content['groupid'] . "
									WHERE ID = " . $content['SOURCEID'];
				}

				$result = DB_Query($sqlquery);
				DB_FreeQuery($result);

				// Done redirect!
				RedirectResult( GetAndReplaceLangStr( $content['LN_SOURCES_HASBEENEDIT'], DB_StripSlahes($content['Name']) ) , "sources.php" );
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Searches
	$content['LISTSOURCES'] = "true";

	// Copy Sources array for further modifications
	$content['SOURCES'] = $content['Sources'];

	// --- Process Sources
	$i = 0; // Help counter!
	foreach ($content['SOURCES'] as &$mySource )
	{
		// --- Set Image for Type
		// NonNUMERIC are config files Sources, can not be editied
		if ( is_numeric($mySource['ID']) )
		{
			// Allow EDIT
			$mySource['ActionsAllowed'] = true;

			if ( $mySource['userid'] != null )
			{
				$mySource['SourcesAssignedToImage']	= $content["MENU_ADMINUSERS"];
				$mySource['SourcesAssignedToText']	= $content["LN_GEN_USERONLY"];
			}
			else if ( $mySource['groupid'] != null )
			{
				$mySource['SourcesAssignedToImage']	= $content["MENU_ADMINGROUPS"];
				$mySource['SourcesAssignedToText']	= GetAndReplaceLangStr( $content["LN_GEN_GROUPONLYNAME"], $mySource['groupname'] );

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$mySource['ActionsAllowed'] = false;
			}
			else
			{
				$mySource['SourcesAssignedToImage']	= $content["MENU_GLOBAL"];
				$mySource['SourcesAssignedToText']	= $content["LN_GEN_GLOBAL"];

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$mySource['ActionsAllowed'] = false;
			}
		}
		else
		{
			// Disallow EDIT
			$mySource['ActionsAllowed'] = false;

			$mySource['SourcesAssignedToImage'] = $content["MENU_INTERNAL"];
			$mySource['SourcesAssignedToText'] = $content["LN_GEN_CONFIGFILE"];
		}
		// ---

		// --- Set SourceType
		if ( $mySource['SourceType'] == SOURCE_DISK )
		{
			$mySource['SourcesTypeImage'] = $content["MENU_SOURCE_DISK"];
			$mySource['SourcesTypeText'] = $content["LN_SOURCES_DISK"];
		}
		else if ( $mySource['SourceType'] == SOURCE_DB )
		{
			$mySource['SourcesTypeImage'] = $content["MENU_SOURCE_DB"];
			$mySource['SourcesTypeText'] = $content["LN_SOURCES_DB"];
			
			// Enabled Database Maintenance functions
			$mySource['IsDatabaseSource'] = true;
		}
		else if ( $mySource['SourceType'] == SOURCE_PDO )
		{
			$mySource['SourcesTypeImage'] = $content["MENU_SOURCE_PDO"];
			$mySource['SourcesTypeText'] = $content["LN_SOURCES_PDO"];

			// Enabled Database Maintenance functions
			$mySource['IsDatabaseSource'] = true;
		}
		else if ( $mySource['SourceType'] == SOURCE_MONGODB )
		{
			$mySource['SourcesTypeImage'] = $content["MENU_SOURCE_MONGODB"];
			$mySource['SourcesTypeText'] = $content["LN_SOURCES_MONGODB"];

			// Enabled Database Maintenance functions
			$mySource['IsDatabaseSource'] = true;
		}
		
		// ---

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$mySource['cssclass'] = "line1";
		else
			$mySource['cssclass'] = "line2";
		$i++;
		// --- 
	}
	// --- 
//	print_r ( $content['SOURCES'] );
}

/*
* Helper function to read and init available msg parsers
*/
function ReadMsgParserList()
{
	global $gl_root_path, $content; 
}

/*
* Helper function to create a OlderThan Listbox
*/
function CreateOlderThanList( $nDefaultSeconds )
{
	global $content; 

	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "1 minute", 'OlderThanSeconds' => 60 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "5 minutes", 'OlderThanSeconds' => 300 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "15 minutes", 'OlderThanSeconds' => 900 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "30 minutes", 'OlderThanSeconds' => 1800 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "1 hour", 'OlderThanSeconds' => 3600 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "12 hours", 'OlderThanSeconds' => 43200 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "1 day", 'OlderThanSeconds' => 86400 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "7 days", 'OlderThanSeconds' => 604800 );
	$content['OLDERTHAN'][] = array( 'OlderThanDisplayName' => "31 days", 'OlderThanSeconds' => 2678400 );

	foreach ( $content['OLDERTHAN'] as &$myTime ) 
	{
		if ( $nDefaultSeconds == $myTime['OlderThanSeconds'] ) 
			$myTime['selected'] = "selected";
		else 
			$myTime['selected'] = "";
	}
}

/*
* Helper function to create a OlderThan Listbox
*/
function CreateOlderDateFields()
{
	global $content; 

	$currentTime = time();
	$currentDay = date("d", $currentTime);
	$currentMonth = date("m", $currentTime);
	$currentYear = date("Y", $currentTime);

	// Init Year, month and day array!
	for ( $i = $currentYear-5; $i <= $currentYear+5; $i++ )
	{
		$content['olderdate_years'][$i]['value'] = $i;
		if ( $i == $currentYear ) { $content['olderdate_years'][$i]['selected'] = "selected"; } else { $content['olderdate_years'][$i]['selected'] = ""; }
	}
	for ( $i = 1; $i <= 12; $i++ )
	{
		$content['olderdate_months'][$i]['value'] = $i;
		if ( $i == $currentMonth ) { $content['olderdate_months'][$i]['selected'] = "selected"; } else { $content['olderdate_months'][$i]['selected'] = ""; }
	}
	for ( $i = 1; $i <= 31; $i++ )
	{
		$content['olderdate_days'][$i]['value'] = $i;
		if ( $i == $currentDay ) { $content['olderdate_days'][$i]['selected'] = "selected"; } else { $content['olderdate_days'][$i]['selected'] = ""; }
	}


}

// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_SOURCEOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_sources.html");
$page -> output(); 
// --- 

?>