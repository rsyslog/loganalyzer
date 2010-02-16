<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Fields Admin File											
	*																	
	* -> Helps administrating fields
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

// --- BEGIN Custom Code

// Only if the user is an admin!
if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
	DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_NOTALLOWED'] );

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
		$content['SearchOnline'] = false;		// If we want to be able to search online
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
				
				// Some fields cannot be edited, if this is an internal field!
				if ( $fields[$content['FieldID']]['IsInternalField'] ) 
					$content['DisableInternalFields'] = "disabled";
				else
					$content['DisableInternalFields'] = "";
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
			$content['FieldID'] = DB_RemoveBadChars($_GET['id']);

			// Get UserInfo
			$result = DB_Query("SELECT FieldCaption FROM " . DB_FIELDS . " WHERE FieldID = '" . $content['FieldID'] . "'"); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['FieldCaption']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_FIELDS_ERROR_IDNOTFOUND'], $content['FieldID'] ); 
			}

			// --- Ask for deletion first!
			if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_FIELDS_WARNDELETESEARCH'], $myrow['FieldCaption'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---

			// do the delete!
			$result = DB_Query( "DELETE FROM " . DB_FIELDS . " WHERE FieldID = '" . $content['FieldID'] . "'" );
			if ($result == FALSE)
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_FIELDS_ERROR_DELSEARCH'], $content['FieldID'] ); 
			}
			else
				DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_FIELDS_ERROR_HASBEENDEL'], $myrow['FieldCaption'] ) , "fields.php" );
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_FIELDS_ERROR_INVALIDID'];
		}
	}
}

if ( isset($_POST['op']) )
{
//print_r ( $_POST );
	// Get FieldID
	if ( isset($_POST['Newid']) ) { $content['FieldID'] = DB_RemoveBadChars($_POST['Newid']); } else if ( isset($_POST['id']) ) { $content['FieldID'] = DB_RemoveBadChars($_POST['id']); } else { $content['FieldID'] = ""; }

	// textfields
	if ( isset($_POST['FieldCaption']) ) { $content['FieldCaption'] = DB_RemoveBadChars($_POST['FieldCaption']); } else {$content['FieldCaption'] = ""; }
	if ( isset($_POST['SearchField']) ) { $content['SearchField'] = DB_RemoveBadChars($_POST['SearchField']); } else {$content['SearchField'] = ""; }
	if ( isset($_POST['NewFieldDefine']) ) { $content['FieldDefine'] = DB_RemoveBadChars($_POST['NewFieldDefine']); } else if ( isset($_POST['FieldDefine']) ) { $content['FieldDefine'] = DB_RemoveBadChars($_POST['FieldDefine']); } else { $content['FieldDefine'] = ""; }
	CreateFieldAlignmentList(0);
	if ( isset($_POST['FieldAlign']) && isset($content['ALIGMENTS'][$_POST['FieldAlign']]) ) { $content['FieldAlign'] = $_POST['FieldAlign']; } else {$content['FieldAlign'] = ALIGN_CENTER; }

	// number fields
	if ( isset($_POST['DefaultWidth']) ) { $content['DefaultWidth'] = intval(DB_RemoveBadChars($_POST['DefaultWidth'])); } else {$content['DefaultWidth'] = 50; }
//	NOT USED YET if ( isset ($_POST['Trunscate']) ) { $content['Trunscate'] = intval(DB_RemoveBadChars($_POST['Trunscate'])); } else {$content['Trunscate'] = 30; }
	CreateFieldTypesList(0);
	if ( isset($_POST['NewFieldType']) && isset($content['FILTERTYPES'][$_POST['NewFieldType']]) ) { $content['FieldType'] = intval($_POST['NewFieldType']); } else if ( isset($_POST['FieldType']) && isset($content['FILTERTYPES'][$_POST['FieldType']]) ) { $content['FieldType'] = intval($_POST['FieldType']); } else { $content['FieldType'] = FILTER_TYPE_STRING; }

	// Checkbox fields
	if ( isset($_POST['SearchOnline']) ) { $content['SearchOnline'] = "true"; } else { $content['SearchOnline'] = "false"; }
//	NOT USED YET if ( isset ($_POST['Sortable']) ) { $content['Sortable'] = true; } else {$content['Sortable'] = false; }

	// --- Check mandotary values
	if ( $content['FieldID'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_FIELDS_ERROR_FIELDIDEMPTY'];
	}
	else if ( $content['FieldCaption'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_FIELDS_ERROR_FIELDCAPTIONEMPTY'];
	}
	else if ( $content['SearchField'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_FIELDS_ERROR_SEARCHFIELDEMPTY'];
	}
	else if ( $content['FieldDefine'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_FIELDS_ERROR_FIELDDEFINEEMPTY'];
	}
	// --- 

	if ( !isset($content['ISERROR']) ) 
	{	
		// Everything was alright, go and check if the entry exists!
		$result = DB_Query("SELECT FieldID FROM " . DB_FIELDS . " WHERE FieldID = '" . $content['FieldID'] . "'");
		$myrow = DB_GetSingleRow($result, true);
		if ( !isset($myrow['FieldID']) )
		{
			// Add custom Field now!
			$sqlquery = "INSERT INTO " . DB_FIELDS . " (FieldID, FieldCaption, FieldDefine, SearchField, FieldAlign, DefaultWidth, FieldType, SearchOnline) 
			VALUES (
					'" . $content['FieldID'] . "', 
					'" . $content['FieldCaption'] . "',
					'" . $content['FieldDefine'] . "',
					'" . $content['SearchField'] . "',
					'" . $content['FieldAlign'] . "', 
					" . $content['DefaultWidth'] . ", 
					" . $content['FieldType'] . ", 
					" . $content['SearchOnline'] . " 
					)";
			$result = DB_Query($sqlquery);
			DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_FIELDS_HASBEENADDED'], DB_StripSlahes($content['FieldCaption']) ) , "fields.php" );
		}
		else
		{

			// Edit the Search Entry now!
			$result = DB_Query("UPDATE " . DB_FIELDS . " SET 
				FieldCaption = '" . $content['FieldCaption'] . "', 
				FieldDefine = '" . $content['FieldDefine'] . "', 
				SearchField = '" . $content['SearchField'] . "', 
				FieldAlign = '" . $content['FieldAlign'] . "', 
				DefaultWidth = " . $content['DefaultWidth'] . ", 
				FieldType = " . $content['FieldType'] . ", 
				SearchOnline = " . $content['SearchOnline'] . "
				WHERE FieldID = '" . $content['FieldID'] . "'");
			DB_FreeQuery($result);

			// Done redirect!
			RedirectResult( GetAndReplaceLangStr( $content['LN_FIELDS_HASBEENEDIT'], DB_StripSlahes($content['FieldCaption']) ) , "fields.php" );
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
		if ( $myField['IsInternalField'] && $myField['FieldFromDB'] ) 
		{
			$myField['AllowDelete'] = true;
			$myField['DELETEIMG'] = $content['MENU_DELETE_FROMDB'];
		}
		else if ( $myField['FieldFromDB'] ) 
		{
			$myField['AllowDelete'] = true;
			$myField['DELETEIMG'] = $content['MENU_DELETE'];
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