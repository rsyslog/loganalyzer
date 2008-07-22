<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Admin Index File											
	*																	
	* -> Shows ...
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

// --- BEGIN Custom Code

if ( isset($_SESSION['SESSION_ISADMIN']) && $_SESSION['SESSION_ISADMIN'] == 1 ) 
	$content['EditAllowed'] = true;
else	
	$content['EditAllowed'] = false;



// Check for changes first | Abort if Edit is not allowed
if ( isset($_POST['op']) && $content['EditAllowed'] )
{
	if ( $_POST['op'] == "edit" )
	{
		// Language needs special treatment
		if ( isset ($_POST['ViewDefaultLanguage']) )
		{ 
			$tmpvar = DB_RemoveBadChars($_POST['ViewDefaultLanguage']); 
			if ( VerifyLanguage($tmpvar) )
				$content['ViewDefaultLanguage'] = $tmpvar;
		}

		// Read default theme
		if ( isset ($_POST['ViewDefaultTheme']) ) { $content['ViewDefaultTheme'] = DB_RemoveBadChars($_POST['ViewDefaultTheme']); }

		// Read checkboxes
		if ( isset ($_POST['ViewUseTodayYesterday']) ) { $content['ViewUseTodayYesterday'] = 1; } else { $content['ViewUseTodayYesterday'] = 0; } 
		if ( isset ($_POST['ViewEnableDetailPopups']) ) { $content['ViewEnableDetailPopups'] = 1; } else { $content['ViewEnableDetailPopups'] = 0; } 
		if ( isset ($_POST['EnableIPAddressResolve']) ) { $content['EnableIPAddressResolve'] = 1; } else { $content['EnableIPAddressResolve'] = 0; } 
		if ( isset ($_POST['MiscShowDebugMsg']) ) { $content['MiscShowDebugMsg'] = 1; } else { $content['MiscShowDebugMsg'] = 0; } 
		if ( isset ($_POST['MiscShowDebugGridCounter']) ) { $content['MiscShowDebugGridCounter'] = 1; } else { $content['MiscShowDebugGridCounter'] = 0; } 
		if ( isset ($_POST['MiscShowPageRenderStats']) ) { $content['MiscShowPageRenderStats'] = 1; } else { $content['MiscShowPageRenderStats'] = 0; } 
		if ( isset ($_POST['MiscEnableGzipCompression']) ) { $content['MiscEnableGzipCompression'] = 1; } else { $content['MiscEnableGzipCompression'] = 0; } 
		if ( isset ($_POST['DebugUserLogin']) ) { $content['DebugUserLogin'] = 1; } else { $content['DebugUserLogin'] = 0; } 

		// Read Text number fields
		if ( isset ($_POST['ViewMessageCharacterLimit']) && is_numeric($_POST['ViewMessageCharacterLimit']) ) { $content['ViewMessageCharacterLimit'] = DB_RemoveBadChars($_POST['ViewMessageCharacterLimit']); }
		if ( isset ($_POST['ViewEntriesPerPage']) && is_numeric($_POST['ViewEntriesPerPage']) ) { $content['ViewEntriesPerPage'] = DB_RemoveBadChars($_POST['ViewEntriesPerPage']); }
		if ( isset ($_POST['ViewEnableAutoReloadSeconds']) && is_numeric($_POST['ViewEnableAutoReloadSeconds']) ) { $content['ViewEnableAutoReloadSeconds'] = DB_RemoveBadChars($_POST['ViewEnableAutoReloadSeconds']); }

		// Read Text fields
		if ( isset ($_POST['PrependTitle']) ) { $content['PrependTitle'] = DB_RemoveBadChars($_POST['PrependTitle']); }
		if ( isset ($_POST['SearchCustomButtonCaption']) ) { $content['SearchCustomButtonCaption'] = DB_RemoveBadChars($_POST['SearchCustomButtonCaption']); }
		if ( isset ($_POST['SearchCustomButtonSearch']) ) { $content['SearchCustomButtonSearch'] = DB_RemoveBadChars($_POST['SearchCustomButtonSearch']); }

		// Save configuration variables now
		WriteConfigValue( "ViewDefaultLanguage", true );
		WriteConfigValue( "ViewDefaultTheme", true );

		WriteConfigValue( "ViewUseTodayYesterday", true );
		WriteConfigValue( "ViewEnableDetailPopups", true );
		WriteConfigValue( "EnableIPAddressResolve", true );
		WriteConfigValue( "MiscShowDebugMsg", true );
		WriteConfigValue( "MiscShowDebugGridCounter", true );
		WriteConfigValue( "MiscShowPageRenderStats", true );
		WriteConfigValue( "MiscEnableGzipCompression", true );
		WriteConfigValue( "DebugUserLogin", true );

		WriteConfigValue( "ViewMessageCharacterLimit", true );
		WriteConfigValue( "ViewEntriesPerPage", true );
		WriteConfigValue( "ViewEnableAutoReloadSeconds", true );

		WriteConfigValue( "PrependTitle", true );
		WriteConfigValue( "SearchCustomButtonCaption", true );
		WriteConfigValue( "SearchCustomButtonSearch", true );

		// Do a redirect
		RedirectResult( $content['LN_GEN_SUCCESSFULLYSAVED'], "index.php" );
	}
}


// Set checkbox States
if ($content['ViewUseTodayYesterday'] == 1) { $content['ViewUseTodayYesterday_checked'] = "checked"; } else { $content['ViewUseTodayYesterday_checked'] = ""; }
if ($content['ViewEnableDetailPopups'] == 1) { $content['ViewEnableDetailPopups_checked'] = "checked"; } else { $content['ViewEnableDetailPopups_checked'] = ""; }
if ($content['EnableIPAddressResolve'] == 1) { $content['EnableIPAddressResolve_checked'] = "checked"; } else { $content['EnableIPAddressResolve_checked'] = ""; }

if ($content['MiscShowDebugMsg'] == 1) { $content['MiscShowDebugMsg_checked'] = "checked"; } else { $content['MiscShowDebugMsg_checked'] = ""; }
if ($content['MiscShowDebugGridCounter'] == 1) { $content['MiscShowDebugGridCounter_checked'] = "checked"; } else { $content['MiscShowDebugGridCounter_checked'] = ""; }
if ($content['MiscShowPageRenderStats'] == 1) { $content['MiscShowPageRenderStats_checked'] = "checked"; } else { $content['MiscShowPageRenderStats_checked'] = ""; }
if ($content['MiscEnableGzipCompression'] == 1) { $content['MiscEnableGzipCompression_checked'] = "checked"; } else { $content['MiscEnableGzipCompression_checked'] = ""; }
if ($content['DebugUserLogin'] == 1) { $content['DebugUserLogin_checked'] = "checked"; } else { $content['DebugUserLogin_checked'] = ""; }
// --- 

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