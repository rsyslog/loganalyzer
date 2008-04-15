<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* -> Stuff which has to be static and predefined					*
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

// --- Some custom defines
define('DATEMODE_ALL', 1);
define('DATEMODE_RANGE', 2);
define('DATEMODE_LASTX', 3);

define('DATEMODE_RANGE_FROM', 4);
define('DATEMODE_RANGE_TO', 5);

define('DATE_LASTX_HOUR', 1);
define('DATE_LASTX_12HOURS', 2);
define('DATE_LASTX_24HOURS', 3);
define('DATE_LASTX_7DAYS', 4);
define('DATE_LASTX_31DAYS', 5);
// --- 


// Helper constants needed for parsing filters
define('FILTER_TMP_KEY', 0);
define('FILTER_TMP_VALUE', 1);
define('FILTER_DATEMODE', 'datemode');
define('FILTER_TYPE', 'filtertype');
define('FILTER_DATEMODENAME', 'datemodename');
define('FILTER_VALUE', 'value');
define('FILTER_MODE', 'filtermode');
define('FILTER_MODE_INCLUDE', 0);
define('FILTER_MODE_EXCLUDE', 1);

?>