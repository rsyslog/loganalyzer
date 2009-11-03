<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Search Admin File											
	*																	
	* -> Helps administrating report modules 
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

// Firts of all init List of Parsers!
InitReportModules();

// Handle GET requests
if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "details") 
	{
		// Set Mode to edit
		$content['ISSHOWDETAILS'] = "true";

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ReportID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['REPORTS'][ $content['ReportID'] ]) )
			{
				// Get Reference to parser!
				$myReport = $content['REPORTS'][ $content['ReportID'] ];

				$content['DisplayName'] = $myReport['DisplayName'];
				$content['Description'] = $myReport['Description'];
				
				if ( strlen($myReport['ReportHelpArticle']) > 0 ) 
				{
					$content['EnableHelpArticle'] = true;
					$content['ReportHelpArticle'] = $myReport['ReportHelpArticle'];
				}
				
				// check for custom fields
				if ( $myReport['NeedsInit'] ) // && count($myReport['CustomFieldsList']) > 0 ) 
				{
					// Needs custom fields!
					$content['EnableNeedsInit'] = true;

					if ( $myReport['Initialized'] ) 
					{
						$content['InitEnabled'] = false;
						$content['DeleteEnabled'] = true;
					}
					else
					{
						$content['InitEnabled'] = true;
						$content['DeleteEnabled'] = false;
					}
				}

				// --- Check for saved reports!
				if ( isset($myReport['SAVEDREPORTS']) && count($myReport['SAVEDREPORTS']) > 0 )
				{
					$content['HASSAVEDREPORTS'] = "true";
					$content['SavedReportRowSpan'] = ( count($myReport['SAVEDREPORTS']) + 1);
					$content['SAVEDREPORTS'] = $myReport['SAVEDREPORTS'];

					$i = 0; // Help counter!
					foreach ($content['SAVEDREPORTS']  as &$mySavedReport )
					{
						// --- Set CSS Class
						if ( $i % 2 == 0 )
							$mySavedReport['srcssclass'] = "line1";
						else
							$mySavedReport['srcssclass'] = "line2";
						$i++;
						// --- 
					}
				}
				// ---

			}
			else
			{
				$content['ISSHOWDETAILS'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_IDNOTFOUND'], $content['ReportID'] );
			}
		}
		else
		{
			$content['ISSHOWDETAILS'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] =  $content['LN_REPORTS_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "removereport") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ReportID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['REPORTS'][ $content['ReportID'] ]) )
			{
				// Get Reference to parser!
				$myReport = $content['REPORTS'][ $content['ReportID'] ];

				if ( !$myReport["NeedsInit"] ) 
				{
					// Do the final redirect
					RedirectResult( GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_REPORTDOESNTNEEDTOBEREMOVED'], $myReport['DisplayName'] ) , "reports.php" );
				}

				// --- Ask for deletion first!
				if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
				{
					// This will print an additional secure check which the user needs to confirm and exit the script execution.
					PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_REPORTS_WARNREMOVE'], $myReport['DisplayName'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
				}
				// ---

				// TODO WHATEVER
/*				// Check if we have fields to delete
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
				*/

			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "initreport") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ReportID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['REPORTS'][ $content['ReportID'] ]) )
			{
				// Get Reference to parser!
				$myParser = $content['REPORTS'][ $content['ReportID'] ];

				// TODO WHATEVER
				/*
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
				*/
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_IDNOTFOUND'], $content['ReportID'] );
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "addsavedreport") 
	{
		// Set Mode to add
//		$content['ISSHOWDETAILS'] = "true";
		$content['ISADDSAVEDREPORT'] = "true";
		$content['REPORT_FORMACTION'] = "addsavedreport";
		$content['REPORT_SENDBUTTON'] = $content['LN_REPORTS_ADDSAVEDREPORT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ReportID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['REPORTS'][ $content['ReportID'] ]) )
			{
				// Get Reference to parser!
				$myReport = $content['REPORTS'][ $content['ReportID'] ];
				
				// Set Report properties
				$content['DisplayName'] = $myReport['DisplayName'];
				$content['Description'] = $myReport['Description'];
				
				// Set defaults for report
				$content['SavedReportID'] = "";
				$content['customTitle'] = $myReport['DisplayName'];
				$content['customComment'] = "";
				$content['filterString'] = ""; 
				$content['customFilters'] = ""; 

				// Copy Sources array for further modifications
				global $currentSourceID;
				$content['SOURCES'] = $content['Sources'];
				foreach ($content['SOURCES'] as &$mySource )
				{
					$mySource['SourceID'] = $mySource['ID'];
					if ( $mySource['ID'] == $currentSourceID ) 
						$mySource['sourceselected'] = "selected";
					else
						$mySource['sourceselected'] = "";
				}
				
				// Create Outputlist
				$content['outputFormat'] = REPORT_OUTPUT_HTML; 
				CreateOutputformatList( $content['outputFormat'] );
				
				// Other settings ... TODO!
				$content['customFilters'] = "";
				$content['outputTarget'] = "";
				$content['scheduleSettings'] = "";
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_INVALIDID'];
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "editsavedreport") 
	{
		// Set Mode to add
		$content['ISADDSAVEDREPORT'] = "true";
		$content['REPORT_FORMACTION'] = "editsavedreport";
		$content['REPORT_SENDBUTTON'] = $content['LN_REPORTS_EDITSAVEDREPORT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ReportID'] = DB_RemoveBadChars($_GET['id']);
			if ( isset($content['REPORTS'][ $content['ReportID'] ]) )
			{
				// Get Reference to report!
				$myReport = $content['REPORTS'][ $content['ReportID'] ];

				// Now Get data from saved report!
				$content['SavedReportID'] = DB_RemoveBadChars($_GET['savedreportid']);

				if ( isset($myReport['SAVEDREPORTS'][$content['SavedReportID']]) ) 
				{
					// Get Reference to savedreport!
					$mySavedReport = $myReport['SAVEDREPORTS'][$content['SavedReportID']];

					// Set Report properties
					$content['DisplayName'] = $myReport['DisplayName'];
					$content['Description'] = $myReport['Description'];
					
					// Set defaults for Savedreport
					$content['customTitle'] = $mySavedReport['customTitle'];
					$content['customComment'] = $mySavedReport['customComment'];
					$content['filterString'] = $mySavedReport['filterString'];
					$content['customFilters'] = $mySavedReport['customFilters'];

					// Copy Sources array for further modifications
					$content['SOURCES'] = $content['Sources'];
					foreach ($content['SOURCES'] as &$mySource )
					{
						$mySource['SourceID'] = $mySource['ID'];
						if ( $mySource['ID'] == $mySavedReport['sourceid'] ) 
							$mySource['sourceselected'] = "selected";
						else
							$mySource['sourceselected'] = "";
					}
					
					// Create Outputlist
					$content['outputFormat'] = $mySavedReport['outputFormat']; 
					CreateOutputformatList( $content['outputFormat'] );
					
					// Other settings ... TODO!
					$content['customFilters'] = "";
					$content['outputTarget'] = "";
					$content['scheduleSettings'] = "";
				}
				else
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_INVALIDSAVEDREPORTID'];
				}
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_INVALIDID'];
			}
		}
	}
	else if ($_GET['op'] == "removesavedreport") 
	{
		// Get SavedReportID!
		if ( isset($_GET['savedreportid']) )
		{
			//PreInit these values 
			$content['SavedReportID'] = DB_RemoveBadChars($_GET['savedreportid']);

			// Get GroupInfo
			$result = DB_Query("SELECT customTitle FROM " . DB_SAVEDREPORTS . " WHERE ID = " . $content['SavedReportID'] ); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['customTitle']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_SAVEDREPORTIDNOTFOUND'], $content['SavedReportID'] ); 
			}
			else
			{
				// --- Ask for deletion first!
				if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
				{
					// This will print an additional secure check which the user needs to confirm and exit the script execution.
					PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_REPORTS_WARNDELETESAVEDREPORT'], $myrow['customTitle'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
				}
				// ---

				// do the delete!
				$result = DB_Query( "DELETE FROM " . DB_SAVEDREPORTS . " WHERE ID = " . $content['SavedReportID'] );
				if ($result == FALSE)
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_DELSAVEDREPORT'], $content['SavedReportID'] ); 
				}
				else
					DB_FreeQuery($result);

				// Do the final redirect
				RedirectResult( GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_HASBEENDEL'], $myrow['customTitle'] ) , "reports.php" );
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_INVALIDSAVEDREPORTID'];
		}
	}
}

