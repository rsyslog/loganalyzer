<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* LogStreamMongoDB provides access to MongoDB databases.
	*
	* \version 1.0.0 Init Version
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008-2012 Adiscon GmbH.
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

class LogStreamMongoDB extends LogStream {
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

	private $_myMongoCon = null; 
	private $_myMongoDB = null; 
	private $_myMongoCollection = null; 
	private $_myMongoFields = null; 
	private $_myMongoQuery = null; 

	// Constructor
	public function LogStreamMongoDB($streamConfigObj) {
		$this->_logStreamConfigObj = $streamConfigObj;

		// Probe if a function exists!
		if ( !function_exists("bson_encode") )
			DieWithFriendlyErrorMsg("Error, MongoDB PHP Driver Extensions is not installed! Please see <a href\"http://www.php.net/manual/en/mongo.installation.php\">http://www.php.net/manual/en/mongo.installation.php</a> for installation details.");
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

		// Create Needed Fields Array first!
		$res = $this->CreateFieldsArray(); 
		if ( $res != SUCCESS ) 
			return $res;
		
		// Create Filters for first time!
// NEEDED OR NOT?
//		$res = $this->CreateQueryArray(UID_UNKNOWN);
//		if ( $res != SUCCESS ) 
//			return $res;

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
		$bReturn = SUCCESS; 
		if ($this->_myMongoCon) 
		{
			if (!$this->_myMongoCon->close())
				$bReturn = false; // return fail
		}
		
		// Reset variables
		$this->_myMongoCon = null; 
		$this->_myMongoDB = null; 
		$this->_myMongoCollection = null; 

		return $bReturn;
	}

	/**
	* Verify if the database connection exists!
	*
	* @return integer Error state
	*/
	public function Verify() {
		// Try to connect to the database
		if ( $this->_myMongoCon == null ) 
		{
			try 
			{
				// Forces to open a new Connection
				$this->_myMongoCon = new Mongo("mongodb://" . $this->_logStreamConfigObj->DBServer . ":" . $this->_logStreamConfigObj->DBPort ); // Connect to Mongo Server
			}
			catch ( MongoConnectionException $e ) 
			{
				// Log error!
				$this->PrintDebugError("Verify:Connect failed with error ' " . $e->getMessage() . " '");

				// Return error code
				return ERROR_DB_CONNECTFAILED;
			}
		}
		
		try 
		{
			$this->_myMongoDB = $this->_myMongoCon->selectDB( $this->_logStreamConfigObj->DBName ); // Connect to Database

			// Only try to auth if Username is configured
			if ( strlen($this->_logStreamConfigObj->DBUser) > 0 )
			{
				// TODO: Not tested yet, sample code!
				$szUsrPwd	= $this->_logStreamConfigObj->DBUser . ":mongo:" . $this->_logStreamConfigObj->DBPassword;
				$hashUsrPwd = md5($szUsrPwd);

				// Get Nonce
				$myNonce = $this->_myMongoDB->command(array("getnonce" => 1));
				$saltedHash = md5($myNonce["nonce"] . $this->_logStreamConfigObj->DBUser . $hashUsrPwd);
				
				$result = $this->_myMongoDB->command(array("authenticate" => 1, 
					"user" => $this->_logStreamConfigObj->DBUser,
					"nonce" => $myNonce["nonce"],
					"key" => $saltedHash
				));

				if ( $result["ok"] == 0 ) 
				{
					// Log error!
					$this->PrintDebugError("Verify:Auth failed with error ' " . $result["errmsg"] . " '");

					// Return error code
					return ERROR_DB_CANNOTSELECTDB;
				}
			}
		}
		catch ( MongoException $e ) 
		{
			// Log error!
			$this->PrintDebugError("Verify:selectDB failed with error ' " . $e->getMessage() . " '");

			// Return error code
			return ERROR_DB_CANNOTSELECTDB;
		}

		// Check if the table exists!
		try 
		{
			$this->_myMongoCollection = $this->_myMongoDB->selectCollection ( $this->_logStreamConfigObj->DBCollection ); 
		}
		catch ( MongoException $e ) 
		{
			// Log error!
			$this->PrintDebugError("Verify:selectCollection failed with error ' " . $e->getMessage() . " '");

			// Return error code
			return ERROR_DB_TABLENOTFOUND;
		}

		// reached this point means success ;)!
		return SUCCESS;
	}


