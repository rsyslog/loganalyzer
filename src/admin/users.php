<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* User Admin File											
	*																	
	* -> Helps administrating users
	*																	
	* All directives are explained within this file
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
	* distribution				
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './../';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Set PAGE to be ADMINPAGE!
define('IS_ADMINPAGE', true);
$content['IS_ADMINPAGE'] = true;
InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!

// Init admin langauge file now!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/admin.php' );
// --- 

// --- Deny if User is READONLY!
if ( !isset($_SESSION['SESSION_ISREADONLY']) || $_SESSION['SESSION_ISREADONLY'] == 1 )
{
	if (	isset($_POST['op']) ||
			(
				(	isset($_GET['op']) && 
					(
						$_GET['op'] == "add" || 
						$_GET['op'] == "delete" 
					)
				)	
				||
				(	isset($_GET['miniop']) && 
					(
						$_GET['miniop'] == "setisadmin" ||
						$_GET['miniop'] == "setisreadonly"
					)
				)
			)	
		)
		DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_READONLY'] );
}
// --- 

// --- BEGIN Custom Code
// Only if the user is an admin!
if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
	DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_NOTALLOWED'] );

if ( isset($_GET['miniop']) ) 
{
	if ( isset($_GET['id']) && isset($_GET['newval']) )
	{
		if ( $_GET['miniop'] == "setisadmin" ) 
		{
			//PreInit these values 
			$content['USERID'] = intval(DB_RemoveBadChars($_GET['id']));
			$iNewVal = intval(DB_RemoveBadChars($_GET['newval']));

			// --- handle special case
			if ( $content['USERID'] == $content['SESSION_USERID'] && (!isset($_GET['verify']) || $_GET['verify'] != "yes") && $iNewVal == 0)
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( $content['LN_USER_WARNREMOVEADMIN'], $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---
			
			// Perform SQL Query!
			$sqlquery = "SELECT * " . 
						" FROM " . DB_USERS . 
						" WHERE ID = " . $content['USERID'];
			$result = DB_Query($sqlquery);
			$myuser = DB_GetSingleRow($result, true);
			if ( isset($myuser['username']) )
			{
				// Update is_admin setting!
				$result = DB_Query("UPDATE " . DB_USERS . " SET 
					is_admin = $iNewVal 
					WHERE ID = " . $content['USERID']);
				DB_FreeQuery($result);
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_USER_ERROR_IDNOTFOUND'], $content['USERID'] );
			}
		}
		else if ( $_GET['miniop'] == "setisreadonly" ) 
		{
			//PreInit these values 
			$content['USERID'] = intval(DB_RemoveBadChars($_GET['id']));
			$iNewVal = intval(DB_RemoveBadChars($_GET['newval']));

			// --- handle special case
			if ( $content['USERID'] == $content['SESSION_USERID'] && (!isset($_GET['verify']) || $_GET['verify'] != "yes") && $iNewVal == 1)
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( $content['LN_USER_WARNRADYONLYADMIN'], $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---
			
			// Perform SQL Query!
			$sqlquery = "SELECT * " . 
						" FROM " . DB_USERS . 
						" WHERE ID = " . $content['USERID'];
			$result = DB_Query($sqlquery);
			$myuser = DB_GetSingleRow($result, true);
			if ( isset($myuser['username']) )
			{
				// Update is_admin setting!
				$result = DB_Query("UPDATE " . DB_USERS . " SET 
					is_readonly = $iNewVal 
					WHERE ID = " . $content['USERID']);
				DB_FreeQuery($result);
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_USER_ERROR_IDNOTFOUND'], $content['USERID'] );
			}
		}
	}
	else
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_USER_ERROR_SETTINGFLAG'];
	}
}


if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "add") 
	{
		// Set Mode to add
		$content['ISEDITORNEWUSER'] = "true";
		$content['USER_FORMACTION'] = "addnewuser";
		$content['USER_SENDBUTTON'] = $content['LN_USER_ADD'];

		//PreInit these values 
		$content['USERNAME'] = "";
		$content['PASSWORD1'] = "";
		$content['PASSWORD2'] = "";
	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWUSER'] = "true";
		$content['USER_FORMACTION'] = "edituser";
		$content['USER_SENDBUTTON'] = $content['LN_USER_EDIT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['USERID'] = DB_RemoveBadChars($_GET['id']);

			$sqlquery = "SELECT * " . 
						" FROM " . DB_USERS . 
						" WHERE ID = " . $content['USERID'];

			$result = DB_Query($sqlquery);
			$myuser = DB_GetSingleRow($result, true);
			if ( isset($myuser['username']) )
			{
				$content['USERID'] = $myuser['ID'];
				$content['USERNAME'] = $myuser['username'];
				
				// Set is_admin flag
				if ( $myuser['is_admin'] == 1 ) 
					$content['CHECKED_ISADMIN'] = "checked";
				else
					$content['CHECKED_ISADMIN'] = "";

				// Set is_readonly flag
				if ( $myuser['is_readonly'] == 1 ) 
					$content['CHECKED_ISREADONLY'] = "checked";
				else
					$content['CHECKED_ISREADONLY'] = "";
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_USER_ERROR_IDNOTFOUND'], $content['USERID'] );
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] =  $content['LN_USER_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "delete") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['USERID'] = DB_RemoveBadChars($_GET['id']);

			if ( !isset($_SESSION['SESSION_USERNAME']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = $content['LN_USER_ERROR_INVALIDSESSIONS'];
			}
			else
			{
				// Get UserInfo
				$result = DB_Query("SELECT username FROM " . DB_USERS . " WHERE ID = " . $content['USERID'] ); 
				$myrow = DB_GetSingleRow($result, true);
				if ( !isset($myrow['username']) )
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_USER_ERROR_IDNOTFOUND'], $content['USERID'] ); 
				}

				if ( $_SESSION['SESSION_USERNAME'] == $myrow['username'] ) 
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_USER_ERROR_DONOTDELURSLF'], $content['USERID'] ); 
				}
				else
				{
					// --- Ask for deletion first!
					if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
					{
						// This will print an additional secure check which the user needs to confirm and exit the script execution.
						PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_USER_WARNDELETEUSER'], $myrow['username'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
					}
					// ---
					
					// Delete User objects!
					PerformSQLDelete( "DELETE FROM " . DB_SOURCES . " WHERE userid = " . $content['USERID'], 'LN_SOURCES_ERROR_DELSOURCE', $content['USERID'] );  
					PerformSQLDelete( "DELETE FROM " . DB_VIEWS . " WHERE userid = " . $content['USERID'], 'LN_VIEWS_ERROR_DELSEARCH', $content['USERID'] );  
					PerformSQLDelete( "DELETE FROM " . DB_SEARCHES . " WHERE userid = " . $content['USERID'], 'LN_SEARCH_ERROR_DELSEARCH', $content['USERID'] );  
					PerformSQLDelete( "DELETE FROM " . DB_CHARTS . " WHERE userid = " . $content['USERID'], 'LN_CHARTS_ERROR_DELCHART', $content['USERID'] );  

					// Finally delete the Userobject!
					PerformSQLDelete( "DELETE FROM " . DB_USERS . " WHERE ID = " . $content['USERID'], 'LN_USER_ERROR_DELUSER', $content['USERID'] );  

					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_USER_ERROR_HASBEENDEL'], $myrow['username'] ) , "users.php" );
				}
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_USER_ERROR_INVALIDID'];
		}
	}
}

if ( isset($_POST['op']) )
{
	if ( isset ($_POST['id']) ) { $content['USERID'] = DB_RemoveBadChars($_POST['id']); } else {$content['USERID'] = ""; }
	if ( isset ($_POST['username']) ) { $content['USERNAME'] = DB_RemoveBadChars($_POST['username']); } else {$content['USERNAME'] = ""; }
	if ( isset ($_POST['password1']) ) { $content['PASSWORD1'] = DB_RemoveBadChars($_POST['password1']); } else {$content['PASSWORD1'] = ""; }
	if ( isset ($_POST['password2']) ) { $content['PASSWORD2'] = DB_RemoveBadChars($_POST['password2']); } else {$content['PASSWORD2'] = ""; }
	if ( isset ($_POST['isadmin']) ) { $content['ISADMIN'] = 1; } else {$content['ISADMIN'] = 0; }
	if ( isset ($_POST['isreadonly']) ) { $content['ISREADONLY'] = 1; } else {$content['ISREADONLY'] = 0; }

	// Check mandotary values
	if ( $content['USERNAME'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_USER_ERROR_USEREMPTY'];
	}

	if ( !isset($content['ISERROR']) ) 
	{	
		// Everything was alright, so we go to the next step!
		if ( $_POST['op'] == "addnewuser" )
		{
			$result = DB_Query("SELECT username FROM " . DB_USERS . " WHERE username = '" . $content['USERNAME'] . "'"); 
			$myrow = DB_GetSingleRow($result, true);
			if ( isset($myrow['username']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = $content['LN_USER_ERROR_USERNAMETAKEN'];
			}
			else
			{
				// Check if Password is set!
				if (	strlen($content['PASSWORD1']) <= 0 ||
						$content['PASSWORD1'] != $content['PASSWORD2'] )
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = $content['LN_USER_ERROR_PASSSHORT'];
				}

				if ( !isset($content['ISERROR']) ) 
				{	
					// Create passwordhash now :)!
					$content['PASSWORDHASH'] = md5( $content['PASSWORD1'] );

					// Add new User now!
					$result = DB_Query("INSERT INTO " . DB_USERS . " (username, password, is_admin, is_readonly) 
					VALUES ('" . $content['USERNAME'] . "', 
							'" . $content['PASSWORDHASH'] . "',
							" . $content['ISADMIN'] . ", 
							" . $content['ISREADONLY'] . ")");
					DB_FreeQuery($result);
					
					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_USER_ERROR_HASBEENADDED'], DB_StripSlahes($content['USERNAME']) ) , "users.php" );
				}
			}
		}
		else if ( $_POST['op'] == "edituser" )
		{
			$result = DB_Query("SELECT ID FROM " . DB_USERS . " WHERE ID = " . $content['USERID']);
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['ID']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_USER_ERROR_IDNOTFOUND'], $content['USERID'] ); 
			}
			else
			{

				// Check if Password is enabled
				if ( isset($content['PASSWORD1']) && strlen($content['PASSWORD1']) > 0 )
				{
					if ( $content['PASSWORD1'] != $content['PASSWORD2'] )
					{
						$content['ISERROR'] = true;
						$content['ERROR_MSG'] = $content['LN_USER_ERROR_PASSSHORT'];
					}

					if ( !isset($content['ISERROR']) ) 
					{
						// Create passwordhash now :)!
						$content['PASSWORDHASH'] = md5( $content['PASSWORD1'] );

						// Edit the User now!
						$result = DB_Query("UPDATE " . DB_USERS . " SET 
							username = '" . $content['USERNAME'] . "', 
							password = '" . $content['PASSWORDHASH'] . "', 
							is_admin = " . $content['ISADMIN'] . ", 
							is_readonly = " . $content['ISREADONLY'] . "
							WHERE ID = " . $content['USERID']);
						DB_FreeQuery($result);
					}
				}
				else
				{
					// Edit the User now!
					$result = DB_Query("UPDATE " . DB_USERS . " SET 
						username = '" . $content['USERNAME'] . "', 
						is_admin = " . $content['ISADMIN'] . ", 
						is_readonly = " . $content['ISREADONLY'] . "
						WHERE ID = " . $content['USERID']);
					DB_FreeQuery($result);
				}

				// Done redirect!
				RedirectResult( GetAndReplaceLangStr( $content['LN_USER_ERROR_HASBEENEDIT'], DB_StripSlahes($content['USERNAME']) ) , "users.php" );
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Users
	$content['LISTUSERS'] = "true";
	
	// Set AddUsers TAB!
	if ( $content['UserDBAuthMode']  == USERDB_AUTH_LDAP )
		$content["ALLOWADDUSERS"] = "false"; 
	else
		$content["ALLOWADDUSERS"] = "true"; 

	// Read all Serverentries
	$sqlquery = "SELECT ID, " . 
				" username, " . 
				" is_admin, " . 
				" is_readonly " . 
				" FROM " . DB_USERS . 
				" ORDER BY ID ";
	$result = DB_Query($sqlquery);
	$content['USERS'] = DB_GetAllRows($result, true);

	// --- Process Users
	for($i = 0; $i < count($content['USERS']); $i++)
	{
		// --- Set Image for IsAdmin
		if ( $content['USERS'][$i]['is_admin'] == 1 ) 
		{
			$content['USERS'][$i]['is_isadmin_string'] = $content['MENU_SELECTION_ENABLED'];
			$content['USERS'][$i]['set_isadmin'] = 0;
		}
		else
		{
			$content['USERS'][$i]['is_isadmin_string'] = $content['MENU_SELECTION_DISABLED'];
			$content['USERS'][$i]['set_isadmin'] = 1;
		}
		// ---

		// --- Set Image for IsReadonly
		if ( $content['USERS'][$i]['is_readonly'] == 1 ) 
		{
			$content['USERS'][$i]['is_readonly_string'] = $content['MENU_SELECTION_ENABLED'];
			$content['USERS'][$i]['set_isreadonly'] = 0;
		}
		else
		{
			$content['USERS'][$i]['is_readonly_string'] = $content['MENU_SELECTION_DISABLED'];
			$content['USERS'][$i]['set_isreadonly'] = 1;
		}
		// ---

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$content['USERS'][$i]['cssclass'] = "line1";
		else
			$content['USERS'][$i]['cssclass'] = "line2";
		// --- 
	}
	// --- 
}

// Helper function to delete SQL Data
function PerformSQLDelete( $szDeleteStm, $szErrMsg, $szUserID)
{
	global $content; 
	$result = DB_Query( $szDeleteStm );
	if ($result == FALSE)
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content[$szErrMsg], $szUserID ); 
		return false; 
	}
	else
		DB_FreeQuery($result);
	// Success
	return true; 
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_USEROPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_users.html");
$page -> output(); 
// --- 

?>