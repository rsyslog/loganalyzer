<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Views Admin File											
	*																	
	* -> Helps administrating custom user views
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
					$_GET['op'] == "delete" 
				)
			)	
		)
		DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_READONLY'] );
}
// --- 

// --- BEGIN Custom Code

// Only if the user is an admin!
//if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
//	DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_NOTALLOWED'] );

// Init helper variable to empty string
$content['FormUrlAddOP'] = "";

// --- Set Helpervariable for non-ADMIN users
if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
	$content['READONLY_ISUSERONLY'] = "disabled"; 
else
	$content['READONLY_ISUSERONLY'] = ""; 
// --- 

if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "add") 
	{
		// Set Mode to add
		$content['ISEDITORNEWVIEW'] = "true";
		$content['VIEW_FORMACTION'] = "addnewview";
		$content['VIEW_SENDBUTTON'] = $content['LN_VIEWS_ADD'];
		
		//PreInit these values 
		$content['DisplayName'] = "";
		$content['VIEWID'] = "";
		$content['FormUrlAddOP'] = "?op=add";
		$content['userid'] = null;
		$content['CHECKED_ISUSERONLY'] = "";

		// --- Can only create a USER source!
		if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
		{
			$content['userid'] = $content['SESSION_USERID']; 
			$content['CHECKED_ISUSERONLY'] = "checked"; 
		}
		// --- 

		// --- Check if groups are available
		$content['SUBGROUPS'] = GetGroupsForSelectfield();
		if ( is_array($content['SUBGROUPS']) )
			$content['ISGROUPSAVAILABLE'] = true;
		else
			$content['ISGROUPSAVAILABLE'] = false;
		// ---
	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWVIEW'] = "true";
		$content['VIEW_FORMACTION'] = "editview";
		$content['VIEW_SENDBUTTON'] = $content['LN_VIEWS_EDIT'];

		// Copy Views array for further modifications
		$content['VIEWS'] = $content['Views'];

		// View must be loaded as well already!
		if ( isset($_GET['id']) && isset($content['VIEWS'][$_GET['id']]) )
		{
			//PreInit these values 
			$content['VIEWID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['VIEWS'][ $content['VIEWID'] ]) )
			{

				//Set the FormAdd URL
				$content['FormUrlAddOP'] = "?op=edit&id=" . $content['VIEWID'];

				$myview = $content['VIEWS'][ $content['VIEWID'] ];

				$content['DisplayName'] = $myview['DisplayName'] ;
				$content['userid'] = $myview['userid'];
				$content['COLUMNS'] = $myview['Columns'];
				if ( $content['userid'] != null )
					$content['CHECKED_ISUSERONLY'] = "checked";
				else
					$content['CHECKED_ISUSERONLY'] = "";

				// --- Can only EDIT own views!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 && $content['userid'] == NULL ) 
					DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_NOTALLOWEDTOEDIT'] );
				// --- 

				// --- Check if groups are available
				$content['SUBGROUPS'] = GetGroupsForSelectfield();
				if ( is_array($content['SUBGROUPS']) )
				{
					// Process All Groups
					for($i = 0; $i < count($content['SUBGROUPS']); $i++)
					{
						if ( $myview['groupid'] != null && $content['SUBGROUPS'][$i]['mygroupid'] == $myview['groupid'] )
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
				$content['ISEDITORNEWVIEW'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_VIEWS_ERROR_IDNOTFOUND'], $content['VIEWID'] );
			}
		}
		else
		{
			$content['ISEDITORNEWVIEW'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_VIEWS_ERROR_INVALIDID'], isset($_GET['id']) ? $_GET['id'] : "<unknown>" );
		}
	}
	else if ($_GET['op'] == "delete") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['VIEWID'] = DB_RemoveBadChars($_GET['id']);

			// Get UserInfo
			$result = DB_Query("SELECT DisplayName FROM " . DB_VIEWS . " WHERE ID = " . $content['VIEWID'] ); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['DisplayName']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_VIEWS_ERROR_IDNOTFOUND'], $content['VIEWID'] ); 
			}

			// --- Ask for deletion first!
			if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_VIEWS_WARNDELETEVIEW'], $myrow['DisplayName'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---

			// do the delete!
			$result = DB_Query( "DELETE FROM " . DB_VIEWS . " WHERE ID = " . $content['VIEWID'] );
			if ($result == FALSE)
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_VIEWS_ERROR_DELSEARCH'], $content['VIEWID'] ); 
			}
			else
				DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_VIEWS_ERROR_HASBEENDEL'], $myrow['DisplayName'] ) , "views.php" );
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_VIEWS_ERROR_INVALIDID'];
		}
	}
}

