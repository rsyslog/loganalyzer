<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Charts Admin File											
	*																	
	* -> Helps administrating LogAnalyzer charts & graphics
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
				||
				(	isset($_GET['miniop']) && 
					(
						$_GET['miniop'] == "setenabled" 
					)
				)

			)	
		)
		DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_READONLY'] );
}
// --- 

// --- BEGIN Custom Code

// --- Set Helpervariable for non-ADMIN users
if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
	$content['READONLY_ISUSERONLY'] = "disabled"; 
else
	$content['READONLY_ISUSERONLY'] = ""; 
// --- 

if ( isset($_GET['miniop']) ) 
{
	if ( isset($_GET['id']) && isset($_GET['newval']) )
	{
		if ( $_GET['miniop'] == "setenabled" ) 
		{
			//PreInit these values 
			$content['CHARTID'] = intval(DB_RemoveBadChars($_GET['id']));
			$iNewVal = intval(DB_RemoveBadChars($_GET['newval']));

			// Perform SQL Query!
			$sqlquery = "SELECT * " . 
						" FROM " . DB_CHARTS . 
						" WHERE ID = " . $content['CHARTID'];
			$result = DB_Query($sqlquery);
			$mychart = DB_GetSingleRow($result, true);
			if ( isset($mychart['DisplayName']) )
			{
				// Update enabled setting!
				$result = DB_Query("UPDATE " . DB_CHARTS . " SET 
					chart_enabled = $iNewVal 
					WHERE ID = " . $content['CHARTID']);
				DB_FreeQuery($result);

				// Reload Charts from DB
				LoadChartsFromDatabase();
			}
			else
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_CHARTIDNOTFOUND'], $content['CHARTID'] );
			}
		}
	}
	else
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = $content['LN_CHARTS_ERROR_SETTINGFLAG'];
	}
}

