<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* -> Stuff which has to be static and predefined					*
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

define('SUCCESS', 0);
define('ERROR', 1);			// This is a simple helper constant! which we can use to check if there even was an error! Any result code above 0 is an error!
define('ERROR_FILE_NOT_FOUND', 2);
define('ERROR_FILE_CANT_CLOSE', 3);
define('ERROR_FILE_EOF', 4);
define('ERROR_FILE_BOF', 5);
define('ERROR_FILE_NOT_READABLE', 15);
define('ERROR_FILE_NOMORETIME', 22);
define('ERROR_UNDEFINED', 6);
define('ERROR_EOS', 7);
define('ERROR_NOMORERECORDS', 8);
define('ERROR_FILTER_NOT_MATCH', 9);
define('ERROR_SOURCENOTFOUND', 24);

define('ERROR_DB_CONNECTFAILED', 10);
define('ERROR_DB_CANNOTSELECTDB', 11);
define('ERROR_DB_QUERYFAILED', 12);
define('ERROR_DB_NOPROPERTIES', 13);
define('ERROR_DB_INVALIDDBMAPPING', 14);
define('ERROR_DB_INVALIDDBDRIVER', 16);
define('ERROR_DB_TABLENOTFOUND', 17);
define('ERROR_DB_DBFIELDNOTFOUND', 19);

define('ERROR_MSG_NOMATCH', 18);
define('ERROR_CHARTS_NOTCONFIGURED', 20);
define('ERROR_MSG_SKIPMESSAGE', 21);
define('ERROR_MSG_SCANABORTED', 23);
define('ERROR_REPORT_NODATA', 25);
define('ERROR_DB_INDEXESMISSING', 26);
define('ERROR_DB_TRIGGERMISSING', 27);
define('ERROR_DB_INDEXFAILED', 28);
define('ERROR_DB_TRIGGERFAILED', 29);
define('ERROR_DB_CHECKSUMERROR', 30);
define('ERROR_DB_CHECKSUMCHANGEFAILED', 31);
define('ERROR_DB_ADDDBFIELDFAILED', 32);
define('ERROR_DB_TIMEOUTFAILED', 34);
define('ERROR_PATH_NOT_ALLOWED', 33);

?>