// Handle POST requests
if ( isset($_POST['op']) )
{
	// Get ReportID!
	if ( isset($_POST['id']) ) { $content['ReportID'] = DB_RemoveBadChars($_POST['id']); } else {$content['ReportID'] = ""; }


	// Only Continue if reportid is valud!
	if ( isset($content['REPORTS'][ $content['ReportID'] ]) )
	{
		// Get Reference to parser!
		$myReport = $content['REPORTS'][ $content['ReportID'] ];

		// Get SavedReportID!
		if ( isset($_POST['savedreportid']) ) { $content['SavedReportID'] = DB_RemoveBadChars($_POST['savedreportid']); } else {$content['SavedReportID'] = ""; }

		// Read parameters
		if ( isset($_POST['SourceID']) ) { $content['SourceID'] = DB_RemoveBadChars($_POST['SourceID']); }
		if ( isset($_POST['report_customtitle']) ) { $content['customTitle'] = DB_RemoveBadChars($_POST['report_customtitle']); } else {$content['report_customtitle'] = ""; }
		if ( isset($_POST['report_customcomment']) ) { $content['customComment'] = DB_RemoveBadChars($_POST['report_customcomment']); } else {$content['report_customcomment'] = ""; }
		if ( isset($_POST['report_filterString']) ) { $content['filterString'] = DB_RemoveBadChars($_POST['report_filterString']); } else {$content['report_filterString'] = ""; }
		if ( isset($_POST['outputFormat']) ) { $content['outputFormat'] = DB_RemoveBadChars($_POST['outputFormat']); }
		
		// TODO!
		// customFilters, outputTarget, scheduleSettings
		$content['customFilters'] = "";
		$content['outputTarget'] = "";
		$content['scheduleSettings'] = "";

		// --- Check mandotary values
		if ( $content['customTitle'] == "" )
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_MISSINGPARAM'], $content['LN_REPORTS_CUSTOMTITLE'] );
		}
		else if ( !isset($content['SourceID']) ) 
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_MISSINGPARAM'], $content['LN_REPORTS_SOURCEID'] );
		}
		else if ( !isset($content['outputFormat']) ) 
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_MISSINGPARAM'], $content['LN_REPORTS_OUTPUTFORMAT'] );
		}
		// --- 


		// --- Now Verify Report Source!
		// Create tmpSavedReport!
		$tmpSavedReport["SavedReportID"] = $content['customFilters'];
		$tmpSavedReport["sourceid"] = $content['SourceID'];
		$tmpSavedReport["customTitle"] = $content['customTitle'];
		$tmpSavedReport["customComment"] = $content['customComment'];
		$tmpSavedReport["filterString"] = $content['filterString'];
		$tmpSavedReport["customFilters"] = $content['customFilters'];
		$tmpSavedReport["outputFormat"] = $content['outputFormat'];
		$tmpSavedReport["outputTarget"] = $content['outputTarget'];
		$tmpSavedReport["scheduleSettings"] = $content['scheduleSettings'];

		// Get Objectreference to report
		$myReportObj = $myReport["ObjRef"];

		// Set SavedReport Settings!
		$myReportObj->InitFromSavedReport($tmpSavedReport);

		// Perform check
		$res = $myReportObj->verifyDataSource();
		if ( $res != SUCCESS ) 
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_ERRORCHECKINGSOURCE'], GetAndReplaceLangStr( GetErrorMessage($res), $content['SourceID']) );
			if ( isset($extraErrorDescription) )
				$content['ERROR_MSG'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
		}
		// ---


		// --- Now ADD/EDIT do the processing!
		if ( !isset($content['ISERROR']) ) 
		{	
			// Everything was alright, so we go to the next step!
			if ( $_POST['op'] == "addsavedreport" )
			{
				// Add custom search now!
				$sqlquery = "INSERT INTO " . DB_SAVEDREPORTS . " (reportid, sourceid, customTitle, customComment, filterString, customFilters, outputFormat, outputTarget, scheduleSettings) 
				VALUES ('" . $content['ReportID'] . "', 
						" . $content['SourceID'] . ", 
						'" . $content['customTitle'] . "', 
						'" . $content['customComment'] . "', 
						'" . $content['filterString'] . "', 
						'" . $content['customFilters'] . "', 
						'" . $content['outputFormat'] . "', 
						'" . $content['outputTarget'] . "', 
						'" . $content['scheduleSettings'] . "'
						)";

				$result = DB_Query($sqlquery);
				DB_FreeQuery($result);

				// Do the final redirect
				RedirectResult( GetAndReplaceLangStr( $content['LN_REPORTS_HASBEENADDED'], DB_StripSlahes($content['customTitle']) ) , "reports.php" );
			}
			else if ( $_POST['op'] == "editsavedreport" )
			{
				$result = DB_Query("SELECT ID FROM " . DB_SAVEDREPORTS . " WHERE ID = " . $content['SavedReportID']);
				$myrow = DB_GetSingleRow($result, true);
				if ( !isset($myrow['ID']) )
				{
					$content['ISERROR'] = true;
					$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_SAVEDREPORTIDNOTFOUND'], $content['SavedReportID'] ); 
				}
				else
				{
					$sqlquery =	"UPDATE " . DB_SAVEDREPORTS . " SET 
									sourceid = " . $content['SourceID'] . ", 
									customTitle = '" . $content['customTitle'] . "', 
									customComment = '" . $content['customComment'] . "', 
									filterString = '" . $content['filterString'] . "', 
									customFilters = '" . $content['customFilters'] . "', 
									outputFormat = '" . $content['outputFormat'] . "', 
									outputTarget = '" . $content['outputTarget'] . "', 
									scheduleSettings = '" . $content['scheduleSettings'] . "' 
									WHERE ID = " . $content['SavedReportID'];

					$result = DB_Query($sqlquery);
					DB_FreeQuery($result);

					// Done redirect!
					RedirectResult( GetAndReplaceLangStr( $content['LN_REPORTS_HASBEENEDIT'], DB_StripSlahes($content['customTitle']) ) , "reports.php" );
				}
			}

		}
	}
	else
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_REPORTS_ERROR_IDNOTFOUND'], $content['ReportID'] ); 
	}
}

