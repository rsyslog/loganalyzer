<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Helperfunctions for the web frontend								*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
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
	InstallFileReminder();
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
	global $content;
	$content['CURRENTURL'] = $_SERVER['PHP_SELF']; // . "?" . $_SERVER['QUERY_STRING']

	// Now the query string:
	if ( isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0 )
	{
		// Append ?
		$content['CURRENTURL'] .= "?";

		$queries = explode ("&", $_SERVER['QUERY_STRING']);
		$counter = 0;
		for ( $i = 0; $i < count($queries); $i++ )
		{
			if ( strpos($queries[$i], "serverid") === false ) 
			{
				$tmpvars = explode ("=", $queries[$i]);
				// 4Server Selector
				$content['HIDDENVARS'][$counter]['varname'] = $tmpvars[0];
				$content['HIDDENVARS'][$counter]['varvalue'] = $tmpvars[1];

				$counter++;
			}
		}
	}
}


?>