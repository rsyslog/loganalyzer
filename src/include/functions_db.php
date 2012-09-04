<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* DB Function Helper File											*
	*																	*
	* -> Needed to establish and maintain the DB connetion				*
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


$userdbconn  = 0;
$errdesc = "";
$errno = 0;

// --- Current Database Version, this is important for automated database Updates!
$content['database_internalversion'] = "10";	// Whenever incremented, a database upgrade is needed
$content['database_installedversion'] = "0";	// 0 is default which means Prior Versioning Database
// --- 

function DB_Connect() 
{
	global $userdbconn;

	// Avoid if already OPEN
	if ($userdbconn) 
		return;

	$userdbconn = @mysql_connect( GetConfigSetting("UserDBServer") . ":" . GetConfigSetting("UserDBPort"), GetConfigSetting("UserDBUser"), GetConfigSetting("UserDBPass"));
	if (!$userdbconn) 
	{
		// Create Error Msg
		$szErrorMsg = "Failed to establish a connection to the configured MYSQL Server. <br>LogAnalyzer is not able to initialize the user system.";
		if ( isset($php_errormsg) ) 
			$szErrorMsg .= "<br><br><b>Extra Error Details</b>:<br>" . $php_errormsg;

		DieWithErrorMsg( $szErrorMsg  );
	}

	//TODO: Check variables first
	
	// --- Now, check Mysql DB Version!
	$strmysqlver = mysql_get_server_info();
	if ( strpos($strmysqlver, "-") !== false )
	{
		$sttmp = explode("-", $strmysqlver );
		$szVerInfo = $sttmp[0];
	}
	else
		$szVerInfo = $strmysqlver;

	$szVerSplit = explode(".", $szVerInfo );

	//Now check for Major Version
	if ( $szVerSplit[0] <= 3 ) 
	{
		//Unfortunatelly MYSQL 3.x is NOT Supported dude!
		DieWithFriendlyErrorMsg( "You are running an MySQL 3.x Database Server Version. Unfortunately MySQL 3.x is NOT supported by LogAnalyzer due the limited SQL Statement support. If this is a commercial webspace, contact your webhoster in order to upgrade to a higher MySQL Database Version. If this is your own rootserver, consider updating your MySQL Server.");
	}
	// ---

	$db_selected = mysql_select_db( GetConfigSetting("UserDBName"), $userdbconn );
	if(!$db_selected) 
		DB_PrintError("Cannot use database '" .GetConfigSetting("UserDBName") . "'", true);
	// :D Success connecting to db

	// TODO Do some more validating on the database
}

function DB_Disconnect()
{
	global $userdbconn;
	mysql_close($userdbconn);
}

function DB_Query($query_string, $bProcessError = true, $bCritical = false)
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	global $userdbconn, $querycount;
	$query_id = mysql_query($query_string,$userdbconn);
	if (!$query_id && $bProcessError) 
		DB_PrintError("Invalid SQL: ".$query_string, $bCritical);

	// For the Stats ;)
	$querycount++;
	
	return $query_id;
}

function DB_FreeQuery($query_id)
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	if ($query_id != false && $query_id != 1 )
		mysql_free_result($query_id);
}

function DB_GetRow($query_id) 
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	$tmp = mysql_fetch_row($query_id);
	$results[] = $tmp;
	return $results[0];
}

function DB_GetSingleRow($query_id, $bClose) 
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	if ($query_id != false && $query_id != 1 )
	{
		$row = mysql_fetch_array($query_id,  MYSQL_ASSOC);

		if ( $bClose )
			DB_FreeQuery ($query_id); 

		if ( isset($row) ) // Return array
			return $row;
		else
			return;
	}
}

function DB_GetAllRows($query_id, $bClose)
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	if ($query_id != false && $query_id != 1 )
	{
		while ($row  =  mysql_fetch_array($query_id,  MYSQL_ASSOC))
			$var[]  =  $row;
		
		if ( $bClose )
			DB_FreeQuery ($query_id); 

		if ( isset($var) )
		{
			// Return array
			return $var;
		}
		else
			return;
	}
}

