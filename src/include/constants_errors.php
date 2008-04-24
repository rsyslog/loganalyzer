<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* -> Stuff which has to be static and predefined					*
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

define('ERROR_DB_CONNECTFAILED', 10);
define('ERROR_DB_CANNOTSELECTDB', 11);
define('ERROR_DB_QUERYFAILED', 12);
define('ERROR_DB_NOPROPERTIES', 13);
define('ERROR_DB_INVALIDDBMAPPING', 14);

define('ERROR_FILE_NOT_READABLE', 15);

?>