if ( isset($_GET['op']) )
{
	if ($_GET['op'] == "add") 
	{
		// Set Mode to add
		$content['ISEDITORNEWCHART'] = "true";
		$content['CHART_FORMACTION'] = "addnewchart";
		$content['CHART_SENDBUTTON'] = $content['LN_CHARTS_ADD'];
		
		//PreInit these values 
		$content['Name'] = "MyChart";
		$content['chart_type'] = CHART_BARS_VERTICAL;
		CreateChartTypesList($content['chart_type']);
		$content['chart_enabled'] = 1;
		$content['CHECKED_ISCHARTENABLED'] = "checked";
		$content['chart_width'] = 400; 
		$content['maxrecords'] = 5; 
		$content['showpercent'] = 0; 
		$content['CHECKED_ISSHOWPERCENT'] = "";
		$content['chart_defaultfilter'] = ""; 
		// Chart Field
		$content['chart_field'] = SYSLOG_HOST; 
		CreateChartFields($content['chart_field']);

		// COMMON Fields
		$content['userid'] = null;
		$content['CHECKED_ISUSERONLY'] = "";
		$content['CHARTID'] = "";

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
	}
	else if ($_GET['op'] == "edit") 
	{
		// Set Mode to edit
		$content['ISEDITORNEWCHART'] = "true";
		$content['CHART_FORMACTION'] = "editchart";
		$content['CHART_SENDBUTTON'] = $content['LN_CHARTS_EDIT'];

		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['CHARTID'] = DB_RemoveBadChars($_GET['id']);

			// Check if exists
			if ( is_numeric($content['CHARTID']) && isset($content['Charts'][ $content['CHARTID'] ]) )
			{
				// Get Source reference
				$myChart = $content['Charts'][ $content['CHARTID'] ];

				// Copy basic properties
				$content['Name'] = $myChart['DisplayName'];
				$content['chart_type'] = $myChart['chart_type'];
				CreateChartTypesList($content['chart_type']);
				$content['chart_enabled'] = $myChart['chart_enabled'];
				if ( $myChart['chart_enabled'] == 1 )
					$content['CHECKED_ISCHARTENABLED'] = "checked";
				else
					$content['CHECKED_ISCHARTENABLED'] = "";
				$content['chart_width'] = $myChart['chart_width'];
				$content['maxrecords'] = $myChart['maxrecords'];
				$content['chart_defaultfilter'] = $myChart['chart_defaultfilter']; 
				$content['showpercent'] = $myChart['showpercent'];
				if ( $myChart['showpercent'] == 1 )
					$content['CHECKED_ISSHOWPERCENT'] = "checked";
				else
					$content['CHECKED_ISSHOWPERCENT'] = "";

				// Chart Field
				$content['chart_field'] = $myChart['chart_field'];
				CreateChartFields($content['chart_field']);
				
				// COMMON Fields
				$content['userid'] = $myChart['userid'];
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
						if ( $myChart['groupid'] != null && $content['SUBGROUPS'][$i]['mygroupid'] == $myChart['groupid'] )
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
				$content['ISEDITORNEWCHART'] = false;
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = "!" . $content['LN_CHARTS_ERROR_INVALIDORNOTFOUNDID'];
			}
		}
		else
		{
			$content['ISEDITORNEWCHART'] = false;
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] =  $content['LN_CHARTS_ERROR_INVALIDID'];
		}
	}
	else if ($_GET['op'] == "delete") 
	{
		if ( isset($_GET['id']) )
		{
			//PreInit these values 
			$content['CHARTID'] = DB_RemoveBadChars($_GET['id']);

			// Get UserInfo
			$result = DB_Query("SELECT DisplayName FROM " . DB_CHARTS . " WHERE ID = " . $content['CHARTID'] ); 
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['DisplayName']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_IDNOTFOUND'], $content['CHARTID'] ); 
			}

			// --- Ask for deletion first!
			if ( (!isset($_GET['verify']) || $_GET['verify'] != "yes") )
			{
				// This will print an additional secure check which the user needs to confirm and exit the script execution.
				PrintSecureUserCheck( GetAndReplaceLangStr( $content['LN_CHARTS_WARNDELETESEARCH'], $myrow['DisplayName'] ), $content['LN_DELETEYES'], $content['LN_DELETENO'] );
			}
			// ---

			// do the delete!
			$result = DB_Query( "DELETE FROM " . DB_CHARTS . " WHERE ID = " . $content['CHARTID'] );
			if ($result == FALSE)
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_DELCHART'], $content['CHARTID'] ); 
			}
			else
				DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_HASBEENDEL'], $myrow['DisplayName'] ) , "charts.php" );
		}
		else
		{
			$content['ISERROR'] = true;
			$content['ERROR_MSG'] = $content['LN_CHARTS_ERROR_INVALIDORNOTFOUNDID'];
		}
	}
}

