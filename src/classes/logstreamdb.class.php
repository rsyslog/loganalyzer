<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* LogStreamDB provides access to the data in database. In the most
	* cases this will be plain text files. If we need access to e.g.
	* zipped files, this will be handled by a separate driver.
	*
	* \version 2.0.0 Init Version
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

// --- Required Includes!
require_once($gl_root_path . 'include/constants_errors.php');
// --- 

class LogStreamDB extends LogStream {
	private $_dbhandle = null;
	
	// Helper to store the database records
	private $bufferedRecords = null;
	private $_currentRecordStart = 0;
	private $_currentRecordNum = 0;
	private $_totalRecordCount = -1;
	private $_previousPageUID = -1;
	private $_lastPageUID = -1;
	private $_firstPageUID = -1;
	private $_currentPageNumber = 0;

	private $_SQLwhereClause = "";
	private $_myDBQuery = null;

	// Constructor
	public function LogStreamDB($streamConfigObj) {
		$this->_logStreamConfigObj = $streamConfigObj;

		if ( $this->_logStreamConfigObj->DBType == DB_MYSQL )
		{
			// Probe if a function exists!
			if ( !function_exists("mysql_connect") )
				DieWithFriendlyErrorMsg("Error, MYSQL Extensions are not enabled! Function 'mysql_connect' does not exist.");
		}
	}

	/**
	* Open and verifies the database conncetion 
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function Open($arrProperties)
	{
		global $dbmapping;

		// Initialise Basic stuff within the Classs
		$this->RunBasicInits();

		// Verify database connection (This also opens the database!)
		$res = $this->Verify();
		if ( $res != SUCCESS ) 
			return $res;

		// Copy the Property Array 
		$this->_arrProperties = $arrProperties;
		
		// Check if DB Mapping exists
		if ( !isset($dbmapping[ $this->_logStreamConfigObj->DBTableType ]) )
			return ERROR_DB_INVALIDDBMAPPING;

		// Create SQL Where Clause first!
		$res = $this->CreateSQLWhereClause();
		if ( $res != SUCCESS ) 
			return $res;

		// Success, this means we init the Pagenumber to ONE!
		$this->_currentPageNumber = 1;
		
		// reached this point means success!
		return SUCCESS;
	}

	/*
	*	Helper function to clear the current querystring!
	*/
	public function ResetFilters()
	{
		// Clear _SQLwhereClause variable! 
		$this->_SQLwhereClause = ""; 
	}

	/**
	* Close the database connection.
	*
	* @return integer Error state
	*/
	public function Close()
	{
		if ($this->_dbhandle) 
			mysql_close($this->_dbhandle);
		$this->_dbhandle = null;
		return SUCCESS;
	}

	/**
	* Verify if the database connection exists!
	*
	* @return integer Error state
	*/
	public function Verify() {
		// Try to connect to the database
		if ( $this->_dbhandle == null ) 
		{
			// Forces to open a new link in all cases!
			$this->_dbhandle = @mysql_connect($this->_logStreamConfigObj->DBServer,$this->_logStreamConfigObj->DBUser,$this->_logStreamConfigObj->DBPassword, true);
			if (!$this->_dbhandle) 
			{
				if ( isset($php_errormsg) )
				{
					global $extraErrorDescription;
					$extraErrorDescription = $php_errormsg;
				}

				// Return error code
				return ERROR_DB_CONNECTFAILED;
			}
		}
		
		// Select the database now!
		$bRet = @mysql_select_db($this->_logStreamConfigObj->DBName, $this->_dbhandle);
		if(!$bRet) 
		{
			if ( isset($php_errormsg) )
			{
				global $extraErrorDescription;
				$extraErrorDescription = $php_errormsg;
			}
			
			// Return error code
			return ERROR_DB_CANNOTSELECTDB;
		}
		
		// Check if the table exists!
		$numTables = @mysql_num_rows( mysql_query("SHOW TABLES LIKE '%" . $this->_logStreamConfigObj->DBTableName . "%'"));
		if( $numTables <= 0 )
			return ERROR_DB_TABLENOTFOUND;

		// reached this point means success ;)!
		return SUCCESS;
	}


