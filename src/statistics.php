<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Details File											
	*																	
	* -> Shows Statistic, Charts and more
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
$gl_root_path = './';

// Now include necessary include files!
include($gl_root_path . 'include/functions_common.php');
include($gl_root_path . 'include/functions_frontendhelpers.php');
include($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
include($gl_root_path . 'classes/logstream.class.php');

InitPhpLogCon();
InitSourceConfigs();
InitFrontEndDefaults();	// Only in WebFrontEnd
InitFilterHelpers();	// Helpers for frontend filtering!
// ---

// --- CONTENT Vars
// ---

// --- BEGIN Custom Code
/*if ( isset($content['Sources'][$currentSourceID]) )
{
	// Obtain and get the Config Object
	$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

	// Create LogStream Object 
	$stream = $stream_config->LogStreamFactory($stream_config);
	$res = $stream->Open( $content['AllColumns'], true );
	if ( $res == SUCCESS ) 
	{
		// This will enable to Stats View 
		$content['statsenabled'] = "true";



	}
	else
	{
		// This will disable to Stats View and show an error message
		$content['statsenabled'] = "false";

		// Set error code 
		$content['error_code'] = $ret;

		if ( $ret == ERROR_FILE_NOT_FOUND ) 
			$content['detailederror'] = $content['LN_ERROR_FILE_NOT_FOUND'];
		else if ( $ret == ERROR_FILE_NOT_READABLE ) 
			$content['detailederror'] = $content['LN_ERROR_FILE_NOT_READABLE'];
		else 
			$content['detailederror'] = $content['LN_ERROR_UNKNOWN'];
	}

	// Close file!
	$stream->Close();
}
*/

// --- 

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();

// Append custom title part!
$content['TITLE'] .= " :: " . $content['LN_MENU_STATISTICS'];
// --- END CREATE TITLE

// --- Parsen and Output
InitTemplateParser();
$page -> parser($content, "statistics.html");
$page -> output(); 
// --- 


?>