if ( isset($_POST['op']) )
{
	// Read parameters first!
	if ( isset($_POST['id']) ) { $content['CHARTID'] = intval(DB_RemoveBadChars($_POST['id'])); } else {$content['CHARTID'] = -1; }
	if ( isset($_POST['Name']) ) { $content['Name'] = DB_RemoveBadChars($_POST['Name']); } else {$content['Name'] = ""; }
	if ( isset($_POST['chart_enabled']) ) { $content['chart_enabled'] = intval(DB_RemoveBadChars($_POST['chart_enabled'])); } else {$content['chart_enabled'] = 0; }
	if ( isset($_POST['chart_type']) ) { $content['chart_type'] = intval(DB_RemoveBadChars($_POST['chart_type'])); }
	if ( isset($_POST['chart_width']) ) { $content['chart_width'] = intval(DB_RemoveBadChars($_POST['chart_width'])); } else {$content['chart_width'] = 400; }
	if ( isset($_POST['chart_field']) ) { $content['chart_field'] = DB_RemoveBadChars($_POST['chart_field']); }
	if ( isset($_POST['maxrecords']) ) { $content['maxrecords'] = intval(DB_RemoveBadChars($_POST['maxrecords'])); }
	if ( isset($_POST['showpercent']) ) { $content['showpercent'] = intval(DB_RemoveBadChars($_POST['showpercent'])); } else {$content['showpercent'] = 0; }
	if ( isset($_POST['chart_defaultfilter']) ) { $content['chart_defaultfilter'] = DB_RemoveBadChars($_POST['chart_defaultfilter']); }
	
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
	if ( $content['Name'] == "" )
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_MISSINGPARAM'], $content['LN_CHARTS_NAME'] );
	}
	else if ( !isset($content['chart_type']) ) 
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_MISSINGPARAM'], $content['LN_CHART_TYPE'] );
	}
	else if ( !isset($content['chart_field']) ) 
	{
		$content['ISERROR'] = true;
		$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_MISSINGPARAM'], $content['LN_CHART_FIELD'] );
	}

	// --- Now ADD/EDIT do the processing!
	if ( !isset($content['ISERROR']) ) 
	{	
		// Everything was alright, so we go to the next step!
		if ( $_POST['op'] == "addnewchart" )
		{
			// Add custom search now!
			$sqlquery = "INSERT INTO " . DB_CHARTS . " (DisplayName, chart_enabled, chart_type, chart_width, chart_field, chart_defaultfilter, maxrecords, showpercent, userid, groupid) 
			VALUES ('" . $content['Name'] . "', 
					" . $content['chart_enabled'] . ", 
					" . $content['chart_type'] . ", 
					" . $content['chart_width'] . ", 
					'" . $content['chart_field'] . "',
					'" . $content['chart_defaultfilter'] . "',
					" . $content['maxrecords'] . ", 
					" . $content['showpercent'] . ", 
					" . $content['userid'] . ", 
					" . $content['groupid'] . " 
					)";

			$result = DB_Query($sqlquery);
			DB_FreeQuery($result);

			// Do the final redirect
			RedirectResult( GetAndReplaceLangStr( $content['LN_CHARTS_HASBEENADDED'], DB_StripSlahes($content['Name']) ) , "charts.php" );
		}
		else if ( $_POST['op'] == "editchart" )
		{
			$result = DB_Query("SELECT ID FROM " . DB_CHARTS . " WHERE ID = " . $content['CHARTID']);
			$myrow = DB_GetSingleRow($result, true);
			if ( !isset($myrow['ID']) )
			{
				$content['ISERROR'] = true;
				$content['ERROR_MSG'] = GetAndReplaceLangStr( $content['LN_CHARTS_ERROR_IDNOTFOUND'], $content['CHARTID'] ); 
			}
			else
			{
				$sqlquery =	"UPDATE " . DB_CHARTS . " SET 
								DisplayName = '" . $content['Name'] . "', 
								chart_enabled = " . $content['chart_enabled'] . ", 
								chart_type = " . $content['chart_type'] . ", 
								chart_width = " . $content['chart_width'] . ", 
								chart_field = '" . $content['chart_field'] . "',
								chart_defaultfilter = '" . $content['chart_defaultfilter'] . "',
								maxrecords = " . $content['maxrecords'] . ", 
								showpercent = " . $content['showpercent'] . ", 
								userid = " . $content['userid'] . ", 
								groupid = " . $content['groupid'] . "
								WHERE ID = " . $content['CHARTID'];

				$result = DB_Query($sqlquery);
				DB_FreeQuery($result);

				// Done redirect!
				RedirectResult( GetAndReplaceLangStr( $content['LN_CHARTS_HASBEENEDIT'], DB_StripSlahes($content['Name']) ) , "charts.php" );
			}
		}
	}
}

