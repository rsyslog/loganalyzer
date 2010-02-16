<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* DBMapping Admin File											
	*																	
	* -> Helps administrating custom database mappings 
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

// Init helper variable to empty string
$content['FormUrlAddOP'] = "";

if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "add") 
	{
		// Set Mode to add
		$content['ISEDITORNEWDBMP'] = "true";
		$content['DBMP_FORMACTION'] = "addnewdbmp";
		$content['DBMP_SENDBUTTON'] = $content['LN_DBMP_ADD'];
		
		//PreInit these values 
		$content['DisplayName'] = "";
		$content['DBMPID'] = "";
		$content['FormUrlAddOP'] = "?op=add";
	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWDBMP'] = "true";
		$content['DBMP_FORMACTION'] = "editdbmp";
		$content['DBMP_SENDBUTTON'] = $content['LN_DBMP_EDIT'];

		// Copy Views array for further modifications
		$content['DBMP'] = $dbmapping;

		// View must be loaded as well already!
		if ( isset($_GET['id']) && isset($content['DBMP'][$_GET['id']]) )
		{
			//PreInit these values 
			$content['DBMPID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['DBMP'][ $content['DBMPID'] ]) )
			{
				//Set the FormAdd URL
				$content['FormUrlAddOP'] = "?op=edit&id=" . $content['DBMPID'];

				$mymapping = $content['DBMP'][ $content['DBMPID'] ];
				$content['DisplayName'] = $mymapping['DisplayName'] ;
				$content['SUBMAPPINGS'] = $mymapping['DBMAPPINGS'];
			}
			else
			{
				$content['ISEDITORNEWDBMP'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBMP_ERROR_IDNOTFOUND'], $content['DBMPID'] );
			}
		}
		else
		{
			$content['ISEDITORNEWDBMP'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBMP_ERROR_INVALIDID'], isset($_GET['id']) ? $_GET['id'] : "<unknown>" );
		}
	}
	else if ($_GET['op'] == "delete") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['DBMPID'] = DB_RemoveBadChars($_GET['id']);

			// Get UserInfo
			$result = DB_Query("SELECT DisplayName FROM " . DB_MAPPINGS . " WHERE ID = " . $content['DBMPID'] ); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['DisplayName']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBMP_ERROR_IDNOTFOUND'], $content['DBMPID'] ); 
			}

			// --- Ask for deletion first!
			if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_DBMP_WARNDELETEMAPPING'], $myrow['DisplayName'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---

			// do the delete!
			$result = DB_Query( "DELETE FROM " . DB_MAPPINGS . " WHERE ID = " . $content['DBMPID'] );
			if ($result == FALSE)
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBMP_ERROR_DELSEARCH'], $content['DBMPID'] ); 
			}
			else
				DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_DBMP_ERROR_HASBEENDEL'], $myrow['DisplayName'] ) , "dbmappings.php" );
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_DBMP_ERROR_INVALIDID'];
		}
	}
}

