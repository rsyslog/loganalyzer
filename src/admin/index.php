<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Admin Index File											
	*																	
	* -> Shows ...
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

// Include LogStream facility
// include($gl_root_path . 'classes/logstream.class.php');

// Set PAGE to be ADMINPAGE!
define('IS_ADMINPAGE', true);
$content['IS_ADMINPAGE'] = true;

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!

// Init admin langauge file now!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/admin.php' );

// --- Deny if User is READONLY!
if ( !isset($_SESSION['SESSION_ISREADONLY']) || $_SESSION['SESSION_ISREADONLY'] == 1 )
{
	if (	isset($_POST['op']) ||
			(
				isset($_GET['op']) && 
				(
					$_GET['op'] == "enableuserops"
				)
			)	
		)
		DieWithFriendlyErrorMsg( $content['LN_ADMIN_ERROR_READONLY'] );
}
// --- 

// --- BEGIN Custom Code
if ( isset($_SESSION['SESSION_ISADMIN']) && $_SESSION['SESSION_ISADMIN'] == 1 ) 
{
	$content['EditAllowed'] = true;
	$content['DISABLE_GLOBALEDIT_FORMCONTROL'] = "";
}
else	
{
	$content['EditAllowed'] = false;
	$content['DISABLE_GLOBALEDIT_FORMCONTROL'] = "disabled";
}

// --- First thing to do is to check the op get parameter!
// Check for changes first | Abort if Edit is not allowed
if ( isset($_GET['op']) && isset($_GET['value']) )
{
	if ( $_GET['op'] == "enableuserops" )
	{
		$iNewVal = intval($_GET['value']);
		if ( $iNewVal == 1 )
			$USERCFG['UserOverwriteOptions'] = 1;
		else
			$USERCFG['UserOverwriteOptions'] = 0;

		// Enable User Options!
		WriteConfigValue( "UserOverwriteOptions", false, $content['SESSION_USERID'] );
	}
}
// ---

// --- Check if user wants to overwrite
$UserOverwriteOptions = GetConfigSetting("UserOverwriteOptions", 0, CFGLEVEL_USER);
if ( $UserOverwriteOptions == 1 )
{
	$content['ENABLEUSEROPTIONS'] = true;
}
else
{
	$content['ENABLEUSEROPTIONS'] = false;


}
// ---

