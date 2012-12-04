<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Group Admin File											
	*																	
	* -> Helps administrating groups
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
				isset($_GET['op']) && 
				(
					$_GET['op'] == "add" || 
					$_GET['op'] == "delete" || 
					$_GET['op'] == "adduser" ||
					$_GET['op'] == "removeuser"
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

if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "add") 
	{
		// Set Mode to add
		$content['ISEDITORNEWGROUP'] = "true";
		$content['GROUP_FORMACTION'] = "addnewgroup";
		$content['GROUP_SENDBUTTON'] = $content['LN_GROUP_ADD'];

		//PreInit these values 
		$content['groupname'] = "";
		$content['groupdescription'] = "";
	}
	else if ($_GET['op'] == "adduser" && isset($_GET['id']) ) 
	{
		//PreInit these values 
		$content['GROUPID'] = intval( DB_RemoveBadChars($_GET['id']) );

		// Set Mode to add
		$content['ISADDUSER'] = "true";
		$content['GROUP_FORMACTION'] = "adduser";
		$content['GROUP_SENDBUTTON'] = $content['LN_GROUP_ADDUSER'];
		
		// --- Get Groupname
		$sqlquery = "SELECT " . 
					DB_GROUPS . ".groupname " . 
					" FROM " . DB_GROUPS .
					" WHERE " . DB_GROUPS . ".id = " . $content['GROUPID'];
		$result = DB_Query($sqlquery);
		$tmparray = DB_GetSingleRow($result, true);
		
		if ( isset($tmparray) )
		{
			// Copy Groupname
			$content['GROUPNAME'] = $tmparray['groupname'];

			// --- Get Group Members
			$sqlquery = "SELECT " . 
						DB_GROUPMEMBERS. ".userid " . 
						" FROM " . DB_GROUPMEMBERS .
						" WHERE " . DB_GROUPMEMBERS . ".groupid = " . $content['GROUPID'];
			$result = DB_Query($sqlquery);
			$tmparray = DB_GetAllRows($result, true);
			if ( count($tmparray) > 0 )
			{
				// Add UserID's to where clause!
				foreach ($tmparray as $datarow)
				{
					if ( isset($whereclause) )
						$whereclause .= ", " . $datarow['userid'];
					else
						$whereclause = " WHERE " . DB_USERS . ".id NOT IN (" . $datarow['userid'];
				}
				// Finish whereclause
				$whereclause .= ") ";
			}
			else
				$whereclause = "";
			// --- 

			// --- Create LIST of Users which are available for selection
			$sqlquery = "SELECT " . 
						DB_USERS. ".ID as userid, " . 
						DB_USERS. ".username " . 
						" FROM " . DB_USERS . 
						" LEFT OUTER JOIN (" . DB_GROUPMEMBERS . 
						") ON (" . 
						DB_GROUPMEMBERS . ".userid=" . DB_USERS . ".ID) " . 
						$whereclause . 
						" ORDER BY " . DB_USERS . ".username";
			$result = DB_Query($sqlquery);
			$content['SUBUSERS'] = DB_GetAllRows($result, true);

			if ( count($content['SUBUSERS']) <= 0 )
			{
				// Disable FORM: 
				$content['ISADDUSER'] = false;

				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERRORNOMOREUSERS'], $content['GROUPNAME'] );
			}
		}
		else
		{
			// Disable FORM: 
			$content['ISADDUSER'] = false;

			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERROR_IDNOTFOUND'], $content['GROUPID'] );
		}
		// ---
	}
	else if ($_GET['op'] == "removeuser" && isset($_GET['id']) ) 
	{
		//PreInit these values 
		$content['GROUPID'] = intval( DB_RemoveBadChars($_GET['id']) );

		// Set Mode to add
		$content['ISREMOVEUSER'] = "true";
		$content['GROUP_FORMACTION'] = "removeuser";
		$content['GROUP_SENDBUTTON'] = $content['LN_GROUP_USERDELETE'];

		// --- Get Groupname
		$sqlquery = "SELECT " . 
					DB_GROUPS . ".groupname " . 
					" FROM " . DB_GROUPS .
					" WHERE " . DB_GROUPS . ".id = " . $content['GROUPID'];
		$result = DB_Query($sqlquery);
		$tmparray = DB_GetSingleRow($result, true);
		
		if ( isset($tmparray) )
		{
			// Copy Groupname
			$content['GROUPNAME'] = $tmparray['groupname'];

			// --- Get Group Members
			$sqlquery = "SELECT " . 
						DB_GROUPMEMBERS. ".userid, " . 
						DB_USERS. ".username " . 
						" FROM " . DB_GROUPMEMBERS .
						" INNER JOIN (" . DB_USERS . 
						") ON (" . 
						DB_GROUPMEMBERS . ".userid=" . DB_USERS . ".ID) " . 
						" WHERE " . DB_GROUPMEMBERS . ".groupid = " . $content['GROUPID'];
			$result = DB_Query($sqlquery);
			$content['SUBRMUSERS'] = DB_GetAllRows($result, true);
			if ( count($content['SUBRMUSERS']) <= 0 )
			{
				// Disable FORM: 
				$content['ISREMOVEUSER'] = false;

				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERRORNOUSERSINGROUP'], $content['GROUPNAME'] );
			}
		}
		else
		{
			// Disable FORM: 
			$content['ISREMOVEUSER'] = false;

			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERROR_IDNOTFOUND'], $content['GROUPID'] );
		}

	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWGROUP'] = "true";
		$content['GROUP_FORMACTION'] = "editgroup";
		$content['GROUP_SENDBUTTON'] = $content['LN_GROUP_EDIT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['GROUPID'] = DB_RemoveBadChars($_GET['id']);

			$sqlquery = "SELECT * " . 
						" FROM " . DB_GROUPS . 
						" WHERE ID = " . $content['GROUPID'];

			$result = DB_Query($sqlquery);
			$myuser = DB_GetSingleRow($result, true);
			if ( isset($myuser['groupname']) )
			{
				$content['GROUPID'] = $myuser['ID'];
				$content['groupname'] = $myuser['groupname'];
				$content['groupdescription'] = $myuser['groupdescription'];
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERROR_IDNOTFOUND'], $content['GROUPID'] );
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_GROUP_ERROR_INVALIDGROUP'];
		}
	}
	else if ($_GET['op'] == "delete") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['GROUPID'] = DB_RemoveBadChars($_GET['id']);

			// Get GroupInfo
			$result = DB_Query("SELECT groupname FROM " . DB_GROUPS . " WHERE ID = " . $content['GROUPID'] ); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['groupname']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERROR_IDNOTFOUND'], $content['USERID'] ); 
			}
			else
			{
				// --- Ask for deletion first!
				if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
				{
					// This will print an additional secure check which the user needs to confirm and exit the script execution.
					PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_GROUP_WARNDELETEGROUP'], $myrow['groupname'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
				}
				// ---

				// Delete User objects!
				PerformSQLDelete( "DELETE FROM " . DB_SOURCES . " WHERE groupid = " . $content['GROUPID'], 'LN_SOURCES_ERROR_DELSOURCE', $content['GROUPID'] );  
				PerformSQLDelete( "DELETE FROM " . DB_VIEWS . " WHERE groupid = " . $content['GROUPID'], 'LN_VIEWS_ERROR_DELSEARCH', $content['GROUPID'] );  
				PerformSQLDelete( "DELETE FROM " . DB_SEARCHES . " WHERE groupid = " . $content['GROUPID'], 'LN_SEARCH_ERROR_DELSEARCH', $content['GROUPID'] );  
				PerformSQLDelete( "DELETE FROM " . DB_CHARTS . " WHERE groupid = " . $content['GROUPID'], 'LN_CHARTS_ERROR_DELCHART', $content['GROUPID'] );  
				PerformSQLDelete( "DELETE FROM " . DB_GROUPMEMBERS . " WHERE groupid = " . $content['GROUPID'], 'LN_GROUP_ERROR_REMUSERFROMGROUP', $content['GROUPID'] );  
													 
				// Finally delete the Groupobject!
				PerformSQLDelete( "DELETE FROM " . DB_GROUPS . " WHERE ID = " . $content['GROUPID'], 'LN_GROUP_ERROR_DELGROUP', $content['GROUPID'] );  

				// Do the final redirect
				RedirectResult( GetAndReplaceLangStr( $content['LN_GROUP_ERROR_HASBEENDEL'], $myrow['groupname'] ) , "groups.php" );
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_GROUP_ERROR_INVALIDGROUP'];
		}
	}
}

