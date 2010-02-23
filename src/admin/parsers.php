<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Search Admin File											
	*																	
	* -> Helps administrating message parsers
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
					$_GET['op'] == "initparser" || 
					$_GET['op'] == "removeparser" 
				)
			)	
		)
		DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_READONLY'] );
}
// --- 

// --- BEGIN Custom Code
// First of all init List of Parsers!
InitMessageParsers();

if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "details") 
	{
		// Set Mode to edit
		$content['ISSHOWDETAILS'] = "true";
//		$content['SEARCH_FORMACTION'] = "editsearch";
//		$content['SEARCH_SENDBUTTON'] = $content['LN_SEARCH_EDIT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ParserID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['PARSERS'][ $content['ParserID'] ]) )
			{
				// Get Reference to parser!
				$myParser = $content['PARSERS'][ $content['ParserID'] ];

				$content['DisplayName'] = $myParser['DisplayName'];
				$content['Description'] = $myParser['Description'];
				
				if ( strlen($myParser['ParserHelpArticle']) > 0 ) 
				{
					$content['EnableHelpArticle'] = true;
					$content['ParserHelpArticle'] = $myParser['ParserHelpArticle'];
				}
				
				// check for custom fields
				if ( isset($myParser['CustomFieldsList']) && count($myParser['CustomFieldsList']) > 0 ) 
				{
					// Needs custom fields!
					$content['EnableCustomField'] = true;
					$content['CustomFieldsList'] = $myParser['CustomFieldsList'];

					foreach( $myParser['CustomFieldsList'] as $myField ) 
					{
						// check if field is in define list!
						if ( !array_key_exists($myField['FieldID'], $fields) ) 
						{
							$content['ParserInitEnabled'] = true;
							break;
						}
						else
						{
							$content['ParserDeleteEnabled'] = true;
							break;
						}
					}
				}

			}
			else
			{
				$content['ISSHOWDETAILS'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_PARSERS_ERROR_IDNOTFOUND'], $content['ParserID'] );
			}
		}
		else
		{
			$content['ISSHOWDETAILS'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] =  $content['LN_PARSERS_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "removeparser") 
	{
		// --- Only if the user is an admin!
		if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
			DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_NOTALLOWED'] );
		// ---

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ParserID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['PARSERS'][ $content['ParserID'] ]) )
			{
				// Get Reference to parser!
				$myParser = $content['PARSERS'][ $content['ParserID'] ];

				// --- Ask for deletion first!
				if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
				{
					// This will print an additional secure check which the user needs to confirm and exit the script execution.
					PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_PARSERS_WARNREMOVE'], $myParser['DisplayName'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
				}
				// ---

				// Check if we have fields to delete
				if ( isset($myParser['CustomFieldsList']) && count($myParser['CustomFieldsList']) > 0 ) 
				{
					// Helper counter
					$deletedFields = 0;

					// Loop through all custom fields!
					foreach( $myParser['CustomFieldsList'] as $myField ) 
					{
						// check if field is in define list!
						if ( array_key_exists($myField['FieldID'], $fields) ) 
						{
							$result = DB_Query( "DELETE FROM " . DB_FIELDS . " WHERE FieldID = '" . $myField['FieldID'] . "'");
							DB_FreeQuery($result);

							// increment counter
							$deletedFields++;
						}
					}

					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_PARSERS_ERROR_HASBEENREMOVED'], $myParser['DisplayName'], $deletedFields ) , "parsers.php" );
				}
				else
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_PARSERS_ERROR_NOFIELDS'], $content['ParserID'] );
				}
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_PARSERS_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "initparser") 
	{
		// --- Only if the user is an admin!
		if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
			DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_NOTALLOWED'] );
		// ---

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ParserID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['PARSERS'][ $content['ParserID'] ]) )
			{
				// Get Reference to parser!
				$myParser = $content['PARSERS'][ $content['ParserID'] ];

				// check for custom fields
				if ( isset($myParser['CustomFieldsList']) && count($myParser['CustomFieldsList']) > 0 ) 
				{
					// Helper counter
					$addedFields = 0;

					// Loop through all custom fields!
					foreach( $myParser['CustomFieldsList'] as $myField ) 
					{
						// check if field is in define list!
						if ( !array_key_exists($myField['FieldID'], $fields) ) 
						{
							// Add field into DB!
							$sqlquery = "INSERT INTO " . DB_FIELDS . " (FieldID, FieldCaption, FieldDefine, SearchField, FieldAlign, DefaultWidth, FieldType, SearchOnline) 
							VALUES (
									'" . $myField['FieldID'] . "', 
									'" . $myField['FieldCaption'] . "',
									'" . $myField['FieldDefine'] . "',
									'" . $myField['SearchField'] . "',
									'" . $myField['FieldAlign'] . "', 
									" . $myField['DefaultWidth'] . ", 
									" . $myField['FieldType'] . ", 
									" . $myField['SearchOnline'] . " 
									)";
							$result = DB_Query($sqlquery);
							DB_FreeQuery($result);

							// increment counter
							$addedFields++;
						}
					}

					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_PARSERS_ERROR_HASBEENADDED'], $myParser['DisplayName'], $addedFields ) , "parsers.php" );
				}
				else
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_PARSERS_ERROR_NOFIELDS'], $content['ParserID'] );
				}
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_PARSERS_ERROR_IDNOTFOUND'], $content['ParserID'] );
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_PARSERS_ERROR_INVALIDID'];
		}
	}
}

// Default mode!
if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	if ( isset($content['PARSERS']) ) 
	{
		// Default Mode = List Searches
		$content['LISTPARSERS'] = "true";

		$i = 0; // Help counter!
		foreach ($content['PARSERS'] as &$myParser )
		{
			// Set if help link is enabled
			if ( strlen($myParser['ParserHelpArticle']) > 0 ) 
				$myParser['ParserHelpEnabled'] = true;
			else
				$myParser['ParserHelpEnabled'] = false;
			
			// Find out if we need to INIT something!
			if ( isset($myParser['CustomFieldsList']) && count($myParser['CustomFieldsList']) > 0 ) 
			{
				foreach( $myParser['CustomFieldsList'] as $myField ) 
				{
					// check if field is in define list!
					if ( !array_key_exists($myField['FieldID'], $fields) ) 
					{
						$myParser['ParserInitEnabled'] = true;
						break;
					}
					else
					{
						$myParser['ParserDeleteEnabled'] = true;
						break;
					}
				}
			}

			// --- Set CSS Class
			if ( $i % 2 == 0 )
				$myParser['cssclass'] = "line1";
			else
				$myParser['cssclass'] = "line2";
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