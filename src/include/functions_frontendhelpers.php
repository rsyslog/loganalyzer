<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Helperfunctions for the web frontend								*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
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

function InitFrontEndDefaults()
{
	// To create the current URL
	CreateCurrentUrl();

	// --- BEGIN Main Info Area


	
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

function CreateCurrentUrl()
{
	global $content, $CFG;
	$content['CURRENTURL'] = $_SERVER['PHP_SELF']; // . "?" . $_SERVER['QUERY_STRING']
	
	// Init additional_url helper variable
	$content['additional_url'] = ""; 
	$content['additional_url_uidonly'] = ""; 
	$content['additional_url_sortingonly'] = ""; 
	$content['additional_url_sourceonly'] = ""; 
	
	// Hidden Vars Counter
	$hvCounter = 0;

	// Append SourceID into everything!
	if ( (isset($CFG['DefaultSourceID']) && isset($content['Sources'][ $CFG['DefaultSourceID'] ])) && isset($_SESSION['currentSourceID']) ) 
	{

		// If the DefaultSourceID differes from the SourceID in our Session, we will append the sourceid within all URL's!
		if ( $CFG['DefaultSourceID'] != $_SESSION['currentSourceID'] )
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
	global $content, $CFG;

	if ( !is_array($evttimearray) )
		return $evttimearray;

	if ( 
			( isset($CFG['ViewUseTodayYesterday']) && $CFG['ViewUseTodayYesterday'] == 1 )
			&&
			( date('m', $evttimearray[EVTIME_TIMESTAMP]) == date('m') && date('Y', $evttimearray[EVTIME_TIMESTAMP]) == date('Y') )
		)
	{
		if ( date('d', $evttimearray[EVTIME_TIMESTAMP]) == date('d') )
			return "Today " . date("H:i:s", $evttimearray[EVTIME_TIMESTAMP] );
		else if ( date('d', $evttimearray[EVTIME_TIMESTAMP] + 86400) == date('d') )
			return "Yesterday " . date("H:i:s", $evttimearray[EVTIME_TIMESTAMP] );
	}

	// Reach return normal format!
	return $szDateFormatted = date("Y-m-d H:i:s", $evttimearray[EVTIME_TIMESTAMP] );
}

function OutputDebugMessage($szDbg)
{
	global $CFG;

	if ( $CFG['MiscShowDebugMsg'] == 1 )
	{
		print("<table width=\"600\" align=\"center\" class=\"with_border\">");
		print("<tr><td valign='top'><B>Debugmessage:</B> </td>");
		print("<td>" . $szDbg . "</td></tr>");
		print("</table><br>");
	}
}

?>