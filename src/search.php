<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Main Index File													*
	*																	*
	* -> Loads the main PhpLogCon Site									*
	*																	*
	* All directives are explained within this file						*
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd

// Init Langauge first!
IncludeLanguageFile( $gl_root_path . '/lang/' . $LANG . '/main.php' );

// Helpers for frontend filtering!
InitFilterHelpers();	
// ***					*** //

// --- Extra Javascript?
$content['EXTRA_JAVASCRIPT'] = "<script type='text/javascript' src='" . $content['BASEPATH'] . "js/searchhelpers.js'></script>";
// --- 

// --- CONTENT Vars
// ---

//if ( isset($content['myserver']) ) 
//	$content['TITLE'] = "PhpLogCon :: Home :: Server '" . $content['myserver']['Name'] . "'";	// Title of the Page 
//else
	$content['TITLE'] = "PhpLogCon :: Search";
// --- 

// --- BEGIN Custom Code

// --- 

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "search.html");
$page -> output(); 
// --- 

?>