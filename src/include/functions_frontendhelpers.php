<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Helperfunctions for the web frontend								*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
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

function InitFrontEndDefaults()
{
	global $content;

	// To create the current URL
	CreateCurrentUrl();

	// --- BEGIN Main Info Area

	$content['MAXURL'] = $content['BASEPATH'] . "userchange.php?";
	if ( isset($_SESSION['SESSION_MAXIMIZED']) && $_SESSION['SESSION_MAXIMIZED'] == true )
	{
		$content['MAXIMIZED'] = true;
		$content['MAXIMAGE'] = $content['MENU_NORMAL'];
		$content['MAXLANGTEXT'] = $content['LN_MENU_NORMALVIEW'];
		$content['MAXURL'] .= "op=maximize&max=0";
	}
	else
	{
		$content['MAXIMIZED'] = false;
		$content['MAXIMAGE'] = $content['MENU_MAXIMIZE'];
		$content['MAXLANGTEXT'] = $content['LN_MENU_MAXVIEW'];
		$content['MAXURL'] .= "op=maximize&max=1";
	}
	
	// --- END Main Info Area
	
	// Check if install file still exists
	// NOT NEEDED ANYMORE InstallFileReminder();
}

function InstallFileReminder()
{
	global $content;

	if ( is_file($content['BASEPATH'] . "install.php") ) 
	{
		// No Servers - display warning!
		$content['error_installfilereminder'] = "true";
	}
}

function GetAdditionalUrl($skipParam, $appendParam = "")
{
	global $content;
//echo $content['additional_url_full'];
	if ( isset($content['additional_url_full']) && strlen($content['additional_url_full']) > 0 )
	{
		if ( strlen($skipParam) > 0 ) 
		{
			// remove parameters from string!
			$szReturn = preg_replace("#(&{$skipParam}=[\w]+)#is", '', $content['additional_url_full']);
			if ( strlen($szReturn) > 0 )
			{
				if ( strlen($appendParam) > 0 )
					return $szReturn . "&" . $appendParam;
				else
					return $szReturn;
			}
			else if ( strlen($appendParam) > 0 )
				return "?" . $appendParam;
			else
				return "";
		}
		else
			return $content['additional_url_full'];
	}
	else
	{
		if ( strlen($appendParam) > 0 )
			return "?" . $appendParam;
		else
			return "";
	}
}

function CreateCurrentUrl()
{
	global $content;
	$content['CURRENTURL'] = $_SERVER['PHP_SELF']; // . "?" . $_SERVER['QUERY_STRING']
	
	// Init additional_url helper variable
	$content['additional_url'] = ""; 
	$content['additional_url_full'] = ""; 
	$content['additional_url_uidonly'] = ""; 
	$content['additional_url_sortingonly'] = ""; 
	$content['additional_url_sourceonly'] = ""; 
	
	// Hidden Vars Counter
	$hvCounter = 0;

	// Append SourceID into everything!
	$tmpDefSourceID = GetConfigSetting("DefaultSourceID", "", CFGLEVEL_USER);
	if ( isset($content['Sources'][ $tmpDefSourceID ]) && isset($_SESSION['currentSourceID']) ) 
	{

		// If the DefaultSourceID differes from the SourceID in our Session, we will append the sourceid within all URL's!
		if ( $tmpDefSourceID != $_SESSION['currentSourceID'] )
		{
//			$content['additional_url'] .= "&sourceid=" . $_SESSION['currentSourceID'];
			$content['additional_url_uidonly'] = "&sourceid=" . $_SESSION['currentSourceID'];
			$content['additional_url_sortingonly'] = "&sourceid=" . $_SESSION['currentSourceID'];
			$content['additional_url_sourceonly'] = "&sourceid=" . $_SESSION['currentSourceID'];

			// For forms!
			$content['HIDDENVARS_SOURCE'][$hvCounter]['varname'] = "sourceid";
			$content['HIDDENVARS_SOURCE'][$hvCounter]['varvalue'] = $_SESSION['currentSourceID'];
			$hvCounter++;
		}
	}

	// Now the query string:
	if ( isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0 )
	{
		// Append ?
		$content['CURRENTURL'] .= "?";

		$queries = explode ("&", $_SERVER['QUERY_STRING']);
		for ( $i = 0; $i < count($queries); $i++ )
		{
			// Some properties need to be filtered out. 
			if (
					strpos($queries[$i], "direction") === false 
						&&
					strpos($queries[$i], "skipone") === false
				) 
			{
				$tmpvars = explode ("=", $queries[$i]);
				if ( isset($tmpvars[1]) ) // Only if value param is set!
				{
					// For forms!
					$content['HIDDENVARS'][$hvCounter]['varname'] = $tmpvars[0];
					$content['HIDDENVARS'][$hvCounter]['varvalue'] = $tmpvars[1];
					
					if ( strlen($tmpvars[1]) > 0 )
					{
						// Append For URL's
						if ( $tmpvars[0] == "uid" )
						{
							// only add once
							if ( strlen($content['additional_url_uidonly']) <= 0 )
								$content['additional_url_uidonly'] .= "&" . $tmpvars[0] . "=" . $tmpvars[1];
						}
						else if ( $tmpvars[0] == "sorting" )
						{
							// only add once
							if ( strlen($content['additional_url_sortingonly']) <= 0 )
								$content['additional_url_sortingonly'] .= "&" . $tmpvars[0] . "=" . $tmpvars[1];
						}
						else if ( $tmpvars[0] == "sourceid" )
						{	
							// Skip this entry
							continue;
						}
						else
							$content['additional_url'] .= "&" . $tmpvars[0] . "=" . $tmpvars[1];

						// always append to this URL!
						$content['additional_url_full'] .= "&" . $tmpvars[0] . "=" . $tmpvars[1];
					}

					$hvCounter++;
				}
			}
		}
	}

	// done
}

