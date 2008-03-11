<?php
	/*
		*********************************************************************
		* Copyright by Adiscon GmbH | 2008!									*
		* -> www.phplogcon.org <-											*
		*																	*
		* Use this script at your own risk!									*
		* -----------------------------------------------------------------	*
		* Maintain and read Source Configurations							*
		*																	*
		* -> Configuration need variables for the Database connection		*
		*********************************************************************
	*/

	// --- Avoid directly accessing this file! 
	if ( !defined('IN_PHPLOGCON') )
	{
		die('Hacking attempt');
		exit;
	}
	// --- 

	require_once('classes/logstreamconfig.class.php');
	require_once('classes/logstreamconfigdisk.class.php');

	function InitSourceConfigs()
	{
		global $CFG, $Sources, $currentSourceID;
		
		// Init Source Configs!
		if ( isset($CFG['Sources']) )
		{	
			$iCount = count($CFG['Sources']);
			for ( $i = 0; $i< $iCount; $i++ )
			{
				if ( isset($CFG['Sources'][$i]['SourceType']) ) 
				{
					// Set Array Index, TODO: Check for invalid characters!
					$iSourceID = $CFG['Sources'][$i]['ID'];
					if ( !isset($Sources[$iSourceID]) ) 
					{
						// Copy general properties
						$Sources[$iSourceID]['Name'] = $CFG['Sources'][$i]['Name'];
						$Sources[$iSourceID]['SourceType'] = $CFG['Sources'][$i]['SourceType'];
						
						// Create Config instance!
						if ( $CFG['Sources'][$i]['SourceType'] == SOURCE_DISK )
						{
							$Sources[$iSourceID]['ObjRef'] = new LogStreamConfigDisk();
							$Sources[$iSourceID]['ObjRef']->FileName = $CFG['Sources'][$i]['DiskFile'];
						}
						else if ( $CFG['Sources'][$i]['SourceType'] == SOURCE_MYSQLDB )
						{	
							// TODO!
							die( "Not supported yet!" );
						}
						else
						{	
							// UNKNOWN, remove config entry!
							unset($Sources[$iSourceID]);

							// TODO: Output CONFIG WARNING
						}

						// Set default SourceID here!
						if ( isset($Sources[$iSourceID]) && !isset($currentSourceID) ) 
							$currentSourceID = $iSourceID;
					}
					else
					{
						// TODO: OUTPUT CONFIG WARNING - duplicated ID!
					}
				}
			}
		}

		// Set Source from session if available!
		if ( isset($_SESSION['currentSourceID']) && isset($Sources[$_SESSION['currentSourceID']]) )
			$currentSourceID = $_SESSION['currentSourceID'];
		else
		{
			// No Source stored in session, then to so now!
			$_SESSION['currentSourceID'] = $currentSourceID;
		}
	}

?>