// --- Additional work todo for the edit view
if ( isset($content['ISEDITORNEWDBMP']) && $content['ISEDITORNEWDBMP'] )
{
	// If Columns are send using POST we use them, otherwise we try to use from the view itself, if available
	if ( isset($_POST['Mappings']) )
		$AllMappings = $_POST['Mappings'];
	else if ( isset($content['SUBMAPPINGS']) )
		$AllMappings = $content['SUBMAPPINGS'];

	// Read Columns from FORM data!
	if ( isset($AllMappings) )
	{
		// --- Read Columns from Formdata
		if ( is_array($AllMappings) )
		{
			// Copy columns ID's
			foreach ($AllMappings as $myColKey => $myFieldName) // $myColKey)
			{
				if ( !is_numeric($myColKey) )
					$content['SUBMAPPINGS'][$myColKey] = array( 'MappingFieldID' => $myColKey, 'MappingDbFieldName' => $myFieldName );
				else
					$content['SUBMAPPINGS'][$myFieldName] = array( 'MappingFieldID' => $myFieldName );
			}
		}
		else	// One element only
			$content['SUBMAPPINGS'][$AllColumns]['MappingFieldID'] = $AllColumns;
		// --- 

		// --- Process Columns for display 
		$i = 0; // Help counter!
		foreach ($content['SUBMAPPINGS'] as $key => &$myColumn )
		{
			// Set Fieldcaption
			if ( isset($fields[$key]) && isset($fields[$key]['FieldCaption']) )
				$myColumn['MappingCaption'] = $fields[$key]['FieldCaption'];
			else
				$myColumn['MappingCaption'] = $key;

			// Append Internal FieldID
			$myColumn['MappingInternalID'] = $fields[$key]['FieldDefine'];
			
			// Set Mapping Fieldname
			if ( isset( $_POST[ $myColumn['MappingFieldID'] ]) ) 
				$myColumn['MappingDbFieldName'] = $_POST[ $myColumn['MappingFieldID'] ];
			else if ( !isset($myColumn['MappingDbFieldName']) && strlen($myColumn['MappingDbFieldName']) > 0) 
				$myColumn['MappingDbFieldName'] = "";

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
	if ( isset($content['SUBMAPPINGS']) )
	{
		foreach ($content['SUBMAPPINGS'] as $key => &$myColumn )
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
	if ( isset ($_POST['id']) ) { $content['DBMPID'] = DB_RemoveBadChars($_POST['id']); } else {$content['DBMPID'] = ""; }
	if ( isset ($_POST['DisplayName']) ) { $content['DisplayName'] = DB_StripSlahes($_POST['DisplayName']); } else {$content['DisplayName'] = ""; }

	// --- Check mandotary values
	if ( $content['DisplayName'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_DBMP_ERROR_DISPLAYNAMEEMPTY'];
	}
	// --- 

	if ( !isset($content['ISERROR']) ) 
	{	
		// Check subop's first!
		if ( isset($_POST['subop']) )
		{
			if ( isset($_POST['newmapping']) )
			{
				// Get NewColID
				$szColId = DB_RemoveBadChars($_POST['newmapping']);
				
				// Add a new Column into our list!
				if ( $_POST['subop'] == $content['LN_DBMP_ADDMAPPING'] && isset($_POST['newmapping']) )
				{
					// Add New entry into columnlist
					$content['SUBMAPPINGS'][$szColId]['MappingFieldID'] = $szColId;

					// Set Internal FieldID
					$content['SUBMAPPINGS'][$szColId]['MappingInternalID'] = $fields[$szColId]['FieldDefine'];

					// Set default for DbFieldName
					$content['SUBMAPPINGS'][$szColId]['MappingDbFieldName'] = "";

					// Set Fieldcaption
					if ( isset($fields[$szColId]['FieldCaption']) )
						$content['SUBMAPPINGS'][$szColId]['MappingCaption'] = $fields[$szColId]['FieldCaption'];
					else
						$content['SUBMAPPINGS'][$szColId]['MappingCaption'] = $szColId;

					// Set CSSClass
					$content['SUBMAPPINGS'][$szColId]['colcssclass'] = count($content['SUBMAPPINGS']) % 2 == 0 ? "line1" : "line2";
					
					// Remove from fields list as well
					if ( isset($content['FIELDS'][$szColId]) ) 
						unset($content['FIELDS'][$szColId]);
				}
			}
		}
		else if ( isset($_POST['subop_edit']) )
		{
			// Actually nothing todo ;), the edit is performed automatically when the SUBMAPPINGS array is created.
		}
		else if ( isset($_POST['subop_delete']) )
		{
			// Get Column ID
			$szColId = DB_RemoveBadChars($_POST['subop_delete']);

			// Remove Entry from Columnslist
			if ( isset($content['SUBMAPPINGS'][$szColId]) )
				unset($content['SUBMAPPINGS'][$szColId]);

			// Add removed entry to field list
			$content['FIELDS'][$szColId] = $fields[$szColId];

			// Set Fieldcaption
			if ( isset($content['FIELDS'][$szColId]['FieldCaption']) )
				$content['FIELDS'][$szColId]['FieldCaption'] = $content['FIELDS'][$szColId]['FieldCaption'];
			else
				$content['FIELDS'][$szColId]['FieldCaption'] = $szColId;

			// Append Internal FieldID
			$content['FIELDS'][$szColId]['FieldCaption'] .= " (" . $content['FIELDS'][$szColId]['FieldDefine'] . ")";
		}
		else if ( isset($_POST['subop_moveup']) )
		{
			// Get Column ID
			$szColId = DB_RemoveBadChars($_POST['subop_moveup']);

			// --- Move Entry one UP in Columnslist
			// Find the entry in the array
			$iArrayNum = 0;
			foreach ($content['SUBMAPPINGS'] as $key => &$myColumn )
			{
				if ( $key == $szColId ) 
					break;

				$iArrayNum++;
			}
			
			// If found move up
			if ( $iArrayNum > 0 )
			{
				// Extract Entry from the array
				$EntryTwoMove = array_slice($content['SUBMAPPINGS'], $iArrayNum, 1);

				// Unset Entry from the array
				unset( $content['SUBMAPPINGS'][$szColId] );

				// Splice the array order!
				array_splice($content['SUBMAPPINGS'], $iArrayNum-1, 0, $EntryTwoMove);
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
			foreach ($content['SUBMAPPINGS'] as $key => &$myColumn )
			{
				if ( $key == $szColId ) 
					break;

				$iArrayNum++;
			}
			
			// If found move down
			if ( $iArrayNum < count($content['SUBMAPPINGS']) )
			{
				// Extract Entry from the array
				$EntryTwoMove = array_slice($content['SUBMAPPINGS'], $iArrayNum, 1);

				// Unset Entry from the array
				unset( $content['SUBMAPPINGS'][$szColId] );

				// Splice the array order!
				array_splice($content['SUBMAPPINGS'], $iArrayNum+1, 0, $EntryTwoMove);
			}
			// --- 
		}
		else // Now SUBOP means normal processing!
		{
			// Now we convert fr DB insert!
			$content['DisplayName'] = DB_RemoveBadChars($_POST['DisplayName']);

			// Everything was alright, so we go to the next step!
			if ( $_POST['op'] == "addnewdbmp" )
			{
				// Create Columnlist comma seperated!
				if ( isset($_POST['Mappings']) && is_array($_POST['Mappings']) )
				{
					// Copy columns ID's
					foreach ($_POST['Mappings'] as $myColKey)
					{
						if ( isset($_POST[$myColKey]) && strlen($_POST[$myColKey]) > 0 ) 
						{
							// Get FieldName
							$myMappingFieldName = DB_StripSlahes($_POST[$myColKey]);

							if ( isset($content['SUBMAPPINGS']) ) 
								$content['SUBMAPPINGS'] .= "," . $myColKey;
							else
								$content['SUBMAPPINGS'] = $myColKey;
							
							// Append Fieldname
							$content['SUBMAPPINGS'] .= "=>" . $myMappingFieldName;
						}
						else
						{
							// Report error!
							$content['ISEDITORNEWDBMP'] = false;
							$content['ISERROR'] = true;
							$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBMP_ERROR_MISSINGFIELDNAME'], $myColKey );  

							// Abort loop
							break;
						}
					}
					
					// Only perform if no error occured
					if ( !isset($content['ISERROR']) ) 
					{
						// Add custom search now!
						$sqlquery = "INSERT INTO " . DB_MAPPINGS. " (DisplayName, Mappings) 
						VALUES ('" . $content['DisplayName'] . "', 
								'" . $content['SUBMAPPINGS'] . "' 
								)";
						$result = DB_Query($sqlquery);
						DB_FreeQuery($result);
						
						// Do the final redirect
						RedirectResult( GetAndReplaceLangStr( $content['LN_DBMP_HASBEENADDED'], DB_StripSlahes($content['DisplayName']) ) , "dbmappings.php" );
					}
				}
				else
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = $content['LN_DBMP_ERROR_NOCOLUMNS']; 
				}
			}
			else if ( $_POST['op'] == "editdbmp" )
			{
				// Now we convert fr DB insert!
				$content['DisplayName'] = DB_RemoveBadChars($_POST['DisplayName']);

				$result = DB_Query("SELECT ID FROM " . DB_MAPPINGS . " WHERE ID = " . $content['DBMPID']);
				$myrow = DB_GetSingleRow($result, true);
				if ( !isset($myrow['ID']) )
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBMP_ERROR_IDNOTFOUND'], $content['DBMPID'] ); 
				}
				else
				{
					// Create Columnlist comma seperated!
					if ( isset($_POST['Mappings']) && is_array($_POST['Mappings']) )
					{
						// Copy columns ID's
						unset($content['SUBMAPPINGS']);
						foreach ($_POST['Mappings'] as $myColKey)
						{
							if ( isset($_POST[$myColKey]) && strlen($_POST[$myColKey]) > 0 ) 
							{
								// Get FieldName
								$myMappingFieldName = DB_StripSlahes($_POST[$myColKey]);

								if ( isset($content['SUBMAPPINGS']) ) 
									$content['SUBMAPPINGS'] .= "," . $myColKey;
								else
									$content['SUBMAPPINGS'] = $myColKey;
								
								// Append Fieldname
								$content['SUBMAPPINGS'] .= "=>" . $myMappingFieldName;
							}
							else
							{
								// Report error!
								$content['ISEDITORNEWDBMP'] = false;
								$content['ISERROR'] = true;
								$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_DBMP_ERROR_MISSINGFIELDNAME'], $myColKey );  

								// Abort loop
								break;
							}
						}

						// Only perform if no error occured
						if ( !isset($content['ISERROR']) ) 
						{
							// Edit the Search Entry now!
							$result = DB_Query("UPDATE " . DB_MAPPINGS . " SET 
								DisplayName = '" . $content['DisplayName'] . "', 
								Mappings = '" . $content['SUBMAPPINGS'] . "' 
								WHERE ID = " . $content['DBMPID']);
							DB_FreeQuery($result);

							// Done redirect!
							RedirectResult( GetAndReplaceLangStr( $content['LN_DBMP_HASBEENEDIT'], DB_StripSlahes($content['DisplayName']) ) , "dbmappings.php" );
						}
					}
					else
					{
						$content['ISERROR'] = true;
						$content['ERROR_MSG'] = $content['LN_DBMP_ERROR_NOCOLUMNS']; 
					}
				}
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Searches
	$content['LISTDBMAPPINGS'] = "true";

	// Copy Views array for further modifications
	$content['DBMP'] = $dbmapping;

	// --- Process Views
	$i = 0; // Help counter!
	foreach ($content['DBMP'] as &$myMappings )
	{
		// So internal Views can not be edited but seen
		if ( is_numeric($myMappings['ID']) )
		{
			$myMappings['ActionsAllowed'] = true;

			// --- Set Image for Type
			$myMappings['ViewTypeImage'] = $content["MENU_GLOBAL"];
			$myMappings['ViewTypeText'] = $content["LN_GEN_GLOBAL"];

			// Check if is ADMIN User, deny if normal user!
			if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
				$myMappings['ActionsAllowed'] = false;
			// ---
		}
		else
		{
			$myMappings['ActionsAllowed'] = false;

			$myMappings['ViewTypeImage'] = $content["MENU_INTERNAL"];
			$myMappings['ViewTypeText'] = $content["LN_GEN_INTERNAL"];
		}

		// --- Add DisplayNames to columns
		$iBegin = true;
		foreach ($myMappings['DBMAPPINGS'] as $myKey => &$myMapping )
		{
			// Set Fieldcaption
			if ( isset($fields[$myKey]) && isset($fields[$myKey]['FieldCaption']) )
				$myMappings['MYMAPPINGS'][$myKey]['FieldCaption'] = $fields[$myKey]['FieldCaption'];
			else
				$myMappings['MYMAPPINGS'][$myKey]['FieldCaption'] = $myKey;
			
			// Set other fields
			$myMappings['MYMAPPINGS'][$myKey]['FieldID'] = $myKey;
			$myMappings['MYMAPPINGS'][$myKey]['FieldMapping'] = $myMapping;
			
			// Set seperator
			if ( $iBegin )
			{
				$myMappings['MYMAPPINGS'][$myKey]['CaptionSeperator'] = "";
				$iBegin = false;
			}
			else
				$myMappings['MYMAPPINGS'][$myKey]['CaptionSeperator'] = ", ";
		}
		// ---

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$myMappings['cssclass'] = "line1";
		else
			$myMappings['cssclass'] = "line2";
		$i++;
		// --- 
	}
	// --- 
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_DBMAPPINGOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_dbmappings.html");
$page -> output(); 
// --- 

?>