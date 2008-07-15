<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Group Admin File											
	*																	
	* -> Helps administrating groups
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
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWGROUP'] = "true";
		$content['GROUP_FORMACTION'] = "edituser";
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

				// do the delete!
				$result = DB_Query( "DELETE FROM " . DB_GROUPS . " WHERE ID = " . $content['GROUPID'] );
				if ($result == FALSE)
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_GROUP_ERROR_DELGROUP'], $content['USERID'] ); 
				}
				else
					DB_FreeQuery($result);

				// TODO: DELETE GROUP SETTINGS, GROUP MEMBERSHIP ...

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

	if ( isset($_POST['op']) )
	{
		if ( isset ($_POST['id']) ) { $content['GROUPID'] = DB_RemoveBadChars($_POST['id']); } else {$content['GROUPID'] = ""; }
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
					RedirectResult( GetAndReplaceLangStr( $content['LN_GROUP_HASBEENADDED'], $content['groupname'] ) , "groups.php" );
				}
			}
			else if ( $_POST['op'] == "edituser" )
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
					RedirectResult( GetAndReplaceLangStr( $content['LN_GROUP_ERROR_HASBEENEDIT'], $content['groupname']) , "groups.php" );
				}
			}
		}
	}
}
else
{
	// Default Mode = List Groups
	$content['LISTGROUPS'] = "true";

	// Read all Serverentries
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
		}
		// --- 
	}
	else
		$content['EMPTYGROUPS'] = "true";
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: Group Options";
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_groups.html");
$page -> output(); 
// --- 

?>