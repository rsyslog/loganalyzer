<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											
	* -----------------------------------------------------------------	
	* LogStreamPDO provides access to the data through PDO Interface
	*
	* \version 2.0.0 Init Version
	*																
	* All directives are explained within this file	
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

class LogStreamPDO extends LogStream {
	private $_dbhandle = null;
	
	// Helper to store the database records
	private $bufferedRecords = null;
	private $_currentRecordStart = 0;
	private $_currentRecordNum = 0;
	private $_totalRecordCount = -1;
	private $_previousPageUID = -1;
	private $_lastPageUID = -1;
	private $_firstPageUID = -1;
	private $_currentPageNumber = -1;

	private $_SQLwhereClause = "";
	private $_myDBQuery = null;

	// Constructor
	public function LogStreamPDO($streamConfigObj) {
		$this->_logStreamConfigObj = $streamConfigObj;

		// Verify if Extension is enabled 
		if ( extension_loaded('pdo') == 0 )
			DieWithFriendlyErrorMsg("Error, PDO Extensions are not enabled or installed! This Source can not operate.");
		
		/*
		if ( $this->_logStreamConfigObj->DBType == DB_MYSQL )
		{
			// Probe if a function exists!
			if ( !function_exists("mysql_connect") )
				DieWithFriendlyErrorMsg("Error, MYSQL Extensions are not enabled! Function 'mysql_connect' does not exist.");
		}
		*/
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

		// Create DSN String
		$myDBDriver = $this->_logStreamConfigObj->GetPDODatabaseType();
		$myDsn = $this->_logStreamConfigObj->CreateConnectDSN();
		if ( strlen($myDsn) > 0 )
		{
			// Check if configured driver is actually loaded!
			//print_r(PDO::getAvailableDrivers());
			if ( !in_array($myDBDriver, PDO::getAvailableDrivers()) )
			{
				$this->PrintDebugError('PDO Database Driver not loaded: ' . $myDBDriver . "<br>Please check your php configuration extensions");
				return ERROR_DB_INVALIDDBDRIVER;
			}

			try 
			{
				// Try to connect to the database
				$this->_dbhandle = new PDO( $myDsn, $this->_logStreamConfigObj->DBUser, $this->_logStreamConfigObj->DBPassword);

//$handle->setAttribute(PDO::ATTR_TIMEOUT, 3);
			}
			catch (PDOException $e) 
			{
				$this->PrintDebugError('PDO Database Connection failed: ' . $e->getMessage() . "<br>DSN: " . $myDsn);
				return ERROR_DB_CONNECTFAILED;
			}
		}
		else
		{
			// Invalid DB Driver!
			return ERROR_DB_INVALIDDBDRIVER;
		}
		
		// Copy the Property Array 
		$this->_arrProperties = $arrProperties;

		// Check if DB Mapping exists
		if ( !isset($dbmapping[ $this->_logStreamConfigObj->DBTableType ]) )
			return ERROR_DB_INVALIDDBMAPPING;

		// Create SQL Where Clause first!
		$this->CreateSQLWhereClause();

		// Only obtain rowcount if enabled and not done before
		if ( $this->_logStreamConfigObj->DBEnableRowCounting && $this->_totalRecordCount == -1 ) 
			$this->_totalRecordCount = $this->GetRowCountFromTable();

// Success, this means we init the Pagenumber to ONE!
//$this->_currentPageNumber = 1;

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
		// trigger closing database query!
		$this->DestroyMainSQLQuery();
		
// TODO CLOSE DB CONN?!

		return true;
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
		global $dbmapping, $fields;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// define $ret
		$ret = SUCCESS;

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
//echo "1mowl " . $this->_currentRecordStart . "=" . $this->_currentRecordNum;

				if ( !isset($this->bufferedRecords[$this->_currentRecordNum] ) )
					$ret = ERROR_NOMORERECORDS;
			}
		}

		if ( $ret == SUCCESS )
		{
			// Init and set variables
			foreach ( $this->_arrProperties as $property ) 
			{
				// Check if mapping exists
				if ( isset($dbmapping[$szTableType][$property]) )
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
				else
					$arrProperitesOut[$property] = '';
			}

			// Set uID to the PropertiesOut! //DEBUG -> $this->_currentRecordNum;
			$uID = $arrProperitesOut[SYSLOG_UID] = $this->bufferedRecords[$this->_currentRecordNum][$dbmapping[$szTableType][SYSLOG_UID]];

			// Increment $_currentRecordNum
			$this->_currentRecordNum++;
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
/*				else
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
					
					// Set totalpages number if available
					if ( $this->_totalRecordCount != -1 )
						$totalpages = intval($this->_totalRecordCount / $this->_logStreamConfigObj->_pageCount);
					else
						$totalpages = 1;

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
							
							// Increment our Pagenumber if needed!
							if ( $this->_currentRecordNum % $this->_logStreamConfigObj->_pageCount == 0 ) 
								$this->_currentPageNumber++;
						}
						
						if ( $this->_currentPageNumber > 1 && $this->_readDirection == EnumReadDirection::Forward) 
							$this->_currentPageNumber = $totalpages - $this->_currentPageNumber + 1;

						//---  Extra check to set the correct $_previousPageUID!
						if ( $this->_currentRecordNum > $this->_logStreamConfigObj->_pageCount && isset($this->bufferedRecords[$this->_currentRecordNum - 50][$uidfieldname]) ) 
						{
							$this->_previousPageUID = $this->bufferedRecords[$this->_currentRecordNum - $this->_logStreamConfigObj->_pageCount - 1][$uidfieldname];
						}
						// TODO! Handle the case where previous ID is not set in the bufferedrecords!
						//--- 

						// We need to load new records, so clear the old ones first!
						$this->ResetBufferedRecords();

						// Set new Record start, will be used in the SQL Statement!
						$this->_currentRecordStart = $this->_currentRecordNum;
					}

					// Delete buffered records, then they will be read automatically in ReadNext()
					$this->ResetBufferedRecords();
				}
*/
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

		$szSql = "SELECT MAX(" . $dbmapping[$szTableType][SYSLOG_UID] . ") FROM " .  $this->_logStreamConfigObj->DBTableName . $this->_SQLwhereClause;
		$myQuery = $this->_dbhandle->query($szSql);
		if ( $myQuery ) 
		{
			$myRow = $myQuery->fetchColumn();
			$this->_firstPageUID = $myRow; // $myRow[0];

			// Free query now
			$myQuery->closeCursor();

			// Increment for the Footer Stats 
			$querycount++;

		}
//echo $szSql . "<br>" . $this->_firstPageUID;
//exit;

		// finally return result!
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

		$szSql = "SELECT MIN(" . $dbmapping[$szTableType][SYSLOG_UID] . ") FROM " .  $this->_logStreamConfigObj->DBTableName . $this->_SQLwhereClause;
		$myQuery = $this->_dbhandle->query($szSql);
		if ( $myQuery ) 
		{
			$myRow = $myQuery->fetchColumn();
			$this->_lastPageUID = $myRow; // $myRow[0];

			// Free query now
			$myQuery->closeCursor();

			// Increment for the Footer Stats 
			$querycount++;

		}
//echo $szSql . "<br>" . $this->_lastPageUID;
//exit;

		// finally return result!
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
								
								// Not create LIKE Filters
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
									$tmpfilters[$propertyname][FILTER_VALUE] .= $dbmapping[$szTableType][$propertyname] . " > '" . date("Y-m-d H:i:s", $nNowTimeStamp) . "'";
								}
								else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_FROM ) 
								{
									// Obtain Event struct for the time!
									$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);
									$tmpfilters[$propertyname][FILTER_VALUE] .= $dbmapping[$szTableType][$propertyname] . " > '" . date("Y-m-d H:i:s", $myeventtime[EVTIME_TIMESTAMP]) . "'";
								}
								else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_TO ) 
								{
									// Obtain Event struct for the time!
									$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);
									$tmpfilters[$propertyname][FILTER_VALUE] .= $dbmapping[$szTableType][$propertyname] . " < '" . date("Y-m-d H:i:s", $myeventtime[EVTIME_TIMESTAMP]) . "'";
								}

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

