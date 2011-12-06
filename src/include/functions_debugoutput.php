<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Helperfunctions to print debug info 
	*
	* ->
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
	* distribution.
	* 
	* Adiscon LogAnalyzer is also available under a commercial license.
	* For details, contact info@adiscon.com or visit
	* http://loganalyzer.adiscon.com/commercial
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

function CreateHTMLHeader()
{
	global $RUNMODE, $content, $gl_root_path;


	// not needed in console mode
	if ( $RUNMODE == RUNMODE_COMMANDLINE )
		return;

	global $currentclass, $currentmenuclass;
	$currentclass = "line0";
	$currentmenuclass = "cellmenu1";

	print ('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
			<html>
			<head>
			<link rel="stylesheet" href="' . $gl_root_path . 'css/defaults.css" type="text/css">
			<link rel="stylesheet" href="' . $gl_root_path . 'css/menu.css" type="text/css">
			<link rel="stylesheet" href="' . $gl_root_path . 'themes/' . $content['web_theme'] . '/main.css" type="text/css">
			</head>
			<SCRIPT language="JavaScript">
				var g_intervalID;
				function scrolldown()
				{
//					scrollTo(0, 1000000);
				}
				// Always scroll down
				g_intervalID = setInterval("scrolldown()",250);
			</SCRIPT>
			<body TOPMARGIN="0" LEFTMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" OnLoad="scrolldown; clearInterval(g_intervalID);"><br>
			');
}

function PrintDebugInfoHeader()
{
	global $RUNMODE; 
	global $currentmenuclass;

	if ( $RUNMODE == RUNMODE_COMMANDLINE )
		print ( "Num.\tFacility . \tDebug Message\n" );
	else if ( $RUNMODE == RUNMODE_WEBSERVER )
	{
	print('	<table width="100%" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#777777">
			<tr> 
				<td class="' . $currentmenuclass . '" width="50" align="center" nowrap><B>Number</B></td>
				<td class="' . $currentmenuclass . '" width="100" align="center" nowrap><B>DebugLevel</B></td>
				<td class="' . $currentmenuclass . '" width="150" align="center" nowrap><B>Facility</B></td>
				<td class="' . $currentmenuclass . '" width="100%" align="center" ><B>DebugMessage</B></td>
			</tr>
			</table>');
	}
}

function PrintHTMLDebugInfo( $facility, $fromwhere, $szDbgInfo )
{
	global $content, $currentclass, $currentmenuclass, $gldbgcounter, $DEBUGMODE, $RUNMODE;

	// No output in this case
	if ( $facility > $DEBUGMODE )
		return;

	if ( !isset($gldbgcounter) )
		$gldbgcounter = 0;
	$gldbgcounter++;

	if ( $RUNMODE == RUNMODE_COMMANDLINE )
		print ( $gldbgcounter . ". \t" . GetFacilityAsString($facility) . ". \t" . $fromwhere . ". \t" . $szDbgInfo . "\n" );
	else if ( $RUNMODE == RUNMODE_WEBSERVER )
	{
		print ('<table width="100%" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#777777">
				<tr> 
					<td class="' . $currentmenuclass . '" width="50" align="center" nowrap><B>' . $gldbgcounter . '</B></td>
					<td class="' . GetDebugClassFacilityAsString($facility) . '" width="100" align="center" nowrap><B>' . GetFacilityAsString($facility) . '</B></td>
					<td class="' . $currentclass . '" width="150" align="center" nowrap><B>' . $fromwhere . '</B></td>
					<td class="' . $currentclass . '" width="100%">&nbsp;&nbsp;' . $szDbgInfo . '</td>
				</tr>
				</table>');

		// Set StyleSheetclasses
		if ( $currentclass == "line0" )
			$currentclass = "line1";
		else
			$currentclass = "line0";
		if ( $currentmenuclass == "cellmenu1" )
			$currentmenuclass = "cellmenu2";
		else
			$currentmenuclass = "cellmenu1";
	}

	//Flush output
	FlushHtmlOutput();
}

function GetFacilityAsString( $facility )
{
	switch ( $facility )
	{
		case DEBUG_ULTRADEBUG:
			return STR_DEBUG_ULTRADEBUG;
		case DEBUG_DEBUG:
			return STR_DEBUG_DEBUG;
		case DEBUG_INFO:
			return STR_DEBUG_INFO;
		case DEBUG_WARN:
			return STR_DEBUG_WARN;
		case DEBUG_ERROR:
			return STR_DEBUG_ERROR;
		case DEBUG_ERROR_WTF:
			return STR_DEBUG_ERROR_WTF;
	}
	
	// reach here = unknown
	return "*Unknown*";
}

function GetDebugClassFacilityAsString( $facility )
{
	switch ( $facility )
	{
		case DEBUG_ULTRADEBUG:
			return "debugultradebug";
		case DEBUG_DEBUG:
			return "debugdebug";
		case DEBUG_INFO:
			return "debuginfo";
		case DEBUG_WARN:
			return "debugwarn";
		case DEBUG_ERROR:
			return "debugerror";
		case DEBUG_ERROR_WTF:
			return "debugerrorwtf";
	}
	
	// reach here = unknown
	return "*Unknown*";
}

function CreateHTMLFooter()
{
	global $content, $ParserStart, $RUNMODE;
	$RenderTime = number_format( microtime_float() - $ParserStart, 4, '.', '');
	
	// not needed in console mode
	if ( $RUNMODE == RUNMODE_COMMANDLINE )
		return;

	print ('<br><center><h3>Finished</h3>
			Total running time was ' . $RenderTime . ' seconds
			<br><br>
			<br>
			</center>
			</body> 
			</html>');
}


?>