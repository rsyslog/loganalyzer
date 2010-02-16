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
//include($gl_root_path . 'include/functions_filters.php');

// Set Upgrade Page!
define('IS_UPRGADEPAGE', true);
$content['IS_UPRGADEPAGE'] = true;

// Set PAGE to be ADMINPAGE!
define('IS_ADMINPAGE', true);
$content['IS_ADMINPAGE'] = true;
InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd

// Init admin langauge file now!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/admin.php' );
// ***					*** //

// --- BEGIN Custom Code
if ( isset($content['database_forcedatabaseupdate']) && $content['database_forcedatabaseupdate'] == "yes" ) 
{
	if ( isset($_GET['op']) )
	{
		if ($_GET['op'] == "upgrade") 
		{
			// Lets start the uodating!
			$content['UPGRADE_RUNNING'] = "1";

			$content['sql_sucess'] = 0;
			$content['sql_failed'] = 0;
			$totaldbdefs = "";

			$tblPref = GetConfigSetting("UserDBPref", "logcon");
			
			// +1 so we start at the right DB Version!
			for( $i = $content['database_installedversion']+1; $i <= $content['database_internalversion']; $i++ )
			{
				$myfilename = "db_update_v" . $i . ".txt";

				// Lets read the table definitions :)
				$handle = @fopen($content['BASEPATH'] . "include/" . $myfilename, "r");
				if ($handle === false) 
				{
					$content['ISERROR'] = "true";
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBUPGRADE_DBFILENOTFOUND'], $myfilename ); 
				}
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

			if ( !isset($content['ISERROR']) )
			{
				if ( strlen($totaldbdefs) <= 0 )
				{
					$content['ISERROR'] = "true";
					$content['ERROR_MSG'] = $content['LN_DBUPGRADE_DBDEFFILESHORT']; 
				}
			
				// Replace stats_ with the custom one ;)
				$totaldbdefs = str_replace( "`logcon_", "`" . $tblPref, $totaldbdefs );
			
				// Now split by sql command
				$mycommands = split( ";\r\n", $totaldbdefs );
			
				// check for different linefeed
				if ( count($mycommands) <= 1 )
					$mycommands = split( ";\n", $totaldbdefs );
	
				//Still only one? Abort
				if ( count($mycommands) <= 1 )
				{
					$content['ISERROR'] = "true";
					$content['ERROR_MSG'] = $content['LN_DBUPGRADE_DBDEFFILESHORT']; 
				}

				if ( !isset($content['ISERROR']) )
				{
					// --- Now execute all commands
					ini_set('error_reporting', E_WARNING); // Enable Warnings!

					for($i = 0; $i < count($mycommands); $i++)
					{
						if ( strlen(trim($mycommands[$i])) > 1 )
						{
							$result = DB_Query( $mycommands[$i], false );
							if ($result == FALSE)
							{
								$content['failedstatements'][ $content['sql_failed'] ]['myerrmsg'] = DB_ReturnSimpleErrorMsg();
								$content['failedstatements'][ $content['sql_failed'] ]['mystatement'] = $mycommands[$i];

								// --- Set CSS Class
								if ( $content['sql_failed'] % 2 == 0 )
									$content['failedstatements'][ $content['sql_failed'] ]['cssclass'] = "line1";
								else
									$content['failedstatements'][ $content['sql_failed'] ]['cssclass'] = "line2";
								// --- 

								$content['sql_failed']++;
							}
							else
								$content['sql_sucess']++;

							// Free result
							DB_FreeQuery($result);
						}
					}
					// --- 

					// --- Upgrade Database Version in Config Table
					$content['database_installedversion'] = $content['database_internalversion'];
					WriteConfigValue( "database_installedversion", true );
					// --- 
				}
			}
		}
		else
			$content['UPGRADE_DEFAULT'] = "1";
	}
	else
		$content['UPGRADE_DEFAULT'] = "1";


}
else 
	$content['UPGRADE_DEFAULT'] = "0";


// disable running to be save! ;)
if ( isset($content['ISERROR']) )
	$content['UPGRADE_RUNNING'] = "0";
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_DBUPGRADE_TITLE'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_upgrade.html");
$page -> output(); 
// --- 

?>