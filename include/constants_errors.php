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

define('SUCCESS ', 0);
define('ERROR_FILE_NOT_FOUND', 1);
define('ERROR_FILE_CANT_CLOSE', 2);
define('ERROR_FILE_EOF', 3);
define('ERROR_FILE_BOF', 4);
define('ERROR_UNDEFINED', 5);
?>