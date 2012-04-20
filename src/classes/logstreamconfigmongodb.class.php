<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* StreamConfig has the capability to create a specific LogStream	*
	* object depending on a configured LogStream*Config object.			*
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
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

class LogStreamConfigMongoDB extends LogStreamConfig {
	public $DBServer = '127.0.0.1';
	public $DBPort = 27017;
	public $DBName = 'syslog';
	public $DBUser = '';					// Default = No database user!
	public $DBPassword = '';				// Default = No Password
	public $DBTableType = 'mongodb';		// Default = Use mongodb layout!
	public $DBCollection = 'rsyslog';		// Default Tabelname from RSYSLOG
//	public $DBEnableRowCounting = true;		// Default RowCounting is enabled!
	
	// Runtime configuration variables
	public $RecordsPerQuery = 100;			// This will determine how to limit sql statements
	public $IDsPerQuery = 5000;				// When we query ID's, we read a lot more the datarecords at once!
	public $SortColumn = SYSLOG_UID;		// Default sorting column

//	public $FileName = '';
//	public $LineParserType = "syslog"; // Default = Syslog!
//	public $_lineParser = null;

	public function LogStreamFactory($o) 
	{
		// An instance is created, then include the logstreamdisk class as well!
		global $gl_root_path;
		require_once($gl_root_path . 'classes/logstreammongodb.class.php');

		// return LogStreamDisk instance
		return new LogStreamMongoDB($o);
	}
}
?>