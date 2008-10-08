<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Fields Admin File											
	*																	
	* -> Helps administrating fields
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
		$content['ISEDITORNEWFIELD'] = "true";
		$content['FIELD_FORMACTION'] = "addnewfield";
		$content['FIELD_SENDBUTTON'] = $content['LN_FIELDS_ADD'];
		
		//PreInit these values 
		$content['FieldID'] = "";
		$content['FieldDefine'] = "SYSLOG_";
		$content['FieldCaption'] = "";		// Field Caption 
		$content['SearchField'] = "";		// Should be set to FieldID for now!
		$content['SearchOnline'] = 0;		// If we want to be able to search online
		$content['CHECKED_SEARCHONLINE'] = "";

		$content['FieldType'] = FILTER_TYPE_STRING;
		CreateFieldTypesList($content['FieldType']);
		$content['FieldAlign'] = ALIGN_CENTER;
		CreateFieldAlignmentList($content['FieldAlign']);
		$content['DefaultWidth'] = "50";

		$content['Trunscate'] = "30";		// Not supported yet!
		$content['Sortable'] = false;		// Not supported yet!
	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWFIELD'] = "true";
		$content['FIELD_FORMACTION'] = "editfield";
		$content['FIELD_SENDBUTTON'] = $content['LN_FIELDS_EDIT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['FieldID'] = DB_RemoveBadChars($_GET['id']);

			if ( isset($fields[$content['FieldID']]['FieldID']) )
			{
				$content['FieldDefine'] = $fields[$content['FieldID']]['FieldDefine'];
				$content['FieldCaption'] = $fields[$content['FieldID']]['FieldCaption'];
				$content['SearchField'] = $fields[$content['FieldID']]['SearchField'];
				$content['SearchOnline'] = $fields[$content['FieldID']]['SearchOnline'];
				if ( $content['SearchOnline'] ) { $content['CHECKED_SEARCHONLINE'] = "checked"; } else { $content['CHECKED_SEARCHONLINE'] = ""; }

				$content['FieldType'] = $fields[$content['FieldID']]['FieldType'];
				CreateFieldTypesList($content['FieldType']);
				$content['FieldAlign'] = $fields[$content['FieldID']]['FieldAlign'];
				CreateFieldAlignmentList($content['FieldAlign']);
				$content['DefaultWidth'] = $fields[$content['FieldID']]['DefaultWidth'];
				
				// Unused fields yet
				$content['Trunscate'] = $fields[$content['FieldID']]['Trunscate'];
				$content['Sortable'] = $fields[$content['FieldID']]['Sortable'];
				if ( $content['Sortable'] ) { $content['CHECKED_SORTABLE'] = "checked"; } else { $content['CHECKED_SORTABLE'] = ""; }
			}
			else
			{
				$content['ISEDITORNEWFIELD'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_FIELDS_ERROR_IDNOTFOUND'], $content['FieldID'] );
			}
		}
		else
		{
			$content['ISEDITORNEWFIELD'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] =  $content['LN_FIELDS_ERROR_INVALIDID'];
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
	$content['LISTFIELDS'] = "true";

	// Copy Search array for further modifications
	$content['FIELDS'] = $fields; 

	$i = 0; // Help counter!
	foreach ($content['FIELDS'] as &$myField )
	{
		// Allow Delete Operation
		if ( $myField['FieldFromDB'] ) 
		{
			$myField['AllowDelete'] = true;
			$myField['DELETEIMG'] = $content['MENU_DELETE'];
		}

		if ( !$myField['IsInternalField'] && $myField['FieldFromDB'] ) 
		{
			$myField['AllowDelete'] = true;
			$myField['DELETEIMG'] = $content['MENU_DELETE_FROMDB'];
		}

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$myField['cssclass'] = "line1";
		else
			$myField['cssclass'] = "line2";
		$i++;
		// --- 
	}
	// --- 
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= $content['LN_ADMINMENU_FIELDOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_fields.html");
$page -> output(); 
// --- 

?>