	/*
	*	Implementation of VerifyFields: Checks if fields exist in table
	*/
	public function VerifyFields( $arrProperitesIn )
	{
		// Not needed, successfull
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
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) ) 
			{
				if ( in_array($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], $arrIndexKeys) )
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
		}
		
		// Successfull
		return SUCCESS; 
	}

	/*
	*	Implementation of VerifyChecksumTrigger: Checks if checksum trigger exists
	*/
	public function VerifyChecksumTrigger( $myTriggerProperty )
	{
		// Not needed, successfull
		return SUCCESS; 
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
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty]) )
			{
				if (in_array($dbmapping[$szTableType]['DBMAPPINGS'][$myproperty], $arrIndexKeys) )
					continue;
				else
				{
					try 
					{
						// Add Unique Index for DBMapping
						$this->_myMongoCollection->ensureIndex(array( $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] => 1) /*, array("unique" => true) */ );

						// Index is missing for this field!
						OutputDebugMessage("LogStreamDB|CreateMissingIndexes: Createing missing INDEX for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "'", DEBUG_INFO);
					}
					catch ( MongoException $e ) 
					{
						// Log error!
						$this->PrintDebugError("CreateMissingIndexes failed with error ' " . $e->getMessage() . " '");
						 
						// Return error code
						return ERROR_DB_QUERYFAILED;
					}
					
	//					// Return failure!
	//					$this->PrintDebugError("Dynamically Adding INDEX for '" . $dbmapping[$szTableType]['DBMAPPINGS'][$myproperty] . "' failed with Statement: '" . $szSql . "'");
	//					return ERROR_DB_INDEXFAILED;
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
		// Successfull
		return SUCCESS; 
	}


	/*
	*	Implementation of GetCreateMissingTriggerSQL: Creates SQL needed to create a TRIGGER
	*/
	public function GetCreateMissingTriggerSQL( $myDBTriggerField, $myDBTriggerCheckSumField )
	{
		// Return nothing
		return ""; 
	}


	/*
	*	Implementation of CreateMissingTrigger: Creates missing triggers !
	*/
	public function CreateMissingTrigger( $myTriggerProperty, $myCheckSumProperty )
	{
		// Successfull
		return SUCCESS; 
	}


	/*
	*	Implementation of ChangeChecksumFieldUnsigned: Changes the Checkusm field to unsigned!
	*/
	public function ChangeChecksumFieldUnsigned()
	{
		// return results
		return SUCCESS;
	}


	/*
	*	Implementation of VerifyChecksumField: Verifies if the checkusm field is signed or unsigned!
	*/
	public function VerifyChecksumField()
	{
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
							{
								$myDateField = $this->bufferedRecords[$this->_currentRecordNum][$dbfieldname]; 
								if ( gettype($myDateField) == "object" && get_class($myDateField) == "MongoDate" ) 
								{
									$arrProperitesOut[$property][EVTIME_TIMESTAMP] = $myDateField->sec;
									$arrProperitesOut[$property][EVTIME_TIMEZONE] = date('O'); // Get default Offset
									$arrProperitesOut[$property][EVTIME_MICROSECONDS] = $myDateField->usec;
								}
								else // Try to parse Date!
									$arrProperitesOut[$property] = GetEventTime( $myDateField );
							}
							else
								$arrProperitesOut[$property] = $this->bufferedRecords[$this->_currentRecordNum][$dbfieldname];
						}
						else
							$arrProperitesOut[$property] = '';
					}
					else
					{
						$arrProperitesOut[$property] = '';
//						echo $property . "=" . $this->bufferedRecords[$this->_currentRecordNum][$dbfieldname];
					}
				}
				
				// --- Add dynamic fields into record! 
				foreach( $this->bufferedRecords[$this->_currentRecordNum] as $propName => $propValue)
				{
					if (	!isset($arrProperitesOut[$propName]) && 
							!$this->CheckFieldnameInMapping($szTableType, $propName) && 
							(isset($propValue) && strlen($propValue) > 0)
						)
					{
						// Add dynamic Property!
						if ( gettype($propValue) == "object" && get_class($propValue) == "MongoDate" )
							// Handle Date fields
							$arrProperitesOut[$propName] = GetFormatedDate($propValue->sec);
						else // Default handling
							$arrProperitesOut[$propName] = 	$propValue; 
					}
				}
				// --- 

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
		// functions became obselete
		return UID_UNKNOWN;
	}

	/**
	* This function returns the first UID for the last PAGE! 
	* Will be done by a seperated SQL Statement.
	*/
	public function GetLastPageUID()
	{
		// functions became obselete
		return UID_UNKNOWN;
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
		global $querycount; 

		$myStats = null;
		$myList = $this->_myMongoDB->listCollections();
		foreach ($myList as $myCollection)
		{
			// Set tablename!
			$tableName = $myCollection->getName(); 
			$myStats[]			= array( 'StatsDisplayName' => 'Table name', 'StatsValue' => $tableName );

			// copy usefull statsdata
			$myStats[]		= array( 'StatsDisplayName' => 'Datacount', 'StatsValue' => $myCollection->count() );
			$myStats[]		= array( 'StatsDisplayName' => 'IndexInfo', 'StatsValue' => var_export($myCollection->getIndexInfo(), true) );
			// $myStats[]		= array( 'StatsDisplayName' => 'validate', 'StatsValue' => var_export($myCollection->validate(), true) );

			$stats[]['STATSDATA'] = $myStats;
			$querycount++; 
		}

		// return results!
		return $stats;
	}

	/**
	* Implementation of GetLogStreamTotalRowCount 
	*
	* Returns the total amount of rows in the main datatable
	*/
	public function GetLogStreamTotalRowCount()
	{
		global $querycount, $dbmapping;

		// Set default rowcount
		$rowcount = null;

		// Perform if Connection is true!
		if ( $this->_myMongoCollection != null ) 
		{
			$rowcount = $this->_myMongoCollection->count(); 
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
		
		if ( $nDateTimeStamp > 0 )
		{
			// Create MongoDate Object from Timestamp
			$myMongoDate = new MongoDate($nDateTimeStamp);

			// Create Criteria Array
			$myCriteria = array( $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] => array('$lte' => $myMongoDate) ); 
		}
		else
		{
			// Use EMPTY array to delete all!
			$myCriteria = array(); 
		}

		try 
		{
			// Get Datacount!
			$myCursor = $this->_myMongoCollection->find( $myCriteria ); 
			$rowcount = $myCursor->count(); 
		
			// we have something to delete!
			if ( $rowcount > 0 ) 
			{
				// Remove all older records now!
				$myResult = $this->_myMongoCollection->remove( $myCriteria ); 
				OutputDebugMessage("LogStreamMongoDB|CleanupLogdataByDate: Result of deleting '$rowcount' objects: '$myResult'", DEBUG_DEBUG);

				// error occured, output DEBUG message
				// $this->PrintDebugError("CleanupLogdataByDate failed with SQL Statement ' " . $szSql . " '");
			}
		}
		catch ( MongoCursorException $e ) 
		{
			// Log error!
			$this->PrintDebugError("CleanupLogdataByDate failed with error ' " . $e->getMessage() . " '");
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

		// --- Create Query Array!
		$myMongoQuery = array(); 
		if ( ($res = $this->CreateQueryArray(UID_UNKNOWN)) != SUCCESS )
			return $res;
		
		// Copy array 
		$myMongoQuery = $this->_myMongoQuery; 

		// Set default for custom fields!
//		$myMongoQuery[ $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] ] = array( '$exists' => FALSE); 
		$myMongoQuery[ $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] ] = null; 
		// var_dump ( $myMongoQuery ); 
		// ---

		// --- Set DB Fields Array 
		$myMongoFields = array(); 
		$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] ] = true; 
		$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_MESSAGE] ] = true; 
		// ---
		
		// DEBUG CODE: KILL all checksums!