function GetFormatedDate($evttimearray)
{
	global $content;

	if ( is_array($evttimearray) )
	{
		if ( 
				GetConfigSetting("ViewUseTodayYesterday", 0, CFGLEVEL_USER) == 1 
				&&
				( date('m', $evttimearray[EVTIME_TIMESTAMP]) == date('m') && date('Y', $evttimearray[EVTIME_TIMESTAMP]) == date('Y') )
			)
		{
			if ( date('d', $evttimearray[EVTIME_TIMESTAMP]) == date('d') )
				return "Today " . date("H:i:s", $evttimearray[EVTIME_TIMESTAMP] );
			else if ( date('d', $evttimearray[EVTIME_TIMESTAMP] + 86400) == date('d') )
				return "Yesterday " . date("H:i:s", $evttimearray[EVTIME_TIMESTAMP] );
		}
		
		// Copy to local variable
		$nMyTimeStamp = $evttimearray[EVTIME_TIMESTAMP]; 
	}
	else
	{
		$nMyTimeStamp  = strtotime($evttimearray); 
		if ( $nMyTimeStamp  === FALSE ) // Could not convert into timestamp so return original!
			return $evttimearray;
	}

	// Reach return normal format!
	return $szDateFormatted = date("Y-m-d H:i:s", $nMyTimeStamp );
}

function GetDebugBgColor( $szDebugMode )
{
	global $severity_colors;

	switch ( $szDebugMode )
	{
		case DEBUG_ULTRADEBUG:
			$szReturn = $severity_colors[SYSLOG_DEBUG];
			break;
		case DEBUG_DEBUG:
			$szReturn = $severity_colors[SYSLOG_INFO];
			break;
		case DEBUG_INFO:
			$szReturn = $severity_colors[SYSLOG_NOTICE];
			break;
		case DEBUG_WARN:
			$szReturn = $severity_colors[SYSLOG_WARNING];
			break;
		case DEBUG_ERROR:
			$szReturn = $severity_colors[SYSLOG_ERR];
			break;
		default: 
			$szReturn = $severity_colors[SYSLOG_NOTICE];
	}
	
	// Return string result
	return $szReturn;
}

function GetDebugModeString( $szDebugMode )
{
	switch ( $szDebugMode )
	{
		case DEBUG_ULTRADEBUG:
			$szReturn = STR_DEBUG_ULTRADEBUG;
			break;
		case DEBUG_DEBUG:
			$szReturn = STR_DEBUG_DEBUG;
			break;
		case DEBUG_INFO:
			$szReturn = STR_DEBUG_INFO;
			break;
		case DEBUG_WARN:
			$szReturn = STR_DEBUG_WARN;
			break;
		case DEBUG_ERROR:
			$szReturn = STR_DEBUG_ERROR;
			break;
		default: 
			$szReturn = STR_DEBUG_INFO;
	}
	
	// Return string result
	return $szReturn;
}


function GetPriorityFromDebugLevel( $DebugLevel ) 
{
	switch ( $DebugLevel )
	{
		case DEBUG_ULTRADEBUG:
			return LOG_DEBUG;
		case DEBUG_DEBUG:
			return LOG_INFO;
		case DEBUG_INFO:
			return LOG_NOTICE;
		case DEBUG_WARN:
			return LOG_WARNING;
		case DEBUG_ERROR:
			return LOG_ERR;
		case DEBUG_ERROR_WTF:
			return LOG_CRIT;
	}
}

?>