<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Installer needed functions
	*																	
	* -> 		Functions in this file are only used by the installer 
	*			and converter script. 
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
	* distribution.
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

// --- BEGIN Installer Helper Functions --- 
function ImportDataFile($szFileName)
{
	global $content, $totaldbdefs;

	// Lets read the table definitions :)
	$handle = @fopen($szFileName, "r");
	if ($handle === false) 
		RevertOneStep( $content['INSTALL_STEP']-1, GetAndReplaceLangStr($content['LN_INSTALL_ERRORREADINGDBFILE'], $szFileName) );
	else
	{
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);

			$pos = strpos($buffer, "--");
			if ($pos === false)
				$totaldbdefs .= $buffer; 
			else if ( $pos > 2 && strlen( trim($buffer) ) > 1 )
				$totaldbdefs .= $buffer; 
		}
	   fclose($handle);
	}
}

function RevertOneStep($stepback, $errormsg)
{
	header("Location: " . STEPSCRIPTNAME . "?step=" . $stepback . "&errormsg=" . urlencode($errormsg) );
	exit;
}

function ForwardOneStep()
{
	global $content; 

	header("Location: " . STEPSCRIPTNAME . "?step=" . ($content['INSTALL_STEP']+1) );
	exit;
}

// --- 

?>