//		echo $this->_myMongoCollection->update( array ( "_id" => array( '$exists' => TRUE) ), array( '$set' => array($dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] => null) ), array("multiple" => true) );
//		exit; 
		//

		// Append LIMIT clause
		$iCount = 0; 
		$myCursor = $this->_myMongoCollection->find($myMongoQuery, $myMongoFields); // ->limit(10); // $collection->find();
		foreach ($myCursor as $mongoid => $myRow)
		{
			// Check if result was successfull! Compare the queried uID and the MONGOID to abort processing if the same ID was returned! Otherwise we have dupplicated results at the end
			if ( $myRow === FALSE || !$myRow && $myCursor->count() <= 1 )
				break;

			// Create Querydata
			$myRow[ "_id" ]; // = base_convert($myRow[ "_id" ], 16, 10); // Convert ID from HEX back to DEC
			// $mongoID = new MongoID( $myRow[ "_id" ] );
			$queryArray = array('_id' => $myRow[ "_id" ]);
			
			// Create Update Data
			$updateChecksum = crc32($myRow[ $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_MESSAGE] ]);
			$updateData = array( '$set' => array($dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] => $updateChecksum) ); 

			// Update data in Collection
			$this->_myMongoCollection->update( $queryArray, $updateData );
			$iCount++; // Debugcounter

			//var_dump ( $updateData ); 
			//var_dump ( $queryArray ); 
			//var_dump ( $this->_myMongoCollection->findOne($queryArray) ); 
			//exit; 
		}

		// Debug Output
		OutputDebugMessage("LogStreamMongoDB|UpdateAllMessageChecksum: Successfully updated Checksum of '" . $iCount . "' datarecords", DEBUG_INFO);
		return SUCCESS; 
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
			// Create Querydata
			$myMongoID = new MongoId( $this->convBaseHelper($arrProperitesIn[SYSLOG_UID], 10, 16) ); 
			$queryArray = array('_id' => $myMongoID);
			
			// Create Update Data
			$updateData = array( '$set' => array($dbmapping[$szTableType]['DBMAPPINGS'][MISC_CHECKSUM] => $arrProperitesIn[MISC_CHECKSUM]) ); 
			
			try 
			{
				// Update data in Collection
				$this->_myMongoCollection->update( $queryArray, $updateData );
			}
			catch ( MongoCursorException $e ) 
			{
				// Log error!
				$this->PrintDebugError("SaveMessageChecksum failed with error ' " . $e->getMessage() . " '");
				 
				// Return error code
				return ERROR_DB_QUERYFAILED;
			}

			// Return success
			return SUCCESS; 
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

		// --- Set DB Field names
		$myDBConsFieldName = $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId];
		$myDBGroupByFieldName = $myDBConsFieldName;

		// Set Sorted Field
		if ( $szConsFieldId == $szSortFieldId ) 
			$myDBSortedFieldName = "itemcount"; 
		else
			$myDBSortedFieldName = $szSortFieldId; 
		// --- 

		// --- Set DB Fields Array 
		$myMongoFields = array(); 
		$myMongoFields[ $myDBConsFieldName ] = true; 
		// ---

