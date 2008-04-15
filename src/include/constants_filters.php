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