// Default mode!
if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	if ( isset($content['REPORTS']) ) 
	{
		// Default Mode = List Searches
		$content['LISTREPORTS'] = "true";

		$i = 0; // Help counter!
		foreach ($content['REPORTS'] as &$myReport )
		{
			// Set if help link is enabled
			if ( strlen($myReport['ReportHelpArticle']) > 0 ) 
				$myReport['ReportHelpEnabled'] = true;
			else
				$myReport['ReportHelpEnabled'] = false;

			// check for custom fields
			if ( $myReport['NeedsInit'] ) // && count($myReport['CustomFieldsList']) > 0 ) 
			{
				// Needs custom fields!
				$myReport['EnableNeedsInit'] = true;

				if ( $myReport['Initialized'] ) 
				{
					$myReport['InitEnabled'] = false;
					$myReport['DeleteEnabled'] = true;
				}
				else
				{
					$myReport['InitEnabled'] = true;
					$myReport['DeleteEnabled'] = false;
				}
			}

			// --- Set CSS Class
			if ( $i % 2 == 0 )
				$myReport['cssclass'] = "line1";
			else
				$myReport['cssclass'] = "line2";
			$i++;
			// --- 

			// --- Check for saved reports!
			if ( isset($myReport['SAVEDREPORTS']) && count($myReport['SAVEDREPORTS']) > 0 )
			{
				$myReport['HASSAVEDREPORTS'] = "true";
				$myReport['SavedReportRowSpan'] = ( count($myReport['SAVEDREPORTS']) + 1);

				$j = 0; // Help counter!
				foreach ($myReport['SAVEDREPORTS']  as &$mySavedReport )
				{
					// --- Set CSS Class
					if ( $j % 2 == 0 )
						$mySavedReport['srcssclass'] = "line1";
					else
						$mySavedReport['srcssclass'] = "line2";
					$i++;
					// --- 
				}
			}
			// ---
		}
	}
	else
	{
		$content['LISTREPORTS'] = "false";
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_NOREPORTS']; 
	}
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_REEPORTSOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_reports.html");
$page -> output(); 
// --- 

?>