function DB_GetMysqlStats()
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	global $userdbconn;
	$status = explode('  ', mysql_stat($userdbconn));
	return $status;
}

function DB_ReturnSimpleErrorMsg()
{
	// Return Mysql Error
	return "Mysql Error " . mysql_errno() . " - Description: " . mysql_error();
}

function DB_PrintError($MyErrorMsg, $DieOrNot)
{
	global $content, $n,$HTTP_COOKIE_VARS, $errdesc, $errno, $linesep;

	$errdesc = mysql_error();
	$errno = mysql_errno();

	// Define global variable so we know an error has occured!
	if ( !defined('PHPLOGCON_INERROR') )
		define('PHPLOGCON_INERROR', true);

	$errormsg="Database error: $MyErrorMsg $linesep";
	$errormsg.="mysql error: $errdesc $linesep";
	$errormsg.="mysql error number: $errno $linesep";
	$errormsg.="Date: ".date("d.m.Y @ H:i").$linesep;
	$errormsg.="Script: ".getenv("REQUEST_URI").$linesep;
	$errormsg.="Referer: ".getenv("HTTP_REFERER").$linesep;

	if ($DieOrNot == true)
		DieWithErrorMsg( "$linesep" . $errormsg );
	else
	{
		OutputDebugMessage("DB_PrintError: $errormsg", DEBUG_ERROR);

		if ( !isset($content['detailederror']) )
		{
			$content['detailederror_code'] = ERROR_DB_QUERYFAILED;
			$content['detailederror'] = GetErrorMessage(ERROR_DB_QUERYFAILED);
		}
		else
			$content['detailederror'] .= "<br><br>" . GetErrorMessage(ERROR_DB_QUERYFAILED);
		
		// Append SQL Detail Error
		$content['detailederror'] .= "<br><br>" . $errormsg;
	}
}

function DB_RemoveParserSpecialBadChars($myString)
{
// DO NOT REPLACE!	$returnstr = str_replace("\\","\\\\",$myString);
	$returnstr = str_replace("'","\\'",$myString);
	return $returnstr;
}

function DB_RemoveBadChars($myValue, $dbEngine = DB_MYSQL, $bForceStripSlahes = false)
{
	// Check if Array
	if ( is_array($myValue) )
	{	// Array value
		$retArray = array(); 
		foreach( $myValue as $mykey => $myString )
		{
			if ( $dbEngine == DB_MSSQL ) 
			{
				// MSSQL needs special treatment -.-
				$retArray[$mykey] = str_replace("'","''",$myString);
			}
			else
			{
				// Replace with internal PHP Functions!
				$retArray[$mykey] = addslashes($myString);
			}
		}

		// Return fixed array!
		return $retArray; 
	}
	else
	{	// Single value
		if ( $dbEngine == DB_MSSQL ) 
		{
			// MSSQL needs special treatment -.-
			return str_replace("'","''",$myValue);
		}
		else
		{
			// Replace with internal PHP Functions!
			return addslashes($myValue);
		}
	}
}

function DB_StripSlahes($myString)
{
	// Replace with internal PHP Functions!
//	if ( get_magic_quotes_gpc() )
		return stripslashes($myString);
//	else
//		return $myString;
}

function DB_ReturnLastInsertID($myResult = false)
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	global $userdbconn;
	return mysql_insert_id($userdbconn);
}

function DB_GetRowCount($query)
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	if ($result = mysql_query($query)) 
	{   
		$num_rows = mysql_num_rows($result);
		mysql_free_result ($result); 
	}
	return $num_rows;
}

function DB_GetRowCountByResult($myresult)
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	if ($myresult) 
		return mysql_num_rows($myresult);
}

function DB_Exec($query)
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	if(mysql_query($query)) 
		return true;
	else 
		return false; 
} 

function PrepareValueForDB($szValue, $bForceStripSlahes = false)
{
	// Wrapper for this function
	return DB_RemoveBadChars($szValue, null, $bForceStripSlahes);
}

