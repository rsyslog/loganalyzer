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
function CheckForUserLogin( $isloginpage, $isUpgradePage = false )
{
	global $content; 

	if ( isset($_SESSION['SESSION_LOGGEDIN']) )
	{
		if ( !$_SESSION['SESSION_LOGGEDIN'] ) 
			RedirectToUserLogin();
		else
		{
			$content['SESSION_LOGGEDIN'] = "true";
			$content['SESSION_USERNAME'] = $_SESSION['SESSION_USERNAME'];
		}

		// New, Check for database Version and may redirect to updatepage!
		if (	isset($content['database_forcedatabaseupdate']) && 
				$content['database_forcedatabaseupdate'] == "yes" && 
				$isUpgradePage == false 
			)
				RedirectToDatabaseUpgrade();
	}
	else
	{
		if ( $isloginpage == false )
			RedirectToUserLogin();
	}

}

function CreateUserName( $username, $password, $access_level )
{
	$md5pass = md5($password);
	$result = DB_Query("SELECT username FROM " . STATS_USERS . " WHERE username = '" . $username . "'");
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
		$result = DB_Query("INSERT INTO " . STATS_USERS . " (username, password, access_level) VALUES ('$username', '$md5pass', $access_level)");
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
	$sqlselect = "SELECT access_level FROM " . STATS_USERS . " WHERE username = '" . $username . "' and password = '" . $md5pass . "'";
	$result = DB_Query($sqlselect);
	$rows = DB_GetAllRows($result, true);
	if ( isset($rows) )
	{
		$_SESSION['SESSION_LOGGEDIN'] = true;
		$_SESSION['SESSION_USERNAME'] = $username;
		$_SESSION['SESSION_ACCESSLEVEL'] = $rows[0]['access_level'];
		
		$content['SESSION_LOGGEDIN'] = "true";
		$content['SESSION_USERNAME'] = $username;

		// Success !
		return true;
	}
	else
	{
		if ( $CFG['MiscShowDebugMsg'] == 1 )
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
	// TODO Referer
	header("Location: login.php?referer=" . $_SERVER['PHP_SELF']);
	exit;
}

function RedirectToDatabaseUpgrade()
{
	// TODO Referer
	header("Location: upgrade.php"); // ?referer=" . $_SERVER['PHP_SELF']);
	exit;
}
// --- END Usermanagement Function --- 


?>