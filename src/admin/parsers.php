<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Search Admin File											
	*																	
	* -> Helps administrating message parsers
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
if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "add") 
	{
		// Set Mode to add
		$content['ISEDITORNEWSEARCH'] = "true";
		$content['SEARCH_FORMACTION'] = "addnewsearch";
		$content['SEARCH_SENDBUTTON'] = $content['LN_SEARCH_ADD'];
		
		//PreInit these values 
		$content['DisplayName'] = "";
		$content['SearchQuery'] = "";

		// General stuff
		$content['userid'] = null;
		$content['CHECKED_ISUSERONLY'] = "";
		$content['SEARCHID'] = "";
		
		// --- Check if groups are available
		$content['SUBGROUPS'] = GetGroupsForSelectfield();
		if ( is_array($content['SUBGROUPS']) )
			$content['ISGROUPSAVAILABLE'] = true;
		else
			$content['ISGROUPSAVAILABLE'] = false;
	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWSEARCH'] = "true";
		$content['SEARCH_FORMACTION'] = "editsearch";
		$content['SEARCH_SENDBUTTON'] = $content['LN_SEARCH_EDIT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['SEARCHID'] = DB_RemoveBadChars($_GET['id']);

			$sqlquery = "SELECT * " . 
						" FROM " . DB_SEARCHES . 
						" WHERE ID = " . $content['SEARCHID'];

			$result = DB_Query($sqlquery);
			$mysearch = DB_GetSingleRow($result, true);
			if ( isset($mysearch['DisplayName']) )
			{
				$content['SEARCHID'] = $mysearch['ID'];
				$content['DisplayName'] = $mysearch['DisplayName'];
				$content['SearchQuery'] = $mysearch['SearchQuery'];
				if ( $mysearch['userid'] != null )
					$content['CHECKED_ISUSERONLY'] = "checked";
				else
					$content['CHECKED_ISUSERONLY'] = "";
				
				// --- Check if groups are available
				$content['SUBGROUPS'] = GetGroupsForSelectfield();
				if ( is_array($content['SUBGROUPS']) )
				{
					// Process All Groups
					for($i = 0; $i < count($content['SUBGROUPS']); $i++)
					{
						if ( $mysearch['groupid'] != null && $content['SUBGROUPS'][$i]['mygroupid'] == $mysearch['groupid'] )
							$content['SUBGROUPS'][$i]['group_selected'] = "selected";
						else
							$content['SUBGROUPS'][$i]['group_selected'] = "";
					}

					// Enable Group Selection
					$content['ISGROUPSAVAILABLE'] = true;
				}
				else
					$content['ISGROUPSAVAILABLE'] = false;
				// ---
			}
			else
			{
				$content['ISEDITORNEWSEARCH'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SEARCH_ERROR_IDNOTFOUND'], $content['SEARCHID'] );
			}
		}
		else
		{
			$content['ISEDITORNEWSEARCH'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] =  $content['LN_SEARCH_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "delete") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['SEARCHID'] = DB_RemoveBadChars($_GET['id']);

			// Get UserInfo
			$result = DB_Query("SELECT DisplayName FROM " . DB_SEARCHES . " WHERE ID = " . $content['SEARCHID'] ); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['DisplayName']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SEARCH_ERROR_IDNOTFOUND'], $content['SEARCHID'] ); 
			}

			// --- Ask for deletion first!
			if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_SEARCH_WARNDELETESEARCH'], $myrow['DisplayName'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---

			// do the delete!
			$result = DB_Query( "DELETE FROM " . DB_SEARCHES . " WHERE ID = " . $content['SEARCHID'] );
			if ($result == FALSE)
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SEARCH_ERROR_DELSEARCH'], $content['SEARCHID'] ); 
			}
			else
				DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_SEARCH_ERROR_HASBEENDEL'], $myrow['DisplayName'] ) , "searches.php" );
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_SEARCH_ERROR_INVALIDID'];
		}
	}
}

