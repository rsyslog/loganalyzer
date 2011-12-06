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

class LogStreamConfigPDO extends LogStreamConfig {
	public $DBServer = 'localhost';
	public $DBPort = 0;
	public $DBName = '';
	public $DBUser = '';
	public $DBPassword = '';
	public $DBType = DB_MYSQL;				// Default = MYSQL!
	public $DBTableType = 'winsyslog';		// Default = WINSYSLOG DB Layout!
	public $DBTableName = 'systemevents';	// Default Tabelname from WINSYSLOG
	public $DBEnableRowCounting = true;		// Default RowCounting is enabled!
	
	// Runtime configuration variables
	public $RecordsPerQuery = 100;			// This will determine how to limit sql statements
	public $IDsPerQuery = 5000;				// When we query ID's, we read a lot more the datarecords at once!
	public $SortColumn = SYSLOG_UID;		// Default sorting column

	public function LogStreamFactory($o) 
	{
		// An instance is created, then include the logstreamdisk class as well!
		global $gl_root_path;
		require_once($gl_root_path . 'classes/logstreampdo.class.php');

		// return LogStreamDisk instance
		return new LogStreamPDO($o);
	}

	
	public function GetPDOTriggersSupported()
	{
		// TRIGGERS are not supported for all db engines!
		switch ($this->DBType)
		{
			case DB_MYSQL:
				return true;
			case DB_MSSQL:
				return true;
			case DB_ODBC:
				return false;
			case DB_PGSQL:
				return true;
			case DB_OCI:
				return false;
			case DB_DB2:
				return false;
			case DB_FIREBIRD:
				return false;
			case DB_INFORMIX:
				return false;
			case DB_SQLITE:
				return false;
			default:
				return false;
		}
	}

	public function GetPDODatabaseType()
	{
		switch ($this->DBType)
		{
			case DB_MYSQL:
				return "mysql";
			case DB_MSSQL:
				return "odbc";
			case DB_ODBC:
				return "odbc";
			case DB_PGSQL:
				return "pgsql";
			case DB_OCI:
				return "oci";
			case DB_DB2:
				return "ibm";
			case DB_FIREBIRD:
				return "firebird";
			case DB_INFORMIX:
				return "informix";
			case DB_SQLITE:
				return "sqlite";
			default:
				return "";
		}
	}

	public function CreateConnectDSN()
	{
		switch ($this->DBType)
		{
			case DB_MYSQL:
				$myDsn = 'mysql:host=' . $this->DBServer /*. ',' . $this->DBPort*/ . ';dbname=' . $this->DBName;
				break;
			case DB_MSSQL:
				$myDsn = 'odbc:Driver={SQL Server}; Server=' . $this->DBServer . '; Uid=' . $this->DBUser . '; Pwd=' . $this->DBPassword . '; Database=' . $this->DBName . ';';
				break;
			case DB_ODBC:
				$myDsn = 'odbc:dsn=' . $this->DBServer. ';uid=' . $this->DBUser . ';pwd=' . $this->DBPassword . ';Database=' . $this->DBName;
				break;
			case DB_PGSQL:
				$myDsn = 'pgsql:host=' . $this->DBServer . ' dbname=' . $this->DBName . ' user=' . $this->DBUser . ' password=' . $this->DBPassword; // port=5432 
				break;
			case DB_OCI:
				$myDsn = 'oci:dbname=' . $this->DBServer . '/' . $this->DBName;
				break;
			case DB_DB2:
				$myDsn = 'ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=' . $this->DBName . '; HOSTNAME=' . $this->DBServer . '; PROTOCOL=TCPIP; UID=' . $this->DBUser . '; PWD=' . $this->DBPassword; // PORT=port ;
				break;
			case DB_FIREBIRD:
				$myDsn = 'firebird:User=' . $this->DBUser . ';Password=' . $this->DBPassword . ';Database=' . $this->DBName . ';DataSource=' . $this->DBServer; //;Port=3050';
				break;
			case DB_INFORMIX:
				$myDsn = 'informix:host=' . $this->DBServer . '; database=' . $this->DBName . '; server=' . $this->DBServer . '; protocol=onsoctcp; EnableScrollableCursors=1';
				break;
			case DB_SQLITE:
				$myDsn = 'sqlite:' . $this->DBName; // DBName is the full Path to the sqlite db file
				break;
			default:
				$myDsn = '';
		}
		
		// return my DSN now!
		return $myDsn;
	}


}
?>