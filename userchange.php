<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Main Index File											*
	*																	*
	* -> Loads the main PhpLogCon Site		*
	*																	*
	* All directives are explained within this file						*
	*********************************************************************
*/

// *** Default includes	and procedures *** //
define('IN_PHPLOGCON', true);
$gl_root_path = './';
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');

InitPhpLogCon();
InitFrontEndDefaults();	// Only in WebFrontEnd
// ***					*** //

// --- BEGIN Custom Code
if ( isset($_SERVER['HTTP_REFERER']) )
	$szRedir = $_SERVER['HTTP_REFERER']; 
else
	$szRedir = "index.php"; // Default


if ( isset($_GET['op']) )
{
	if ( $_GET['op'] == "changestyle" ) 
	{
		if ( VerifyTheme($_GET['stylename']) ) 
			$_SESSION['CUSTOM_THEME'] = $_GET['stylename'];
	}

	if ( $_GET['op'] == "changelang" ) 
	{
		if ( VerifyLanguage($_GET['langcode']) ) 
			$_SESSION['CUSTOM_LANG'] = $_GET['langcode'];
	}
}

// Final redirect
RedirectPage( $szRedir );
// --- 
?>