if ( isset($_POST['op']) )
{
	if ( isset ($_POST['id']) ) { $content['GROUPID'] = intval( DB_RemoveBadChars($_POST['id']) ); } else {$content['GROUPID'] = ""; }
	if ( isset ($_POST['groupname']) ) { $content['groupname'] = DB_RemoveBadChars($_POST['groupname']); } else {$content['groupname'] = ""; }
	if ( isset ($_POST['groupdescription']) ) { $content['groupdescription'] = DB_RemoveBadChars($_POST['groupdescription']); } else {$content['groupdescription'] = ""; }

	// Check mandotary values
	if ( $content['groupname'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_GROUP_ERROR_GROUPEMPTY'];
	}

	if ( !isset($content['ISERROR']) ) 
	{	
		// Everything was alright, so we go to the next step!
		if ( $_POST['op'] == "addnewgroup" )
		{
			$result = DB_Query("SELECT groupname FROM " . DB_GROUPS . " WHERE groupname = '" . $content['groupname'] . "'"); 
			$myrow = DB_GetSingleRow($result, true);
			if ( isset($myrow['groupname']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = $content['LN_GROUP_ERROR_GROUPNAMETAKEN'];
			}
			else
			{
				// Add new Group now!
				$result = DB_Query("INSERT INTO " . DB_GROUPS . " (groupname, groupdescription) 
				VALUES ( '" . $content['groupname'] . "', 
						 '" . $content['groupdescription'] . "' )");
				DB_FreeQuery($result);
				
				// Do the final redirect
				RedirectResult( GetAndReplaceLangStr( $content['LN_GROUP_HASBEENADDED'], DB_StripSlahes($content['groupname']) ) , "groups.php" );
			}
		}
		else if ( $_POST['op'] == "editgroup" )
		{
			$result = DB_Query("SELECT ID FROM " . DB_GROUPS . " WHERE ID = " . $content['GROUPID']);
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['ID']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERROR_IDNOTFOUND'], $content['GROUPID'] ); 
			}
			else
			{
				// Edit the User now!
				$result = DB_Query("UPDATE " . DB_GROUPS . " SET 
					groupname = '" . $content['groupname'] . "', 
					groupdescription = '" . $content['groupdescription'] . "'
					WHERE ID = " . $content['GROUPID']);
				DB_FreeQuery($result);

				// Done redirect!
				RedirectResult( GetAndReplaceLangStr( $content['LN_GROUP_ERROR_HASBEENEDIT'], DB_StripSlahes($content['groupname']) ) , "groups.php" );
			}
		}
		else if ( $_POST['op'] == "adduser" )
		{
			if ( isset($_POST['userid']) ) 
			{ 
				// Copy UserID
				$content['USERID'] = intval( DB_RemoveBadChars($_POST['userid']) ); 

				$result = DB_Query("SELECT username FROM " . DB_USERS . " WHERE id = " . $content['USERID']); 
				$myrow = DB_GetSingleRow($result, true);
				if ( isset($myrow['username']) )
				{
					// Add Groupmembership now!
					$result = DB_Query("INSERT INTO " . DB_GROUPMEMBERS . " (groupid, userid, is_member) 
					VALUES ( " . $content['GROUPID'] . ", 
							 " . $content['USERID'] . ", 
							 1 )");
					DB_FreeQuery($result);
					
					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_GROUP_USERHASBEENADDEDGROUP'], $myrow['username'], $content['groupname'] ) , "groups.php" );
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
				$content['ERROR_MSG'] = $content['LN_GROUP_ERROR_USERIDMISSING']; 
			}
		}
		else if ( $_POST['op'] == "removeuser" )
		{
			if ( isset($_POST['userid']) ) 
			{ 
				// Copy UserID
				$content['USERID'] = intval( DB_RemoveBadChars($_POST['userid']) ); 

				$result = DB_Query("SELECT username FROM " . DB_USERS . " WHERE id = " . $content['USERID']); 
				$myrow = DB_GetSingleRow($result, true);
				if ( isset($myrow['username']) )
				{
					// remove user from group
					$result = DB_Query( "DELETE FROM " . DB_GROUPMEMBERS . " WHERE userid = " . $content['USERID'] . " AND groupid = " . $content['GROUPID']);
					if ($result == FALSE)
					{
						$content['ISERROR'] = true;
						$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERROR_REMUSERFROMGROUP'], $myrow['username'], $content['groupname'] ); 
					}
					else
						DB_FreeQuery($result);

					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_GROUP_USERHASBEENREMOVED'], $myrow['username'], $content['groupname'] ) , "groups.php" );
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
				$content['ERROR_MSG'] = $content['LN_GROUP_ERROR_USERIDMISSING']; 
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Groups
	$content['LISTGROUPS'] = "true";

	// Read all Groupentries
	$sqlquery = "SELECT ID, " . 
				" groupname, " . 
				" groupdescription " . 
				" FROM " . DB_GROUPS. 
				" ORDER BY ID ";
	$result = DB_Query($sqlquery);
	$content['GROUPS'] = DB_GetAllRows($result, true);

	if ( count($content['GROUPS']) > 0 ) 
	{
		// --- Process Groups
		for($i = 0; $i < count($content['GROUPS']); $i++)
		{
			// --- Set CSS Class
			if ( $i % 2 == 0 )
				$content['GROUPS'][$i]['cssclass'] = "line1";
			else
				$content['GROUPS'][$i]['cssclass'] = "line2";
			// --- 

			// --- Read all Memberentries for this group
			$sqlquery = "SELECT " . 
						DB_USERS. ".username, " . 
						DB_GROUPMEMBERS . ".userid, " . 
						DB_GROUPMEMBERS . ".groupid, " . 
						DB_GROUPMEMBERS . ".is_member " . 
						" FROM " . DB_GROUPMEMBERS . 
						" INNER JOIN (" . DB_USERS . 
						") ON (" . 
						DB_GROUPMEMBERS . ".userid=" . DB_USERS . ".ID) " . 
						" WHERE " . DB_GROUPMEMBERS . ".groupid = " . $content['GROUPS'][$i]['ID'] . 
						" ORDER BY " . DB_USERS . ".username";
			$result = DB_Query($sqlquery);
			$content['GROUPS'][$i]['USERS'] = DB_GetAllRows($result, true);

			if ( count($content['GROUPS'][$i]['USERS']) > 0 ) 
			{
				// Enable Groupmembers
				$content['GROUPS'][$i]['GROUPMEMBERS'] = true;

				// Process Groups
				$subUserCount = count($content['GROUPS'][$i]['USERS']);
				for($j = 0; $j < $subUserCount; $j++)
					$content['GROUPS'][$i]['USERS'][$j]['seperator'] = ", ";
				$content['GROUPS'][$i]['USERS'][$subUserCount-1]['seperator'] = ""; // last one is empty
			}
			// --- 
		}
		// --- 
	}
	else
		$content['EMPTYGROUPS'] = "true";
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
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_GROUPOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_groups.html");
$page -> output(); 
// --- 

?>