//echo $this->_SQLwhereClause;
			//$dbmapping[$szTableType][SYSLOG_UID]
		}
		else // No filters means nothing to do!
			return SUCCESS;
	}

	/*
	*	Create the SQL QUery!
	*/
	private function CreateMainSQLQuery($uID)
	{
		global $querycount;

		// create query if necessary!
		if ( $this->_myDBQuery == null )
		{
			// Get SQL Statement
			$szSql = $this->CreateSQLStatement($uID);

			// Perform Database Query
			$this->_myDBQuery = $this->_dbhandle->query($szSql);
			if ( !$this->_myDBQuery ) 
			{
				$this->PrintDebugError( "Invalid SQL: ".$szSql . "<br><br>Errorcode: " . $this->_dbhandle->errorCode() );
				return ERROR_DB_QUERYFAILED;
			}

			// Increment for the Footer Stats 
			$querycount++;
		}

		// return success state if reached this point!
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
	//		$this->_myDBQuery->closeCursor();
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
		// Create query if necessary
		if ( $this->_myDBQuery == null )
		{
			// return error if there was one!
			if ( ($res = $this->CreateMainSQLQuery($uID)) != SUCCESS )
				return $res;

			// return specially with NO RECORDS when 0 records are returned! Otherwise it will be -1
			if ( $this->_myDBQuery->rowCount() == 0 )
				return ERROR_NOMORERECORDS;
		}

		// Copy rows into the buffer!
		$iBegin = $this->_currentRecordNum;

		$iCount = 0;
		while( $this->_logStreamConfigObj->RecordsPerQuery > $iCount)
		{
			//Obtain next record 
			$myRow = $this->_myDBQuery->fetch(PDO::FETCH_ASSOC);

			// Check if result was successfull!
			if ( $myRow === FALSE || !$myRow  )
				break;

			$this->bufferedRecords[$iBegin] = $myRow;
			$iBegin++;

			// Increment counter
			$iCount++;
		}

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
//		if ( $this->_logStreamConfigObj->DBEnableRowCounting ) // with SQL_CALC_FOUND_ROWS
//			$sqlString = "SELECT SQL_CALC_FOUND_ROWS " . $dbmapping[$szTableType][SYSLOG_UID];
//		else													// without row calc
			$sqlString = "SELECT " . $dbmapping[$szTableType][SYSLOG_UID];
		
		// Append fields if needed
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

		// Append UID QUERY!
		if ( $uID != -1 )
		{
			if ( $this->_readDirection == EnumReadDirection::Forward )
				$myOperator = ">=";
			else
				$myOperator = "<=";

			if ( strlen($this->_SQLwhereClause) > 0 )
				$sqlString .= " AND " . $dbmapping[$szTableType][SYSLOG_UID] . " $myOperator $uID";
			else
				$sqlString .= " WHERE " . $dbmapping[$szTableType][SYSLOG_UID] . " $myOperator $uID";
		}

		// Append ORDER clause
		if ( $this->_readDirection == EnumReadDirection::Forward )
			$sqlString .= " ORDER BY " .  $dbmapping[$szTableType][$szSortColumn];
		else if ( $this->_readDirection == EnumReadDirection::Backward )
			$sqlString .= " ORDER BY " .  $dbmapping[$szTableType][$szSortColumn] . " DESC";

//echo $sqlString;
//exit;

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
		if ( GetConfigSetting("MiscShowDebugMsg", 0, CFGLEVEL_USER) == 1 )
		{
			$errdesc = $this->_dbhandle == null ? "" : implode( ";", $this->_dbhandle->errorInfo() );
			$errno = $this->_dbhandle == null ? "" : $this->_dbhandle->errorCode();

			$errormsg ="<table width=\"600\" align=\"center\" class=\"with_border\"><tr><td>";
			$errormsg.="<center><H3><font color='red'>Error: " . $szErrorMsg . "</font></H3><br></center>";
			$errormsg.="<B>Errordetails:</B><br>";
			$errormsg.="Detail Error: $errdesc <br>";
			$errormsg.="Error Code: $errno <br>";
			$errormsg.="Date: ".date("d.m.Y @ H:i"). "<br>";
			$errormsg.="</td></tr></table>";
			
			//Output!
			print( $errormsg );
		}
	}
	
	/*
	*	Returns the number of possible records by using a select count statement!
	*/
	private function GetRowCountFromTable()
	{
/*
		if ( $myquery = mysql_query("Select FOUND_ROWS();", $this->_dbhandle) ) 
		{
			// Get first and only row!
			$myRow = mysql_fetch_array($myquery);
			
			// copy row count
			$numRows = $myRow[0];
		}
		else
			$numRows = -1;
*/

		/* OLD slow code! */
		global $dbmapping,$querycount;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Create Statement and perform query!
		$szSql = "SELECT count(" . $dbmapping[$szTableType][SYSLOG_UID] . ") FROM " . $this->_logStreamConfigObj->DBTableName . $this->_SQLwhereClause;
		$myQuery = $this->_dbhandle->query($szSql);
		if ($myQuery)
		{
			// obtain first and only row
			$myRow = $myQuery->fetchColumn();
			$numRows = $myRow; // $myRow[0];

			// Increment for the Footer Stats 
			$querycount++;

			// Free query now
			$myQuery->closeCursor();
		}
		else
		{
			$this->PrintDebugError("RowCount query failed: " . $szSql);
			$numRows = -1;
		}

		// return result!
		return $numRows;
	}


}

?>