function WriteConfigValue($szPropName, $is_global = true, $userid = false, $groupid = false, $bForceStripSlahes = false)
{
	global $content;

	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	if ( $is_global ) 
	{
		if ( isset($content[$szPropName]) )
		{
			// Copy value for DB and check for BadDB Chars!
			$szDbValue = PrepareValueForDB( $content[$szPropName], $bForceStripSlahes );
		}
		else
		{
			// Set empty in this case
			$szDbValue = "";
			$content[$szPropName] = "";
		}

		// Copy to $CFG array as well
		$CFG[$szPropName] = $content[$szPropName];
		
		// Check if we need to INSERT or UPDATE
		$result = DB_Query("SELECT propname FROM " . DB_CONFIG . " WHERE propname = '" . $szPropName . "' AND is_global = " . $is_global);
		$rows = DB_GetAllRows($result, true);
		if ( !isset($rows) )
		{
			// New Entry
			if ( strlen($szDbValue) < 255 ) 
				$result = DB_Query("INSERT INTO  " . DB_CONFIG . " (propname, propvalue, is_global) VALUES ( '" . $szPropName . "', '" . $szDbValue . "', " . $is_global . ")");
			else
				$result = DB_Query("INSERT INTO  " . DB_CONFIG . " (propname, propvalue_text, is_global) VALUES ( '" . $szPropName . "', '" . $szDbValue . "', " . $is_global . ")");
			DB_FreeQuery($result);
		}
		else
		{
			// Update Entry
			if ( strlen($szDbValue) < 255 ) 
				$result = DB_Query("UPDATE " . DB_CONFIG . " SET propvalue = '" . $szDbValue . "', propvalue_text = '' WHERE propname = '" . $szPropName . "' AND is_global = " . $is_global);
			else
				$result = DB_Query("UPDATE " . DB_CONFIG . " SET propvalue_text = '" . $szDbValue . "', propvalue = '' WHERE propname = '" . $szPropName . "' AND is_global = " . $is_global);
			DB_FreeQuery($result);
		}
	}
	else if ( $userid != false ) 
	{
		global $USERCFG;

		if ( isset($USERCFG[$szPropName]) )
		{
			// Copy value for DB and check for BadDB Chars!
			$szDbValue = PrepareValueForDB( $USERCFG[$szPropName], $bForceStripSlahes );
		}
		else
		{
			// Set empty in this case
			$szDbValue = "";
			$USERCFG[$szPropName] = "";
		}

		// Check if we need to INSERT or UPDATE
		$result = DB_Query("SELECT propname FROM " . DB_CONFIG . " WHERE propname = '" . $szPropName . "' AND userid = " . $userid);
		$rows = DB_GetAllRows($result, true);
		if ( !isset($rows) )
		{
			// New Entry
			$result = DB_Query("INSERT INTO  " . DB_CONFIG . " (propname, propvalue, userid) VALUES ( '" . $szPropName . "', '" . $szDbValue . "', " . $userid . ")");
			DB_FreeQuery($result);
		}
		else
		{
			// Update Entry
			$result = DB_Query("UPDATE " . DB_CONFIG . " SET propvalue = '" . $szDbValue . "' WHERE propname = '" . $szPropName . "' AND userid = " . $userid);
			DB_FreeQuery($result);
		}

	}
	else if ( $groupid != false ) 
		DieWithFriendlyErrorMsg( "Critical Error occured in WriteConfigValue, writing GROUP specific properties is not supported yet!" );

		

} 

function GetSingleDBEntryOnly( $myqry )
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	$result = DB_Query( $myqry );
	$row = DB_GetRow($result);
	DB_FreeQuery ($query_id); 

	if ( isset($row) )
		return $row[0];
	else
		return -1;
}

function GetRowsAffected()
{
	// --- Abort in this case!
	if ( GetConfigSetting("UserDBEnabled", false) == false ) 
		return;
	// ---

	return mysql_affected_rows();
}

?>