	/*
	*	Implementation of VerifyFields: Checks if fields exist in table
	*/
	public function VerifyFields( $arrProperitesIn )
	{
		global $dbmapping, $fields;

		// Get List of Indexes as Array
		$arrFieldKeys = $this->GetFieldsAsArray(); 
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Loop through all fields to see which one is missing!
		foreach ( $arrProperitesIn as $myproperty ) 
		{
//			echo $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "<br>";
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) && in_array($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], $arrFieldKeys) )
			{
				OutputDebugMessage("LogStreamDB|VerifyFields: Found Field for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "'", DEBUG_ULTRADEBUG);
				continue;
			}
			else
			{
				// Index is missing for this field!
				OutputDebugMessage("LogStreamDB|VerifyFields: Missing Field for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "'", DEBUG_WARN);
				return ERROR_DB_DBFIELDNOTFOUND; 
			}
		}
		
		// Successfull
		return SUCCESS; 
	}


	/*
	*	Implementation of VerifyIndexes: Checks if indexes exist for desired fields
	*/
	public function VerifyIndexes( $arrProperitesIn )
	{
		global $dbmapping, $fields;

		// Get List of Indexes as Array
		$arrIndexKeys = $this->GetIndexesAsArray(); 
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Loop through all fields to see which one is missing!
		foreach ( $arrProperitesIn as $myproperty ) 
		{
//			echo $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "<br>";
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) && in_array($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], $arrIndexKeys) )
			{
				OutputDebugMessage("LogStreamDB|VerifyIndexes: Found INDEX for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "'", DEBUG_ULTRADEBUG);
				continue;
			}
			else
			{
				// Index is missing for this field!
				OutputDebugMessage("LogStreamDB|VerifyIndexes: Missing INDEX for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "'", DEBUG_WARN);
				return ERROR_DB_INDEXESMISSING; 
			}
		}
		
		// Successfull
		return SUCCESS; 
	}


	/*
	*	Implementation of VerifyChecksumTrigger: Checks if checksum trigger exists
	*/
	public function VerifyChecksumTrigger( $myTriggerProperty )
	{
		global $dbmapping, $fields;

		// Get List of Triggers as Array
		$arrIndexTriggers = $this->GetTriggersAsArray(); 

		$szTableType = $this->_logStreamConfigObj->DBTableType;
		$szDBName = $this->_logStreamConfigObj->DBName;
		$szTableName = $this->_logStreamConfigObj->DBTableName;
		$szDBTriggerField = $dbmapping[$szTableType]['DBMAPPINGS'][$myTriggerProperty]; 

		// Create Triggername | lowercase!
		$szTriggerName = strtolower( $szDBName . "_" . $szTableName . "_" . $szDBTriggerField ); 
		
		// Try to find logstream trigger
		if ( count($arrIndexTriggers) > 0 ) 
		{
			if ( in_array($szTriggerName, $arrIndexTriggers) )
				return SUCCESS; 
			else
			{
				// Index is missing for this field!
				OutputDebugMessage("LogStreamDB|VerifyChecksumTrigger: Missing TRIGGER '" . $szTriggerName . "' for Table '" . $szTableName . "'", DEBUG_WARN);
				return ERROR_DB_TRIGGERMISSING; 
			}
		}
		else
		{
			// Index is missing for this field!
			OutputDebugMessage("LogStreamDB|VerifyChecksumTrigger: No TRIGGERS found in your database", DEBUG_WARN);
			return ERROR_DB_TRIGGERMISSING; 
		}
	}


	/*
	*	Implementation of CreateMissingIndexes: Checks if indexes exist for desired fields
	*/
	public function CreateMissingIndexes( $arrProperitesIn )
	{
		global $dbmapping, $fields, $querycount;
	
		// Get List of Indexes as Array
		$arrIndexKeys = $this->GetIndexesAsArray(); 
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Loop through all fields to see which one is missing!
		foreach ( $arrProperitesIn as $myproperty ) 
		{
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) && in_array($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], $arrIndexKeys) )
				continue;
			else
			{
				// Update Table schema now!
				$szSql = "ALTER TABLE " . $this->_logStreamConfigObj->DBTableName . " ADD INDEX ( " . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . " )"; 

				// Index is missing for this field!
				OutputDebugMessage("LogStreamDB|CreateMissingIndexes: Createing missing INDEX for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "' - " . $szSql, DEBUG_INFO);
				
				// Add missing INDEX now!
				$myQuery = mysql_query($szSql, $this->_dbhandle);
				if (!$myQuery)
				{
					// Return failure!
					$this->PrintDebugError("Dynamically Adding INDEX for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "' failed with Statement: '" . $szSql . "'");
					return ERROR_DB_INDEXFAILED;
				}
			}
		}
		
		// Successfull
		return SUCCESS; 
	}


	/*
	*	Implementation of CreateMissingFields: Checks if indexes exist for desired fields
	*/
	public function CreateMissingFields( $arrProperitesIn )
	{
		global $dbmapping, $fields, $querycount;
	
		// Get List of Indexes as Array
		$arrFieldKeys = $this->GetFieldsAsArray(); 
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Loop through all fields to see which one is missing!
		foreach ( $arrProperitesIn as $myproperty ) 
		{
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) && in_array($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], $arrFieldKeys) )
				continue;
			else
			{
				if ( $this->HandleMissingField( $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], $arrProperitesIn ) == SUCCESS )
				{
					// Index is missing for this field!
					OutputDebugMessage("LogStreamDB|CreateMissingFields: Createing missing FIELD for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], DEBUG_INFO);
				}
				else
				{
					// Return failure!
					$this->PrintDebugError("Dynamically Adding FIELD for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "' failed!");
					return ERROR_DB_ADDDBFIELDFAILED;
				}
			}
		}
		
		// Successfull
		return SUCCESS; 
	}


	/*
	*	Implementation of GetCreateMissingTriggerSQL: Creates SQL needed to create a TRIGGER
	*/
	public function GetCreateMissingTriggerSQL( $myDBTriggerField, $myDBTriggerCheckSumField )
	{
		global $dbmapping, $fields, $querycount;

		// Get List of Triggers as Array
		$szDBName = $this->_logStreamConfigObj->DBName;
		$szTableName = $this->_logStreamConfigObj->DBTableName;
		
		// Create Triggername
		$szTriggerName = $szDBName . "_" . $szTableName . "_" . $myDBTriggerField; 

		// Create TRIGGER SQL!
		$szSql =	"CREATE TRIGGER " . $szTriggerName . " BEFORE INSERT ON `" . $szTableName . "`
					 FOR EACH ROW
					 BEGIN
					 SET NEW." . $myDBTriggerCheckSumField . " = crc32(NEW." . $myDBTriggerField . ");
					 END
					;";

		return $szSql; 
	}


	/*
	*	Implementation of CreateMissingTrigger: Creates missing triggers !
	*/
	public function CreateMissingTrigger( $myTriggerProperty, $myCheckSumProperty )
	{
		global $dbmapping, $fields, $querycount;
	
		// Get List of Triggers as Array
		$szTableName = $this->_logStreamConfigObj->DBTableName;
		$szTableType = $this->_logStreamConfigObj->DBTableType;
		$szDBTriggerField = $dbmapping[$szTableType]['DBMAPPINGS'][$myTriggerProperty]; 
		$szDBTriggerCheckSumField = $dbmapping[$szTableType]['DBMAPPINGS'][$myCheckSumProperty]; 

		// Get SQL Code to create the trigger!
		$szSql = $this->GetCreateMissingTriggerSQL( $szDBTriggerField, $szDBTriggerCheckSumField ); 
		
		// Index is missing for this field!
		OutputDebugMessage("LogStreamDB|CreateMissingTrigger: Creating missing TRIGGER for '" . $szTableName . "' - $szDBTriggerCheckSumField = crc32(NEW.$szDBTriggerField)" . $szSql, DEBUG_INFO);
		
		// Add missing INDEX now!
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if (!$myQuery)
		{
			// Return failure!
			$this->PrintDebugError("Dynamically Adding TRIGGER for '" . $szTableName . "' failed!<br/><br/>If you want to manually add the TRIGGER, use the following SQL Command:<br/> " . str_replace("\n", "<br/>", $szSql) . "<br/>");
			
			return ERROR_DB_TRIGGERFAILED;
		}
		
		// Successfull
		return SUCCESS; 
	}


	/*
	*	Implementation of ChangeChecksumFieldUnsigned: Changes the Checkusm field to unsigned!
	*/
	public function ChangeChecksumFieldUnsigned()
	{
		global $dbmapping, $fields, $querycount;

		// Get variables
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Change Checksumfield to use UNSIGNED!
		$szUpdateSql = "ALTER TABLE `" . $this->_logStreamConfigObj->DBTableName . "` CHANGE `" . 
						$dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . "` `" . 
						$dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . "` INT(11) UNSIGNED NOT NULL DEFAULT '0'"; 

		// Update Table schema now!
		$myQuery = mysql_query($szUpdateSql, $this->_dbhandle);
		if (!$myQuery)
		{
			// Return failure!
			$this->PrintDebugError("ER_BAD_FIELD_ERROR - Failed to Change field '" . $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . "' from signed to unsigned with sql statement: '" . $szUpdateSql . "'");
			return ERROR_DB_CHECKSUMCHANGEFAILED;
		}

		// return results
		return SUCCESS;
	}


	/*
	*	Implementation of VerifyChecksumField: Verifies if the checkusm field is signed or unsigned!
	*/
	public function VerifyChecksumField()
	{
		global $dbmapping, $fields, $querycount;
		
		// Get variables
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Create SQL and Get INDEXES for table!
		$szSql = "SHOW COLUMNS FROM " . $this->_logStreamConfigObj->DBTableName . " WHERE Field = '" . $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . "'"; 
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if ($myQuery)
		{
			// Get result!
			$myRow = mysql_fetch_array($myQuery,  MYSQL_ASSOC);
			if (strpos( strtolower($myRow['Type']), "unsigned") === false ) 
			{
				// return error code!
				return ERROR_DB_CHECKSUMERROR; 
			}

			// Free query now
			mysql_free_result ($myQuery); 

			// Increment for the Footer Stats 
			$querycount++;
		}
	
		// return results
		return SUCCESS;
	}


	/**
	* Read the data from a specific uID which means in this
	* case beginning with from the Database ID
	* 
	* @param uID integer in/out: unique id of the data row 
	* @param arrProperitesOut array out: array filled with properties
	* @return integer Error state
	* @see ReadNext()
	*/
	public function Read($uID, &$arrProperitesOut)
	{
		// Seek the first uID!
		if ( $this->Sseek($uID, EnumSeek::UID, 0) == SUCCESS)
		{
			// Read the next record!
			$ret = $this->ReadNext($uID, $arrProperitesOut);
		}
		else
			$ret = ERROR_NOMORERECORDS;
	
		// return result!
		return $ret;
	}

	/**
	* Read the next line from the file depending on the current
	* read direction.
	*
	* Hint: If the current stream becomes unavailable an error
	* stated is retuned. A typical case is if a log rotation
	* changed the original data source.
	*
	* @param uID integer out: uID is the offset of data row
	* @param arrProperitesOut array out: properties
	* @return integer Error state
	* @see ReadNext
	*/
	public function ReadNext(&$uID, &$arrProperitesOut, $bParseMessage = true)
	{
		// Helpers needed for DB Mapping
		global $content, $gl_starttime;
		global $dbmapping, $fields;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// define $ret
		$ret = SUCCESS;

		do
		{
			// No buffer? then read from DB!
			if ( $this->bufferedRecords == null )
				$ret = $this->ReadNextRecordsFromDB($uID);
			else
			{
				if ( !isset($this->bufferedRecords[$this->_currentRecordNum] ) )
				{
					// We need to load new records, so clear the old ones first!
					$this->ResetBufferedRecords();

					// Set new Record start, will be used in the SQL Statement!
					$this->_currentRecordStart = $this->_currentRecordNum; // + 1;
					
					// Now read new ones
					$ret = $this->ReadNextRecordsFromDB($uID);

					// Check if we found more records
					if ( !isset($this->bufferedRecords[$this->_currentRecordNum] ) )
						$ret = ERROR_NOMORERECORDS;
				}
			}

			if ( $ret == SUCCESS && $this->_arrProperties != null )
			{
				// Init and set variables
				foreach ( $this->_arrProperties as $property ) 
				{
					// Check if mapping exists
					if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$property]) )
					{
						// Copy property if available!
						$dbfieldname = $dbmapping[$szTableType]['DBMAPPINGS'][$property];
						if ( isset($this->bufferedRecords[$this->_currentRecordNum][$dbfieldname]) ) 
						{
							if ( isset($fields[$property]['FieldType']) && $fields[$property]['FieldType'] == FILTER_TYPE_DATE ) // Handle as date!
								$arrProperitesOut[$property] = GetEventTime( $this->bufferedRecords[$this->_currentRecordNum][$dbfieldname] );
							else
								$arrProperitesOut[$property] = $this->bufferedRecords[$this->_currentRecordNum][$dbfieldname];
						}
						else
							$arrProperitesOut[$property] = '';
					}
					else
						$arrProperitesOut[$property] = '';
				}

				// Run optional Message Parsers now
				if ( isset($arrProperitesOut[SYSLOG_MESSAGE]) ) 
				{
					$retParser = $this->_logStreamConfigObj->ProcessMsgParsers($arrProperitesOut[SYSLOG_MESSAGE], $arrProperitesOut);

					// Check if we have to skip the message!
					if ( $retParser == ERROR_MSG_SKIPMESSAGE )
						$ret = $retParser;
				}

				// Set uID to the PropertiesOut! //DEBUG -> $this->_currentRecordNum;
				$uID = $arrProperitesOut[SYSLOG_UID] = $this->bufferedRecords[$this->_currentRecordNum][$dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID]];
				
				// Increment $_currentRecordNum
				$this->_currentRecordNum++;
			}

			// Check how long we are running. If only two seconds of execution time are left, we abort further reading!
			$scriptruntime = intval(microtime_float() - $gl_starttime);
			if ( $content['MaxExecutionTime'] > 0 && $scriptruntime > ($content['MaxExecutionTime']-2) )
			{
				// This may display a warning message, so the user knows we stopped reading records because of the script timeout. 
				$content['logstream_warning'] = "false";
				$content['logstream_warning_details'] = $content['LN_WARNING_LOGSTREAMDISK_TIMEOUT'];
				$content['logstream_warning_code'] = ERROR_FILE_NOMORETIME;
				
				// Return error code 
				return ERROR_FILE_NOMORETIME;
			}

		// This additional filter check will take care on dynamic fields from the message parser!
		} while ( $this->ApplyFilters($ret, $arrProperitesOut) != SUCCESS && $ret == SUCCESS );

		// reached here means return result!
		return $ret;
	}

	/**
	* Implementation of Seek
	*/
	public function Sseek(&$uID, $mode, $numrecs)
	{
		// predefine return value
		$ret = SUCCESS;

		switch ($mode) 
		{ 
			case EnumSeek::UID:
//				if ( $uID == UID_UNKNOWN ) // set uID to first ID!
				{
					// No buffer? then read from DB!
					if ( $this->bufferedRecords == null )
						$ret = $this->ReadNextRecordsFromDB($uID);

					if ( $ret == SUCCESS ) 
					{
						$this->_currentRecordNum = 0;
						$uID = $this->bufferedRecords[ $this->_currentRecordNum ];
					}
				}
				break;
		}

		// Return result!
		return $ret; 
	}

	/**
	* GetMessageCount will return the count of Message. 
	* If this count is not available, the function will 
	* return the default -1
	*/
	public function GetMessageCount()
	{
		return $this->_totalRecordCount;
	}

	/**
	* This function returns the first UID for previous PAGE, if availbale! 
	* Otherwise will return -1!
	*/
	public function GetPreviousPageUID()
	{
		return $this->_previousPageUID;
	}

	/**
	* This function returns the FIRST UID for the FIRST PAGE! 
	* Will be done by a seperated SQL Statement.
	*/
	public function GetFirstPageUID()
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;
		
		// Only perform query if row counting is enabled!
		if ( strlen($this->_SQLwhereClause) > 0 && !$this->_logStreamConfigObj->DBEnableRowCounting )
			return $this->_firstPageUID;

		$szSql = "SELECT MAX(" . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID] . ") FROM " .  $this->_logStreamConfigObj->DBTableName . $this->_SQLwhereClause;
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if ($myQuery)
		{
			// obtain first and only row
			$myRow = mysql_fetch_row($myQuery);
			$this->_firstPageUID = $myRow[0];

			// Free query now
			mysql_free_result ($myQuery); 

			// Increment for the Footer Stats 
			$querycount++;
		}

		// Return result!
		return $this->_firstPageUID;
	}

	/**
	* This function returns the first UID for the last PAGE! 
	* Will be done by a seperated SQL Statement.
	*/
	public function GetLastPageUID()
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Only perform query if row counting is enabled!
		if ( strlen($this->_SQLwhereClause) > 0 && !$this->_logStreamConfigObj->DBEnableRowCounting )
			return $this->_lastPageUID;

		$szSql = "SELECT MIN(" . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID] . ") FROM " .  $this->_logStreamConfigObj->DBTableName . $this->_SQLwhereClause;
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if ($myQuery)
		{
			// obtain first and only row
			$myRow = mysql_fetch_row($myQuery);
			$this->_lastPageUID = $myRow[0];

			// Free query now
			mysql_free_result ($myQuery); 

			// Increment for the Footer Stats 
			$querycount++;
		}
		
		// Return result!
		return $this->_lastPageUID;
	}

	/**
	* This function returns the current Page number, if availbale! 
	* Otherwise will return 0! We also assume that this function is 
	* only called once DB is open!
	*/
	public function GetCurrentPageNumber()
	{
		return $this->_currentPageNumber;
	}

	/*
	* Implementation of IsPropertySortable
	*
	* For now, sorting is only possible for the UID Property!
	*/
	public function IsPropertySortable($myProperty)
	{
		global $fields;

		// TODO: HARDCODED | FOR NOW only FALSE!
		return false;

		if ( isset($fields[$myProperty]) && $myProperty == SYSLOG_UID )
			return true;
		else
			return false;
	}

	/**
	* Implementation of GetLogStreamStats 
	*
	* Returns an Array og logstream statsdata 
	*	Count of Data Items
	*	Total Filesize
	*/
	public function GetLogStreamStats()
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Perform if Connection is true!
		if ( $this->_dbhandle != null ) 
		{
			// Obtain Stats data for this table!
			$szSql = "SHOW TABLE STATUS FROM " .  $this->_logStreamConfigObj->DBName; 
			$myQuery = mysql_query($szSql, $this->_dbhandle);
			if ($myQuery)
			{
				// Loop through results
				while ($myRow = mysql_fetch_array($myQuery,  MYSQL_ASSOC))
				{
					// Set tablename!
					$tableName = $myRow['Name'];
					$myStats = null;
					$myStats[]			= array( 'StatsDisplayName' => 'Table name', 'StatsValue' => $tableName );

					// copy usefull statsdata
					if ( isset($myRow['Engine']) ) 
						$myStats[]		= array( 'StatsDisplayName' => 'Table engine', 'StatsValue' => $myRow['Engine'] );
					if ( isset($myRow['Rows']) ) 
						$myStats[]		= array( 'StatsDisplayName' => 'Rowcount', 'StatsValue' => $myRow['Rows'] );
					
					if ( isset($myRow['Data_length']) ) 
						$myStats[]		= array( 'StatsDisplayName' => 'Table filesize (bytes)', 'StatsValue' => $myRow['Data_length'] );
					if ( isset($myRow['Collation']) ) 
						$myStats[]		= array( 'StatsDisplayName' => 'Collation', 'StatsValue' => $myRow['Collation'] );
					if ( isset($myRow['Comment']) ) 
						$myStats[]		= array( 'StatsDisplayName' => 'Comment', 'StatsValue' => $myRow['Comment'] );

					$stats[]['STATSDATA'] = $myStats;
				}

				// Free query now
				mysql_free_result ($myQuery); 

				// Increment for the Footer Stats 
				$querycount++;
			}
			
			// return results!
			return $stats;
		}
		else
			return null;
	}

	/**
	* Implementation of GetLogStreamTotalRowCount 
	*
	* Returns the total amount of rows in the main datatable
	*/
	public function GetLogStreamTotalRowCount()
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Set default rowcount
		$rowcount = null;

		// Perform if Connection is true!
		if ( $this->_dbhandle != null ) 
		{
			// SHOW TABLE STATUS FROM
			$szSql = "SELECT count(" . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID] . ") as Counter FROM " .  $this->_logStreamConfigObj->DBTableName; 
			$myQuery = mysql_query($szSql, $this->_dbhandle);
			if ($myQuery)
			{
				// Obtain RowCount!
				$myRow		= mysql_fetch_row($myQuery); 
				$rowcount = $myRow[0];

				// Free query now
				mysql_free_result ($myQuery); 

				// Increment for the Footer Stats 
				$querycount++;
			}
		}

		//return result
		return $rowcount; 
	}

	/**
	* Implementation of the CleanupLogdataByDate function! Returns affected rows!
	*/
	public function CleanupLogdataByDate( $nDateTimeStamp )
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Set default rowcount
		$rowcount = null;

		// Perform if Connection is true!
		if ( $this->_dbhandle != null ) 
		{
			// --- Init Filters if necessary!
			if ( $this->_filters == null )
				$this->SetFilter( "" ); // This will init filters!
			
			// Create SQL Where Clause!
			$this->CreateSQLWhereClause();
			// ---

			// --- Add default WHERE clause
			if ( strlen($this->_SQLwhereClause) > 0 ) 
				$szWhere = $this->_SQLwhereClause;
			else 
				$szWhere = ""; 
			
			// Add Datefilter if necessary!
			if ( $nDateTimeStamp > 0 ) 
			{
				if ( strlen($szWhere) > 0 ) 
					$szWhere .= " AND "; 
				else
					$szWhere = " WHERE "; 
				
				// Append Date Filter!
				$szWhere .= " UNIX_TIMESTAMP(" . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] . ") < " . $nDateTimeStamp; 
			}
			// ---

			// DELETE DATA NOW!
			$szSql = "DELETE FROM " .  $this->_logStreamConfigObj->DBTableName . $szWhere; 
			OutputDebugMessage("LogStreamDB|CleanupLogdataByDate: Created SQL Query:<br>" . $szSql, DEBUG_DEBUG);
			$myQuery = mysql_query($szSql, $this->_dbhandle);
			if ($myQuery)
			{
				// Get affected rows and return!
				$rowcount = mysql_affected_rows();
				
				// Reset AUTO_INCREMENT if all records were deleted!
				if ( $nDateTimeStamp == 0 ) 
				{
					$szSql = "ALTER TABLE " . $this->_logStreamConfigObj->DBTableName . " AUTO_INCREMENT=0";
					$myQuery = mysql_query($szSql, $this->_dbhandle);
					// error occured, output DEBUG message
					if (!$myQuery)
						$this->PrintDebugError("CleanupLogdataByDate failed to reset AUTO_INCREMENT for '" . $this->_logStreamConfigObj->DBTableName . "' table. ");
				}

				// Free result not needed here!
				//mysql_free_result ($myQuery); 
			}
			else
			{
				// error occured, output DEBUG message
				$this->PrintDebugError("CleanupLogdataByDate failed with SQL Statement ' " . $szSql . " '");
			}
		}

		//return affected rows
		return $rowcount; 
	}

	
	/*
	*	Implementation of the UpdateAllMessageChecksum
	*
	*	Update all missing checksum properties in the current database
	*/
	public function UpdateAllMessageChecksum( )
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// UPDATE DATA NOW!
		$szSql =	"UPDATE " . $this->_logStreamConfigObj->DBTableName . 
					" SET " . $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . " = crc32(" . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_MESSAGE] . ") " . 
					" WHERE " . $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . " IS NULL OR " . $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . " = 0"; 

		// Output Debug Informations
		OutputDebugMessage("LogStreamDB|UpdateAllMessageChecksum: Running Created SQL Query:<br>" . $szSql, DEBUG_ULTRADEBUG);
		
		// Running SQL Query
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if ($myQuery)
		{
			// Debug Output
			OutputDebugMessage("LogStreamDB|UpdateAllMessageChecksum: Successfully updated Checksum of '" . mysql_affected_rows($this->_dbhandle) . "' datarecords", DEBUG_INFO);

			// Return success
			return SUCCESS; 
		}
		else
		{
			// error occured, output DEBUG message
			$this->PrintDebugError("SaveMessageChecksum failed with SQL Statement ' " . $szSql . " '");

			// Failed
			return ERROR; 
		}
	}


	/*
	*	Implementation of the SaveMessageChecksum
	*
	*	Creates an database UPDATE Statement and performs it!
	*/
	public function SaveMessageChecksum( $arrProperitesIn )
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		if ( isset($arrProperitesIn[SYSLOG_UID]) && isset($arrProperitesIn[MISC_CHECKSUM]) && isset($dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM]) )
		{
			// UPDATE DATA NOW!
			$szSql =	"UPDATE " . $this->_logStreamConfigObj->DBTableName . 
						" SET " . $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] . " = " . $arrProperitesIn[MISC_CHECKSUM] . 
						" WHERE " . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID] . " = " . $arrProperitesIn[SYSLOG_UID]; 
			$myQuery = mysql_query($szSql, $this->_dbhandle);
			if ($myQuery)
			{
				// Return success
				return SUCCESS; 
			}
			else
			{
				// error occured, output DEBUG message
				$this->PrintDebugError("SaveMessageChecksum failed with SQL Statement ' " . $szSql . " '");

				// Failed
				return ERROR; 
			}
		}
		else
			// Missing important properties
			return ERROR; 
	}


	/**
	* Implementation of ConsolidateItemListByField 
	*
	* In the native MYSQL Logstream, the database will do most of the work
	*
	* @return integer Error stat
	*/
	public function ConsolidateItemListByField($szConsFieldId, $nRecordLimit, $szSortFieldId, $nSortingOrder)
	{
		global $content, $dbmapping, $fields;

		// Copy helper variables, this is just for better readability
		$szTableType = $this->_logStreamConfigObj->DBTableType;
		
		// Check if fields are available 
		if ( !isset($dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId]) || !isset($dbmapping[$szTableType]['DBMAPPINGS'][$szSortFieldId]) )
			return ERROR_DB_DBFIELDNOTFOUND;

		// --- Set Options 
		$nConsFieldType = $fields[$szConsFieldId]['FieldType'];

		if ( $nSortingOrder == SORTING_ORDER_DESC ) 
			$szSortingOrder = "DESC"; 
		else
			$szSortingOrder = "ASC"; 
		// --- 

		// --- Set DB Field names
		$myDBConsFieldName = $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId];
		$myDBGroupByFieldName = $myDBConsFieldName;
		$myDBQueryFields = $myDBConsFieldName . ", ";

		// Set Sorted Field
		if ( $szConsFieldId == $szSortFieldId ) 
			$myDBSortedFieldName = "itemcount"; 
		else
			$myDBSortedFieldName = $szSortFieldId; 
		// --- 
		
		// Special handling for date fields
		if ( $nConsFieldType == FILTER_TYPE_DATE )
		{
			// Helper variable for the select statement
			$mySelectFieldName = $myDBGroupByFieldName . "Grouped";
			$myDBQueryFieldName = "DATE( " . $myDBConsFieldName . ") AS " . $myDBGroupByFieldName ;
		}

		// Set Limit String
		if ( $nRecordLimit > 0 ) 
			$szLimitSql = " LIMIT " . $nRecordLimit;
		else
			$szLimitSql = "";

		// Create SQL Where Clause!
		if ( $this->_SQLwhereClause == "" ) 
		{
			$res = $this->CreateSQLWhereClause();
			if ( $res != SUCCESS ) 
				return $res;
		}

		// Create SQL String now!
		$szSql =	"SELECT " . 
					$myDBQueryFields .  
					"count(" . $myDBConsFieldName . ") as itemcount " . 
					" FROM " . $this->_logStreamConfigObj->DBTableName . 
					$this->_SQLwhereClause . 
					" GROUP BY " . $myDBGroupByFieldName . 
					" ORDER BY " . $myDBSortedFieldName . " " . $szSortingOrder . 
					$szLimitSql ;

		// Output Debug Informations
		OutputDebugMessage("LogStreamDB|ConsolidateItemListByField: Running Created SQL Query:<br>" . $szSql, DEBUG_ULTRADEBUG);

		// Perform Database Query
		$myquery = mysql_query($szSql, $this->_dbhandle);
		if ( !$myquery ) 
			return ERROR_DB_QUERYFAILED;
		
		// Initialize Array variable
		$aResult = array();

		// read data records
		while ($myRow = mysql_fetch_array($myquery,  MYSQL_ASSOC))
		{
			// Create new row
			$aNewRow = array();

			foreach ( $myRow as $myFieldName => $myFieldValue ) 
			{
				if ( $myFieldName == $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId] )
					$aNewRow[$szConsFieldId] = $myFieldValue;
				else
					$aNewRow[$myFieldName] = $myFieldValue;
			}
			
			// Add new row to result
			$aResult[] = $aNewRow;
		}

		// return finished array
		if ( count($aResult) > 0 )
			return $aResult;
		else
			return ERROR_NOMORERECORDS;
	}


	/**
	* Implementation of ConsolidateDataByField 
	*
	* In the native MYSQL Logstream, the database will do most of the work
	*
	* @return integer Error stat
	*/
	public function ConsolidateDataByField($szConsFieldId, $nRecordLimit, $szSortFieldId, $nSortingOrder, $aIncludeCustomFields = null, $bIncludeLogStreamFields = false, $bIncludeMinMaxDateFields = false)
	{
		global $content, $dbmapping, $fields;

		// Copy helper variables, this is just for better readability
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Check if fields are available 
		if ( !isset($dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId]) || !isset($dbmapping[$szTableType]['DBMAPPINGS'][$szSortFieldId]) )
			return ERROR_DB_DBFIELDNOTFOUND;

		// --- Set Options 
		$nConsFieldType = $fields[$szConsFieldId]['FieldType'];

		if ( $nSortingOrder == SORTING_ORDER_DESC ) 
			$szSortingOrder = "DESC"; 
		else
			$szSortingOrder = "ASC"; 
		// --- 

		// --- Set DB Field names
		$myDBConsFieldName = $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId];
		$myDBGroupByFieldName = $myDBConsFieldName;
		
		// Check which fields to include
		if ( $aIncludeCustomFields != null ) 
		{
			$myDBQueryFields = "";
			foreach ( $aIncludeCustomFields as $myFieldName ) 
			{
				if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName]) ) 
					$myDBQueryFields .= $dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName] . ", ";
			}
			
			// Append Sortingfield
			if ( !in_array($szConsFieldId, $aIncludeCustomFields) )
				$myDBQueryFields .= $myDBConsFieldName . ", ";
		}
		else if ( $bIncludeLogStreamFields ) 
		{
			$myDBQueryFields = "";
			foreach ( $this->_arrProperties as $myFieldName ) 
			{
				if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName]) ) 
					$myDBQueryFields .= $dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName] . ", ";
			}
		}
		else // Only Include ConsolidateField
			$myDBQueryFields = $myDBConsFieldName . ", ";
		
		// Add Min and Max fields for DATE if desired 
		if ( $bIncludeMinMaxDateFields )
		{
			$myDBQueryFields .= "Min(" . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] . ") as firstoccurrence_date, ";
			$myDBQueryFields .= "Max(" . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] . ") as lastoccurrence_date, ";
		}
		
		if ( $szConsFieldId == $szSortFieldId ) 
			$myDBSortedFieldName = "itemcount"; 
		else
			$myDBSortedFieldName = $szSortFieldId; 
		// --- 

		// Special handling for date fields
		if ( $nConsFieldType == FILTER_TYPE_DATE )
		{
			// Helper variable for the select statement
			$mySelectFieldName = $myDBGroupByFieldName . "Grouped";
			$myDBQueryFieldName = "DATE( " . $myDBConsFieldName . ") AS " . $myDBGroupByFieldName ;
		}

		// Set Limit String
		if ( $nRecordLimit > 0 ) 
			$szLimitSql = " LIMIT " . $nRecordLimit;
		else
			$szLimitSql = "";

		// Create SQL Where Clause!
		if ( strlen($this->_SQLwhereClause) <= 0 ) 
		{
			$res = $this->CreateSQLWhereClause();
			if ( $res != SUCCESS ) 
				return $res;
		}

		// Create SQL String now!
		$szSql =	"SELECT " . 
					$myDBQueryFields .  
					"count(" . $myDBConsFieldName . ") as itemcount " . 
					" FROM " . $this->_logStreamConfigObj->DBTableName . 
					$this->_SQLwhereClause . 
					" GROUP BY " . $myDBGroupByFieldName . 
					" ORDER BY " . $myDBSortedFieldName . " " . $szSortingOrder . 
					$szLimitSql ;

		// Output Debug Informations
		OutputDebugMessage("LogStreamDB|ConsolidateDataByField: Running Created SQL Query:<br>" . $szSql, DEBUG_ULTRADEBUG);

		// Perform Database Query
		$myquery = mysql_query($szSql, $this->_dbhandle);
		if ( !$myquery ) 
			return ERROR_DB_QUERYFAILED;

		// Initialize Array variable
		$aResult = array();

		// read data records
		while ($myRow = mysql_fetch_array($myquery,  MYSQL_ASSOC))
		{
			// Create new row
			$aNewRow = array();

			foreach ( $myRow as $myFieldName => $myFieldValue ) 
			{
				$myFieldID = $this->GetFieldIDbyDatabaseMapping($szTableType, $myFieldName); 
				$aNewRow[ $myFieldID ] = $myFieldValue;

/*				if ( $myFieldName == $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId] )
					$aNewRow[$szConsFieldId] = $myFieldValue;
				else
				{
					$aNewRow[$myFieldName] = $myFieldValue;
*/
//				}
			}
			// Add new row to result
			$aResult[] = $aNewRow;
		}

		// return finished array
		if ( count($aResult) > 0 )
			return $aResult;
		else
			return ERROR_NOMORERECORDS;
	}

	/**
	* Implementation of GetCountSortedByField 
	*
	* In the native MYSQL Logstream, the database will do most of the work
	*
	* @return integer Error stat
	*/
	public function GetCountSortedByField($szFieldId, $nFieldType, $nRecordLimit)
	{
		global $content, $dbmapping;

		// Copy helper variables, this is just for better readability
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$szFieldId]) )
		{
			// Set DB Field name first!
			$myDBFieldName = $dbmapping[$szTableType]['DBMAPPINGS'][$szFieldId];
			$myDBQueryFieldName = $myDBFieldName;
			$mySelectFieldName = $myDBFieldName;
			
			// Special handling for date fields
			if ( $nFieldType == FILTER_TYPE_DATE )
			{
				// Helper variable for the select statement
				$mySelectFieldName = $mySelectFieldName . "Grouped";
				$myDBQueryFieldName = "DATE( " . $myDBFieldName . ") AS " . $mySelectFieldName ;
			}

			// Create SQL Where Clause!
			if ( $this->_SQLwhereClause == "" ) 
			{
				$res = $this->CreateSQLWhereClause();
				if ( $res != SUCCESS ) 
					return $res;
			}

			// Create SQL String now!
			$szSql =	"SELECT " . 
						$myDBQueryFieldName . ", " . 
						"count(" . $myDBFieldName . ") as TotalCount " . 
						" FROM " . $this->_logStreamConfigObj->DBTableName . 
						$this->_SQLwhereClause . 
						" GROUP BY " . $mySelectFieldName . 
						" ORDER BY TotalCount DESC" . 
						" LIMIT " . $nRecordLimit;

			// Perform Database Query
			$myquery = mysql_query($szSql, $this->_dbhandle);
			if ( !$myquery ) 
				return ERROR_DB_QUERYFAILED;
			
			// Initialize Array variable
			$aResult = array();

			// read data records
			while ($myRow = mysql_fetch_array($myquery,  MYSQL_ASSOC))
				$aResult[ $myRow[$mySelectFieldName] ] = $myRow['TotalCount'];

			// return finished array
			if ( count($aResult) > 0 )
				return $aResult;
			else
				return ERROR_NOMORERECORDS;
		}
		else
		{
			// return error code, field mapping not found
			return ERROR_DB_DBFIELDNOTFOUND;
		}
	}



	/*
	*	============= Beginn of private functions =============
	*/

	/*
	*	This function expects the filters to already being set earlier. 
	*	Otherwise no usual WHERE Clause can be created!
	*/
	private function CreateSQLWhereClause()
	{
		if ( $this->_filters != null )
		{
			global $dbmapping;
			$szTableType = $this->_logStreamConfigObj->DBTableType;

			// Reset WhereClause
			$this->_SQLwhereClause = "";
			
			// --- Build Query Array
			$arrayQueryProperties = $this->_arrProperties; 
			if ( isset($this->_arrFilterProperties) && $this->_arrFilterProperties != null)
			{
				foreach ( $this->_arrFilterProperties as $filterproperty )
				{
					if ( $this->_arrProperties == null || !in_array($filterproperty, $this->_arrProperties) ) 
						$arrayQueryProperties[] = $filterproperty; 
				}
			}
			// --- 

			// Loop through all available properties
			foreach( $arrayQueryProperties as $propertyname )
			{
				// If the property exists in the filter array, we have something to filter for ^^!
				if ( array_key_exists($propertyname, $this->_filters) )
				{
					// Process all filters
					foreach( $this->_filters[$propertyname] as $myfilter ) 
					{
						// Only perform if database mapping is available for this filter!
						if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$propertyname]) ) 
						{
							switch( $myfilter[FILTER_TYPE] )
							{
								case FILTER_TYPE_STRING:
									// --- Either make a LIKE or a equal query!
									if ( $myfilter[FILTER_MODE] & FILTER_MODE_SEARCHFULL )
									{
										// Set addnot to nothing
										$addnod = "";

										// --- Check if user wants to include or exclude!
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE)
										{
											$szSearchBegin = " = '";
											$szSearchEnd = "' ";
										}
										else
										{
											$szSearchBegin = " <> '";
											$szSearchEnd = "' ";
										}
										// ---
									}
									else if ( $myfilter[FILTER_MODE] & FILTER_MODE_SEARCHREGEX )
									{
										// --- Check if user wants to include or exclude!
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE)
											$addnod = "";
										else
											$addnod = " NOT";
										// ---

										$szSearchBegin = " REGEXP '";
										$szSearchEnd = "' ";
									}
									else
									{
										// --- Check if user wants to include or exclude!
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE)
											$addnod = "";
										else
											$addnod = " NOT";
										// ---

										$szSearchBegin = " LIKE '%";
										$szSearchEnd = "%' ";
									}
									// ---

									// --- If Syslog message, we have AND handling, otherwise OR!
									if ( $propertyname == SYSLOG_MESSAGE )
										$addor = " AND ";
									else
									{
										// If we exclude filters, we need to combine with AND
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE)
											$addor = " OR ";
										else
											$addor = " AND ";
									}
									// ---
									
									// Now Create LIKE Filters
									if ( isset($tmpfilters[$propertyname]) ) 
										$tmpfilters[$propertyname][FILTER_VALUE] .= $addor . $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . $addnod . $szSearchBegin . DB_RemoveBadChars($myfilter[FILTER_VALUE]) . $szSearchEnd;
									else
									{
										$tmpfilters[$propertyname][FILTER_TYPE] = FILTER_TYPE_STRING;
										$tmpfilters[$propertyname][FILTER_VALUE] = $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . $addnod . $szSearchBegin . DB_RemoveBadChars($myfilter[FILTER_VALUE]) . $szSearchEnd;
									}
									break;
								case FILTER_TYPE_NUMBER:
									// --- Check if user wants to include or exclude!
									if ( $myfilter[FILTER_MODE] & FILTER_MODE_EXCLUDE )
									{
										// Add to filterset
										$szArrayKey = $propertyname . "-NOT";
										if ( isset($tmpfilters[$szArrayKey]) ) 
											$tmpfilters[$szArrayKey][FILTER_VALUE] .= ", " . $myfilter[FILTER_VALUE];
										else
										{
											$tmpfilters[$szArrayKey][FILTER_TYPE] = FILTER_TYPE_NUMBER;
											$tmpfilters[$szArrayKey][FILTER_VALUE] = $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . " NOT IN (" . DB_RemoveBadChars($myfilter[FILTER_VALUE]);
										}
									}
									else
									{
										// Add to filterset
										if ( isset($tmpfilters[$propertyname]) ) 
											$tmpfilters[$propertyname][FILTER_VALUE] .= ", " . $myfilter[FILTER_VALUE];
										else
										{
											$tmpfilters[$propertyname][FILTER_TYPE] = FILTER_TYPE_NUMBER;
											$tmpfilters[$propertyname][FILTER_VALUE] = $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . " IN (" . DB_RemoveBadChars($myfilter[FILTER_VALUE]);
										}
									}
									// ---
									break;
								case FILTER_TYPE_DATE:
									if ( isset($tmpfilters[$propertyname]) ) 
										$tmpfilters[$propertyname][FILTER_VALUE] .= " AND ";
									else
									{
										$tmpfilters[$propertyname][FILTER_VALUE] = "";
										$tmpfilters[$propertyname][FILTER_TYPE] = FILTER_TYPE_DATE;
									}
									
									if ( $myfilter[FILTER_DATEMODE] == DATEMODE_LASTX ) 
									{
										// Get current timestamp
										$nNowTimeStamp = time();

										if		( $myfilter[FILTER_VALUE] == DATE_LASTX_HOUR )
											$nNowTimeStamp -= 60 * 60; // One Hour!
										else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_12HOURS )
											$nNowTimeStamp -= 60 * 60 * 12; // 12 Hours!
										else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_24HOURS )
											$nNowTimeStamp -= 60 * 60 * 24; // 24 Hours!
										else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_7DAYS )
											$nNowTimeStamp -= 60 * 60 * 24 * 7; // 7 days
										else if	( $myfilter[FILTER_VALUE] == DATE_LASTX_31DAYS )
											$nNowTimeStamp -= 60 * 60 * 24 * 31; // 31 days
										else 
										{
											// Set filter to unknown and Abort in this case!
											$tmpfilters[$propertyname][FILTER_TYPE] = FILTER_TYPE_UNKNOWN;
											break;
										}
										
										// Append filter
										$tmpfilters[$propertyname][FILTER_VALUE] .= $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . " > '" . date("Y-m-d H:i:s", $nNowTimeStamp) . "'";
									}
									else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_FROM ) 
									{
										// Obtain Event struct for the time!
										$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);
										$tmpfilters[$propertyname][FILTER_VALUE] .= $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . " > '" . date("Y-m-d H:i:s", $myeventtime[EVTIME_TIMESTAMP]) . "'";
									}
									else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_TO ) 
									{
										// Obtain Event struct for the time!
										$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);
										$tmpfilters[$propertyname][FILTER_VALUE] .= $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . " < '" . date("Y-m-d H:i:s", $myeventtime[EVTIME_TIMESTAMP]) . "'";
									}
									else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_DATE ) 
									{
										// Obtain Event struct for the time!
										$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);
										$tmpfilters[$propertyname][FILTER_VALUE] .= $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . " > '" . date("Y-m-d H:i:s", $myeventtime[EVTIME_TIMESTAMP]) . "' AND " . 
																					$dbmapping[$szTableType]['DBMAPPINGS'][$propertyname] . " < '" . date("Y-m-d H:i:s", ($myeventtime[EVTIME_TIMESTAMP]+86400) ) . "'";
									}

									break;
								default:
									// Nothing to do!
									break;
							}
						}
						else
						{
							// Check how to treat not found db mappings / filters
							if ( GetConfigSetting("TreatNotFoundFiltersAsTrue", 0, CFGLEVEL_USER) == 0 )
								return ERROR_DB_DBFIELDNOTFOUND;
						}
					}
				}
			}

			// Check and combine all filters now!
			if ( isset($tmpfilters) )
			{
				// Append filters
				foreach( $tmpfilters as $tmpfilter ) 
				{
					// Init WHERE or Append AND
					if ( strlen($this->_SQLwhereClause) > 0 )	
						$this->_SQLwhereClause .= " AND ";
					else
						$this->_SQLwhereClause = " WHERE ";

					switch( $tmpfilter[FILTER_TYPE] )
					{
						case FILTER_TYPE_STRING:
							$this->_SQLwhereClause .= "( " . $tmpfilter[FILTER_VALUE] . ") ";
							break;
						case FILTER_TYPE_NUMBER:
							$this->_SQLwhereClause .= $tmpfilter[FILTER_VALUE] . ") ";
							break;
						case FILTER_TYPE_DATE:
							$this->_SQLwhereClause .= $tmpfilter[FILTER_VALUE];
							break;
						default:
							// Should not happen, wrong filters! 
							// We add a dummy into the where clause, just as a place holder 
							$this->_SQLwhereClause .= " 1=1 ";
							break;
					}
				}
			}

