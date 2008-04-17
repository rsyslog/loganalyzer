<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
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

	private $_SQLwhereClause = "";

/*	private $_currentOffset = -1;
	private $_currentStartPos = -1;
	private $_fp = null;
	private $_bEOS = false;

	const _BUFFER_length = 8192;
	private $_buffer = false;
	private $_buffer_length = 0;
	private $_p_buffer = -1;
*/
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

		// Try to connect to the database
		$this->_dbhandle = mysql_connect($this->_logStreamConfigObj->DBServer,$this->_logStreamConfigObj->DBUser,$this->_logStreamConfigObj->DBPassword);
		if (!$this->_dbhandle) 
			return ERROR_DB_CONNECTFAILED;

		$bRet = mysql_select_db($this->_logStreamConfigObj->DBName, $this->_dbhandle);
		if(!$bRet) 
			return ERROR_DB_CANNOTSELECTDB;
		
		// Copy the Property Array 
		$this->_arrProperties = $arrProperties;
		
		// Check if DB Mapping exists
		if ( !isset($dbmapping[ $this->_logStreamConfigObj->DBTableType ]) )
			return ERROR_DB_INVALIDDBMAPPING;

		// Create SQL Where Clause first!
		$this->CreateSQLWhereClause();

		// Obtain count of records
		$this->_totalRecordCount = $this->GetRowCountFromTable();

		if ( $this->_totalRecordCount <= 0 )
			return ERROR_NOMORERECORDS;
		
		// reached this point means success!
		return SUCCESS;
	}

	/**
	* Close the database connection.
	*
	* @return integer Error state
	*/
	public function Close()
	{
		mysql_close($this->_dbhandle);
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
	public function ReadNext(&$uID, &$arrProperitesOut)
	{
		// Helpers needed for DB Mapping
		global $dbmapping, $fields;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// define $ret
		$ret = SUCCESS;

		// No buffer? then read from DB!
		if ( $this->bufferedRecords == null )
			$ret = $this->ReadNextRecordsFromDB($uID);

		if ( $ret == SUCCESS )
		{
			// Init and set variables
			foreach ( $this->_arrProperties as $property ) 
			{
				// Copy property if available!
				$dbfieldname = $dbmapping[$szTableType][$property];
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

			// Set uID to the PropertiesOut! //DEBUG -> $this->_currentRecordNum;
			$uID = $arrProperitesOut[SYSLOG_UID] = $this->bufferedRecords[$this->_currentRecordNum][$dbmapping[$szTableType][SYSLOG_UID]];
			
			// Increment $_currentRecordNum
			$this->_currentRecordNum++;

			if ( !isset($this->bufferedRecords[$this->_currentRecordNum] ) )
			{
				// We need to load new records, so clear the old ones first!
				$this->ResetBufferedRecords();

				// Set new Record start, will be used in the SQL Statement!
				$this->_currentRecordStart = $this->_currentRecordNum; // + 1;
				
				// Now read new ones
				$ret = $this->ReadNextRecordsFromDB($uID);

				// TODO Check and READ next record! 
//				die ("omfg wtf ReadNext " . $this->_currentRecordNum);
			}
		}

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
				if ( $uID == UID_UNKNOWN ) // set uID to first ID!
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
				else
				{
					// Obtain fieldname for uID
					global $dbmapping;
					$uidfieldname = $dbmapping[$this->_logStreamConfigObj->DBTableType][SYSLOG_UID];
					
					// Clear if necessary!
					if ( $this->bufferedRecords == null )
						$this->ResetBufferedRecords();

					// Loop through all records for now, maybe optimized later!
					$bFound = false;
					$tmpuID = $uID;
					$ret = ERROR_NOMORERECORDS; // Set Default error code!
					while( $bFound == false && $this->ReadNextIDsFromDB() == SUCCESS )
					{
						foreach ( $this->bufferedRecords as $myRecord )
						{
							if ( $myRecord[$uidfieldname] == $uID )
							{
								$bFound = true;
								$ret = SUCCESS;
								break; // Break foreach loop!
							}
							else
							{
								$tmpuID = $myRecord[$uidfieldname];
								// Only Increment $_currentRecordNum
								$this->_currentRecordNum++;
							}
						}

						// We need to load new records, so clear the old ones first!
						$this->ResetBufferedRecords();

						// Set new Record start, will be used in the SQL Statement!
						$this->_currentRecordStart = $this->_currentRecordNum;
					}

					// Delete buffered records, then they will be read automatically in ReadNext()
					$this->ResetBufferedRecords();
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

	/*
	* GetSortOrderProperties is not implemented yet. So it always
	* return null.
	*/
	public function GetSortOrderProperties()
	{
/*
		return null;
*/
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

			// Loop through all available properties
			foreach( $this->_arrProperties as $propertyname )
			{
				// If the property exists in the filter array, we have something to filter for ^^!
				if ( array_key_exists($propertyname, $this->_filters) )
				{
					// Process all filters
					foreach( $this->_filters[$propertyname] as $myfilter ) 
					{
						switch( $myfilter[FILTER_TYPE] )
						{
							case FILTER_TYPE_STRING:
								// Check if user wants to include or exclude!
								if ( $myfilter[FILTER_MODE] == FILTER_MODE_INCLUDE)
									$addnod = "";
								else
									$addnod = " NOT";

								// If Syslog message, we have AND handling, otherwise OR!
								if ( $propertyname == SYSLOG_MESSAGE )
									$addor = " AND ";
								else
									$addor = " OR ";



								if ( isset($tmpfilters[$propertyname]) ) 
									$tmpfilters[$propertyname][FILTER_VALUE] .= $addor . $dbmapping[$szTableType][$propertyname] . $addnod . " LIKE '%" . $myfilter[FILTER_VALUE] . "%'";
								else
								{
									$tmpfilters[$propertyname][FILTER_TYPE] = FILTER_TYPE_STRING;
									$tmpfilters[$propertyname][FILTER_VALUE] = $dbmapping[$szTableType][$propertyname] . $addnod . " LIKE '%" . $myfilter[FILTER_VALUE] . "%'";
								}
								break;
							case FILTER_TYPE_NUMBER:
								if ( isset($tmpfilters[$propertyname]) ) 
									$tmpfilters[$propertyname][FILTER_VALUE] .= ", " . $myfilter[FILTER_VALUE];
								else
								{
									$tmpfilters[$propertyname][FILTER_TYPE] = FILTER_TYPE_NUMBER;
									$tmpfilters[$propertyname][FILTER_VALUE] = $dbmapping[$szTableType][$propertyname] . " IN (" . $myfilter[FILTER_VALUE];
								}
								break;
							case FILTER_TYPE_DATE:
								break;
							default:
								// Nothing to do!
								break;
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
							break;
						default:
							// Nothing to do!
							break;
					}
				}
			}

//echo $this->_SQLwhereClause;
			//$dbmapping[$szTableType][SYSLOG_UID]

			//$this->_SQLwhereClause;
		}
		else // No filters means nothing to do!
			return SUCCESS;
	}


	/*
	*	This function only reads the uID values from the database. Using this method, 
	*	it will be much faster to find the starting uID point we need when paging is used.
	*/
	private function ReadNextIDsFromDB()
	{
		global $querycount;

		// Get SQL Statement
		$szSql = $this->CreateSQLStatement(-1, false);

		// Append LIMIT clause
		$szSql .= " LIMIT " . $this->_currentRecordStart . ", " . $this->_logStreamConfigObj->IDsPerQuery;

		// Perform Database Query
		$myquery = mysql_query($szSql, $this->_dbhandle);
		if ( !$myquery ) 
		{
			$this->PrintDebugError("Invalid SQL: ".$szSql);
			return ERROR_DB_QUERYFAILED;
		}

		// Copy rows into the buffer!
		$iBegin = $this->_currentRecordNum;
		while ($myRow = mysql_fetch_array($myquery,  MYSQL_ASSOC))
		{
			$this->bufferedRecords[$iBegin] = $myRow;
			$iBegin++;
		}

		// Free Query ressources
		mysql_free_result ($myquery); 

		// Increment for the Footer Stats 
		$querycount++;
		
		// return success state if reached this point!
		return SUCCESS;
	}

	/*
	*	This helper function will read the next records into the buffer. 
	*/
	private function ReadNextRecordsFromDB($uID)
	{
		global $querycount;

		// Get SQL Statement
		$szSql = $this->CreateSQLStatement($uID);
		
		// Append LIMIT clause
		$szSql .= " LIMIT " . $this->_currentRecordStart . ", " . $this->_logStreamConfigObj->RecordsPerQuery;

		// Perform Database Query
		$myquery = mysql_query($szSql, $this->_dbhandle);
		if ( !$myquery ) 
		{
			$this->PrintDebugError("Invalid SQL: ".$szSql);
			return ERROR_DB_QUERYFAILED;
		}
		
		// Copy rows into the buffer!
		$iBegin = $this->_currentRecordNum;
		while ($myRow = mysql_fetch_array($myquery,  MYSQL_ASSOC))
		{
			$this->bufferedRecords[$iBegin] = $myRow;
			$iBegin++;
		}

		// Free Query ressources
		mysql_free_result ($myquery); 

		// Increment for the Footer Stats 
		$querycount++;
		
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
		
		// Create SQL String
		$sqlString = "SELECT " . $dbmapping[$szTableType][SYSLOG_UID];
		if ( $includeFields && $this->_arrProperties != null ) 
		{
			// Loop through all requested fields
			foreach ( $this->_arrProperties as $myproperty ) 
			{	
				// SYSLOG_UID already added!
				if ( $myproperty != SYSLOG_UID && isset($dbmapping[$szTableType][$myproperty]) )
				{
					// Append field!
					$sqlString .= ", " . $dbmapping[$szTableType][$myproperty];
				}
			}
		}

		// Append FROM 'table'!
		$sqlString .= " FROM " . $this->_logStreamConfigObj->DBTableName;

		// Append precreated where clause
		$sqlString .= $this->_SQLwhereClause;

		// Append ORDER clause
		if ( $this->_readDirection == EnumReadDirection::Forward )
			$sqlString .= " ORDER BY " .  $dbmapping[$szTableType][$szSortColumn];
		else if ( $this->_readDirection == EnumReadDirection::Backward )
			$sqlString .= " ORDER BY " .  $dbmapping[$szTableType][$szSortColumn] . " DESC";

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
		global $CFG;

		if ( isset($CFG['MiscShowDebugMsg']) && $CFG['MiscShowDebugMsg'] == 1 )
		{
			$errdesc = mysql_error();
			$errno = mysql_errno();

			$errormsg="Database error: $szErrorMsg <br>";
			$errormsg.="mysql error: $errdesc <br>";
			$errormsg.="mysql error number: $errno <br>";
			$errormsg.="Date: ".date("d.m.Y @ H:i"). "<br>";
			$errormsg.="Script: ".getenv("REQUEST_URI"). "<br>";
			$errormsg.="Referer: ".getenv("HTTP_REFERER"). "<br>";
			
			//Output!
			print( $errormsg );
		}
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
		global $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;
		
		// Create Statement and perform query!
		$szQuery = "SELECT count(" . $dbmapping[$szTableType][SYSLOG_UID] . ") FROM " . $this->_logStreamConfigObj->DBTableName . $this->_SQLwhereClause;
		if ($myQuery = mysql_query($szQuery)) 
		{
			// obtain first and only row
			$myRow = mysql_fetch_row($myQuery);
			$numRows = $myRow[0];

			// Free query now
			mysql_free_result ($myQuery); 
		}

		// return result!
		return $numRows;
	}


}

?>