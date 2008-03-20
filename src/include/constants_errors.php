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

define('SUCCESS', 0);
define('ERROR', 1);			// This is a simple helper constant! which we can use to check if there even was an error! Any result code above 0 is an error!
define('ERROR_FILE_NOT_FOUND', 2);
define('ERROR_FILE_CANT_CLOSE', 3);
define('ERROR_FILE_EOF', 4);
define('ERROR_FILE_BOF', 5);
define('ERROR_UNDEFINED', 6);
define('ERROR_EOS', 7);
define('ERROR_NOMORERECORDS', 8);
define('ERROR_FILTER_NOT_MATCH', 9);
?>