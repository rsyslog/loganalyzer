<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* DB Function Helper File											*
	*																	*
	* -> Needed to establish and maintain the DB connetion				*
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


$userdbconn  = 0;
$errdesc = "";
$errno = 0;

// --- Current Database Version, this is important for automated database Updates!
$content['database_internalversion'] = "1";	// Whenever incremented, a database upgrade is needed
$content['database_installedversion'] = "0";	// 0 is default which means Prior Versioning Database
// --- 

function DB_Connect() 
{
	global $userdbconn, $CFG;

	//TODO: Check variables first
	$userdbconn = mysql_connect($CFG['UserDBServer'],$CFG['UserDBUser'],$CFG['UserDBPass']);
	if (!$userdbconn) 
		DB_PrintError("Link-ID == false, connect to ".$CFG['UserDBServer']." failed", true);
	
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
		DieWithFriendlyErrorMsg( "You are running an MySQL 3.x Database Server Version. Unfortunately MySQL 3.x is NOT supported by PhpLogCon due the limited SQL Statement support. If this is a commercial webspace, contact your webhoster in order to upgrade to a higher MySQL Database Version. If this is your own rootserver, consider updating your MySQL Server.");
	}
	// ---

	$db_selected = mysql_select_db($CFG['UserDBName'], $userdbconn);
	if(!$db_selected) 
		DB_PrintError("Cannot use database '" . $CFG['UserDBName'] . "'", true);
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
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
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
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
		return;
	// ---

	if ($query_id != false && $query_id != 1 )
		mysql_free_result($query_id);
}

function DB_GetRow($query_id) 
{
	// --- Abort in this case!
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
		return;
	// ---

	$tmp = mysql_fetch_row($query_id);
	$results[] = $tmp;
	return $results[0];
}

function DB_GetSingleRow($query_id, $bClose) 
{
	// --- Abort in this case!
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
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
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
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
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
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
	global $n,$HTTP_COOKIE_VARS, $errdesc, $errno, $linesep, $CFG;

	$errdesc = mysql_error();
	$errno = mysql_errno();

	$errormsg="Database error: $MyErrorMsg $linesep";
	$errormsg.="mysql error: $errdesc $linesep";
	$errormsg.="mysql error number: $errno $linesep";
	$errormsg.="Date: ".date("d.m.Y @ H:i").$linesep;
	$errormsg.="Script: ".getenv("REQUEST_URI").$linesep;
	$errormsg.="Referer: ".getenv("HTTP_REFERER").$linesep;

	if ($DieOrNot == true)
		DieWithErrorMsg( "$linesep" . $errormsg );
	else
		echo $errormsg;
}

function DB_RemoveParserSpecialBadChars($myString)
{
// DO NOT REPLACD!	$returnstr = str_replace("\\","\\\\",$myString);
	$returnstr = str_replace("'","\\'",$myString);
	return $returnstr;
}

function DB_RemoveBadChars($myString)
{
	$returnstr = str_replace("\\","\\\\",$myString);
	$returnstr = str_replace("'","\\'",$returnstr);
	return $returnstr;
}

function DB_GetRowCount($query)
{
	// --- Abort in this case!
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
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
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
		return;
	// ---

	if ($myresult) 
		return mysql_num_rows($myresult);
}

function DB_Exec($query)
{
	// --- Abort in this case!
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
		return;
	// ---

	if(mysql_query($query)) 
		return true;
	else 
		return false; 
} 

function WriteConfigValue($szValue, $is_global = true)
{
	// --- Abort in this case!
	global $CFG, $content;
	if ( $CFG['UserDBEnabled'] == false ) 
		return;
	// ---

	$result = DB_Query("SELECT name FROM " . STATS_CONFIG . " WHERE name = '" . $szValue . "' AND is_global = " . $is_global);
	$rows = DB_GetAllRows($result, true);
	if ( !isset($rows) )
	{
		// New Entry
		$result = DB_Query("INSERT INTO  " . STATS_CONFIG . " (name, value, is_global) VALUES ( '" . $szValue . "', '" . $CFG[$szValue] . "', " . $is_global . ")");
		DB_FreeQuery($result);
	}
	else
	{
		// Update Entry
		$result = DB_Query("UPDATE " . STATS_CONFIG . " SET value = '" . $CFG[$szValue] . "' WHERE name = '" . $szValue . "' AND is_global = " . $is_global);
		DB_FreeQuery($result);
	}
} 

function GetSingleDBEntryOnly( $myqry )
{
	// --- Abort in this case!
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
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
	global $CFG;
	if ( $CFG['UserDBEnabled'] == false ) 
		return;
	// ---

	return mysql_affected_rows();
}



?>