/*
		// Special handling for date fields
		if ( $nConsFieldType == FILTER_TYPE_DATE )
		{
			// Helper variable for the select statement
			$mySelectFieldName = $myDBGroupByFieldName . "Grouped";
			$myDBQueryFieldName = "DATE( " . $myDBConsFieldName . ") AS " . $myDBGroupByFieldName ;
		}
*/

		// --- Create Query Array!
		if ( ($res = $this->CreateQueryArray(UID_UNKNOWN)) != SUCCESS )
			return $res;

		// Create Options array 
		$myOptions = array( 'condition' => $this->_myMongoQuery ); 

		// Set default for counter field!
		$myMongoInit = array( $myDBSortedFieldName => 0 ); 
		// ---

		// --- Process Data and consolidate!
		// Create reduce function
		$groupReduce = "function (obj, prev) { prev." . $myDBSortedFieldName . "++; }";

		try 
		{
			// Output Debug Informations
			OutputDebugMessage("LogStreamMongoDB|ConsolidateItemListByField: Running MongoDB group query", DEBUG_ULTRADEBUG);

			// mongodb group is simular to groupby from MYSQL
			$myResult = $this->_myMongoCollection->group( array($myDBConsFieldName => 1), $myMongoInit, $groupReduce, $myOptions );
		}
		catch ( MongoCursorException $e ) 
		{
			// Log error!
			$this->PrintDebugError("ConsolidateItemListByField failed with error ' " . $e->getMessage() . " '");
			 
			// Return error code
			return ERROR_DB_QUERYFAILED;
		}

		// Initialize Array variable
		$aResult = array();

		// Loop through results
		if ( isset($myResult['retval']) ) 
		{
			foreach ($myResult['retval'] as $myid => $myRow)
			{
				// Create new row for resultarray
				$aNewRow = array();

				foreach ( $myRow as $myFieldName => $myFieldValue ) 
				{
					if ( !is_array($myFieldValue) && !is_object($myFieldValue) ) // Process normal values
					{
						$myFieldID = $this->GetFieldIDbyDatabaseMapping($szTableType, $myFieldName); 
						$aNewRow[ $myFieldID ] = $myFieldValue;
					}
				}
				// Add new row to result
				$aResult[] = $aNewRow;
			}
		}
		else
		{
			// Return error code
			OutputDebugMessage("LogStreamMongoDB|ConsolidateItemListByField: myResult['retval'] was empty, see myResult: " . var_export($myResult, true) . ")", DEBUG_WARN);
			return ERROR_NOMORERECORDS;
		}

		// return finished array
		if ( count($aResult) > 0 )
		{
			// Use callback function to sort array
			if ( $nSortingOrder == SORTING_ORDER_DESC )
				uasort($aResult, "MultiSortArrayByItemCountDesc");
			else
				uasort($aResult, "MultiSortArrayByItemCountAsc");

			// Check if we have to truncate the array
			if ($nRecordLimit != 0 && count($aResult) > $nRecordLimit)
			{	
				// Create new stripped array
				$aStripResult = array (); 
				for($iCount = 0; $iCount < $nRecordLimit; $iCount++)
					$aStripResult[$iCount] = $aResult[$iCount]; 
				
				// Overwrite stripped results
				$aResult = $aStripResult; 
			}

			OutputDebugMessage("LogStreamMongoDB|ConsolidateItemListByField: Results Array (count " . count($aResult) . ")", DEBUG_ULTRADEBUG);
			// OutputDebugMessage("LogStreamMongoDB|ConsolidateItemListByField: Results Array <pre>" . var_export($aResult, true) . "</pre>", DEBUG_ULTRADEBUG);
			return $aResult;
		}
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

		// --- Set DB Field names
		$myDBConsFieldName = $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId];
		$myDBGroupByFieldName = $myDBConsFieldName;
		// Set Sorted Field
		if ( $szConsFieldId == $szSortFieldId ) 
			$myDBSortedFieldName = "itemcount"; 
		else
			$myDBSortedFieldName = $szSortFieldId; 
		// --- 

		// --- Set DB Fields Array 
		$myMongoFields = array(); 
		$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId] ] = true; 
		$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][$szSortFieldId] ] = true; 

		// Check which fields to include
		if ( $aIncludeCustomFields != null ) 
		{
			foreach ( $aIncludeCustomFields as $myFieldName ) 
			{
				if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName]) ) 
					$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName] ] = true; 
			}
			
			// Append Sortingfield
			if ( !in_array($szConsFieldId, $aIncludeCustomFields) )
				$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId] ] = true; 
		}
		else if ( $bIncludeLogStreamFields ) 
		{
			// var_dump($this->_arrProperties ); 
			foreach ( $this->_arrProperties as $myFieldName ) 
			{
				if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName]) ) 
					$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][$myFieldName] ] = true; 
			}
		}

//$myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][$szConsFieldId] ] = 1; 
//var_dump($myMongoFields); 

		// --- Create Query Array!
		if ( ($res = $this->CreateQueryArray(UID_UNKNOWN)) != SUCCESS )
			return $res;

		// Create Options array 
		$myOptions = array( 'condition' => $this->_myMongoQuery ); 

		// Set default for counter field!
		$myMongoInit = array( $myDBSortedFieldName => 0 ); 

		//TODO, LIMIT not possible currently!
		// $myMongoQuery[ '$limit' ] = 5; 
		//var_dump($myMongoQuery); 
		// ---

		// --- Process Data and consolidate!
		// --- Create reduce function
		$groupReduce = "
		function (obj, prev) 
		{ 
			try {\n
			prev.$myDBSortedFieldName++;\n
			if ( prev.$myDBSortedFieldName == 1 ) 
			{
			"; 
			// Add fields!
			foreach( $myMongoFields as $key => $myfield )
			{
				if ( $key != $myDBConsFieldName ) 
					$groupReduce .= " prev.$key = obj.$key;\n"; 
			}

			$groupReduce .= "
			}
			";

			if ( $bIncludeMinMaxDateFields )
			{
				$groupReduce .= "
				if ( prev.firstoccurrence_date == null || prev.firstoccurrence_date > obj." . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] . " ) {\n
					prev.firstoccurrence_date = obj." . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] . ";\n
				}
				if ( prev.lastoccurrence_date == null || prev.lastoccurrence_date < obj." . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] . " ) {\n
					prev.lastoccurrence_date = obj." . $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE] . ";\n
				}";
			}
		$groupReduce .= "
			}
			catch ( e ){
				// For now ingore error!
				theerror = e.toString();
			}
			// assert( theerror, \"B3\" )
		}
		";
		// ---

		// --- Create Mongo KEY Array
		// Workarround to reduce Datekeys by DAY. Otherwise we will run into 20000 unique Key limit
		if ( $nConsFieldType == FILTER_TYPE_DATE ) // Handle as date!
			$mongoKey = new MongoCode( 
				"function(doc) { 
					return {" . $myDBConsFieldName . " : new Date( doc." . $myDBConsFieldName . " - (doc." . $myDBConsFieldName . " % 86400) )}; 
				}"); 
		else
			$mongoKey = array($myDBConsFieldName => 1); 
