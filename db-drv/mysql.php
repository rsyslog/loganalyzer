<?php

/*#### #### #### #### #### #### #### #### #### ####
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2004-2005  Adiscon GmbH



This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; 
if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, 
MA  02111-1307, USA.

If you have questions about phpLogCon in general, please email info@adiscon.com. 
To learn more about phpLogCon, please visit http://www.phplogcon.com.

This Project was intiated and is maintened by Rainer Gerhards <rgerhards@hq.adiscon.com>. 
See AUTHORS to learn who helped make it become a reality.

*/#### #### #### #### #### #### #### #### #### #### 



	/* 
	 * This file contains the function for database connection
	 * via mysql
	 */
	 
	/*! \addtogroup DB Converter  
	 *
	 * All converter functions using to prepare things to work with database queries 
	 * use the prefix "dbc_" 
	 * @{
	 */
	 
	/*
	 * Generate the tags for a time argument using in a sql statment
	 * \param timestamp which need tags
	 * \return timestamp_with_tags returns the timestamp with tags in the nesessary format
	 */
	function dbc_sql_timeformat($times)
	{
		if (defined('_UTCtime') && _UTCtime)
		{
			$times = GetUTCtime($times);
		}
		return "'".date("Y-m-d H:i:s", $times)."'";
	}

	/*! @} */

	/*
	 * Database driver
	 */
	 
	/* 
	 * Attempts to establish a connection to the database. 
	 * /return the connection handle if the connection was successful, NULL if the connection was unsuccessful.
	 */
	function db_connection()
	{
		if (!isset($_SESSION['database']))
		{
			$_SESSION['database'] = _DBNAME;
		}
		$db = mysql_connect(_DBSERVER, _DBUSERID, _DBPWD) or db_die_with_error(_MSGNoDBCon);
		mysql_select_db($_SESSION['database']) or db_die_with_error(_MSGChDB);
		return $db;
	}

	function db_own_connection($host, $port, $user, $pass, $dbname)
	{
		if($port != 0)
			$db = mysql_connect($host . ":" . $port, $user, $pass) or db_die_with_error(_MSGNoDBCon);
		else
			$db = mysql_connect($host, $user, $pass) or db_die_with_error(_MSGNoDBCon);
		mysql_select_db($dbname) or db_die_with_error(_MSGChDB);
		return $db;
	}

	/*
	 * Executes the SQL.
	 * /param db Database connection handle
	 * /param cmdSQL SQL statement
	 *
	 * /return Resource handle
	 */
	function db_exec($db, $cmdSQL)
	{
		//echo "<br><br><b>" . $cmdSQL . "</b><br><br>";
		$result = mysql_query($cmdSQL, $db) or db_die_with_error(_MSGInvQur);
		return $result;
	}

	/*
	 * Executes the SQL.
	 * /param res Rescource hanndle
	 *
	 * /return The number of rows in the result set. 
	 */
	function db_num_rows($res)
	{
		$result = mysql_num_rows($res);
		return $result;
	}

	/*
	 * Fetch a result row as an associative array, a numeric array, or both. 
	 * /param res Rescource hanndle
	 *
	 * /return Returns an array that corresponds to the fetched row, or FALSE if there are no more rows.
	 */
	function db_fetch_array($res)
	{
		$result = mysql_fetch_array($res);
		return $result;
	}

	/*
	 * db_fetch_singleresult is need in ODBC mode, so db_fetch_singleresult and db_fetch_array
	 * are the same in MySQL
	*/
	function db_fetch_singleresult($result)
	{
		$result = mysql_fetch_array($result);
		return $result;
	}

	/*
	 * Get the result data. 
	 * /param res Rescource hanndle
	 * /param res_name either be an integer containing the column number of the field you want; 
		or it can be a string containing the         name of the field. For example: 
	 *
	 * /return the contents of one cell from the result set.
	 */
	function db_result($res, $res_name)
	{
		$result = mysql_result($res, 1, $res_name) or db_die_with_error(_MSGNoRes);
		return $result;
	} 

	function db_close($db)
	{
		mysql_close($db) or db_die_with_error(_MSGNoDBHan);
	}

	function db_num_count($cmdSQLwhere_part)
	{
		return 'SELECT COUNT(*) AS num FROM ' . _DBTABLENAME . $cmdSQLwhere_part;
	}

	/*
	 * fetch a result row as an a numeric array
	 * the array points to the first data record you want to display
	 * at current page. You need it for paging.
	 */
	function db_exec_limit($db, $cmdSQLfirst_part, $cmdSQLmain_part, $cmdSQLwhere_part, $limitlower, $perpage, $order)
	{
		$cmdSQL = $cmdSQLfirst_part . $cmdSQLmain_part . $cmdSQLwhere_part . " limit ".($limitlower-1)."," . $perpage;
	    return db_exec($db, $cmdSQL);
	}

	function db_free_result($result)
	{
		return mysql_free_result($result);
	}

	function db_get_tables($dbCon, $dbName)
	{
		$query = "SHOW TABLES FROM " . $dbName;
		return mysql_query($query);
	}

	function db_errno()
	{
		return mysql_errno();
	}

	function db_error()
	{
		return mysql_error();
	}

	function db_die_with_error($MyErrorMsg)
	{
		$errdesc = mysql_error();
		$errno = mysql_errno();

		$errormsg="<br>Database error: $MyErrorMsg <br>";
		$errormsg.="mysql error: $errdesc <br>";
		$errormsg.="mysql error number: $errno<br>";
		$errormsg.="Date: ".date("d.m.Y @ H:i")."<br>";
		$errormsg.="Script: ".getenv("REQUEST_URI")."<br>";
		$errormsg.="Referer: ".getenv("HTTP_REFERER")."<br><br>";

		echo $errormsg;
		exit;
	}
  
  /*
  * Returns what wildcut the database use (e.g. %, *, ...)
  */
  function db_get_wildcut()
  {
    return '%';
  }

?>