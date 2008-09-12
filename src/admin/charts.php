<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Charts Admin File											
	*																	
	* -> Helps administrating phplogcon charts & graphics
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

// --- BEGIN Custom Code

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
		$content['SourceType'] = SOURCE_DISK;
		CreateSourceTypesList($content['SourceType']);
		$content['MsgParserList'] = "";
		$content['MsgNormalize'] = 0;
		$content['CHECKED_ISNORMALIZEMSG'] = "";

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
		$content['SourceDBName'] = "phplogcon";
		$content['SourceDBTableType'] = "monitorware";
		$content['SourceDBServer'] = "localhost";
		$content['SourceDBTableName'] = "systemevents";
		$content['SourceDBUser'] = "user";
		$content['SourceDBPassword'] = "";
		$content['SourceDBEnableRowCounting'] = "false";
		$content['SourceDBEnableRowCounting_true'] = "";
		$content['SourceDBEnableRowCounting_false'] = "checked";

		// General stuff
		$content['userid'] = null;
		$content['CHECKED_ISUSERONLY'] = "";
		$content['SOURCEID'] = "";
		
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
				$content['SourceType'] = $mysource['SourceType'];
				CreateSourceTypesList($content['SourceType']);
				$content['MsgParserList'] = $mysource['MsgParserList'];
				$content['MsgNormalize'] = $mysource['MsgNormalize'];
				if ( $mysource['MsgNormalize'] == 1 )
					$content['CHECKED_ISNORMALIZEMSG'] = "checked";
				else
					$content['CHECKED_ISNORMALIZEMSG'] = "";

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
				$content['SourceDBName'] = $mysource['DBName'];
				$content['SourceDBTableType'] = $mysource['DBTableType'];
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

				if ( $mysource['userid'] != null )
					$content['CHECKED_ISUSERONLY'] = "checked";
				else
					$content['CHECKED_ISUSERONLY'] = "";
				
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

			// Get UserInfo
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
}

