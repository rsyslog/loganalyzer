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
					// Copy general properties
//						$content['Sources'][$iSourceID]['ID'] = $mysource['ID'];
//						$content['Sources'][$iSourceID]['Name'] = $mysource['Name'];
//						$content['Sources'][$iSourceID]['SourceType'] = $mysource['SourceType'];
					
					// Set default if not set!
					if ( !isset($mysource['LogLineType']) ) 
						$content['Sources'][$iSourceID]['LogLineType'] = "syslog";

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
						$content['Sources'][$iSourceID]['ObjRef']->DBType = $mysource['DBType'];
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

						// TODO: Output CONFIG WARNING
						die( "Not supported yet!" );
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
									   );
		$CFG['Views']['EVTRPT']= array( 
										'ID' =>			"EVTRPT", 
										'DisplayName' =>"EventLog Fields", 
										'Columns' =>	array ( SYSLOG_DATE, SYSLOG_HOST, SYSLOG_SEVERITY, SYSLOG_EVENT_LOGTYPE, SYSLOG_EVENT_SOURCE, SYSLOG_EVENT_ID, SYSLOG_EVENT_USER, SYSLOG_MESSAGE ), 
									   );
		
		// Set default of 'DefaultViewsID'
		$CFG['DefaultViewsID'] = "SYSLOG";

		// Loop through views now and copy into content array!
		foreach ( $CFG['Views'] as $key => $view )
		{
			$content['Views'][$key] = $view;

			/*
			// Set View from session if available!
			if ( isset($_SESSION['currentSourceID']) )
			{
				$currentSourceID = $_SESSION['currentSourceID'];

				if ( isset($_SESSION[$currentSourceID . "-View"]) && )
					$content['Views'][$key]['selected'] = "selected";
			}
			*/
		}
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

?>