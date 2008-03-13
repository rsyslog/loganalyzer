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

	// --- Perform necessary includes
	require_once($gl_root_path . 'classes/logstreamconfig.class.php');
	require_once($gl_root_path . 'classes/logstreamconfigdisk.class.php');
	// --- 

	function InitSourceConfigs()
	{
		global $CFG, $content, $currentSourceID;

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
					if ( !isset($content['Sources'][$iSourceID]) ) 
					{
						// Copy general properties
						$content['Sources'][$iSourceID]['ID'] = $CFG['Sources'][$i]['ID'];
						$content['Sources'][$iSourceID]['Name'] = $CFG['Sources'][$i]['Name'];
						$content['Sources'][$iSourceID]['SourceType'] = $CFG['Sources'][$i]['SourceType'];
						$content['Sources'][$iSourceID]['selected'] = ""; // Only for the display box
						
						// Create Config instance!
						if ( $CFG['Sources'][$i]['SourceType'] == SOURCE_DISK )
						{
							$content['Sources'][$iSourceID]['ObjRef'] = new LogStreamConfigDisk();
							$content['Sources'][$iSourceID]['ObjRef']->FileName = $CFG['Sources'][$i]['DiskFile'];
						}
						else if ( $CFG['Sources'][$i]['SourceType'] == SOURCE_MYSQLDB )
						{	
							// TODO!
							die( "Not supported yet!" );
						}
						else
						{	
							// UNKNOWN, remove config entry!
							unset($content['Sources'][$iSourceID]);

							// TODO: Output CONFIG WARNING
						}

						// Set default SourceID here!
						if ( isset($content['Sources'][$iSourceID]) && !isset($currentSourceID) ) 
							$currentSourceID = $iSourceID;
					}
					else
					{
						// TODO: OUTPUT CONFIG WARNING - duplicated ID!
					}
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