if ( isset($_POST['op']) )
{
	// Read parameters first!
	if ( isset($_POST['id']) ) { $content['SOURCEID'] = intval(DB_RemoveBadChars($_POST['id'])); } else {$content['SOURCEID'] = -1; }
	if ( isset($_POST['Name']) ) { $content['Name'] = DB_RemoveBadChars($_POST['Name']); } else {$content['Name'] = ""; }
	if ( isset($_POST['SourceType']) ) { $content['SourceType'] = DB_RemoveBadChars($_POST['SourceType']); }
	if ( isset($_POST['MsgParserList']) ) { $content['MsgParserList'] = DB_RemoveBadChars($_POST['MsgParserList']); }
	if ( isset($_POST['MsgNormalize']) ) { $content['MsgNormalize'] = intval(DB_RemoveBadChars($_POST['MsgNormalize'])); } else {$content['MsgNormalize'] = 0; }
	if ( isset($_POST['SourceViewID']) ) { $content['SourceViewID'] = DB_RemoveBadChars($_POST['SourceViewID']); }

	if ( isset($content['SourceType']) )
	{
		// Disk Params
		if ( $content['SourceType'] == SOURCE_DISK ) 
		{
			if ( isset($_POST['SourceLogLineType']) ) { $content['SourceLogLineType'] = DB_RemoveBadChars($_POST['SourceLogLineType']); }
			if ( isset($_POST['SourceDiskFile']) ) { $content['SourceDiskFile'] = DB_RemoveBadChars($_POST['SourceDiskFile']); }
		}
		// DB Params
		else if ( $content['SourceType'] == SOURCE_DB || $content['SourceType'] == SOURCE_PDO ) 
		{
			if ( isset($_POST['SourceDBType']) ) { $content['SourceDBType'] = DB_RemoveBadChars($_POST['SourceDBType']); }
			if ( isset($_POST['SourceDBName']) ) { $content['SourceDBName'] = DB_RemoveBadChars($_POST['SourceDBName']); }
			if ( isset($_POST['SourceDBTableType']) ) { $content['SourceDBTableType'] = DB_RemoveBadChars($_POST['SourceDBTableType']); }
			if ( isset($_POST['SourceDBServer']) ) { $content['SourceDBServer'] = DB_RemoveBadChars($_POST['SourceDBServer']); }
			if ( isset($_POST['SourceDBTableName']) ) { $content['SourceDBTableName'] = DB_RemoveBadChars($_POST['SourceDBTableName']); }
			if ( isset($_POST['SourceDBUser']) ) { $content['SourceDBUser'] = DB_RemoveBadChars($_POST['SourceDBUser']); }
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
		$content['userid'] = "null"; 
		if ( isset ($_POST['groupid']) && $_POST['groupid'] != -1 ) 
			$content['groupid'] = intval($_POST['groupid']); 
		else 
			$content['groupid'] = "null";
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
				$content['SourceDiskFileTesting'] = DB_StripSlahes($content['SourceDiskFile']);

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
/*
				if ( !is_file($content['SourceDiskFileTesting']) )
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_NOTAVALIDFILE'], $szFileName );
				}
*/
			}
		}
		// DB Params
		else if ( $content['SourceType'] == SOURCE_DB || $content['SourceType'] == SOURCE_PDO ) 
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
		$tmpSource['ID']			= $content['SOURCEID'];
		$tmpSource['Name']			= $content['Name'];
		$tmpSource['SourceType']	= $content['SourceType'];
		$tmpSource['MsgParserList']	= $content['MsgParserList'];
		$tmpSource['MsgNormalize']	= $content['MsgNormalize'];
		$tmpSource['ViewID']		= $content['SourceViewID'];
		if ( $tmpSource['SourceType'] == SOURCE_DISK ) 
		{
			$tmpSource['LogLineType']	= $content['SourceLogLineType'];
			$tmpSource['DiskFile']		= $content['SourceDiskFileTesting']; // use SourceDiskFileTesting rather then SourceDiskFile as it is corrected
		}
		// DB Params
		else if ( $tmpSource['SourceType'] == SOURCE_DB || $tmpSource['SourceType'] == SOURCE_PDO ) 
		{
			$tmpSource['DBType']		= $content['SourceDBType'];
			$tmpSource['DBName']		= $content['SourceDBName'];
			$tmpSource['DBTableType']	= $content['SourceDBTableType'];
			$tmpSource['DBServer']		= $content['SourceDBServer'];
			$tmpSource['DBTableName']	= $content['SourceDBTableName'];
			$tmpSource['DBUser']		= $content['SourceDBUser'];
			$tmpSource['DBPassword']	= $content['SourceDBPassword'];
			$tmpSource['DBEnableRowCounting'] = $content['SourceDBEnableRowCounting'];
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
				$sqlquery = "INSERT INTO " . DB_SOURCES . " (Name, SourceType, MsgParserList, MsgNormalize, ViewID, LogLineType, DiskFile, userid, groupid) 
				VALUES ('" . $content['Name'] . "', 
						" . $content['SourceType'] . ", 
						'" . $content['MsgParserList'] . "',
						" . $content['MsgNormalize'] . ", 
						'" . $content['SourceViewID'] . "',
						'" . $content['SourceLogLineType'] . "',
						'" . $content['SourceDiskFile'] . "',
						" . $content['userid'] . ", 
						" . $content['groupid'] . " 
						)";
			}
			else if ( $content['SourceType'] == SOURCE_DB || $content['SourceType'] == SOURCE_PDO ) 
			{
				$sqlquery = "INSERT INTO " . DB_SOURCES . " (Name, SourceType, MsgParserList, MsgNormalize, ViewID, DBTableType, DBType, DBServer, DBName, DBUser, DBPassword, DBTableName, DBEnableRowCounting, userid, groupid) 
				VALUES ('" . $content['Name'] . "', 
						" . $content['SourceType'] . ", 
						'" . $content['MsgParserList'] . "', 
						" . $content['MsgNormalize'] . ", 
						'" . $content['SourceViewID'] . "',
						'" . $content['SourceDBTableType'] . "',
						" . $content['SourceDBType'] . ",
						'" . $content['SourceDBServer'] . "',
						'" . $content['SourceDBName'] . "',
						'" . $content['SourceDBUser'] . "',
						'" . $content['SourceDBPassword'] . "',
						'" . $content['SourceDBTableName'] . "',
						" . $content['SourceDBEnableRowCounting'] . ",
						" . $content['userid'] . ", 
						" . $content['groupid'] . " 
						)";
			}

			$result = DB_Query($sqlquery);
			DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_SOURCE_HASBEENADDED'], $content['Name'] ) , "sources.php" );
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
									SourceType = " . $content['SourceType'] . ", 
									MsgParserList = '" . $content['MsgParserList'] . "', 
									MsgNormalize = " . $content['MsgNormalize'] . ", 
									ViewID = '" . $content['SourceViewID'] . "', 
									LogLineType = '" . $content['SourceLogLineType'] . "', 
									DiskFile = '" . $content['SourceDiskFile'] . "', 
									userid = " . $content['userid'] . ", 
									groupid = " . $content['groupid'] . "
									WHERE ID = " . $content['SOURCEID'];
				}
				else if ( $content['SourceType'] == SOURCE_DB || $content['SourceType'] == SOURCE_PDO ) 
				{
					$sqlquery =	"UPDATE " . DB_SOURCES . " SET 
									Name = '" . $content['Name'] . "', 
									SourceType = " . $content['SourceType'] . ", 
									MsgParserList = '" . $content['MsgParserList'] . "', 
									MsgNormalize = " . $content['MsgNormalize'] . ", 
									ViewID = '" . $content['SourceViewID'] . "', 
									DBTableType = '" . $content['SourceDBTableType'] . "', 
									DBType = " . $content['SourceDBType'] . ", 
									DBServer = '" . $content['SourceDBServer'] . "', 
									DBName = '" . $content['SourceDBName'] . "', 
									DBUser = '" . $content['SourceDBUser'] . "', 
									DBPassword = '" . $content['SourceDBPassword'] . "', 
									DBTableName = '" . $content['SourceDBTableName'] . "', 
									DBEnableRowCounting = " . $content['SourceDBEnableRowCounting'] . ", 
									userid = " . $content['userid'] . ", 
									groupid = " . $content['groupid'] . "
									WHERE ID = " . $content['SOURCEID'];
				}

				$result = DB_Query($sqlquery);
				DB_FreeQuery($result);

				// Done redirect!
				RedirectResult( GetAndReplaceLangStr( $content['LN_SOURCES_HASBEENEDIT'], $content['Name']) , "sources.php" );
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Searches
	$content['LISTCHARTS'] = "true";

	// Copy Sources array for further modifications
	$content['CHARTS'] = $content['Charts'];

	// --- Process Sources
	$i = 0; // Help counter!
	foreach ($content['CHARTS'] as &$myChart )
	{
		// --- Set Image for Type
		// NonNUMERIC are config files Sources, can not be editied
		if ( is_numeric($myChart['ID']) )
		{
			// Allow EDIT
			$myChart['ActionsAllowed'] = true;

			if ( $myChart['userid'] != null )
			{
				$myChart['ChartAssignedToImage']	= $content["MENU_ADMINUSERS"];
				$myChart['ChartAssignedToText']		= $content["LN_GEN_USERONLY"];
			}
			else if ( $myChart['groupid'] != null )
			{
				$myChart['ChartAssignedToImage']	= $content["MENU_ADMINGROUPS"];
				$myChart['ChartAssignedToText']	= GetAndReplaceLangStr( $content["LN_GEN_GROUPONLYNAME"], $myChart['groupname'] );

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$myChart['ActionsAllowed'] = false;
			}
			else
			{
				$myChart['ChartAssignedToImage']	= $content["MENU_GLOBAL"];
				$myChart['ChartAssignedToText']	= $content["LN_GEN_GLOBAL"];

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$myChart['ActionsAllowed'] = false;
			}
		}
		else
		{
			// Disallow EDIT
			$myChart['ActionsAllowed'] = false;

			$myChart['ChartAssignedToImage']	= $content["MENU_INTERNAL"];
			$myChart['ChartAssignedToText']		= $content["LN_GEN_CONFIGFILE"];
		}
		// ---

		// --- Set SourceType
		if ( $myChart['chart_type'] == CHART_CAKE )
		{
			$myChart['ChartTypeImage']	= $content["MENU_CHART_CAKE"];
			$myChart['ChartTypeText']	= $content["LN_CHART_TYPE_CAKE"];
		}
		else if ( $myChart['chart_type'] == CHART_BARS_VERTICAL )
		{
			$myChart['ChartTypeImage']	= $content["MENU_CHART_BARSVERT"];
			$myChart['ChartTypeText']	= $content["LN_CHART_TYPE_BARS_VERTICAL"];
		}
		else if ( $myChart['chart_type'] == CHART_BARS_HORIZONTAL )
		{
			$myChart['ChartTypeImage']	= $content["MENU_CHART_BARSHORI"];
			$myChart['ChartTypeText']	= $content["LN_CHART_TYPE_BARS_HORIZONTAL"];
		}
		// ---

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$myChart['cssclass'] = "line1";
		else
			$myChart['cssclass'] = "line2";
		$i++;
		// --- 
	}
	// --- 
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_CHARTOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_charts.html");
$page -> output(); 
// --- 

?>