if ( !isset($_POST['op']) && !isset($_GET['op']) )
{
	// Default Mode = List Searches
	$content['LISTCHARTS'] = "true";

	// Copy Sources array for further modifications
	$content['CHARTS'] = $content['Charts'];

	// --- Process Sources
	$i = 0; // Help counter!
	foreach ($content['CHARTS'] as &$myChart )
	{
		// --- Set Image for Type
		// NonNUMERIC are config files Sources, can not be editied
		if ( is_numeric($myChart['ID']) )
		{
			// Allow EDIT
			$myChart['ActionsAllowed'] = true;

			if ( $myChart['userid'] != null )
			{
				$myChart['ChartAssignedToImage']	= $content["MENU_ADMINUSERS"];
				$myChart['ChartAssignedToText']		= $content["LN_GEN_USERONLY"];
			}
			else if ( $myChart['groupid'] != null )
			{
				$myChart['ChartAssignedToImage']	= $content["MENU_ADMINGROUPS"];
				$myChart['ChartAssignedToText']	= GetAndReplaceLangStr( $content["LN_GEN_GROUPONLYNAME"], $myChart['groupname'] );

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$myChart['ActionsAllowed'] = false;
			}
			else
			{
				$myChart['ChartAssignedToImage']	= $content["MENU_GLOBAL"];
				$myChart['ChartAssignedToText']	= $content["LN_GEN_GLOBAL"];

				// Check if is ADMIN User, deny if normal user!
				if ( !isset($_SESSION['SESSION_ISADMIN']) || $_SESSION['SESSION_ISADMIN'] == 0 ) 
					$myChart['ActionsAllowed'] = false;
			}
		}
		else
		{
			// Disallow EDIT
			$myChart['ActionsAllowed'] = false;

			$myChart['ChartAssignedToImage']	= $content["MENU_INTERNAL"];
			$myChart['ChartAssignedToText']		= $content["LN_GEN_CONFIGFILE"];
		}
		// ---

		// --- Set SourceType
		if ( $myChart['chart_type'] == CHART_CAKE )
		{
			$myChart['ChartTypeImage']	= $content["MENU_CHART_CAKE"];
			$myChart['ChartTypeText']	= $content["LN_CHART_TYPE_CAKE"];
		}
		else if ( $myChart['chart_type'] == CHART_BARS_VERTICAL )
		{
			$myChart['ChartTypeImage']	= $content["MENU_CHART_BARSVERT"];
			$myChart['ChartTypeText']	= $content["LN_CHART_TYPE_BARS_VERTICAL"];
		}
		else if ( $myChart['chart_type'] == CHART_BARS_HORIZONTAL )
		{
			$myChart['ChartTypeImage']	= $content["MENU_CHART_BARSHORI"];
			$myChart['ChartTypeText']	= $content["LN_CHART_TYPE_BARS_HORIZONTAL"];
		}
		// ---

		// --- Set enabled or disabled state
		if ( $myChart['chart_enabled'] == 1 )
		{
			$myChart['ChartEnabledImage']	= $content["MENU_SELECTION_ENABLED"];
			$myChart['set_enabled'] = 0;
		}
		else 
		{
			$myChart['ChartEnabledImage']	= $content["MENU_SELECTION_DISABLED"];
			$myChart['set_enabled'] = 1;
		}

		
		// ---

		// --- Set Chart default Filterstring
		if ( strlen($myChart['chart_defaultfilter']) > 0 )
			$myChart['chart_defaultfilter_urldecoded']	= urlencode($myChart['chart_defaultfilter']);
		else 
			$myChart['chart_defaultfilter_urldecoded'] = "";
		// ---

		// --- Set CSS Class
		if ( $i % 2 == 0 )
			$myChart['cssclass'] = "line1";
		else
			$myChart['cssclass'] = "line2";
		$i++;
		// --- 
	}
	// --- 
}
// --- END Custom Code

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_CHARTOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_charts.html");
$page -> output(); 
// --- 

?>