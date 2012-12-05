<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Main Index File											
	*																	
	* -> File to login users in LogAnalyzer
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
	* 
	* Adiscon LogAnalyzer is also available under a commercial license.
	* For details, contact info@adiscon.com or visit
	* http://loganalyzer.adiscon.com/commercial
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
//include($gl_root_path . 'include/functions_filters.php');

// To avoid infinite redirects!
define('IS_NOLOGINPAGE', true);
$content['IS_NOLOGINPAGE'] = true;
InitPhpLogCon();
// --- //

// --- BEGIN Custom Code

// Set Defaults
$content['uname'] = "";
$content['pass'] = "";

// Set Referer
if ( isset($_GET['referer']) )
	$szRedir = $_GET['referer'];
else if ( isset($_POST['referer']) )
	$szRedir = $_POST['referer'];
else
	$szRedir = "index.php"; // Default

if ( isset($_POST['op']) && $_POST['op'] == "login" )
{
	// Perform login!
	if ( $_POST['op'] == "login" )
	{
		if ( 
			 (isset($_POST['uname']) && strlen($_POST['uname']) > 0) 
				&& 
			 (isset($_POST['pass']) && strlen($_POST['pass']) > 0)
			)
		{
			// Set Username and password
			$content['uname'] = DB_RemoveBadChars($_POST['uname']);
			$content['pass'] = DB_RemoveBadChars($_POST['pass']);

			if ( !CheckUserLogin( $content['uname'], $content['pass']) )
			{
				$content['ISERROR'] = "true";
				$content['ERROR_MSG'] = $content['LN_LOGIN_ERRWRONGPASSWORD'];
			}
			else
				RedirectPage( urldecode($szRedir) );
		}
		else
		{
			$content['ISERROR'] = "true";
			$content['ERROR_MSG'] = $content['LN_LOGIN_USERPASSMISSING'];
		}
	}
}
else if ( isset($_GET['op']) && $_GET['op'] == "logoff" )
{
	// logoff in this case
	DoLogOff();
}
// --- END Custom Code

// --- CONTENT Vars
$content['REDIR_LOGIN'] = $szRedir;
$content['TITLE'] = InitPageTitle();
// Append custom title part!
$content['TITLE'] .= " :: " . $content['LN_LOGIN_TITLE'];
// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "login.html");
$page -> output(); 
// --- 

?>