// Check for changes first | Abort if Edit is not allowed
if ( isset($_POST['op']) )
{
	if ( $_POST['op'] == "edit" )
	{
		// Do if User is ADMIN
		if ( $content['EditAllowed'] )
		{
			// Language needs special treatment
			if ( isset ($_POST['ViewDefaultLanguage']) )
			{ 
				$tmpvar = DB_RemoveBadChars($_POST['ViewDefaultLanguage']); 
				if ( VerifyLanguage($tmpvar) )
					$content['ViewDefaultLanguage'] = $tmpvar;
			}

			// Read default theme
			if ( isset ($_POST['ViewDefaultTheme']) ) { $content['ViewDefaultTheme'] = $_POST['ViewDefaultTheme']; }

			// Read default VIEW | Check if View exists as well!
			if ( isset ($_POST['DefaultViewsID']) && isset($content['Views'][$_POST['DefaultViewsID']] )) { $content['DefaultViewsID'] = $_POST['DefaultViewsID']; }

			// Read default SOURCES | Check if Source exists as well!
			if ( isset ($_POST['DefaultSourceID']) && isset($content['Sources'][$_POST['DefaultSourceID']] )) { $content['DefaultSourceID'] = $_POST['DefaultSourceID']; }

			// Read checkboxes
			if ( isset ($_POST['ViewUseTodayYesterday']) ) { $content['ViewUseTodayYesterday'] = 1; } else { $content['ViewUseTodayYesterday'] = 0; } 
			if ( isset ($_POST['ViewEnableDetailPopups']) ) { $content['ViewEnableDetailPopups'] = 1; } else { $content['ViewEnableDetailPopups'] = 0; } 
			if ( isset ($_POST['EnableContextLinks']) ) { $content['EnableContextLinks'] = 1; } else { $content['EnableContextLinks'] = 0; } 
			if ( isset ($_POST['EnableIPAddressResolve']) ) { $content['EnableIPAddressResolve'] = 1; } else { $content['EnableIPAddressResolve'] = 0; } 
			if ( isset ($_POST['MiscShowDebugMsg']) ) { $content['MiscShowDebugMsg'] = 1; } else { $content['MiscShowDebugMsg'] = 0; } 
			if ( isset ($_POST['MiscShowDebugGridCounter']) ) { $content['MiscShowDebugGridCounter'] = 1; } else { $content['MiscShowDebugGridCounter'] = 0; } 
			if ( isset ($_POST['MiscShowPageRenderStats']) ) { $content['MiscShowPageRenderStats'] = 1; } else { $content['MiscShowPageRenderStats'] = 0; } 
			if ( isset ($_POST['MiscEnableGzipCompression']) ) { $content['MiscEnableGzipCompression'] = 1; } else { $content['MiscEnableGzipCompression'] = 0; } 
			if ( isset ($_POST['SuppressDuplicatedMessages']) ) { $content['SuppressDuplicatedMessages'] = 1; } else { $content['SuppressDuplicatedMessages'] = 0; } 
			if ( isset ($_POST['TreatNotFoundFiltersAsTrue']) ) { $content['TreatNotFoundFiltersAsTrue'] = 1; } else { $content['TreatNotFoundFiltersAsTrue'] = 0; } 
			if ( isset ($_POST['InlineOnlineSearchIcons']) ) { $content['InlineOnlineSearchIcons'] = 1; } else { $content['InlineOnlineSearchIcons'] = 0; } 
			if ( isset ($_POST['DebugUserLogin']) ) { $content['DebugUserLogin'] = 1; } else { $content['DebugUserLogin'] = 0; } 
			if ( isset ($_POST['MiscDebugToSyslog']) ) { $content['MiscDebugToSyslog'] = 1; } else { $content['MiscDebugToSyslog'] = 0; } 

			// Read Text number fields
			if ( isset ($_POST['ViewMessageCharacterLimit']) && is_numeric($_POST['ViewMessageCharacterLimit']) ) { $content['ViewMessageCharacterLimit'] = $_POST['ViewMessageCharacterLimit']; }
			if ( isset ($_POST['ViewStringCharacterLimit']) && is_numeric($_POST['ViewStringCharacterLimit']) ) { $content['ViewStringCharacterLimit'] = $_POST['ViewStringCharacterLimit']; }
			if ( isset ($_POST['PopupMenuTimeout']) && is_numeric($_POST['PopupMenuTimeout']) ) { $content['PopupMenuTimeout'] = $_POST['PopupMenuTimeout']; }
			if ( isset ($_POST['ViewEntriesPerPage']) && is_numeric($_POST['ViewEntriesPerPage']) ) { $content['ViewEntriesPerPage'] = $_POST['ViewEntriesPerPage']; }
			if ( isset ($_POST['ViewEnableAutoReloadSeconds']) && is_numeric($_POST['ViewEnableAutoReloadSeconds']) ) { $content['ViewEnableAutoReloadSeconds'] = $_POST['ViewEnableAutoReloadSeconds']; }
			if ( isset ($_POST['AdminChangeWaitTime']) && is_numeric($_POST['AdminChangeWaitTime']) ) { $content['AdminChangeWaitTime'] = $_POST['AdminChangeWaitTime']; }
			if ( isset ($_POST['MiscMaxExecutionTime']) && is_numeric($_POST['MiscMaxExecutionTime']) ) { $content['MiscMaxExecutionTime'] = $_POST['MiscMaxExecutionTime']; }

			// Read Text fields
			if ( isset ($_POST['PrependTitle']) ) { $content['PrependTitle'] = $_POST['PrependTitle']; }
			if ( isset ($_POST['SearchCustomButtonCaption']) ) { $content['SearchCustomButtonCaption'] = $_POST['SearchCustomButtonCaption']; }
			if ( isset ($_POST['SearchCustomButtonSearch']) ) { $content['SearchCustomButtonSearch'] = $_POST['SearchCustomButtonSearch']; }

			if ( isset ($_POST['InjectHtmlHeader']) ) { $content['InjectHtmlHeader'] = $_POST['InjectHtmlHeader']; }
			if ( isset ($_POST['InjectBodyHeader']) ) { $content['InjectBodyHeader'] = $_POST['InjectBodyHeader']; }
			if ( isset ($_POST['InjectBodyFooter']) ) { $content['InjectBodyFooter'] = $_POST['InjectBodyFooter']; }
			if ( isset ($_POST['PhplogconLogoUrl']) ) { $content['PhplogconLogoUrl'] = $_POST['PhplogconLogoUrl']; }
			if ( isset ($_POST['UseProxyServerForRemoteQueries']) ) { $content['UseProxyServerForRemoteQueries'] = $_POST['UseProxyServerForRemoteQueries']; }
			if ( isset ($_POST['HeaderDefaultEncoding']) ) { $content['HeaderDefaultEncoding'] = $_POST['HeaderDefaultEncoding']; }

			// Save configuration variables now
			SaveGeneralSettingsIntoDB();
		}
		
		// Do if User wants extra options
		if ( $content['ENABLEUSEROPTIONS'] )
		{
			// Language needs special treatment
			if ( isset ($_POST['User_ViewDefaultLanguage']) )
			{ 
				$tmpvar = DB_RemoveBadChars($_POST['User_ViewDefaultLanguage']); 
				if ( VerifyLanguage($tmpvar) )
					$USERCFG['ViewDefaultLanguage'] = $tmpvar;
			}

			// Read default theme
			if ( isset ($_POST['User_ViewDefaultTheme']) ) { $USERCFG['ViewDefaultTheme'] = $_POST['User_ViewDefaultTheme']; }

			// Read default VIEW | Check if View exists as well!
			if ( isset ($_POST['User_DefaultViewsID']) && isset($content['Views'][$_POST['User_DefaultViewsID']] )) { $USERCFG['DefaultViewsID'] = $_POST['User_DefaultViewsID']; }

			// Read default SOURCES | Check if Source exists as well!
			if ( isset ($_POST['User_DefaultSourceID']) && isset($content['Sources'][$_POST['User_DefaultSourceID']] )) { $USERCFG['DefaultSourceID'] = $_POST['User_DefaultSourceID']; }

			// Read checkboxes
			if ( isset ($_POST['User_ViewUseTodayYesterday']) ) { $USERCFG['ViewUseTodayYesterday'] = 1; } else { $USERCFG['ViewUseTodayYesterday'] = 0; } 
			if ( isset ($_POST['User_ViewEnableDetailPopups']) ) { $USERCFG['ViewEnableDetailPopups'] = 1; } else { $USERCFG['ViewEnableDetailPopups'] = 0; } 
			if ( isset ($_POST['User_EnableContextLinks']) ) { $USERCFG['EnableContextLinks'] = 1; } else { $USERCFG['EnableContextLinks'] = 0; } 
			if ( isset ($_POST['User_EnableIPAddressResolve']) ) { $USERCFG['EnableIPAddressResolve'] = 1; } else { $USERCFG['EnableIPAddressResolve'] = 0; } 
			if ( isset ($_POST['User_MiscShowDebugMsg']) ) { $USERCFG['MiscShowDebugMsg'] = 1; } else { $USERCFG['MiscShowDebugMsg'] = 0; } 
			if ( isset ($_POST['User_MiscShowDebugGridCounter']) ) { $USERCFG['MiscShowDebugGridCounter'] = 1; } else { $USERCFG['MiscShowDebugGridCounter'] = 0; } 
			if ( isset ($_POST['User_MiscShowPageRenderStats']) ) { $USERCFG['MiscShowPageRenderStats'] = 1; } else { $USERCFG['MiscShowPageRenderStats'] = 0; } 
			if ( isset ($_POST['User_MiscEnableGzipCompression']) ) { $USERCFG['MiscEnableGzipCompression'] = 1; } else { $USERCFG['MiscEnableGzipCompression'] = 0; } 
			if ( isset ($_POST['User_SuppressDuplicatedMessages']) ) { $USERCFG['SuppressDuplicatedMessages'] = 1; } else { $USERCFG['SuppressDuplicatedMessages'] = 0; } 
			if ( isset ($_POST['User_InlineOnlineSearchIcons']) ) { $USERCFG['InlineOnlineSearchIcons'] = 1; } else { $USERCFG['InlineOnlineSearchIcons'] = 0; } 
			if ( isset ($_POST['User_TreatNotFoundFiltersAsTrue']) ) { $USERCFG['TreatNotFoundFiltersAsTrue'] = 1; } else { $USERCFG['TreatNotFoundFiltersAsTrue'] = 0; } 

			// Read Text number fields
			if ( isset ($_POST['User_ViewMessageCharacterLimit']) && is_numeric($_POST['User_ViewMessageCharacterLimit']) ) { $USERCFG['ViewMessageCharacterLimit'] = $_POST['User_ViewMessageCharacterLimit']; }
			if ( isset ($_POST['User_ViewStringCharacterLimit']) && is_numeric($_POST['User_ViewStringCharacterLimit']) ) { $USERCFG['ViewStringCharacterLimit'] = $_POST['User_ViewStringCharacterLimit']; }
			if ( isset ($_POST['User_PopupMenuTimeout']) && is_numeric($_POST['User_PopupMenuTimeout']) ) { $USERCFG['PopupMenuTimeout'] = $_POST['User_PopupMenuTimeout']; }
			if ( isset ($_POST['User_ViewEntriesPerPage']) && is_numeric($_POST['User_ViewEntriesPerPage']) ) { $USERCFG['ViewEntriesPerPage'] = $_POST['User_ViewEntriesPerPage']; }
			if ( isset ($_POST['User_ViewEnableAutoReloadSeconds']) && is_numeric($_POST['User_ViewEnableAutoReloadSeconds']) ) { $USERCFG['ViewEnableAutoReloadSeconds'] = $_POST['User_ViewEnableAutoReloadSeconds']; }
			if ( isset ($_POST['User_AdminChangeWaitTime']) && is_numeric($_POST['User_AdminChangeWaitTime']) ) { $USERCFG['AdminChangeWaitTime'] = $_POST['User_AdminChangeWaitTime']; }
// TODO!!!!!!!!!!!111111111			

			// Read Text fields
			if ( isset ($_POST['User_PrependTitle']) ) { $USERCFG['PrependTitle'] = $_POST['User_PrependTitle']; }
			if ( isset ($_POST['User_SearchCustomButtonCaption']) ) { $USERCFG['SearchCustomButtonCaption'] = $_POST['User_SearchCustomButtonCaption']; }
			if ( isset ($_POST['User_SearchCustomButtonSearch']) ) { $USERCFG['SearchCustomButtonSearch'] = $_POST['User_SearchCustomButtonSearch']; }

			// Save configuration variables now
			SaveUserGeneralSettingsIntoDB();
		}

		// Do a redirect
		RedirectResult( $content['LN_GEN_SUCCESSFULLYSAVED'], "index.php" );
	}
}

