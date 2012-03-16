<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* UserDB needed functions											*
	*																	*
	* -> 		*
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

// --- Basic Includes
//include($gl_root_path . 'include/constants_general.php');
///include($gl_root_path . 'include/constants_logstream.php');
// --- 

// --- Define User System initialized!
define('IS_USERSYSTEMENABLED', true);
$content['IS_USERSYSTEMENABLED'] = true;
// --- 

// --- BEGIN Usermanagement Function --- 
function InitUserSession()
{
	global $USERCFG, $content; 

	// --- Hide donate Button if not on Admin Page
	if ( !defined('IS_ADMINPAGE') )
		$content['SHOW_DONATEBUTTON'] = false;
	// --- 

	if ( isset($_SESSION['SESSION_LOGGEDIN']) )
	{
		if (	!$_SESSION['SESSION_LOGGEDIN'] || 
				!isset($_SESSION['SESSION_USERID']) /* Check if UserID is set! */
			) 
		{
			$content['SESSION_LOGGEDIN'] = false;
			
			// Not logged in
			return false;
		}
		else
		{
			// Copy variables from session!
			$content['SESSION_LOGGEDIN'] = true;
			$content['SESSION_USERNAME'] = $_SESSION['SESSION_USERNAME'];
			$content['SESSION_USERID'] = $_SESSION['SESSION_USERID'];
			$content['SESSION_ISADMIN'] = $_SESSION['SESSION_ISADMIN'];
			$content['SESSION_ISREADONLY'] = $_SESSION['SESSION_ISREADONLY'];
			if ( isset($_SESSION['SESSION_GROUPIDS']) )
				$content['SESSION_GROUPIDS'] = $_SESSION['SESSION_GROUPIDS'];

			// --- Now we obtain user specific general settings from the DB for the user!
			$result = DB_Query("SELECT * FROM " . DB_CONFIG . " WHERE userid = " . $content['SESSION_USERID']);
			if ( $result )
			{
				$rows = DB_GetAllRows($result, true);
				// Read results from DB and overwrite in $CFG Array!
				if ( isset($rows ) )
				{
					for($i = 0; $i < count($rows); $i++)
					{
						// Store and overwrite settings from the user here!
						$USERCFG[ $rows[$i]['propname'] ] = $rows[$i]['propvalue'];
//						$content[ $rows[$i]['propname'] ] = $rows[$i]['propvalue'];
					}
				}
			}
			else // Critical ERROR HERE!
				DieWithFriendlyErrorMsg( "Critical Error occured while trying to access the database in table '" . DB_CONFIG . "'" );
			// --- 

			if ( isset($_SESSION['UPDATEAVAILABLE']) && $_SESSION['UPDATEAVAILABLE'] ) 
			{
				// Check Version numbers again to avoid update notification if update was done during meantime!
				if ( CompareVersionNumbers($content['BUILDNUMBER'], $_SESSION['UPDATEVERSION']) )
				{
					$content['UPDATEVERSION'] = $_SESSION['UPDATEVERSION'];
					$content['isupdateavailable'] = true;
					$content['isupdateavailable_updatelink'] = $_SESSION['UPDATELINK'];
					$content['UPDATE_AVAILABLETEXT'] = GetAndReplaceLangStr($content['LN_UPDATE_AVAILABLETEXT'], $content['BUILDNUMBER'], $_SESSION['UPDATEVERSION']);
				}
			}

			// --- Extracheck for available database updates!
			if ( isset($content['database_forcedatabaseupdate']) && $content['database_forcedatabaseupdate'] == "yes" && !defined('IS_UPRGADEPAGE') )
				RedirectToDatabaseUpgrade();
			// ---
			
			// Successfully logged in
			return true;
		}
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
	global $content;

	// Check if LDAP Auth has to be used!
	if ( GetConfigSetting("UserDBAuthMode", USERDB_AUTH_INTERNAL) == USERDB_AUTH_LDAP)
	{
		// perform user auth using LDAP, will add user record to loganalyzer DB if necessary
		$myrow = CheckLDAPUserLogin( $username, $password );
	}
	else // Normal MYSQL Login!
	{
		// TODO: SessionTime and AccessLevel check
		$md5pass = md5($password);
		$sqlquery = "SELECT * FROM " . DB_USERS . " WHERE username = '" . $username . "' and password = '" . $md5pass . "'";
		$result = DB_Query($sqlquery);
		$myrow = DB_GetSingleRow($result, true);
	}

	// The admin field must be set!
	if ( isset($myrow['is_admin']) )
	{
		$_SESSION['SESSION_LOGGEDIN'] = true;
		$_SESSION['SESSION_USERNAME'] = $username;
		$_SESSION['SESSION_USERID'] = $myrow['ID'];
		$_SESSION['SESSION_ISADMIN'] = $myrow['is_admin'];
		// Check Readonly setting
		if ( $content['database_installedversion'] > 8 )
			$_SESSION['SESSION_ISREADONLY'] = $myrow['is_readonly'];
		else
			$_SESSION['SESSION_ISREADONLY'] = false; 

		$content['SESSION_LOGGEDIN'] = $_SESSION['SESSION_LOGGEDIN'];
		$content['SESSION_USERNAME'] = $_SESSION['SESSION_USERNAME'];
		$content['SESSION_USERID'] = $_SESSION['SESSION_USERID'];
		$content['SESSION_ISADMIN'] = $_SESSION['SESSION_ISADMIN'];
		$content['SESSION_ISREADONLY'] = $_SESSION['SESSION_ISREADONLY'];

		// --- Read Groupmember ship for the user!
		$sqlquery = "SELECT " . 
					DB_GROUPMEMBERS . ".groupid, " . 
					DB_GROUPMEMBERS . ".is_member " . 
					"FROM " . DB_GROUPMEMBERS . " WHERE userid = " . $content['SESSION_USERID'] . " AND " . DB_GROUPMEMBERS . ".is_member = 1";
		$result = DB_Query($sqlquery);
		$myrows = DB_GetAllRows($result, true);
		if ( isset($myrows ) && count($myrows) > 0 )
		{
			for($i = 0; $i < count($myrows); $i++)
			{
				if ( isset($content['SESSION_GROUPIDS']) ) 
					$content['SESSION_GROUPIDS'] .= ", " . $myrows[$i]['groupid'];
				else
					$content['SESSION_GROUPIDS'] = $myrows[$i]['groupid'];
			}
		}

		// Copy into session as well
		$_SESSION['SESSION_GROUPIDS'] = $content['SESSION_GROUPIDS'];
		// ---

		// ---Set LASTLOGIN Time!
		$result = DB_Query("UPDATE " . DB_USERS . " SET last_login = " . time() . " WHERE ID = " . $content['SESSION_USERID']);
		DB_FreeQuery($result);
		// ---

		// --- Extracheck for available database updates!
		if ( isset($content['database_forcedatabaseupdate']) && $content['database_forcedatabaseupdate'] == "yes" && !defined('IS_UPRGADEPAGE') )
			RedirectToDatabaseUpgrade();
		// ---

		// --- Now we check for an PhpLogCon Update
		$iProxyLen = strlen(GetConfigSetting("UseProxyServerForRemoteQueries", "")); 
		if ( $iProxyLen > 0 )
		{
			// Proxy Server configured, create a context with proxy option!
			$opts = array('http' => array('proxy' => 'tcp://' . GetConfigSetting("UseProxyServerForRemoteQueries", ""), 'request_fulluri' => true));
			$context = stream_context_create($opts);
			
			// Create handle with my context!
			$myHandle = @fopen($content['UPDATEURL'], "r", false, $context);
		}
		else
			$myHandle = @fopen($content['UPDATEURL'], "r");

		if( $myHandle ) 
		{
			$myBuffer = "";
			while (!feof ($myHandle))
				$myBuffer .= fgets($myHandle, 4096);
			fclose($myHandle);

			$myLines = explode("\n", $myBuffer);

			// Compare Version numbers!
			if ( CompareVersionNumbers($content['BUILDNUMBER'], $myLines[0]) )
			{	
				// True means new version available!
				$_SESSION['UPDATEAVAILABLE'] = true;
				$_SESSION['UPDATEVERSION'] = $myLines[0];
				if ( isset($myLines[1]) ) 
					$_SESSION['UPDATELINK'] = $myLines[1];
				else
					$_SESSION['UPDATELINK'] = "http://www.phplogcon.org";
			}
		}
		// --- 

		// Success !
		return true;
	}
	else
	{
		/*
		if (isset($myrow) && is_numeric($myrow) ) 
		{
			//return error code!
			return $myrow;
		}
		*/
		if ( GetConfigSetting("DebugUserLogin", 0) == 1 )
			DieWithFriendlyErrorMsg( "Debug Error: Could not login user '" . $username . "' <br><br><B>Sessionarray</B> <pre>" . var_export($_SESSION, true) . "</pre><br><B>SQL Statement</B>: " . $sqlselect );
			
		// Default return false
		return false;
	}
}

function DoLDAPConnect()
{
	global $content;

	// Open LDAP connection
	if (!($ldapConn=@ldap_connect($content['LDAPServer'],$content['LDAPPort'])))
		return false;

	ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
	 
	// reached this point means success!
	return $ldapConn; 
}

function DoLDAPBind($ldapConn)
{
	global $content;

	// Bind as the privilegied user
	return ldap_bind($ldapConn, $content['LDAPBindDN'], $content['LDAPBindPassword']); 
}

function CheckLDAPUserLogin( $username, $password )
{
	global $content;

	// Create LDAP Searchfilter
	$ldap_filter='(&'.$content['LDAPSearchFilter'].'('.$content['LDAPUidAttribute'].'='.$username.'))';

	// Get LDAP Connection 
	$ldapConn = DoLDAPConnect(); 
	if ( $ldapConn ) 
	{
		if ( !DoLDAPBind($ldapConn) ) 
		{
			if ( GetConfigSetting("DebugUserLogin", 0) == 1 )
			{
				// Die with error
				DebugLDAPErrorAndDie( GetAndReplaceLangStr($content['LN_LOGIN_LDAP_USERBINDFAILED'], $content['LDAPBindDN'], ldap_err2str(ldap_errno($ldapConn))), $ldap_filter ); 
			}

			return false; 
		}
	}
	else
	{
		if ( GetConfigSetting("DebugUserLogin", 0) == 1 )
		{
			// Die with error
			DebugLDAPErrorAndDie( GetAndReplaceLangStr($content['LN_LOGIN_LDAP_SERVERFAILED'], $content['LDAPServer'] . ":" . $content['LDAPPort'], ldap_err2str(ldap_errno($ldapConn))), $ldap_filter ); 
		}
		
		// return false in this case
		return false;
	}

	// Search for the user
	if (!($r=@ldap_search( $ldapConn, $content['LDAPBaseDN'], $ldap_filter, array("uid","cn","localentryid","userpassword") )))
	{
		if ( GetConfigSetting("DebugUserLogin", 0) == 1 )
		{
			// Die with error
			DebugLDAPErrorAndDie( GetAndReplaceLangStr($content['LN_LOGIN_LDAP_USERCOULDNOTLOGIN'], $username, ldap_err2str(ldap_errno($ldapConn))), $ldap_filter ); 
		}

		// return false in this case
		return false;
	}

	$info = ldap_get_entries($ldapConn, $r);
	if (!$info || $info["count"] != 1)
	{
		if ( GetConfigSetting("DebugUserLogin", 0) == 1 )
		{
			// Die with error
			DebugLDAPErrorAndDie( GetAndReplaceLangStr( $content['LN_LOGIN_LDAP_USERNOTFOUND'], $username ), $ldap_filter ); 
		}

		// return false in this case
		return false;
	}
	 
	// now we have the user data. Do a bind to check for his password
	if (!($r=@ldap_bind( $ldapConn, $info[0]['dn'],$password)))
	{
		if ( GetConfigSetting("DebugUserLogin", 0) == 1 )
		{
			// Die with error
			DebugLDAPErrorAndDie( GetAndReplaceLangStr( $content['LN_LOGIN_LDAP_PASSWORDFAIL'], $username ), $ldap_filter ); 
		}

		// return false in this case
		return false;
	}
	 
	// for the moment when a user logs in from LDAP, create it in the DB.
	// then the prefs and group management is done in the DB and we don't rewrite the whole Loganalyzer code…
	 
	// check if the user already exist
	$sqlquery = "SELECT * FROM " . DB_USERS . " WHERE username = '" . $username . "'";
	$result = DB_Query($sqlquery);
	$myrow = DB_GetSingleRow($result, true);
	if (!isset($myrow['is_admin']) )
	{
		// Create User | use password to create MD5 Hash, so technically the user could login without LDAP as well
		$sqlcmd = "INSERT INTO " . DB_USERS . " (username, password, is_admin, is_readonly) VALUES ('" . $username . "', '" . md5($password) . "', 0, 1)"; 

		$result = DB_Query($sqlcmd);
		DB_FreeQuery($result);
		$myrow['is_admin'] = 0;
		$myrow['last_login'] = 0;
		$myrow['is_readonly'] = 1;
	}
	
	// Construct Row and return
	$myrowfinal['username'] = $username;
	$myrowfinal['password'] = md5($password);
	$myrowfinal['dn'] = $info[0]['dn'];
	if ( isset($myrow['ID']) ) 
		$myrowfinal['ID'] = $myrow['ID'];				// Get from SELECT
	else
		$myrowfinal['ID'] = DB_ReturnLastInsertID();	// Get from last insert!
	$myrowfinal['is_admin'] = $myrow['is_admin'];
	$myrowfinal['is_readonly'] = $myrow['is_readonly'];
	$myrowfinal['last_login'] = $myrow['last_login'];
	return $myrowfinal;

}

/*
*	LDAP Debug Helpre function
*/
function DebugLDAPErrorAndDie($szErrorMsg, $szLdapFilter)
{
	global $content;

	// Add extra debug if wanted!
	if ( GetConfigSetting("MiscShowDebugMsg", 0, CFGLEVEL_USER) == 1 )
	{
		$szErrorMsg .=	
					"</br></br>LDAPBind DN: " . $content['LDAPBindDN'] . 
					"</br>Search Filter: " . $szLdapFilter . 
					"</br><pre>Session Array: </br>" . var_export($_SESSION, true) . "</pre>"; 
	}

	// USER NOT FOUND
	DieWithFriendlyErrorMsg( $szErrorMsg ); 
}


function DoLogOff()
{
	global $content;

	unset( $_SESSION['SESSION_LOGGEDIN'] );
	unset( $_SESSION['SESSION_USERNAME'] );
	unset( $_SESSION['SESSION_USERID'] );
	unset( $_SESSION['SESSION_ACCESSLEVEL'] );

	// Redir to Index Page
	RedirectPage( "index.php");
}

function RedirectToUserLogin()
{
	global $content;

	// build referer
	$referer = $_SERVER['PHP_SELF'];
	if ( isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0 )
		$referer .= "?" . $_SERVER['QUERY_STRING'];

	header("Location: " . $content['BASEPATH'] . "login.php?referer=" . urlencode($referer) );
	exit;
}

function RedirectToDatabaseUpgrade()
{
	global $content;

	// build referer
	$referer = $_SERVER['PHP_SELF'];
	if ( isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0 )
		$referer .= "?" . $_SERVER['QUERY_STRING'];

	header("Location: " . $content['BASEPATH'] . "admin/upgrade.php?referer=" . urlencode($referer) );
	exit;
}
// --- END Usermanagement Function --- 


/*
* Helper function to obtain a list of groups for display 
*/
function GetGroupsForSelectfield()
{
	global $content;

	$sqlquery = "SELECT " . 
				DB_GROUPS . ".ID as mygroupid, " . 
				DB_GROUPS . ".groupname " . 
				"FROM " . DB_GROUPS . 
				" ORDER BY " . DB_GROUPS . ".groupname";
	$result = DB_Query($sqlquery);
	$mygroups = DB_GetAllRows($result, true);
	if ( isset($mygroups) && count($mygroups) > 0 )
	{
		// Process All Groups
		for($i = 0; $i < count($mygroups); $i++)
			$mygroups[$i]['group_selected'] = "";

		// Enable Group Selection
		array_unshift( $mygroups, array ("mygroupid" => -1, "groupname" => $content['LN_SEARCH_SELGROUPENABLE'], "group_selected" => "") );
		
		// return result
		return $mygroups;
	}
	else
		return false;
	// ---
}

// Helper function to compare versions
function CompareVersionNumbers( $oldVer, $newVer )
{
	// Split version numbers
	$currentVersion = explode(".", trim($oldVer) );
	$newVersion = explode(".", trim($newVer) );

	// Check if the format is correct!
	if ( count($newVersion) != 3 )
		return false;

	// check for update
	if		( isset($newVersion[0]) && $newVersion[0] > $currentVersion[0] )
		return true;
	else if	( isset($newVersion[1]) && $newVersion[0] == $currentVersion[0] && $newVersion[1] > $currentVersion[1] )
		return true;
	else if ( isset($newVersion[2]) && $newVersion[0] == $currentVersion[0] && $newVersion[1] == $currentVersion[1] && $newVersion[2] > $currentVersion[2] )
		return true;
	else
		return false;
}



?>