// --- Additional work todo for the edit view
if ( isset($content['ISEDITORNEWVIEW']) && $content['ISEDITORNEWVIEW'] )
{
	// If Columns are send using POST we use them, otherwise we try to use from the view itself, if available
	if ( isset($_POST['Columns']) )
		$AllColumns = DB_RemoveBadChars($_POST['Columns']);
	else if ( isset($content['COLUMNS']) )
		$AllColumns = $content['COLUMNS'];

	// Read Columns from FORM data!
	if ( isset($AllColumns) )
	{
		// --- Read Columns from Formdata
		if ( is_array($AllColumns) )
		{
			// Copy columns ID's
			foreach ($AllColumns as $myColKey)
				$content['SUBCOLUMNS'][$myColKey]['ColFieldID'] = $myColKey;
		}
		else	// One element only
			$content['SUBCOLUMNS'][$AllColumns]['ColFieldID'] = $AllColumns;
		// --- 

		// --- Process Columns for display 
		$i = 0; // Help counter!
		foreach ($content['SUBCOLUMNS'] as $key => &$myColumn )
		{
			// Set Fieldcaption
			if ( isset($fields[$key]) && isset($fields[$key]['FieldCaption']) )
				$myColumn['ColCaption'] = $fields[$key]['FieldCaption'];
			else
				$myColumn['ColCaption'] = $key;

			// Append Internal FieldID
			$myColumn['ColInternalID'] = $fields[$key]['FieldDefine'];

			// --- Set CSS Class
			if ( $i % 2 == 0 )
				$myColumn['colcssclass'] = "line1";
			else
				$myColumn['colcssclass'] = "line2";
			$i++;
			// --- 
		}
		// --- 
	}

	// --- Copy fields data array
	$content['FIELDS'] = $fields; 
	
	// removed already added fields 
	if ( isset($content['SUBCOLUMNS']) )
	{
		foreach ($content['SUBCOLUMNS'] as $key => &$myColumn )
		{
			if ( isset($content['FIELDS'][$key]) ) 
				unset($content['FIELDS'][$key]);
		}
	}

	// set fieldcaption
	foreach ($content['FIELDS'] as $key => &$myField )
	{
		// Set Fieldcaption
		if ( isset($myField['FieldCaption']) )
			$myField['FieldCaption'] = $myField['FieldCaption'];
		else
			$myField['FieldCaption'] = $key;

		// Append Internal FieldID
		$myField['FieldCaption'] .= " (" . $fields[$key]['FieldDefine'] . ")";
	}
	// ---
}
// --- 

