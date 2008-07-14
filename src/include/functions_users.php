<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* UserDB needed functions											*
	*																	*
	* -> 		*
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

// --- Basic Includes
//include($gl_root_path . 'include/constants_general.php');
///include($gl_root_path . 'include/constants_logstream.php');
// --- 

// --- BEGIN Usermanagement Function --- 
function InitUserSession()
{
	global $content; 

	if ( isset($_SESSION['SESSION_LOGGEDIN']) )
	{
		if ( !$_SESSION['SESSION_LOGGEDIN'] ) 
		{
			$content['SESSION_LOGGEDIN'] = false;
			
			// Not logged in
			return false;
		}
		else
		{
			$content['SESSION_LOGGEDIN'] = true;
			$content['SESSION_USERNAME'] = $_SESSION['SESSION_USERNAME'];
			$content['SESSION_ISADMIN'] = $_SESSION['SESSION_ISADMIN'];
			
			// Successfully logged in
			return true;
		}
/*
		// New, Check for database Version and may redirect to updatepage!
		if (	isset($content['database_forcedatabaseupdate']) && 
				$content['database_forcedatabaseupdate'] == "yes" && 
				$isUpgradePage == false 
			)
				RedirectToDatabaseUpgrade();
*/
	}
	else
	{
		$content['SESSION_LOGGEDIN'] = false;

		// Not logged in ^^
		return false;
	}
}

function CreateUserName( $username, $password, $is_admin )
{
	$md5pass = md5($password);
	$result = DB_Query("SELECT username FROM " . DB_USERS . " WHERE username = '" . $username . "'");
	$rows = DB_GetAllRows($result, true);

	if ( isset($rows) )
	{
		DieWithFriendlyErrorMsg( "User $username already exists!" );

		// User not created!
		return false;
	}
	else
	{
		// Create User
		$result = DB_Query("INSERT INTO " . DB_USERS . " (username, password, is_admin) VALUES ('$username', '$md5pass', $is_admin)");
		DB_FreeQuery($result);

		// Success
		return true;
	}
}

function CheckUserLogin( $username, $password )
{
	global $content, $CFG;

	// TODO: SessionTime and AccessLevel check

	$md5pass = md5($password);
	$sqlselect = "SELECT * FROM " . DB_USERS . " WHERE username = '" . $username . "' and password = '" . $md5pass . "'";
	$result = DB_Query($sqlselect);
	$myrow = DB_GetSingleRow($result, true);


	if ( isset($myrow['is_admin']) )
	{
		$_SESSION['SESSION_LOGGEDIN'] = true;
		$_SESSION['SESSION_USERNAME'] = $username;
		$_SESSION['SESSION_ISADMIN'] = $myrow['is_admin'];

		$content['SESSION_LOGGEDIN'] = $_SESSION['SESSION_LOGGEDIN'];
		$content['SESSION_USERNAME'] = $_SESSION['SESSION_USERNAME'];
		$content['SESSION_ISADMIN'] = $_SESSION['SESSION_ISADMIN'];

		// TODO SET LAST LOGIN TIME!

		// Success !
		return true;
	}
	else
	{
		if ( $CFG['DebugUserLogin'] == 1 )
			DieWithFriendlyErrorMsg( "Debug Error: Could not login user '" . $username . "' <br><br><B>Sessionarray</B> <pre>" . var_export($_SESSION, true) . "</pre><br><B>SQL Statement</B>: " . $sqlselect );
		
		// Default return false
		return false;
	}
}

function DoLogOff()
{
	global $content;

	unset( $_SESSION['SESSION_LOGGEDIN'] );
	unset( $_SESSION['SESSION_USERNAME'] );
	unset( $_SESSION['SESSION_ACCESSLEVEL'] );

	// Redir to Index Page
	RedirectPage( "index.php");
}

function RedirectToUserLogin()
{
	// build referer
	$referer = $_SERVER['PHP_SELF'];
	if ( isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0 )
		$referer .= "?" . $_SERVER['QUERY_STRING'];

	header("Location: login.php?referer=" . urlencode($referer) );
	exit;
}

function RedirectToDatabaseUpgrade()
{
	// build referer
	$referer = $_SERVER['PHP_SELF'];
	if ( isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0 )
		$referer .= "?" . $_SERVER['QUERY_STRING'];

	header("Location: upgrade.php?referer=" . urlencode($referer) );
	exit;
}
// --- END Usermanagement Function --- 


?>