// PreInit newer values if necessary
if ( !isset($content['SuppressDuplicatedMessages']) ) { $content['SuppressDuplicatedMessages'] = 0; }
if ( !isset($content['TreatNotFoundFiltersAsTrue']) ) { $content['TreatNotFoundFiltersAsTrue'] = 0; }
if ( !isset($content['InlineOnlineSearchIcons']) ) { $content['InlineOnlineSearchIcons'] = 1; }
if ( !isset($content['AdminChangeWaitTime']) ) { $content['AdminChangeWaitTime'] = 2; }

// Set checkbox States
if (isset($content['ViewUseTodayYesterday']) && $content['ViewUseTodayYesterday'] == 1) { $content['ViewUseTodayYesterday_checked'] = "checked"; } else { $content['ViewUseTodayYesterday_checked'] = ""; }
if (isset($content['ViewEnableDetailPopups']) && $content['ViewEnableDetailPopups'] == 1) { $content['ViewEnableDetailPopups_checked'] = "checked"; } else { $content['ViewEnableDetailPopups_checked'] = ""; }
if (isset($content['EnableContextLinks']) && $content['EnableContextLinks'] == 1) { $content['EnableContextLinks_checked'] = "checked"; } else { $content['EnableContextLinks_checked'] = ""; }
if (isset($content['EnableIPAddressResolve']) && $content['EnableIPAddressResolve'] == 1) { $content['EnableIPAddressResolve_checked'] = "checked"; } else { $content['EnableIPAddressResolve_checked'] = ""; }

