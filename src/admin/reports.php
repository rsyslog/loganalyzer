<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Search Admin File											
	*																	
	* -> Helps administrating report modules 
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
					$_GET['op'] == "initreport" || 
					$_GET['op'] == "removereport" ||
					$_GET['op'] == "addsavedreport" ||
					$_GET['op'] == "removesavedreport"
				)
			)	
		)
		DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_READONLY'] );
}
// --- 

// --- BEGIN Custom Code
// Hardcoded settings
define('URL_ONLINEREPORTS', 'http://tools.adiscon.net/listreports.php');
$content['OPTIONAL_TITLE'] = "";

// Firts of all init List of Reports!
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

				$content['Category'] = $myReport['Category'];
				$content['DisplayName'] = $myReport['DisplayName'];
				$content['Description'] = $myReport['Description'];
				
				if ( strlen($myReport['ReportHelpArticle']) > 0 ) 
				{
					$content['EnableHelpArticle'] = true;
					$content['ReportHelpArticle'] = $myReport['ReportHelpArticle'];
				}

				// check for custom fields
				
				if ( isset($myReport['RequiredFieldsList']) && count($myReport['RequiredFieldsList']) > 0 ) 
				{
					// Needs custom fields!
					$content['EnableRequiredFields'] = true;
					// $content['CustomFieldsList'] = $myParser['CustomFieldsList'];

					foreach( $myReport['RequiredFieldsList'] as $myField ) 
					{
						if ( isset($fields[$myField]) ) 
							$content['RequiredFieldsList'][$myField] = array ("FieldID" => $myField, "FieldDefine" => $fields[$myField]["FieldDefine"], "FieldCaption" => $fields[$myField]["FieldCaption"] );  
						else
							$content['RequiredFieldsList'][$myField] = array ("FieldID" => $myField, "FieldDefine" => $myField, "FieldCaption" => $myField );  
					}
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
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['ReportID'] = DB_RemoveBadChars($_GET['id']);

			// Init Form variables 
			$content['ISADDSAVEDREPORT'] = "true";
			$content['REPORT_FORMACTION'] = "addsavedreport";
			$content['REPORT_SENDBUTTON'] = $content['LN_REPORTS_ADDSAVEDREPORT'];
			$content['FormUrlAddOP'] = "?op=addsavedreport&id=" . $content['ReportID'];
			
			// Check if report exists
			if ( isset($content['REPORTS'][ $content['ReportID'] ]) )
			{
				// Get Reference to parser!
				$myReport = $content['REPORTS'][ $content['ReportID'] ];
				
				// Set Extra language strings
				$content['REPORTS_DETAILSFOR'] = GetAndReplaceLangStr( $content['LN_REPORTS_DETAILSFOR'], $content['ReportID'] ); 

				// Set Report properties
				$content['DisplayName'] = $myReport['DisplayName'];
				$content['Description'] = $myReport['Description'];
				
				// Set defaults for report
				$content['SavedReportID'] = "";
				$content['customTitle'] = $myReport['DisplayName'];
				$content['customComment'] = "";
				$content['filterString'] = ""; 
				
				// Init Custom Filters
				InitCustomFilterDefinitions($myReport, "");
//				$content['customFilters'] = ""; 

				// Copy Sources array for further modifications
				global $currentSourceID;
				$content['SOURCES'] = $content['Sources'];
				foreach ($content['SOURCES'] as &$mySource )
				{
					$mySource['SourceID'] = $mySource['ID'];
					if ( $mySource['ID'] == $currentSourceID ) 
					{
						$content['SourceID'] = $currentSourceID; 
						$mySource['sourceselected'] = "selected";
					}
					else
						$mySource['sourceselected'] = "";
				}
				
				// Create Outputlist
				$content['outputFormat'] = REPORT_OUTPUT_HTML; 
				CreateOutputformatList( $content['outputFormat'] );
				
				// Create Outputtargetlist
				$content['outputTarget'] = REPORT_TARGET_STDOUT; 
				CreateOutputtargetList( $content['outputTarget'] );
				
				// Init other outputTarget properties
				$content['outputTarget_filename'] = "";
				
				// Create visible CronCommand
				$content['cronCommand'] = CreateCronCommand( $content['ReportID'] );

				// Other settings ... TODO!
//				$content['customFilters'] = "";
//				$content['outputTarget'] = "";
//				$content['scheduleSettings'] = "";
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

				// Set Extra language strings
				$content['REPORTS_DETAILSFOR'] = GetAndReplaceLangStr( $content['LN_REPORTS_DETAILSFOR'], $content['ReportID'] ); 

				// Now Get data from saved report!
				$content['SavedReportID'] = DB_RemoveBadChars($_GET['savedreportid']);

				if ( isset($myReport['SAVEDREPORTS'][$content['SavedReportID']]) ) 
				{
					// Get Reference to savedreport!
					$mySavedReport = $myReport['SAVEDREPORTS'][$content['SavedReportID']];
					
					// Subform helper
					$content['FormUrlAddOP'] = "?op=editsavedreport&id=" . $content['ReportID'] . "&savedreportid=" . $content['SavedReportID'];

					// Set Report properties
					$content['DisplayName'] = $myReport['DisplayName'];
					$content['Description'] = $myReport['Description'];
					
					// Set defaults for Savedreport
					$content['customTitle'] = $mySavedReport['customTitle'];
					$content['customComment'] = $mySavedReport['customComment'];
					$content['filterString'] = $mySavedReport['filterString'];

					// Init Custom Filters
					InitCustomFilterDefinitions($myReport, $mySavedReport['customFilters']);
//					$content['customFilters'] = $mySavedReport['customFilters'];

					// Copy Sources array for further modifications
					$content['SOURCES'] = $content['Sources'];
					foreach ($content['SOURCES'] as &$mySource )
					{
						$mySource['SourceID'] = $mySource['ID'];
						if ( $mySource['ID'] == $mySavedReport['sourceid'] ) 
						{
							$mySource['sourceselected'] = "selected";
							$content['SourceID'] = $mySavedReport['sourceid']; 
						}
						else
							$mySource['sourceselected'] = "";
					}
					
					// Create Outputlist
					$content['outputFormat'] = $mySavedReport['outputFormat']; 
					CreateOutputformatList( $content['outputFormat'] );

					// Create Outputtargetlist
					$content['outputTarget'] = $mySavedReport['outputTarget']; 
					CreateOutputtargetList( $content['outputTarget'] );

					// Init other outputTarget properties
					$content['outputTarget_filename'] = "";
					InitOutputtargetDefinitions($myReport, $mySavedReport['outputTargetDetails']);
					
					// Create visible CronCommand
					$content['cronCommand'] = CreateCronCommand( $content['ReportID'], $content['SavedReportID'] );

					// Other settings ... TODO!
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
	else if ($_GET['op'] == "onlinelist") 
	{
		// Set Mode to list Online Reports
		$content['LISTONLINEREPORTS'] = "true";
		$content['OPTIONAL_TITLE'] = " - " . $content['LN_REPORTMENU_ONLINELIST'];

		if ( InitOnlineReports() ) 
		{
			$j = 0; // Help counter!
			foreach ($content['ONLINEREPORTS']  as &$myOnlineReport )
			{
				// Split reportID
				preg_match("/report\.(.*?)\.(.*?)\.class$/", $myOnlineReport['reportid'], $out );
				$myOnlineReport['reportcat'] = $out[1]; 
				$myOnlineReport['reportid'] = $out[2]; 

				// Set Installed Flag!
				$myOnlineReport['installed'] = false;
				foreach($content['REPORTS'] as $myReport)
				{
					// check if already installed!
					if ( $myOnlineReport['reportid'] == $myReport['ID'] ) 
						$myOnlineReport['installed'] = true;
				}

				// --- Set Icon!
				if ( $myOnlineReport['installed'] ) 
				{
					$myOnlineReport['installed_icon'] = $content['MENU_CHECKED']; 
					$myOnlineReport['installed_text'] = $content['LN_REPORTS_INSTALLED']; 
				}
				else
				{
					$myOnlineReport['installed_icon'] = $content['MENU_DELETE']; 
					$myOnlineReport['installed_text'] = $content['LN_REPORTS_NOTINSTALLED']; 
				}
				// --- 

				// --- Set CSS Class
				if ( $j % 2 == 0 )
					$myOnlineReport['cssclass'] = "line1";
				else
					$myOnlineReport['cssclass'] = "line2";
				$j++;
				// --- 
			}
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_REPORTS_ERROR_ONLINELIST'];
		}
	}
}


// --- Additional work regarding FilterDisplay todo for the edit view
if ( isset($content['ISADDSAVEDREPORT']) && $content['ISADDSAVEDREPORT'] )
{
	// If Filters are send using POST we read them and create a FilterSting!

	// Init Filterstring variable
	$szFilterString = ""; 
	
	if (	strlen($content['filterString']) > 0 && 
			!isset($_POST['subop']) && 
			!isset($_POST['subop_edit']) && 
			!isset($_POST['subop_delete']) && 
			isset($_POST['report_filterString']) && $content['filterString'] != $_POST['report_filterString'] ) 
	{
		// Overwrite filterString from form data instead of filter array!
		$content['filterString'] = DB_RemoveBadChars($_POST['report_filterString']);
	}
	else
	{
		// Process POST data!
		if ( isset($_POST['Filters']) )
		{
			// Get Filter array
			$AllFilters = $_POST['Filters'];
			
			// Loop through filters and build filterstring!
			$i = 0;
			foreach( $AllFilters as $tmpFilterID )
			{
				if ( isset($_POST['subop_delete']) && $_POST['subop_delete'] == $i )
				{
					// Do nothing, user wants this filter to be deleted!
				}
				else
				{
					// Get Comparison Bit
					if ( isset($_POST['newcomparison_' . $i]) ) 
						$tmpComparison = DB_RemoveBadChars($_POST['newcomparison_' . $i]); 
					else
						$tmpComparison = 0; // Default bit

					// Get FilterValue
					if ( isset($_POST['FilterValue_' . $i]) ) 
						$tmpFilterValue = DB_RemoveBadChars($_POST['FilterValue_' . $i]); 
					else
						$tmpFilterValue = ""; // Default value

					// Get Filtertype from FilterID
					if ( isset($fields[$tmpFilterID]) ) 
					{
						// Append to Filterstring
						$tmpField = $fields[ $tmpFilterID ]; 

						if ( $tmpField['FieldType'] == FILTER_TYPE_DATE ) 
						{
							// Append comparison
							switch ( $tmpComparison ) 
							{
								case 4:		// DATEMODE_RANGE_FROM
									$szFilterString .= "datefrom:"; 
									$szFilterString .= CreateTimeStampFromValues($i); 
									break; 
								case 5:		// DATEMODE_RANGE_TO
									$szFilterString .= "dateto:"; 
									$szFilterString .= CreateTimeStampFromValues($i); 
									break; 
								case 3:		// DATEMODE_LASTX
									$szFilterString .= "datelastx:"; 
									if ( isset($_POST['filter_daterange_last_x_' . $i]) ) 
										$szFilterString .= DB_RemoveBadChars($_POST['filter_daterange_last_x_' . $i]); 
									else
										$szFilterString .= DATE_LASTX_24HOURS; // Default value
									break; 
							}
						}
						else if ( $tmpField['FieldType'] == FILTER_TYPE_NUMBER ) 
						{
							// Append Fieldname
							$szFilterString .= $tmpField['SearchField']; 

							// Append comparison
							switch ( $tmpComparison ) 
							{
								case 1:		// FILTER_MODE_INCLUDE
									$szFilterString .= ":="; 
									break; 
								case 2:		// FILTER_MODE_EXCLUDE
									$szFilterString .= ":-="; 
									break; 
							}
							
							if ( $tmpFilterID == SYSLOG_SEVERITY ) 
							{
								// Append field value
								$szFilterString .= GetSeverityDisplayName($tmpFilterValue); 
							}
							else if ( $tmpFilterID == SYSLOG_FACILITY ) 
							{
								// Append field value
								$szFilterString .= GetFacilityDisplayName($tmpFilterValue); 
							}
							else
							{
								// Append field value
								$szFilterString .= $tmpFilterValue; 
							}
						}
						else if ( $tmpField['FieldType'] == FILTER_TYPE_STRING ) 
						{
							// Append Fieldname, if set!
							if (isset($tmpField['SearchField']) && strlen($tmpField['SearchField']) > 0 ) 
								$szFilterString .= $tmpField['SearchField'] . ":"; 

							// Append comparison
							switch ( $tmpComparison ) 
							{
								case 1:		// FILTER_MODE_INCLUDE
									$szFilterString .= ""; 
									break; 
								case 2:		// FILTER_MODE_EXCLUDE
									$szFilterString .= "-"; 
									break; 
								case 5:		// FILTER_MODE_INCLUDE + FILTER_MODE_SEARCHFULL
									$szFilterString .= "="; 
									break; 
								case 6:		// FILTER_MODE_EXCLUDE + FILTER_MODE_SEARCHFULL
									$szFilterString .= "-="; 
									break; 
								case 9:		// FILTER_MODE_INCLUDE + FILTER_MODE_SEARCHREGEX
									$szFilterString .= "~"; 
									break; 
								case 10:	// FILTER_MODE_EXCLUDE + FILTER_MODE_SEARCHREGEX
									$szFilterString .= "-~"; 
									break; 
							}

							// Append field value
							$szFilterString .= $tmpFilterValue; 
						}

						// Append trailing space
						$szFilterString .= " "; 
					}
				}

				//Increment Helpcounter
				$i++;
			}

	//		echo $content['filterString'] . "<br>";
	//		echo $szFilterString . "<br>"; 
	//		print_r ( $AllFilters ); 
		}
	}

	// Add new filter if wanted
	if ( isset($_POST['subop']) )
	{
		if ( $_POST['subop'] == $content['LN_REPORTS_ADDFILTER'] && isset($_POST['newfilter']) ) 
		{
			if ( isset($fields[ $_POST['newfilter'] ]) ) 
			{
				// Get Field Info
				$myNewField = $fields[ $_POST['newfilter'] ]; 

				if ( $myNewField['FieldType'] == FILTER_TYPE_DATE ) 
				{
					$szFilterString .= "datelastx:" . DATE_LASTX_24HOURS; 
				}
				else if ( $myNewField['FieldType'] == FILTER_TYPE_NUMBER ) 
				{
					// Append sample filter
					$szFilterString .= $myNewField['SearchField']. ":="; 

					if ( $myNewField['FieldID'] == SYSLOG_SEVERITY ) 
					{
						// Append field value
						$szFilterString .= GetSeverityDisplayName(SYSLOG_NOTICE); 
					}
					else if ( $myNewField['FieldID'] == SYSLOG_FACILITY ) 
					{
						// Append field value
						$szFilterString .= GetFacilityDisplayName(SYSLOG_LOCAL0); 
					}
					else
					{
						// Append sample value
						$szFilterString .= "1"; 
					}
				}
				else if ( $myNewField['FieldType'] == FILTER_TYPE_STRING ) 
				{
					// Searchfield filter
					if (isset($myNewField['SearchField']) && strlen($myNewField['SearchField']) > 0 ) 
						$szFilterString .= $myNewField['SearchField'] . ":";
					
					// Append sample
					$szFilterString .= "sample"; 
				}
			}
			// Append to Filterstring
		}
	}
	// Copy Final Filterstring if necessary
	if ( strlen($szFilterString) > 0 ) 
		$content['filterString'] = $szFilterString; 

	//	echo $content['SourceID'];
	if ( isset($content['Sources'][$content['SourceID']]['ObjRef']) ) 
	{
		// Obtain and get the Config Object
		$stream_config = $content['Sources'][$content['SourceID']]['ObjRef']; 

		// Create LogStream Object 
		$stream = $stream_config->LogStreamFactory($stream_config);
		$stream->SetFilter( $content['filterString'] );
		
		// Copy filter array
		$AllFilters = $stream->ReturnFiltersArray(); 
//		print_r( $stream->ReturnFiltersArray() ); 
	}

	if ( isset($AllFilters) )
	{
		//$AllFilters = $content['AllFilters'];
		foreach( $AllFilters as $tmpFieldId=>$tmpFieldFilters ) 
		{
			foreach( $tmpFieldFilters as $tmpFilter ) 
			{
				// Create new row
				$aNewFilter = array();

				$aNewFilter['FilterFieldID'] = $tmpFieldId; 
				$aNewFilter['FilterType'] = $tmpFilter[FILTER_TYPE]; 
				$aNewFilter['FilterValue'] = $tmpFilter[FILTER_VALUE]; 
				if ( isset($tmpFilter[FILTER_DATEMODE]) ) 
					$aNewFilter['FilterDateMode'] = $tmpFilter[FILTER_DATEMODE]; 
				if ( isset($tmpFilter[FILTER_MODE]) ) 
					$aNewFilter['FilterMode'] = $tmpFilter[FILTER_MODE]; 

//				$aNewFilter['FilterInternalID' => 1, 
//				$aNewFilter['FilterCaption' => "1", 

				// Add to filters array
				$content['SUBFILTERS'][] = $aNewFilter; 
			}
		}
	}
	
	// Init Comparison Arrays
	$ComparisonsNumber[] = array (	'ComparisonBit' => FILTER_MODE_INCLUDE, 
									'ComparisonCaption' => "=", 
									); 
	$ComparisonsNumber[] = array (	'ComparisonBit' => FILTER_MODE_EXCLUDE, 
									'ComparisonCaption' => "!=", 
									); 

	$ComparisonsString[] = array ('ComparisonBit' => FILTER_MODE_INCLUDE, 
							'ComparisonCaption' => "contains", 
							); 
	$ComparisonsString[] = array ('ComparisonBit' => FILTER_MODE_EXCLUDE, 
							'ComparisonCaption' => "does not contain", 
							); 
	$ComparisonsString[] = array ('ComparisonBit' => FILTER_MODE_INCLUDE + FILTER_MODE_SEARCHFULL, 
							'ComparisonCaption' => "equals", 
							); 
	$ComparisonsString[] = array ('ComparisonBit' => FILTER_MODE_EXCLUDE + FILTER_MODE_SEARCHFULL, 
							'ComparisonCaption' => "does not equal", 
							); 
	$ComparisonsString[] = array ('ComparisonBit' => FILTER_MODE_INCLUDE + FILTER_MODE_SEARCHREGEX, 
							'ComparisonCaption' => "matches regular expression", 
							); 
	$ComparisonsString[] = array ('ComparisonBit' => FILTER_MODE_EXCLUDE + FILTER_MODE_SEARCHREGEX, 
							'ComparisonCaption' => "does not matches regular expression", 
							); 
	
	$ComparisonsDate[] = array ('ComparisonBit' => DATEMODE_LASTX, 
								'ComparisonCaption' => "last", 
								); 
	$ComparisonsDate[] = array ('ComparisonBit' => DATEMODE_RANGE_FROM, 
								'ComparisonCaption' => "From", 
								); 
	$ComparisonsDate[] = array ('ComparisonBit' => DATEMODE_RANGE_TO, 
								'ComparisonCaption' => "To", 
								); 


	// Prepare Filters for display
	if ( isset($content['SUBFILTERS']) )
	{
		$i = 0; // Help counter!
		foreach( $content['SUBFILTERS'] as &$tmpFilter ) 
		{
			// Set Field Displayname
			if ( isset($fields[ $tmpFilter['FilterFieldID'] ]['FieldCaption']) ) 
				$tmpFilter['FilterFieldName'] = $fields[ $tmpFilter['FilterFieldID'] ]['FieldCaption'];
			else
				$tmpFilter['FilterFieldName'] = $tmpFilter['FilterFieldID']; 

			// --- Set CSS Class
			if ( $i % 2 == 0 )
				$tmpFilter['colcssclass'] = "line1";
			else
				$tmpFilter['colcssclass'] = "line2";
			$i++;
			// --- 

			if ( $tmpFilter['FilterType'] == FILTER_TYPE_STRING ) 
				$tmpFilter['Comparisons'] = $ComparisonsString; 
			else if ( $tmpFilter['FilterType'] == FILTER_TYPE_NUMBER ) 
				$tmpFilter['Comparisons'] = $ComparisonsNumber; 
			else if ( $tmpFilter['FilterType'] == FILTER_TYPE_DATE ) 
				$tmpFilter['Comparisons'] = $ComparisonsDate; 
			
			// Set right checkbox item
			foreach( $tmpFilter['Comparisons'] as &$tmpComparisons ) 
			{
				if ( $tmpFilter['FilterType'] == FILTER_TYPE_DATE ) 
				{
					if ( $tmpComparisons['ComparisonBit'] == $tmpFilter['FilterDateMode'] ) 
						$tmpComparisons['cp_selected'] = "selected"; 
					else
						$tmpComparisons['cp_selected'] = ""; 
					
					// Init Date Field Helpers!
					InitDatefieldHelpers( $tmpFilter );
				}
				else
				{
					if ( $tmpComparisons['ComparisonBit'] == $tmpFilter['FilterMode'] ) 
						$tmpComparisons['cp_selected'] = "selected"; 
					else
						$tmpComparisons['cp_selected'] = ""; 
				}
			}

		}
//		print_r( $content['SUBFILTERS'] ); 
	}

	// --- Copy fields data array
	$content['FIELDS'] = $fields; 

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


//		if ( isset($_POST['report_filterString']) ) { $content['filterString'] = DB_RemoveBadChars($_POST['report_filterString']); } else {$content['report_filterString'] = ""; }

//echo $szFilterString . "!" . $content['filterString'];
//exit;


		if ( isset($_POST['outputFormat']) ) { $content['outputFormat'] = DB_RemoveBadChars($_POST['outputFormat']); }
		if ( isset($_POST['outputTarget']) ) { $content['outputTarget'] = DB_RemoveBadChars($_POST['outputTarget']); }
		if ( isset($_POST['outputTarget_filename']) ) { $content['outputTarget_filename'] = DB_RemoveBadChars($_POST['outputTarget_filename']); }

		// Read Custom Filters
		foreach ( $content['CUSTOMFILTERS'] as &$tmpCustomFilter ) 
		{
//			print_r ( $tmpCustomFilter ); 
			// Set fieldvalue if available from POST data
			if ( isset($_POST[ $tmpCustomFilter['fieldname'] ]) ) 
				$tmpCustomFilter['fieldvalue'] = DB_RemoveBadChars($_POST[ $tmpCustomFilter['fieldname'] ]); 
		}
		
		// Read done, now build "customFilters" string!
		$content['customFilters'] = "";
		foreach ( $content['CUSTOMFILTERS'] as &$tmpCustomFilter ) 
		{
			// Append comma if necessary
			if (strlen($content['customFilters']) > 0) 
				$content['customFilters'] .= ", "; 

			// Append customFilter!
			$content['customFilters'] .= $tmpCustomFilter['fieldname'] . "=>" . $tmpCustomFilter['fieldvalue']; 
		}

		// TODO!
		// customFilters, outputTarget, scheduleSettings
//		$content['customFilters'] = "";
//		$content['outputTarget'] = "";
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
		else if ( !isset($content['outputTarget']) ) 
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_MISSINGPARAM'], $content['LN_REPORTS_OUTPUTTARGET'] );
		}
		// --- 


		// --- Now Verify Report Source!
		// Create tmpSavedReport!
		$tmpSavedReport["SavedReportID"] = 0;
		$tmpSavedReport["sourceid"] = $content['SourceID'];
		$tmpSavedReport["customTitle"] = $content['customTitle'];
		$tmpSavedReport["customComment"] = $content['customComment'];
		$tmpSavedReport["filterString"] = $content['filterString'];
		$tmpSavedReport["customFilters"] = $content['customFilters'];
		$tmpSavedReport["outputFormat"] = $content['outputFormat'];
		$tmpSavedReport["outputTarget"] = $content['outputTarget'];
		$tmpSavedReport["scheduleSettings"] = $content['scheduleSettings'];
		$tmpSavedReport["outputTargetDetails"] = ""; // Init Value
		if ( isset($content['outputTarget_filename']) ) 
			$tmpSavedReport["outputTargetDetails"] .= "filename=>" . $content['outputTarget_filename'] . ",";
		$content["outputTargetDetails"] = $tmpSavedReport["outputTargetDetails"]; // Copy into content var

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
				$sqlquery = "INSERT INTO " . DB_SAVEDREPORTS . " (reportid, sourceid, customTitle, customComment, filterString, customFilters, outputFormat, outputTarget, outputTargetDetails, scheduleSettings) 
				VALUES ('" . $content['ReportID'] . "', 
						" . $content['SourceID'] . ", 
						'" . $content['customTitle'] . "', 
						'" . $content['customComment'] . "', 
						'" . $content['filterString'] . "', 
						'" . $content['customFilters'] . "', 
						'" . $content['outputFormat'] . "', 
						'" . $content['outputTarget'] . "', 
						'" . $content['outputTargetDetails'] . "', 
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
									outputTargetDetails = '" . $content['outputTargetDetails'] . "', 
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
		$content['OPTIONAL_TITLE'] = " - " . $content['LN_REPORTMENU_LIST'];

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
					$j++;
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

// --- 
// --- BEGIN Helper functions 
// --- 
function InitCustomFilterDefinitions($myReport, $CustomFilterValues)
{
	global $content; 

	// Get Objectreference to report
	$myReportObj = $myReport["ObjRef"];

	// Get Array of Custom filter Defs
	$customFilterDefs = $myReportObj->GetCustomFiltersDefs();

	// Include Custom language file if available
	$myReportObj->InitReportLanguageFile( $myReportObj->GetReportIncludePath() ); 

	// Parse and Split CustomFilterValues
	if ( strlen($CustomFilterValues) > 0 ) 
	{
		$tmpFilterValues = explode( ",", $CustomFilterValues );
	
		//Loop through mappings
		foreach ($tmpFilterValues as &$myFilterValue )
		{
			// Split subvalues
			$tmpArray = explode( "=>", $myFilterValue );
			
			// Set into temporary array
			$tmpfilterid = trim($tmpArray[0]);
			$myFilterValues[$tmpfilterid] =  trim($tmpArray[1]);
		}
	}

	// Loop through filters
	$i = 0; // Help counter!
	foreach( $customFilterDefs as $filterID => $tmpCustomFilter ) 
	{
		// Check if value is available in $CustomFilterValues
		if ( isset($myFilterValues[$filterID]) ) 
			$szDefaultValue = $myFilterValues[$filterID]; 
		else
			$szDefaultValue = $tmpCustomFilter['DefaultValue']; 

		// TODO Check MIN and MAX value!

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$szColcssclass = "line1";
		else
			$szColcssclass = "line2";
		$i++;
		// --- 

		// Add to Display Array of custom filters!
		$content['CUSTOMFILTERS'][] = array (
										'fieldname'			=> $filterID, 
										'fieldcaption'		=> $content[ $tmpCustomFilter['DisplayLangID'] ],
										'fielddescription'	=> $content[ $tmpCustomFilter['DescriptLangID'] ],
										'filtertype'		=> $tmpCustomFilter['filtertype'], 
										'fieldvalue'		=> $szDefaultValue, 
										'colcssclass'		=> $szColcssclass, 
									);
	}
}


function InitOutputtargetDefinitions($myReport, $outputTargetDetails)
{
	global $content; 

	// Get Objectreference to report
	$myReportObj = $myReport["ObjRef"];
	
	// Init Detail variables manually
	$myReportObj->SetOutputTargetDetails($outputTargetDetails);

	// Get Array of Custom filter Defs
	$outputTargetArray = $myReportObj->GetOutputTargetDetails();

	if ( isset($outputTargetArray) && count($outputTargetArray) > 0 )
	{
		// Loop through Detail Properties
		$i = 0; // Help counter!
		foreach( $outputTargetArray as $propertyID => $propertyValue ) 
		{
			// Set property Value by ID
			$content['outputTarget_' . $propertyID] = $propertyValue;
		}
	}
}

function CreateCronCommand( $myReportID, $mySavedReportID = null )
{
	global $content, $gl_root_path; 

	if ( isset($mySavedReportID) ) 
	{
		$pos = strpos( strtoupper(PHP_OS), "WIN");
		if ($pos !== false) 
		{	
			// Running on Windows
			$phpCmd = PHP_BINDIR . "\\php.exe"; 
			$phpScript = realpath($gl_root_path) . "cron\\cmdreportgen.php";
		} 
		else 
		{
			// Running on LINUX
			$phpCmd = PHP_BINDIR . "/php"; 
			$phpScript = realpath($gl_root_path) . "/cron/cmdreportgen.php";
		}
		
		// Enable display of report command
		$content['enableCronCommand'] = true;
		$szCommand = $phpCmd . " " . $phpScript . " runreport " . $myReportID . " " . $mySavedReportID;
	}
	else
	{
		// Disable display of report command
		$content['enableCronCommand'] = false;
		$szCommand = "";
	}

	// return result 
	return $szCommand; 
}

function InitOnlineReports()
{
	global $content; 

	$xmlArray = xml2array( URL_ONLINEREPORTS ); 
	if ( is_array($xmlArray) && isset($xmlArray['reports']['report']) && count($xmlArray['reports']['report']) > 0 ) 
	{
		foreach( $xmlArray['reports']['report'] as $myOnlineReport ) 
		{
			// Copy to OnlineReports Array
			$content['ONLINEREPORTS'][] = $myOnlineReport; 
		}

		// Success!
		return true;
	}
	else
		// Failure
		return false;
}

// Helper function from php doc
function xml2array($url, $get_attributes = 1, $priority = 'tag')
{
    $contents = "";
    if (!function_exists('xml_parser_create'))
    {
        return false;
    }
    $parser = xml_parser_create('');
    if (!($fp = @ fopen($url, 'rb')))
    {
        return false;
    }
    while (!feof($fp))
    {
        $contents .= fread($fp, 8192);
    }
    fclose($fp);
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return; //Hmm...
    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    $current = & $xml_array;
    $repeated_tag_index = array (); 
    foreach ($xml_values as $data)
    {
        unset ($attributes, $value);
        extract($data);
        $result = array ();
        $attributes_data = array ();
        if (isset ($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset ($attributes) and $get_attributes)
        {
            foreach ($attributes as $attr => $val)
            {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open")
        { 
            $parent[$level -1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current))))
            {
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else
            {
                if (isset ($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                { 
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    ); 
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset ($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset ($current[$tag][0]) and is_array($current[$tag]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data)
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    ); 
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset ($current[$tag . '_attr']))
                        { 
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if ($attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close')
        {
            $current = & $parent[$level -1];
        }
    }
    return ($xml_array);
}

/*
*	Helper functions to init a Datefield
*/
function InitDatefieldHelpers( &$myFilter )
{
	global $content; 
	global $currentTime, $currentDay, $currentMonth, $currentYear, $tomorrowTime, $tomorrowDay, $tomorrowMonth, $tomorrowYear; 

	if ( $myFilter['FilterDateMode'] == DATEMODE_LASTX ) 
		$myFilter['filter_lastx_default'] = intval($myFilter['FilterValue']);
	else
		$myFilter['filter_lastx_default'] = DATE_LASTX_7DAYS;

	$myFilter['MyFilter_daterange_last_x_list'][0]['LastXID'] = DATE_LASTX_HOUR;
	$myFilter['MyFilter_daterange_last_x_list'][0]['LastXDisplayName'] = $content['LN_DATE_LASTX_HOUR'];
	if ( $myFilter['filter_lastx_default'] == DATE_LASTX_HOUR ) { $myFilter['MyFilter_daterange_last_x_list'][0]['selected'] = "selected"; } else { $myFilter['MyFilter_daterange_last_x_list'][0]['selected'] = ""; }

	$myFilter['MyFilter_daterange_last_x_list'][1]['LastXID'] = DATE_LASTX_12HOURS;
	$myFilter['MyFilter_daterange_last_x_list'][1]['LastXDisplayName'] = $content['LN_DATE_LASTX_12HOURS'];
	if ( $myFilter['filter_lastx_default'] == DATE_LASTX_12HOURS ) { $myFilter['MyFilter_daterange_last_x_list'][1]['selected'] = "selected"; } else { $myFilter['MyFilter_daterange_last_x_list'][1]['selected'] = ""; }

	$myFilter['MyFilter_daterange_last_x_list'][2]['LastXID'] = DATE_LASTX_24HOURS;
	$myFilter['MyFilter_daterange_last_x_list'][2]['LastXDisplayName'] = $content['LN_DATE_LASTX_24HOURS'];
	if ( $myFilter['filter_lastx_default'] == DATE_LASTX_24HOURS ) { $myFilter['MyFilter_daterange_last_x_list'][2]['selected'] = "selected"; } else { $myFilter['MyFilter_daterange_last_x_list'][2]['selected'] = ""; }

	$myFilter['MyFilter_daterange_last_x_list'][3]['LastXID'] = DATE_LASTX_7DAYS;
	$myFilter['MyFilter_daterange_last_x_list'][3]['LastXDisplayName'] = $content['LN_DATE_LASTX_7DAYS'];
	if ( $myFilter['filter_lastx_default'] == DATE_LASTX_7DAYS ) { $myFilter['MyFilter_daterange_last_x_list'][3]['selected'] = "selected"; } else { $myFilter['MyFilter_daterange_last_x_list'][3]['selected'] = ""; }

	$myFilter['MyFilter_daterange_last_x_list'][4]['LastXID'] = DATE_LASTX_31DAYS;
	$myFilter['MyFilter_daterange_last_x_list'][4]['LastXDisplayName'] = $content['LN_DATE_LASTX_31DAYS'];
	if ( $myFilter['filter_lastx_default'] == DATE_LASTX_31DAYS ) { $myFilter['MyFilter_daterange_last_x_list'][4]['selected'] = "selected"; } else { $myFilter['MyFilter_daterange_last_x_list'][4]['selected'] = ""; }
	// ---
	
	// Init Date/Time values 
	if ( GetDateTimeDetailsFromTimeString($myFilter['FilterValue'], $mysecond, $myminute, $myhour, $myday, $mymonth, $myyear) ) 
	{
		$myFilter['filter_daterange_year'] = intval($myyear);
		$myFilter['filter_daterange_month'] = intval($mymonth);
		$myFilter['filter_daterange_day'] = intval($myday);
		$myFilter['filter_daterange_hour'] = intval($myhour);
		$myFilter['filter_daterange_minute'] = intval($myminute);
		$myFilter['filter_daterange_second'] = intval($mysecond);
	}
	else
	{
		$myFilter['filter_daterange_year'] = $tomorrowYear;
		$myFilter['filter_daterange_month'] = $tomorrowMonth;
		$myFilter['filter_daterange_day'] = $tomorrowDay;
		$myFilter['filter_daterange_hour'] = 0;
		$myFilter['filter_daterange_minute'] = 0;
		$myFilter['filter_daterange_second'] = 0;
	}

	ReportsFillDateRangeArray($content['years'], $myFilter, "filter_daterange_year_list", "filter_daterange_year");
	ReportsFillDateRangeArray($content['months'], $myFilter, "filter_daterange_month_list", "filter_daterange_month");
	ReportsFillDateRangeArray($content['days'], $myFilter, "filter_daterange_day_list", "filter_daterange_day");
	ReportsFillDateRangeArray($content['hours'], $myFilter, "filter_daterange_hour_list", "filter_daterange_hour");
	ReportsFillDateRangeArray($content['minutes'], $myFilter, "filter_daterange_minute_list", "filter_daterange_minute");
	ReportsFillDateRangeArray($content['seconds'], $myFilter, "filter_daterange_second_list", "filter_daterange_second");
}

function CreateTimeStampFromValues($iNum)
{
	global $currentTime, $currentDay, $currentMonth, $currentYear, $tomorrowTime, $tomorrowDay, $tomorrowMonth, $tomorrowYear; 
	
	// Read and parse Date
	if ( isset($_POST['filter_daterange_year_' . $iNum]) ) 
		$tmpYear = DB_RemoveBadChars($_POST['filter_daterange_year_' . $iNum]); 
	else
		$tmpYear = $currentYear;	// Default value
	if ( isset($_POST['filter_daterange_month_' . $iNum]) ) 
		$tmpMonth = DB_RemoveBadChars($_POST['filter_daterange_month_' . $iNum]); 
	else
		$tmpMonth = $tomorrowMonth;	// Default value
	if ( isset($_POST['filter_daterange_day_' . $iNum]) ) 
		$tmpDay = DB_RemoveBadChars($_POST['filter_daterange_day_' . $iNum]); 
	else
		$tmpDay = $currentDay;		// Default value

	// Read and parse Time
	if ( isset($_POST['filter_daterange_hour_' . $iNum]) ) 
		$tmpHour = DB_RemoveBadChars($_POST['filter_daterange_hour_' . $iNum]); 
	else
		$tmpHour = 0;	// Default value
	if ( isset($_POST['filter_daterange_minute_' . $iNum]) ) 
		$tmpMinute = DB_RemoveBadChars($_POST['filter_daterange_minute_' . $iNum]); 
	else
		$tmpMinute = 0; // Default value
	if ( isset($_POST['filter_daterange_second_' . $iNum]) ) 
		$tmpSecond = DB_RemoveBadChars($_POST['filter_daterange_second_' . $iNum]); 
	else
		$tmpSecond = 0; // Default value

	return $tmpYear . "-" . $tmpMonth . "-" . $tmpDay . "T" . $tmpHour . ":" . $tmpMinute . ":" . $tmpSecond; 
}

function ReportsFillDateRangeArray($sourcearray, &$myFilter, $szArrayListName, $szFilterName)
{
	global $content; 
	$iCount = count($sourcearray);

	for ( $i = 0; $i < $iCount; $i++)
	{
		$myFilter[$szArrayListName][$i]['value'] = $sourcearray[$i];
		if ( $myFilter[$szFilterName]  == $sourcearray[$i] ) 
			$myFilter[$szArrayListName][$i]['selected'] = "selected";
		else
			$myFilter[$szArrayListName][$i]['selected'] = "";
	}
}

// --- END Helper functions 

?>