if ( isset($_POST['op']) )
{
	if ( isset ($_POST['id']) ) { $content['SEARCHID'] = intval(DB_RemoveBadChars($_POST['id'])); } else {$content['SEARCHID'] = -1; }
	if ( isset ($_POST['DisplayName']) ) { $content['DisplayName'] = DB_RemoveBadChars($_POST['DisplayName']); } else {$content['DisplayName'] = ""; }
	if ( isset ($_POST['SearchQuery']) ) { $content['SearchQuery'] = DB_RemoveBadChars($_POST['SearchQuery']); } else {$content['SearchQuery'] = ""; }

	// User & Group handeled specially
	if ( isset ($_POST['isuseronly']) ) 
	{ 
		$content['userid'] = $content['SESSION_USERID']; 
		$content['groupid'] = "null"; // Either user or group not both!
	} 
	else 
	{
		$content['userid'] = "null"; 
		if ( isset ($_POST['groupid']) && $_POST['groupid'] != -1 ) 
			$content['groupid'] = intval($_POST['groupid']); 
		else 
			$content['groupid'] = "null";
	}

	// --- Check mandotary values
	if ( $content['DisplayName'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_SEARCH_ERROR_DISPLAYNAMEEMPTY'];
	}
	else if ( $content['SearchQuery'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_SEARCH_ERROR_SEARCHQUERYEMPTY'];
	}
	// --- 

	if ( !isset($content['ISERROR']) ) 
	{	
		// Everything was alright, so we go to the next step!
		if ( $_POST['op'] == "addnewsearch" )
		{
			// Add custom search now!
			$sqlquery = "INSERT INTO " . DB_SEARCHES . " (DisplayName, SearchQuery, userid, groupid) 
			VALUES ('" . $content['DisplayName'] . "', 
					'" . $content['SearchQuery'] . "',
					" . $content['userid'] . ", 
					" . $content['groupid'] . " 
					)";
			$result = DB_Query($sqlquery);
			DB_FreeQuery($result);
			
			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_SEARCH_HASBEENADDED'], $content['DisplayName'] ) , "searches.php" );
		}
		else if ( $_POST['op'] == "editsearch" )
		{
			$result = DB_Query("SELECT ID FROM " . DB_SEARCHES . " WHERE ID = " . $content['SEARCHID']);
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['ID']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_SEARCH_ERROR_IDNOTFOUND'], $content['SEARCHID'] ); 
			}
			else
			{
				// Edit the Search Entry now!
				$result = DB_Query("UPDATE " . DB_SEARCHES . " SET 
					DisplayName = '" . $content['DisplayName'] . "', 
					SearchQuery = '" . $content['SearchQuery'] . "', 
					userid = " . $content['userid'] . ", 
					groupid = " . $content['groupid'] . "
					WHERE ID = " . $content['SEARCHID']);
				DB_FreeQuery($result);

				// Done redirect!
				RedirectResult( GetAndReplaceLangStr( $content['LN_SEARCH_HASBEENEDIT'], $content['DisplayName']) , "searches.php" );
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Searches
	$content['LISTPARSERS'] = "true";

	// Init List of Parsers
	InitMessageParsers();
//	$content['PARSERS'] = $content['Search'];
	
	if ( isset($content['PARSERS']) ) 
	{
		$i = 0; // Help counter!
		foreach ($content['PARSERS'] as &$myParsers )
		{
	//		$mySearch['SearchQuery_Display'] = strlen($mySearch['SearchQuery']) > 25 ? substr($mySearch['SearchQuery'], 0, 25) . " ..." : $mySearch['SearchQuery'];

			// Allow EDIT
	//		$mySearch['ActionsAllowed'] = true;
			
			// Set if help link is enabled
			if ( strlen($myParsers['ParserHelpArticle']) > 0 ) 
				$myParsers['ParserHelpEnabled'] = true;
			else
				$myParsers['ParserHelpEnabled'] = false;

			// --- Set CSS Class
			if ( $i % 2 == 0 )
				$myParsers['cssclass'] = "line1";
			else
				$myParsers['cssclass'] = "line2";
			$i++;
			// --- 
		}
	}
	else
	{
		$content['LISTPARSERS'] = "false";
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_PARSERS_ERROR_NOPARSERS']; 
	}
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_MSGPARSERSOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_parsers.html");
$page -> output(); 
// --- 

?>