if (isset($content['MiscShowDebugMsg']) && $content['MiscShowDebugMsg'] == 1) { $content['MiscShowDebugMsg_checked'] = "checked"; } else { $content['MiscShowDebugMsg_checked'] = ""; }
if (isset($content['MiscShowDebugGridCounter']) && $content['MiscShowDebugGridCounter'] == 1) { $content['MiscShowDebugGridCounter_checked'] = "checked"; } else { $content['MiscShowDebugGridCounter_checked'] = ""; }
if (isset($content['MiscShowPageRenderStats']) && $content['MiscShowPageRenderStats'] == 1) { $content['MiscShowPageRenderStats_checked'] = "checked"; } else { $content['MiscShowPageRenderStats_checked'] = ""; }
if (isset($content['MiscEnableGzipCompression']) && $content['MiscEnableGzipCompression'] == 1) { $content['MiscEnableGzipCompression_checked'] = "checked"; } else { $content['MiscEnableGzipCompression_checked'] = ""; }
if (isset($content['SuppressDuplicatedMessages']) && $content['SuppressDuplicatedMessages'] == 1) { $content['SuppressDuplicatedMessages_checked'] = "checked"; } else { $content['SuppressDuplicatedMessages_checked'] = ""; }
if (isset($content['TreatNotFoundFiltersAsTrue']) && $content['TreatNotFoundFiltersAsTrue'] == 1) { $content['TreatNotFoundFiltersAsTrue_checked'] = "checked"; } else { $content['TreatNotFoundFiltersAsTrue_checked'] = ""; }
if (isset($content['InlineOnlineSearchIcons']) && $content['InlineOnlineSearchIcons'] == 1) { $content['InlineOnlineSearchIcons_checked'] = "checked"; } else { $content['InlineOnlineSearchIcons_checked'] = ""; }