// echo $this->_SQLwhereClause;
			//$dbmapping[$szTableType][SYSLOG_UID]
		}
		else // No filters means nothing to do!
			return SUCCESS;
	}

	/*
	*	Destroy the SQL QUery!
	*/
	private function DestroyMainSQLQuery()
	{
		// create query if necessary!
		if ( $this->_myDBQuery != null )
		{
			// Free Query ressources
			mysql_free_result ($this->_myDBQuery); 
			$this->_myDBQuery = null;
		}

		// return success state if reached this point!
		return SUCCESS;
	}

	/*
	*	This helper function will read the next records into the buffer. 
	*/
	private function ReadNextRecordsFromDB($uID)
	{
		global $querycount;

		// Clear SQL Query first!
		$this->DestroyMainSQLQuery();

		// return error if there was one!
		if ( ($res = $this->CreateMainSQLQuery($uID)) != SUCCESS )
			return $res;

		// Append LIMIT clause
//		$szSql .= " LIMIT " . $this->_currentRecordStart . ", " . $this->_logStreamConfigObj->RecordsPerQuery;
		
		// Copy rows into the buffer!
		$iBegin = $this->_currentRecordNum;
		while ($myRow = mysql_fetch_array($this->_myDBQuery,  MYSQL_ASSOC))
		{
			// Check if result was successfull!
			if ( $myRow === FALSE || !$myRow  )
				break;
			
			// Keys will be converted into lowercase!
			$this->bufferedRecords[$iBegin] = array_change_key_case($myRow, CASE_LOWER);
			$iBegin++;
		}

		// --- Check if results were found
		if ( $iBegin == $this->_currentRecordNum )
			return ERROR_NOMORERECORDS;
		// --- 

		// Free Query ressources
//		mysql_free_result ($myquery); 

		// Only obtain count if enabled and not done before
		if ( $this->_logStreamConfigObj->DBEnableRowCounting && $this->_totalRecordCount == -1 ) 
		{
			$this->_totalRecordCount = $this->GetRowCountFromTable();

			if ( $this->_totalRecordCount <= 0 )
				return ERROR_NOMORERECORDS;
		}

		// Increment for the Footer Stats 
		$querycount++;
		
		// return success state if reached this point!
		return SUCCESS;
	}

	/*
	*	Create the SQL QUery!
	*/
	private function CreateMainSQLQuery($uID)
	{
		global $querycount;
		
		// Get SQL Statement
		$szSql = $this->CreateSQLStatement($uID);

		// --- Append LIMIT 
		$szSql .= " LIMIT " . $this->_logStreamConfigObj->RecordsPerQuery;
		// ---

		// Perform Database Query
		$this->_myDBQuery = mysql_query($szSql, $this->_dbhandle);
		if ( !$this->_myDBQuery ) 
		{
			// Check if a field is missing!
			if ( mysql_errno() == 1054 ) 
			{
				// Handle missing field and try again!
				if ( $this->HandleMissingField() == SUCCESS ) 
				{
					$this->_myDBQuery = mysql_query($szSql, $this->_dbhandle);
					if ( !$this->_myDBQuery ) 
					{
						$this->PrintDebugError("Invalid SQL: ".$szSql);
						return ERROR_DB_QUERYFAILED;
					}
				}
				else // Failed to add field dynamically
					return ERROR_DB_QUERYFAILED;
			}
			else
			{
				$this->PrintDebugError("Invalid SQL: ".$szSql);
				return ERROR_DB_QUERYFAILED;
			}
		}
		else
		{
			// Skip one entry in this case
			if ( $this->_currentRecordStart > 0 ) 
			{
				// Throw away 
				$myRow = mysql_fetch_array($this->_myDBQuery,  MYSQL_ASSOC);
			}
		}

		// Increment for the Footer Stats 
		$querycount++;
		
		// Output Debug Informations
		OutputDebugMessage("LogStreamDB|CreateMainSQLQuery: Created SQL Query:<br>" . $szSql, DEBUG_DEBUG);

		// return success state if reached this point!
		return SUCCESS;
	}

	/*
	*	Creates the SQL Statement we are going to use!
	*/
	private function CreateSQLStatement($uID, $includeFields = true)
	{
		global $dbmapping;
		
		// Copy helper variables, this is just for better readability
		$szTableType = $this->_logStreamConfigObj->DBTableType;
		$szSortColumn = $this->_logStreamConfigObj->SortColumn;
		
		// Create Basic SQL String
		if ( $this->_logStreamConfigObj->DBEnableRowCounting ) // with SQL_CALC_FOUND_ROWS
			$sqlString = "SELECT SQL_CALC_FOUND_ROWS " . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID];
		else													// without row calc
			$sqlString = "SELECT " . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID];
		
		// Append fields if needed
		if ( $includeFields && $this->_arrProperties != null ) 
		{
			// Loop through all requested fields
			foreach ( $this->_arrProperties as $myproperty ) 
			{	
				// SYSLOG_UID already added!
				if ( $myproperty != SYSLOG_UID && isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) )
				{
					// Append field!
					$sqlString .= ", " . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty];
				}
			}
		}

		// Append FROM 'table'!
		$sqlString .= " FROM " . $this->_logStreamConfigObj->DBTableName;

		// Append precreated where clause
		$sqlString .= $this->_SQLwhereClause;

		// Append UID QUERY!
		if ( $uID != -1 )
		{
			if ( $this->_readDirection == EnumReadDirection::Forward )
				$myOperator = ">=";
			else
				$myOperator = "<=";

			if ( strlen($this->_SQLwhereClause) > 0 )
				$sqlString .= " AND " . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID] . " $myOperator $uID";
			else
				$sqlString .= " WHERE " . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID] . " $myOperator $uID";
		}

		// Append ORDER clause
		if ( $this->_readDirection == EnumReadDirection::Forward )
			$sqlString .= " ORDER BY " .  $dbmapping[$szTableType]['DBMAPPINGS'][$szSortColumn];
		else if ( $this->_readDirection == EnumReadDirection::Backward )
			$sqlString .= " ORDER BY " .  $dbmapping[$szTableType]['DBMAPPINGS'][$szSortColumn] . " DESC";

		// return SQL result string:
		return $sqlString;
	}

	/*
	*	Reset record buffer in this function!
	*/
	private function ResetBufferedRecords()
	{
		if ( isset($this->bufferedRecords) )
		{
			// Loop through all subrecords first!
			foreach ($this->bufferedRecords as $mykey => $myrecord)
				unset( $this->bufferedRecords[$mykey] );

			// Set buffered records to NULL!
			$this->bufferedRecords = null;
		}
	}

	/*
	*	Helper function to display SQL Errors for now!
	*/
	private function PrintDebugError($szErrorMsg)
	{
		global $extraErrorDescription; 

		$errdesc = mysql_error();
		$errno = mysql_errno();

		$errormsg="$szErrorMsg <br>";
		$errormsg.="Detail error: $errdesc <br>";
		$errormsg.="Error Code: $errno <br>";
		
		// Add to additional error output
		$extraErrorDescription = $errormsg;

		//Output!
		OutputDebugMessage("LogStreamDB|PrintDebugError: $errormsg", DEBUG_ERROR);
	}
	
	/*
	*	Returns the number of possible records by using a query
	*/
	private function GetRowCountByString($szQuery)
	{
		if ($myQuery = mysql_query($szQuery)) 
		{   
			$num_rows = mysql_num_rows($myQuery);
			mysql_free_result ($myQuery); 
		}
		return $num_rows;
	}

	/*
	*	Returns the number of possible records by using an existing queryid
	*/
	private function GetRowCountByQueryID($myQuery)
	{
		$num_rows = mysql_num_rows($myQuery);
		return $num_rows;
	}

	/*
	*	Returns the number of possible records by using a select count statement!
	*/
	private function GetRowCountFromTable()
	{
		if ( $myquery = mysql_query("Select FOUND_ROWS();", $this->_dbhandle) ) 
		{
			// Get first and only row!
			$myRow = mysql_fetch_array($myquery);
			
			// copy row count
			$numRows = $myRow[0];
		}
		else
			$numRows = -1;

		// return result!
		return $numRows;

		/* OLD slow code!
		global $dbmapping,$querycount;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Create Statement and perform query!
		$szSql = "SELECT count(" . $dbmapping[$szTableType][SYSLOG_UID] . ") FROM " . $this->_logStreamConfigObj->DBTableName . $this->_SQLwhereClause;
		if ($myQuery = mysql_query($szSql, $this->_dbhandle)) 
		{
			// obtain first and only row
			$myRow = mysql_fetch_row($myQuery);
			$numRows = $myRow[0];

			// Increment for the Footer Stats 
			$querycount++;

			// Free query now
			mysql_free_result ($myQuery); 
		}
		else
			$numRows = -1;
		*/
	}

	/*
	*	Function handles missing database fields automatically!
	*/
	private function HandleMissingField( $szMissingField = null, $arrProperties = null )
	{
		global $dbmapping, $fields;

		// Get Err description
		$errdesc = mysql_error();
		
		// Try to get missing field from SQL Error of not specified as argument
		if ( $szMissingField == null ) 
		{
			if ( preg_match("/Unknown column '(.*?)' in '(.*?)'$/", $errdesc, $errOutArr ) ) 
				$szMissingField = $errOutArr[1]; 
			else
			{
				$this->PrintDebugError("ER_BAD_FIELD_ERROR - SQL Statement: ". $errdesc);
				return ERROR_DB_DBFIELDNOTFOUND;
			}
		}

		// Set Properties to default if NULL 
		if ( $arrProperties == null ) 
			$arrProperties = $this->_arrProperties; 
		
		// Get Tabletype
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Loop through all fields to see which one is missing!
		foreach ( $arrProperties as $myproperty ) 
		{
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) && $szMissingField == $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] )
			{
				// Create SQL Numeric field
				$szUpdateSql = ""; $szUnsigned = "";
				if ( $fields[$myproperty]['FieldType'] == FILTER_TYPE_NUMBER ) 
				{
					// This will add the checksum field as unsigned automatically!
					if ( $myproperty == MISC_CHECKSUM ) 
						$szUnsigned = "UNSIGNED";
					$szUpdateSql = "ALTER TABLE `" . $this->_logStreamConfigObj->DBTableName . "` ADD `" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "` int(11) " . $szUnsigned . " NOT NULL DEFAULT '0'"; 
				}
				if ( $fields[$myproperty]['FieldType'] == FILTER_TYPE_STRING ) 
					$szUpdateSql = "ALTER TABLE `" . $this->_logStreamConfigObj->DBTableName . "` ADD `" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "` varchar(60) NOT NULL DEFAULT ''"; 
				if ( $fields[$myproperty]['FieldType'] == FILTER_TYPE_DATE ) 
					$szUpdateSql = "ALTER TABLE `" . $this->_logStreamConfigObj->DBTableName . "` ADD `" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'"; 

				if ( strlen($szUpdateSql) > 0 )
				{
					// Update Table schema now!
					$myQuery = mysql_query($szUpdateSql, $this->_dbhandle);
					if (!$myQuery)
					{
						// Return failure!
						$this->PrintDebugError("ER_BAD_FIELD_ERROR - Dynamically Adding field '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "' with Statement failed: '" . $szUpdateSql . "'");
						return ERROR_DB_ADDDBFIELDFAILED;
					}
				}
				else
				{
					// Return failure!
					$this->PrintDebugError("ER_BAD_FIELD_ERROR - Field '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "' is missing has to be added manually to the database layout!'");
					return ERROR_DB_ADDDBFIELDFAILED;
				}
			}
		}

		// Reached this point means success!
		return SUCCESS; 
	}

	/*
	*	Helper function to return a list of Indexes for the logstream table 
	*/
	private function GetIndexesAsArray()
	{
		global $querycount;

		// Verify database connection (This also opens the database!)
		$res = $this->Verify();
		if ( $res != SUCCESS ) 
			return $res;
		
		// Init Array
		$arrIndexKeys = array();

		// Create SQL and Get INDEXES for table!
		$szSql = "SHOW INDEX FROM " .  $this->_logStreamConfigObj->DBTableName; 
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if ($myQuery)
		{
			// Loop through results
			while ($myRow = mysql_fetch_array($myQuery,  MYSQL_ASSOC))
			{
				// Add to index keys
				$arrIndexKeys[] = strtolower($myRow['Key_name']); 
			}

			// Free query now
			mysql_free_result ($myQuery); 

			// Increment for the Footer Stats 
			$querycount++;
		}

		// return Array
		return $arrIndexKeys; 
	}


	/*
	*	Helper function to return a list of Fields from the logstream table 
	*/
	private function GetFieldsAsArray()
	{
		global $querycount;

		// Verify database connection (This also opens the database!)
		$res = $this->Verify();
		if ( $res != SUCCESS ) 
			return $res;
		
		// Init Array
		$arrFieldKeys = array();

		// Create SQL and Get INDEXES for table!
		$szSql = "SHOW FIELDS FROM " .  $this->_logStreamConfigObj->DBTableName; 
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if ($myQuery)
		{
			// Loop through results
			while ($myRow = mysql_fetch_array($myQuery,  MYSQL_ASSOC))
			{
				// Add to index keys
				$arrFieldKeys[] = strtolower($myRow['Field']); 
			}

			// Free query now
			mysql_free_result ($myQuery); 

			// Increment for the Footer Stats 
			$querycount++;
		}

		// return Array
		return $arrFieldKeys; 
	}


	/*
	*	Helper function to return a list of Indexes for the logstream table 
	*/
	private function GetTriggersAsArray()
	{
		global $querycount;

		// Verify database connection (This also opens the database!)
		$res = $this->Verify();
		if ( $res != SUCCESS ) 
			return $res;
		
		// Init Array
		$arrIndexTriggers = array();

		// Create SQL and Get INDEXES for table!
		$szSql = "SHOW TRIGGERS"; 
		$myQuery = mysql_query($szSql, $this->_dbhandle);
		if ($myQuery)
		{
			// Loop through results
			while ($myRow = mysql_fetch_array($myQuery,  MYSQL_ASSOC))
			{
//				print_r (  $myRow ); 
				// Add to index keys
				$arrIndexTriggers[] = strtolower($myRow['Trigger']); 
			}

			// Free query now
			mysql_free_result ($myQuery); 

			// Increment for the Footer Stats 
			$querycount++;
		}

		// return Array
		return $arrIndexTriggers; 
	}

// --- End of Class!
}

?>