/*			$mongoKey = new MongoCode( 
				"function() {
					emit( 
						" . $myDBConsFieldName . ":this." . $myDBConsFieldName . ", 
						{count:1, _id:this._id} 
						);
				}"); 
*/
		// ---

		try 
		{
			// Uncomment for more Debug Informations
			OutputDebugMessage("LogStreamMongoDB|ConsolidateDataByField: Running MongoDB group query with mongoKey (type $nConsFieldType): <pre>" . var_export($mongoKey, true) . "</pre>", DEBUG_ULTRADEBUG);

			// mongodb group is simular to groupby from MYSQL
			$myResult = $this->_myMongoCollection->group( $mongoKey, $myMongoInit, $groupReduce, $myOptions);
		}
		catch ( MongoCursorException $e ) 
		{
			// Log error!
			$this->PrintDebugError("ConsolidateDataByField failed with error ' " . $e->getMessage() . " '");
			 
			// Return error code
			return ERROR_DB_QUERYFAILED;
		}

		// Initialize Array variable
		$aResult = array();
		
		// Loop through results
		if ( isset($myResult['retval']) ) 
		{
			foreach ($myResult['retval'] as $myid => $myRow)
			{

				// Create new row for resultarray
				$aNewRow = array();
				
				// Handly Datefields for min and max!
				if ( $bIncludeMinMaxDateFields )
				{
					if ( isset($myRow['firstoccurrence_date']) && isset($myRow['lastoccurrence_date']) ) 
					{
						$aNewRow['firstoccurrence_date'] = date( "Y-m-d H:i:s ", $myRow['firstoccurrence_date']->sec );
						$aNewRow['lastoccurrence_date'] = date( "Y-m-d H:i:s", $myRow['lastoccurrence_date']->sec );
					}
					else
					{
						// Get default date 
						$myDate = $myRow[$dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_DATE]]; 
						if ( gettype($myDate) == "object" && get_class($myDate) == "MongoDate" ) 
						{
							$aNewRow['firstoccurrence_date'] = date( "Y-m-d H:i:s ", $myDate->sec );
							$aNewRow['lastoccurrence_date'] = date( "Y-m-d H:i:s", $myDate->sec );
						}
					}
	//echo "!". gettype($myDate); 
	//echo "!" . $myDate->sec; 
	//var_dump ( $myRow ); 
	//exit;
				}

				foreach ( $myRow as $myFieldName => $myFieldValue ) 
				{
					if ( !is_array($myFieldValue) && !is_object($myFieldValue) ) // Only Copy NON-Array and NON-Object values!
					{
						$myFieldID = $this->GetFieldIDbyDatabaseMapping($szTableType, $myFieldName); 
						$aNewRow[ $myFieldID ] = $myFieldValue;
					}
				}
				// Add new row to result
				$aResult[] = $aNewRow;
			}
		}
		else
		{
			// Return error code
			OutputDebugMessage("LogStreamMongoDB|ConsolidateDataByField: myResult['retval'] was empty, see myResult: " . var_export($myResult, true) . ")", DEBUG_WARN);
			return ERROR_NOMORERECORDS;
		}

		// return finished array
		if ( count($aResult) > 0 )
		{
			// Use callback function to sort array
			if ( $nSortingOrder == SORTING_ORDER_DESC )
				uasort($aResult, "MultiSortArrayByItemCountDesc");
			else
				uasort($aResult, "MultiSortArrayByItemCountAsc");

			// Check if we have to truncate the array
			if ($nRecordLimit != 0 && count($aResult) > $nRecordLimit)
			{	
				// Create new stripped array
				$aStripResult = array (); 
				for($iCount = 0; $iCount < $nRecordLimit; $iCount++)
					$aStripResult[$iCount] = $aResult[$iCount]; 
				
				// Overwrite stripped results
				$aResult = $aStripResult; 
			}

			OutputDebugMessage("LogStreamMongoDB|ConsolidateDataByField: Results Array (count " . count($aResult) . ")", DEBUG_ULTRADEBUG);
			// OutputDebugMessage("LogStreamMongoDB|ConsolidateDataByField: Results Array <pre>" . var_export($aResult, true) . "</pre>", DEBUG_ULTRADEBUG);
			return $aResult;
		}
		else
			return ERROR_NOMORERECORDS;
		// ---
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
		global $content, $dbmapping, $fields;

		// Copy helper variables, this is just for better readability
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$szFieldId]) )
		{
			// Set DB Field name first!
			$myDBFieldName = $dbmapping[$szTableType]['DBMAPPINGS'][$szFieldId];
			$myDBQueryFieldName = $myDBFieldName;
			$mySelectFieldName = $myDBFieldName;

			// --- Create Query Array!
			if ( ($res = $this->CreateQueryArray(UID_UNKNOWN)) != SUCCESS )
				return $res;

			// Create Options array 
			$myOptions = array( 'condition' => $this->_myMongoQuery ); 

			// Copy array 
			$myMongoQuery = $this->_myMongoQuery; 

			// Set default for counter field!
			$myMongoInit = array( 'TotalCount' => 0 ); 
			// ---

			// --- Process Data and consolidate!
			// Create reduce function
			$groupReduce = "function (obj, prev) { prev.TotalCount++; }";

			// Workarround to reduce Datekeys by DAY. Otherwise we will run into 20000 unique Key limit
			if ( isset($fields[$szFieldId]['FieldType']) && $fields[$szFieldId]['FieldType'] == FILTER_TYPE_DATE ) // Handle as date!
				$mongoKey = new MongoCode( 
					"function(doc) { 
						return {" . $mySelectFieldName . " : new Date( doc." . $mySelectFieldName . " - (doc." . $mySelectFieldName . " % 86400) )}; 
					}"); 
			else
				$mongoKey = array($mySelectFieldName => 1); 

			try 
			{
				// Uncomment for more Debug Informations
				// OutputDebugMessage("LogStreamMongoDB|GetCountSortedByField: Running MongoDB group query with Map Function: <pre>" . $groupReduce . "</pre>", DEBUG_ULTRADEBUG);

				// mongodb group is simular to groupby from MYSQL
				$myResult = $this->_myMongoCollection->group( $mongoKey, $myMongoInit, $groupReduce, $myOptions);
			}
			catch ( MongoCursorException $e ) 
			{
				// Log error!
				$this->PrintDebugError("GetCountSortedByField failed with error ' " . $e->getMessage() . " '");
				 
				// Return error code
				return ERROR_DB_QUERYFAILED;
			}

			// Initialize Array variable
			$aResult = array();

			// Loop through results
			if ( isset($myResult['retval']) ) 
			{
				foreach ($myResult['retval'] as $myid => $myRow)
				{
					if ( !is_array($myRow[$mySelectFieldName]) && !is_object($myRow[$mySelectFieldName]) ) // Process normal values
						$aResult[ $myRow[$mySelectFieldName] ] = $myRow['TotalCount'];
					else
					{
						// Special Handling for datetype!
						if ( gettype($myRow[$mySelectFieldName]) == "object" && get_class($myRow[$mySelectFieldName]) == "MongoDate" ) 
						{
							if ( !isset($aResult[ date("Y-m-d", $myRow[$mySelectFieldName]->sec) ]) ) 
								$aResult[ date("Y-m-d", $myRow[$mySelectFieldName]->sec) ] = $myRow['TotalCount'];
							else
								$aResult[ date("Y-m-d", $myRow[$mySelectFieldName]->sec) ] += $myRow['TotalCount'];
						}
						else
							$aResult[ "Unknown Type" ] = $myRow['TotalCount'];
					}
				}
			}
			else
			{
				// Return error code
				OutputDebugMessage("LogStreamMongoDB|GetCountSortedByField: myResult['retval'] was empty, see myResult: " . var_export($myResult, true) . ")", DEBUG_WARN);
				return ERROR_NOMORERECORDS;
			}

			// return finished array
			if ( count($aResult) > 0 )
			{
				// Sort Array
				arsort($aResult,SORT_NUMERIC);
				// Check if we have to truncate the array
				if ($nRecordLimit != 0 && count($aResult) > $nRecordLimit)
				{	
					// Slice all unecessary entries from array!
					$aStripResult = array_slice($aResult, 0, $nRecordLimit);

					// Overwrite stripped results
					$aResult = $aStripResult; 
				}

				OutputDebugMessage("LogStreamMongoDB|GetCountSortedByField: Results Array (count " . count($aResult) . ")", DEBUG_ULTRADEBUG);
				// OutputDebugMessage("LogStreamMongoDB|ConsolidateItemListByField: Results Array <pre>" . var_export($aResult, true) . "</pre>", DEBUG_ULTRADEBUG);
				
				// return results
				return $aResult;
			}
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
	* Helper function to create the Field Array
	*/
	private function CreateFieldsArray()
	{
		global $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;
		
		// Init Array
		$this->_myMongoFields = array(); 

		// Init Fields Array
		foreach ( $this->_arrProperties as $property ) 
		{
			// Check if mapping exists
			if ( isset($dbmapping[$szTableType]['DBMAPPINGS'][$property]) )
			{
				$this->_myMongoFields[ $dbmapping[$szTableType]['DBMAPPINGS'][$property] ] = true; 
			}
		}
		
		// Success 
		return SUCCESS;
	}

	/*
	* Helper function to create the Query Array
	*/
	private function CreateQueryArray($uID)
	{
		global $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// Init Array
		$this->_myMongoQuery = array(); 

		if ( $this->_filters != null )
		{
			// Loop through all available properties
			foreach( $this->_arrProperties as $propertyname )
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
							$szMongoPropID = $dbmapping[$szTableType]['DBMAPPINGS'][$propertyname]; 

							switch( $myfilter[FILTER_TYPE] )
							{
								case FILTER_TYPE_STRING:
									// --- Either make a LIKE or a equal query!
									if ( $myfilter[FILTER_MODE] & FILTER_MODE_SEARCHFULL )
									{
										// --- Check if user wants to include or exclude!
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE)
										{
											if ( $propertyname == SYSLOG_MESSAGE ) 
												// If we filter for Syslog MSG, we use $ALL to match all values
												$this->_myMongoQuery[ $szMongoPropID ]['$all'][] = $myfilter[FILTER_VALUE]; 
											else
												// We use $in by default to get results for each value
												$this->_myMongoQuery[ $szMongoPropID ]['$in'][] = $myfilter[FILTER_VALUE]; 
										}
										else
											// $ne equals NOT EQUAL 
											$this->_myMongoQuery[ $szMongoPropID ]['$ne'][] = $myfilter[FILTER_VALUE]; 
										// ---
									}
									else if ( $myfilter[FILTER_MODE] & FILTER_MODE_SEARCHREGEX )
									{
										// --- Check if user wants to include or exclude!
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE)
										{
											// Use REGEX to filter for values, NOT TESTED YET!
											$this->_myMongoQuery[ $szMongoPropID ]['$regex'][] = $myfilter[FILTER_VALUE]; 
										}
										else
											// Negate the query using $NOT operator. 
											$this->_myMongoQuery[ $szMongoPropID ]['$not']['$regex'][] = $myfilter[FILTER_VALUE]; 
										// ---
									}
									else
									{
										// This should be a typical LIKE query: Some more checking NEEDED (TODO)!

										// --- Check if user wants to include or exclude!
										if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE)
										{
											if ( $propertyname == SYSLOG_MESSAGE ) 
												// If we filter for Syslog MSG, we use $ALL to match all values
												$this->_myMongoQuery[ $szMongoPropID ]['$regex'][] = $myfilter[FILTER_VALUE]; // Using REGEX for now!
											else
												// We use $in by default to get results for each value
												$this->_myMongoQuery[ $szMongoPropID ]['$regex'][] = $myfilter[FILTER_VALUE]; // Using REGEX for now!
										}
										else
											// $ne equals NOT EQUAL 
											$this->_myMongoQuery[ $szMongoPropID ]['$nin'][] = $myfilter[FILTER_VALUE]; 
										// ---
									}
									// ---
									break;

								case FILTER_TYPE_NUMBER:
									// --- Check if user wants to include or exclude!
									if ( $myfilter[FILTER_MODE] & FILTER_MODE_INCLUDE )
									{
										// We use $in by default to get results for each value
										$this->_myMongoQuery[ $szMongoPropID ]['$in'][] = intval($myfilter[FILTER_VALUE]); 
									}
									else
									{
										// $ne equals NOT EQUAL 
										$this->_myMongoQuery[ $szMongoPropID ]['$nin'][] = intval($myfilter[FILTER_VALUE]); 
									}
									// ---

									break;
								case FILTER_TYPE_DATE:
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

										// Create MongoDate Object from Timestamp
										$myMongoDate = new MongoDate($nNowTimeStamp);

										// add to query array
										$this->_myMongoQuery[ $szMongoPropID ]['$gte'] = $myMongoDate;  
									}
									else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_FROM ) 
									{
										// We use $gt (>) by default to get filter by date 
										$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);

										// Create MongoDate Object from Timestamp
										$myMongoDate = new MongoDate($myeventtime[EVTIME_TIMESTAMP]);
										
										// add to query array
										$this->_myMongoQuery[ $szMongoPropID ]['$gte'] = $myMongoDate; 
									}
									else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_TO ) 
									{
										// Obtain Event struct for the time!
										$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);

										// Create MongoDate Object from Timestamp
										$myMongoDate = new MongoDate($myeventtime[EVTIME_TIMESTAMP]);

										// add to query array
										$this->_myMongoQuery[ $szMongoPropID ]['$lte'] = $myMongoDate;
									}
									else if ( $myfilter[FILTER_DATEMODE] == DATEMODE_RANGE_DATE ) 
									{
										// Obtain Event struct for the time!
										$myeventtime = GetEventTime($myfilter[FILTER_VALUE]);

										// Create MongoDate Object from Timestamp
										$myMongoDateTo = new MongoDate($myeventtime[EVTIME_TIMESTAMP] + 86400);
										$myMongoDateFrom = new MongoDate($myeventtime[EVTIME_TIMESTAMP]);

										// Add to query array
										$this->_myMongoQuery[ $szMongoPropID ]['$lte'] = $myMongoDateTo;
										$this->_myMongoQuery[ $szMongoPropID ]['$gte'] = $myMongoDateFrom;
									}
									
									break;
								default:
									// Nothing to do!
									break;
							}
						}
					}
				}
			}

			//print_r (  array('x' => array( '$gt' => 5, '$lt' => 20 )) ); 
			OutputDebugMessage("CreateQueryArray verbose: " . var_export($this->_myMongoQuery, true), DEBUG_DEBUG);
		}

		if ( $uID != UID_UNKNOWN ) 
		{
			// Add uID Filter as well!
			$myMongoID = new MongoId( $this->convBaseHelper($uID, 10, 16) ); 
			$this->_myMongoQuery[ $dbmapping[$szTableType]['DBMAPPINGS'][SYSLOG_UID] ] = array( '$lte' => $myMongoID ); 
		}

		// Success 
		return SUCCESS;
	}

	/*
	*	This helper function will read the next records into the buffer. 
	*/
	private function ReadNextRecordsFromDB($uID)
	{
		global $querycount, $dbmapping;
		$szTableType = $this->_logStreamConfigObj->DBTableType;

		// return error if there was one!
		if ( ($res = $this->CreateQueryArray($uID)) != SUCCESS )
			return $res;

		try 
		{
			// Debug Informations
			OutputDebugMessage("LogStreamMongoDB|ReadNextRecordsFromDB: Running FIND ", DEBUG_ULTRADEBUG);
			
			// Find Data in MongoCollection 
			$myCursor = $this->_myMongoCollection->find($this->_myMongoQuery)->sort(array("_id" => -1))->limit($this->_logStreamConfigObj->RecordsPerQuery); // , $this->_myMongoFields); 


//		echo "<pre>";
//	var_dump($this->_myMongoQuery);
//	var_dump(iterator_to_array($myCursor));
//		echo "</pre>";

		}
		catch ( MongoCursorException $e ) 
		{
			// Log error!
			$this->PrintDebugError("ReadNextRecordsFromDB failed with error ' " . $e->getMessage() . " '");
			 
			// Return error code
			return ERROR_DB_QUERYFAILED;
		}

		// Uncomment for debug!
		// OutputDebugMessage("LogStreamMongoDB|ReadNextRecordsFromDB: myCursor->info() = <pre>" . var_export($myCursor->info(), true) . "</pre>", DEBUG_ULTRADEBUG);

/* MOVED to find() call aboev
		// Limit records
		$myCursor->limit( $this->_logStreamConfigObj->RecordsPerQuery );
		// OutputDebugMessage("Cursor verbose: " . var_export($myCursor->explain(), true), DEBUG_DEBUG);
		$myCursor = $myCursor->sort(array("_id" => -1));
/**/ 

		try 
		{
			// Copy rows into the buffer!
			$iBegin = $this->_currentRecordNum;
			$mongoidprev = -1;
			foreach ($myCursor as $mongoid => $myRow)
			{
				// Check if result was successfull! Compare the queried uID and the MONGOID to abort processing if the same ID was returned! Otherwise we have dupplicated results at the end
				if ( $myRow === FALSE || !$myRow )
					break;
				
				// Convert MongoID
				$mongoid = $this->convBaseHelper($mongoid, 16, 10);

				// Additional Check to stop processing
				if (	($uID == $mongoid && $myCursor->count() <= 1) ||
						(strpos($mongoidprev,$mongoid) !== FALSE) /* Force STRING Type comparison, otherwise PHP will try to compare as NUMBER (INT Limit)!*/
						// (count($this->bufferedRecords) > $myCursor->count(true))
					)
				{
					// echo count($this->bufferedRecords) . "<br>" . $myCursor->count(true) . "<br>"; //  $mongoidprev<br>$mongoid<br>"; 
					break;
				}

				// Convert ID from HEX back to DEC
				$myRow[ "_id" ] = $mongoid; // base_convert($mongoid, 16, 10); 
				$mongoidprev = $mongoid;	// Helper variable to compare last row

				// Keys will be converted into lowercase!
				$this->bufferedRecords[$iBegin] = array_change_key_case( $myRow, CASE_LOWER);

				$iBegin++;
			}
		}
		catch ( MongoCursorTimeoutException $e ) 
		{
			// Log error!
			$this->PrintDebugError("ReadNextRecordsFromDB Timeout while operation  ' " . $e->getMessage() . " '");
			 
			// Return error code
			return ERROR_DB_TIMEOUTFAILED;
		}

		// Uncomment for debug!
		// OutputDebugMessage("LogStreamMongoDB|ReadNextRecordsFromDB: bufferedRecords =  Array <pre>" . var_export($this->bufferedRecords, true) . "</pre>", DEBUG_ULTRADEBUG);
		OutputDebugMessage("LogStreamMongoDB|ReadNextRecordsFromDB: ibegin = $iBegin, recordnum = " . $this->_currentRecordNum, DEBUG_ULTRADEBUG);
 
		// --- Check if results were found
		if ( $iBegin == $this->_currentRecordNum )
			return ERROR_NOMORERECORDS;
		// --- 

		// Only obtain count if enabled and not done before
		if ( /*$this->_logStreamConfigObj->DBEnableRowCounting &&*/ $this->_totalRecordCount == -1 ) 
		{
			$this->_totalRecordCount = $myCursor->count();

			if ( $this->_totalRecordCount <= 0 )
				return ERROR_NOMORERECORDS;
		}

		// Increment for the Footer Stats 
		$querycount++;
		
		// return success state if reached this point!
		return SUCCESS;
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
		$aMongoIndexes = $this->_myMongoCollection->getIndexInfo(); 
		if (is_array($aMongoIndexes) && count($aMongoIndexes) > 0 ) 
		{
			// LOOP through indexes
			foreach($aMongoIndexes as $myIndex)
			{
				if ( strpos($myIndex['ns'], $this->_logStreamConfigObj->DBCollection) !== FALSE ) 
				{
					// LOOP through keys
					foreach($myIndex['key'] as $myKeyID => $myKey)
					{
						// Add to index keys
						$arrIndexKeys[] = strtolower($myKeyID); 
					}
				}
			}
		}

		//echo "<pre>" . var_export($this->_myMongoCollection->getIndexInfo(), true) . "</pre>"; 
		//echo "<pre>" . var_export($arrIndexKeys, true) . "</pre>"; 
		//exit;

		// Increment for the Footer Stats 
		$querycount++;

		// return Array
		return $arrIndexKeys; 
	}

	/*
	*	Helper function to display SQL Errors for now!
	*/
	private function PrintDebugError($szErrorMsg)
	{
		global $extraErrorDescription; 
		$errormsg="$szErrorMsg <br>";

		// Add to additional error output
		$extraErrorDescription = $errormsg;

		//Output!
		OutputDebugMessage("LogStreamMongoDB|PrintDebugError: $errormsg", DEBUG_ERROR);
	}

	/*
	*	Helper function to workaround larg numbers bug from php base_convert() taken from comments
	*/
	function convBaseHelper($str, $frombase=10, $tobase=36)
	{ 
		$str = trim($str); 
		if (intval($frombase) != 10) { 
			$len = strlen($str); 
			$q = 0; 
			for ($i=0; $i<$len; $i++) { 
				$r = base_convert($str[$i], $frombase, 10); 
				$q = bcadd(bcmul($q, $frombase), $r); 
			} 
		} 
		else 
			$q = $str; 

		if (intval($tobase) != 10) { 
			$s = ''; 
			while (bccomp($q, '0', 0) > 0) { 
				$r = intval(bcmod($q, $tobase)); 
				$s = base_convert($r, 10, $tobase) . $s; 
				$q = bcdiv($q, $tobase, 0); 
			} 
		} 
		else 
			$s = $q; 

		return $s; 
	}


/* OLD CODE
	private function convBaseHelper($numberInput, $fromBaseInput, $toBaseInput)
	{
		if ($fromBaseInput==$toBaseInput) 
			return $numberInput;

		$fromBase = str_split($fromBaseInput,1);
		$toBase = str_split($toBaseInput,1);
		$number = str_split($numberInput,1);
		$fromLen=strlen($fromBaseInput);
		$toLen=strlen($toBaseInput);
		$numberLen=strlen($numberInput);
		$retval='';

		if ($toBaseInput == '0123456789')
		{
			$retval=0;
			for ($i = 1;$i <= $numberLen; $i++)
				$retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase),bcpow($fromLen,$numberLen-$i)));
			return $retval;
		}
		if ($fromBaseInput != '0123456789')
			$base10=$this->convBaseHelper($numberInput, $fromBaseInput, '0123456789');
		else
			$base10 = $numberInput;
		if ($base10<strlen($toBaseInput))
			return $toBase[$base10];
		while($base10 != '0')
		{
			$retval = $toBase[bcmod($base10,$toLen)].$retval;
			$base10 = bcdiv($base10,$toLen,0);
		}
		return $retval;
	}
*/

// --- End of Class!
}

?>