if (isset($content['DebugUserLogin']) && $content['DebugUserLogin'] == 1) { $content['DebugUserLogin_checked'] = "checked"; } else { $content['DebugUserLogin_checked'] = ""; }
if (isset($content['MiscDebugToSyslog']) && $content['MiscDebugToSyslog'] == 1) { $content['MiscDebugToSyslog_checked'] = "checked"; } else { $content['MiscDebugToSyslog_checked'] = ""; }
// --- 

// --- Init for Style field!

// copy STYLES Array
$content['GLOBAL_STYLES'] = $content['STYLES'];
$defaultStyleID = GetConfigSetting('ViewDefaultTheme', "default", CFGLEVEL_GLOBAL);
foreach ( $content['GLOBAL_STYLES'] as &$myStyle )
{
	if ( $myStyle['StyleName'] == $defaultStyleID )
		$myStyle['selected'] = "selected";
	else
		$myStyle['selected'] = "";
}
// --- 

// --- Init for ViewDefaultLanguage field!
// copy LANGUAGES Array
$content['GLOBAL_LANGUAGES'] = $content['LANGUAGES'];

$defaultLangID = GetConfigSetting('ViewDefaultLanguage', "en", CFGLEVEL_GLOBAL);
foreach ( $content['GLOBAL_LANGUAGES'] as &$myLang )
{
	if ( $myLang['langcode'] == $defaultLangID )
		$myLang['selected'] = "selected";
	else
		$myLang['selected'] = "";
}
// --- 

// --- Init for DefaultView field!
// copy Views Array
$content['VIEWS'] = $content['Views'];
if ( !isset($content['DefaultViewsID']) ) { $content['DefaultViewsID'] = 'SYSLOG'; }
foreach ( $content['VIEWS'] as &$myView )
{
	if ( $myView['ID'] == $content['DefaultViewsID'] )
		$myView['selected'] = "selected";
	else
		$myView['selected'] = "";
}
// --- 

