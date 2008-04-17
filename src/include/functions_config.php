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
						$content['Sources'][$iSourceID]['ObjRef']->DBTableType = $mysource['DBTableType'];
						$content['Sources'][$iSourceID]['ObjRef']->DBTableName = $mysource['DBTableName'];
						
						// Optional parameters!
						if ( isset($mysource['DBPort']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBPort = $mysource['DBPort']; }
						if ( isset($mysource['DBUser']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBUser = $mysource['DBUser']; }
						if ( isset($mysource['DBPassword']) ) { $content['Sources'][$iSourceID]['ObjRef']->DBPassword = $mysource['DBPassword']; }
					}
					else
					{	
						// UNKNOWN, remove config entry!
						unset($content['Sources'][$iSourceID]);

						// TODO: Output CONFIG WARNING
						die( "Not supported yet!" );
					}

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
				// No Source stored in session, then to so now!
				$_SESSION['currentSourceID'] = $currentSourceID;
			}
		}
		
		// Set for the selection box in the header
		$content['Sources'][$currentSourceID]['selected'] = "selected";
	}

?>