// --- Process POST Form Data
if ( isset($_POST['op']) )
{
	if ( isset ($_POST['id']) ) { $content['VIEWID'] = DB_RemoveBadChars($_POST['id']); } else {$content['VIEWID'] = ""; }
	if ( isset ($_POST['DisplayName']) ) { $content['DisplayName'] = DB_StripSlahes($_POST['DisplayName']); } else {$content['DisplayName'] = ""; }

	// User & Group handeled specially
	if ( isset ($_POST['isuseronly']) ) 
	{ 
		$content['userid'] = $content['SESSION_USERID']; 
		$content['groupid'] = "null"; // Either user or group not both!
	} 
	else 
	{
		// --- Can only create a USER source!
		if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
		{
			$content['userid'] = $content['SESSION_USERID']; 
			$content['groupid'] = "null"; 
		}
		else
		{
			$content['userid'] = "null"; 
			if ( isset ($_POST['groupid']) && $_POST['groupid'] != -1 ) 
				$content['groupid'] = intval($_POST['groupid']); 
			else 
				$content['groupid'] = "null";
		}
	}
	
	// --- Check mandotary values
	if ( $content['DisplayName'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_VIEWS_ERROR_DISPLAYNAMEEMPTY'];
	}
	// --- 

	if ( !isset($content['ISERROR']) ) 
	{	

		// --- Set SUBOP Helpers
		if ( $content['userid'] == "null" )
			$content['CHECKED_ISUSERONLY'] = "";
		else
			$content['CHECKED_ISUSERONLY'] = "checked";

		if ( $content['ISGROUPSAVAILABLE'] && $content['groupid'] != "null" )
		{
			// Process All Groups
			for($i = 0; $i < count($content['SUBGROUPS']); $i++)
			{
				if ( $content['SUBGROUPS'][$i]['mygroupid'] == $content['groupid'] )
					$content['SUBGROUPS'][$i]['group_selected'] = "selected";
				else
					$content['SUBGROUPS'][$i]['group_selected'] = "";
			}
		}
		// --- 

		// Check subop's first!
		if ( isset($_POST['subop']) )
		{
			if ( isset($_POST['newcolumn']) )
			{
				// Get NewColID
				$szColId = DB_RemoveBadChars($_POST['newcolumn']);
				
				// Add a new Column into our list!
				if ( $_POST['subop'] == $content['LN_VIEWS_ADDCOLUMN'] && isset($_POST['newcolumn']) )
				{
					// Add New entry into columnlist
					$content['SUBCOLUMNS'][$szColId]['ColFieldID'] = $szColId;

					// Set Internal FieldID
					$content['SUBCOLUMNS'][$szColId]['ColInternalID'] = $fields[$szColId]['FieldDefine'];

					// Set Fieldcaption
					if ( isset($fields[$szColId]['FieldCaption']) )
						$content['SUBCOLUMNS'][$szColId]['ColCaption'] = $fields[$szColId]['FieldCaption'];
					else
						$content['SUBCOLUMNS'][$szColId]['ColCaption'] = $szColId;

					// Set CSSClass
					$content['SUBCOLUMNS'][$szColId]['colcssclass'] = count($content['SUBCOLUMNS']) % 2 == 0 ? "line1" : "line2";
					
					// Remove from fields list as well
					if ( isset($content['FIELDS'][$szColId]) ) 
						unset($content['FIELDS'][$szColId]);
				}
			}
		}
		else if ( isset($_POST['subop_delete']) )
		{
			// Get Column ID
			$szColId = DB_RemoveBadChars($_POST['subop_delete']);

			// Remove Entry from Columnslist
			if ( isset($content['SUBCOLUMNS'][$szColId]) )
				unset($content['SUBCOLUMNS'][$szColId]);

			// Add removed entry to field list
			$content['FIELDS'][$szColId] = $szColId;

			// Set Fieldcaption
			if ( isset($fields[$szColId]) && isset($fields[$szColId]['FieldCaption']) )
				$content['FIELDS'][$szColId]['FieldCaption'] = $fields[$szColId]['FieldCaption'];
			else
				$content['FIELDS'][$szColId]['FieldCaption'] = $szColId;
		}
		else if ( isset($_POST['subop_moveup']) )
		{
			// Get Column ID
			$szColId = DB_RemoveBadChars($_POST['subop_moveup']);

			// --- Move Entry one UP in Columnslist
			// Find the entry in the array
			$iArrayNum = 0;
			foreach ($content['SUBCOLUMNS'] as $key => &$myColumn )
			{
				if ( $key == $szColId ) 
					break;

				$iArrayNum++;
			}
			
			// If found move up
			if ( $iArrayNum > 0 )
			{
				// Extract Entry from the array
				$EntryTwoMove = array_slice($content['SUBCOLUMNS'], $iArrayNum, 1);

				// Unset Entry from the array
				unset( $content['SUBCOLUMNS'][$szColId] );

				// Splice the array order!
				array_splice($content['SUBCOLUMNS'], $iArrayNum-1, 0, $EntryTwoMove);
			}
			// --- 
		}
		else if ( isset($_POST['subop_movedown']) )
		{
			// Get Column ID
			$szColId = DB_RemoveBadChars($_POST['subop_movedown']);

			// --- Move Entry one DOWN in Columnslist
			// Find the entry in the array
			$iArrayNum = 0;
			foreach ($content['SUBCOLUMNS'] as $key => &$myColumn )
			{
				if ( $key == $szColId ) 
					break;

				$iArrayNum++;
			}
			
			// If found move down
			if ( $iArrayNum < count($content['SUBCOLUMNS']) )
			{
				// Extract Entry from the array
				$EntryTwoMove = array_slice($content['SUBCOLUMNS'], $iArrayNum, 1);

				// Unset Entry from the array
				unset( $content['SUBCOLUMNS'][$szColId] );

				// Splice the array order!
				array_splice($content['SUBCOLUMNS'], $iArrayNum+1, 0, $EntryTwoMove);
			}
			// --- 
		}
		else // Now SUBOP means normal processing!
		{
			// Now we convert fr DB insert!
			$content['DisplayName'] = DB_RemoveBadChars($_POST['DisplayName']);

			// Everything was alright, so we go to the next step!
			if ( $_POST['op'] == "addnewview" )
			{
				// Create Columnlist comma seperated!
				if ( isset($_POST['Columns']) && is_array($_POST['Columns']) )
				{
					// Copy columns ID's
					foreach ( $_POST['Columns'] as $myColKey)
					{
						if ( isset($content['COLUMNS']) ) 
							$content['COLUMNS'] .= ", " . DB_RemoveBadChars($myColKey);
						else
							$content['COLUMNS'] = DB_RemoveBadChars($myColKey);
					}

					// Add custom search now!
					$sqlquery = "INSERT INTO " . DB_VIEWS. " (DisplayName, Columns, userid, groupid) 
					VALUES ('" . $content['DisplayName'] . "', 
							'" . $content['COLUMNS'] . "',
							" . $content['userid'] . ", 
							" . $content['groupid'] . " 
							)";
					$result = DB_Query($sqlquery);
					DB_FreeQuery($result);
					
					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_VIEWS_HASBEENADDED'], DB_StripSlahes($content['DisplayName']) ) , "views.php" );
				}
				else
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = $content['LN_VIEWS_ERROR_NOCOLUMNS']; 
				}
			}
			else if ( $_POST['op'] == "editview" )
			{
				// Now we convert fr DB insert!
				$content['DisplayName'] = DB_RemoveBadChars($_POST['DisplayName']);

				$result = DB_Query("SELECT ID FROM " . DB_VIEWS . " WHERE ID = " . $content['VIEWID']);
				$myrow = DB_GetSingleRow($result, true);
				if ( !isset($myrow['ID']) )
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_VIEWS_ERROR_IDNOTFOUND'], $content['VIEWID'] ); 
				}
				else
				{
					// Create Columnlist comma seperated!
					if ( isset($_POST['Columns']) && is_array($_POST['Columns']) )
					{
						// Copy columns ID's
						unset($content['COLUMNS']);
						foreach ($_POST['Columns'] as $myColKey)
						{
							if ( isset($content['COLUMNS']) ) 
								$content['COLUMNS'] .= ", " . DB_RemoveBadChars($myColKey);
							else
								$content['COLUMNS'] = DB_RemoveBadChars($myColKey);
						}


						// Edit the Search Entry now!
						$result = DB_Query("UPDATE " . DB_VIEWS . " SET 
							DisplayName = '" . $content['DisplayName'] . "', 
							Columns = '" . $content['COLUMNS'] . "', 
							userid = " . $content['userid'] . ", 
							groupid = " . $content['groupid'] . "
							WHERE ID = " . $content['VIEWID']);
						DB_FreeQuery($result);

						// Done redirect!
						RedirectResult( GetAndReplaceLangStr( $content['LN_VIEWS_HASBEENEDIT'], DB_StripSlahes($content['DisplayName']) ) , "views.php" );
					}
					else
					{
						$content['ISERROR'] = true;
						$content['ERROR_MSG'] = $content['LN_VIEWS_ERROR_NOCOLUMNS']; 
					}
				}
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Searches
	$content['LISTVIEWS'] = "true";

	// Copy Views array for further modifications
	$content['VIEWS'] = $content['Views'];

	// --- Process Views
	$i = 0; // Help counter!
	foreach ($content['VIEWS'] as &$myView )
	{
		// So internal Views can not be edited but seen
		if ( is_numeric($myView['ID']) )
		{
			$myView['ActionsAllowed'] = true;

			// --- Set Image for Type
			if ( $myView['userid'] != null )
			{
				$myView['ViewTypeImage'] = $content["MENU_ADMINUSERS"];
				$myView['ViewTypeText'] = $content["LN_GEN_USERONLY"];
			}
			else if ( $myView['groupid'] != null )
			{
				$myView['ViewTypeImage'] = $content["MENU_ADMINGROUPS"];
				$myView['ViewTypeText'] = GetAndReplaceLangStr( $content["LN_GEN_GROUPONLYNAME"], $myView['groupname'] );

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$myView['ActionsAllowed'] = false;
			}
			else
			{
				$myView['ViewTypeImage'] = $content["MENU_GLOBAL"];
				$myView['ViewTypeText'] = $content["LN_GEN_GLOBAL"];

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$myView['ActionsAllowed'] = false;
			}
			// ---
		}
		else
		{
			$myView['ActionsAllowed'] = false;

			$myView['ViewTypeImage'] = $content["MENU_INTERNAL"];
			$myView['ViewTypeText'] = $content["LN_GEN_INTERNAL"];
		}

		// --- Add DisplayNames to columns
		$iBegin = true;
		foreach ($myView['Columns'] as $myCol )
		{
			// Get Fieldcaption
			if ( isset($fields[$myCol]) && isset($fields[$myCol]['FieldCaption']) )
				$myView['COLUMNS'][$myCol]['FieldCaption'] = $fields[$myCol]['FieldCaption'];
			else
				$myView['COLUMNS'][$myCol]['FieldCaption'] = $myCol;

			if ( $iBegin )
			{
				$myView['COLUMNS'][$myCol]['FieldCaptionSeperator'] = "";
				$iBegin = false;
			}
			else
				$myView['COLUMNS'][$myCol]['FieldCaptionSeperator'] = ", ";

		}
		// ---

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$myView['cssclass'] = "line1";
		else
			$myView['cssclass'] = "line2";
		$i++;
		// --- 
	}
	// --- 
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_VIEWSOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_views.html");
$page -> output(); 
// --- 

?>