// --- Init for DefaultSource  field!
// copy Sources Array
$content['SOURCES'] = $content['Sources'];
if ( !isset($content['DefaultSourceID']) ) { $content['DefaultSourceID'] = ''; }
foreach ( $content['SOURCES'] as &$mySource )
{
	if ( $mySource['ID'] == $content['DefaultSourceID'] )
		$mySource['selected'] = "selected";
	else
		$mySource['selected'] = "";
}
// --- 

// --- Init for DefaultEncoding field!
// copy Sources Array
$content['ENCODINGS'] = $encodings;
// if ( !isset($content['DefaultSourceID']) ) { $content['DefaultSourceID'] = ''; }
foreach ( $content['ENCODINGS'] as &$myEncoding)
{
	$myEncoding['DisplayName'] = 	$myEncoding['ID'];
	if ( $myEncoding['ID'] == $content['HeaderDefaultEncoding'] )
		$myEncoding['selected'] = "selected";
	else
		$myEncoding['selected'] = "";
}
// --- 



// Do if User wants extra options
if ( $content['ENABLEUSEROPTIONS'] )
{
	// Set checkbox States
	if ( GetConfigSetting('ViewUseTodayYesterday', $content['ViewUseTodayYesterday'], CFGLEVEL_USER) == 1) { $content['User_ViewUseTodayYesterday_checked'] = "checked"; } else { $content['User_ViewUseTodayYesterday_checked'] = ""; }
	if ( GetConfigSetting('ViewEnableDetailPopups', $content['ViewEnableDetailPopups'], CFGLEVEL_USER) == 1) { $content['User_ViewEnableDetailPopups_checked'] = "checked"; } else { $content['User_ViewEnableDetailPopups_checked'] = ""; }
	if ( GetConfigSetting('EnableContextLinks', $content['EnableContextLinks'], CFGLEVEL_USER) == 1) { $content['User_EnableContextLinks_checked'] = "checked"; } else { $content['User_EnableContextLinks_checked'] = ""; }
	if ( GetConfigSetting('EnableIPAddressResolve', $content['EnableIPAddressResolve'], CFGLEVEL_USER) == 1) { $content['User_EnableIPAddressResolve_checked'] = "checked"; } else { $content['User_EnableIPAddressResolve_checked'] = ""; }

	if ( GetConfigSetting('MiscShowDebugMsg', $content['MiscShowDebugMsg'], CFGLEVEL_USER) == 1) { $content['User_MiscShowDebugMsg_checked'] = "checked"; } else { $content['User_MiscShowDebugMsg_checked'] = ""; }
	if ( GetConfigSetting('MiscShowDebugGridCounter', $content['MiscShowDebugGridCounter'], CFGLEVEL_USER) == 1) { $content['User_MiscShowDebugGridCounter_checked'] = "checked"; } else { $content['User_MiscShowDebugGridCounter_checked'] = ""; }
	if ( GetConfigSetting('MiscShowPageRenderStats', $content['MiscShowPageRenderStats'], CFGLEVEL_USER) == 1) { $content['User_MiscShowPageRenderStats_checked'] = "checked"; } else { $content['User_MiscShowPageRenderStats_checked'] = ""; }
	if ( GetConfigSetting('MiscEnableGzipCompression', $content['MiscEnableGzipCompression'], CFGLEVEL_USER) == 1) { $content['User_MiscEnableGzipCompression_checked'] = "checked"; } else { $content['User_MiscEnableGzipCompression_checked'] = ""; }
	if ( GetConfigSetting('SuppressDuplicatedMessages', $content['SuppressDuplicatedMessages'], CFGLEVEL_USER) == 1) { $content['User_SuppressDuplicatedMessages_checked'] = "checked"; } else { $content['User_SuppressDuplicatedMessages_checked'] = ""; }
	if ( GetConfigSetting('TreatNotFoundFiltersAsTrue', $content['TreatNotFoundFiltersAsTrue'], CFGLEVEL_USER) == 1) { $content['User_TreatNotFoundFiltersAsTrue_checked'] = "checked"; } else { $content['User_TreatNotFoundFiltersAsTrue_checked'] = ""; }
	if ( GetConfigSetting('InlineOnlineSearchIcons', $content['InlineOnlineSearchIcons'], CFGLEVEL_USER) == 1) { $content['User_InlineOnlineSearchIcons_checked'] = "checked"; } else { $content['User_InlineOnlineSearchIcons_checked'] = ""; }
	// --- 

	// --- Set TextFields!
	$content['User_PrependTitle'] = GetConfigSetting('PrependTitle', $content['PrependTitle'], CFGLEVEL_USER);
	$content['User_ViewMessageCharacterLimit'] = GetConfigSetting('ViewMessageCharacterLimit', $content['ViewMessageCharacterLimit'], CFGLEVEL_USER);
	$content['User_ViewStringCharacterLimit'] = GetConfigSetting('ViewStringCharacterLimit', $content['ViewStringCharacterLimit'], CFGLEVEL_USER);
	$content['User_PopupMenuTimeout'] = GetConfigSetting('PopupMenuTimeout', $content['PopupMenuTimeout'], CFGLEVEL_USER);
	$content['User_ViewEntriesPerPage'] = GetConfigSetting('ViewEntriesPerPage', $content['ViewEntriesPerPage'], CFGLEVEL_USER);
	$content['User_ViewEnableAutoReloadSeconds'] = GetConfigSetting('ViewEnableAutoReloadSeconds', $content['ViewEnableAutoReloadSeconds'], CFGLEVEL_USER);
	$content['User_AdminChangeWaitTime'] = GetConfigSetting('AdminChangeWaitTime', $content['AdminChangeWaitTime'], CFGLEVEL_USER);
	$content['User_SearchCustomButtonCaption'] = GetConfigSetting('SearchCustomButtonCaption', $content['SearchCustomButtonCaption'], CFGLEVEL_USER);
	$content['User_SearchCustomButtonSearch'] = GetConfigSetting('SearchCustomButtonSearch', $content['SearchCustomButtonSearch'], CFGLEVEL_USER);
	// ---

	// --- Init for ViewDefaultTheme field!
	// copy STYLES Array
	$content['USER_STYLES'] = $content['STYLES'];
	$userStyleID = GetConfigSetting('ViewDefaultTheme', $content['ViewDefaultTheme'], CFGLEVEL_USER);
	foreach ( $content['USER_STYLES'] as &$myStyle )
	{
		if ( $myStyle['StyleName'] == $userStyleID )
			$myStyle['selected'] = "selected";
		else
			$myStyle['selected'] = "";
	}
	// --- 

	// --- Init for ViewDefaultLanguage field!
	// copy LANGUAGES Array
	$content['USER_LANGUAGES'] = $content['LANGUAGES'];
	$userLangID = GetConfigSetting('ViewDefaultLanguage', $content['ViewDefaultLanguage'], CFGLEVEL_USER);
	foreach ( $content['USER_LANGUAGES'] as &$myLang )
	{
		if ( $myLang['langcode'] == $userLangID )
			$myLang['selected'] = "selected";
		else
			$myLang['selected'] = "";
	}
	// --- 

	// --- Init for DefaultView field!
	// copy Views Array
	$content['USER_VIEWS'] = $content['Views'];
	$userViewID = GetConfigSetting('DefaultViewsID', $content['DefaultViewsID'], CFGLEVEL_USER);
	foreach ( $content['USER_VIEWS'] as &$myView )
	{
		if ( $myView['ID'] == $userViewID )
			$myView['selected'] = "selected";
		else
			$myView['selected'] = "";
	}
	// --- 

	// --- Init for DefaultSource field!
	// copy Sources Array
	$content['USER_SOURCES'] = $content['Sources'];
	$userSourceID = GetConfigSetting('DefaultSourceID', $content['DefaultSourceID'], CFGLEVEL_USER);
	foreach ( $content['USER_SOURCES'] as &$mySource )
	{
		if ( $mySource['ID'] == $userSourceID )
			$mySource['selected'] = "selected";
		else
			$mySource['selected'] = "";
	}
	// --- 
}

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
$content['TITLE'] .= " :: " . $content['LN_ADMINMENU_GENOPT'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "admin/admin_index.html");
$page -> output(); 
// --- 


?>