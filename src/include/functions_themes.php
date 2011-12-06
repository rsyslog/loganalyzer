<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Theme specific functions											*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
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

function CreateLanguageList()
{
	global $gl_root_path, $content;

	$alldirectories = list_directories( $gl_root_path . "lang/");
	for($i = 0; $i < count($alldirectories); $i++)
	{
		// --- gen_lang
		$content['LANGUAGES'][$i]['langcode'] = $alldirectories[$i];
		if ( $content['gen_lang'] == $alldirectories[$i] )
			$content['LANGUAGES'][$i]['selected'] = "selected";
		else
			$content['LANGUAGES'][$i]['selected'] = "";
		// ---

		// --- user_lang
		$content['USERLANG'][$i]['langcode'] = $alldirectories[$i];
		if ( $content['user_lang'] == $alldirectories[$i] )
			$content['USERLANG'][$i]['is_selected'] = "selected";
		else
			$content['USERLANG'][$i]['is_selected'] = "";
		// ---

		// Init Language DisplayName
		$content['USERLANG'][$i]['DisplayName'] = GetLanguageDisplayName( $alldirectories[$i] );
		$content['LANGUAGES'][$i]['DisplayName'] = GetLanguageDisplayName( $alldirectories[$i] );
	}
}

function CreateThemesList()
{
	global $gl_root_path, $content;

	$alldirectories = list_directories( $gl_root_path . "themes/");
	for($i = 0; $i < count($alldirectories); $i++)
	{
		// --- web_theme
		$content['STYLES'][$i]['StyleName'] = $alldirectories[$i];
		if ( $content['web_theme'] == $alldirectories[$i] )
			$content['STYLES'][$i]['selected'] = "selected";
		else
			$content['STYLES'][$i]['selected'] = "";
		// ---

		// --- user_theme
		$content['USERSTYLES'][$i]['StyleName'] = $alldirectories[$i];
		if ( $content['user_theme'] == $alldirectories[$i] )
			$content['USERSTYLES'][$i]['is_selected'] = "selected";
		else
			$content['USERSTYLES'][$i]['is_selected'] = "";
		// ---
	}
}

function VerifyTheme( $newtheme ) 
{ 
	global $gl_root_path;

	if ( is_dir( $gl_root_path . "themes/" . $newtheme ) )
		return true;
	else
		return false;
}

function InitThemeAbout( $themename ) 
{
	global $content, $gl_root_path;
	$szAboutFile = $gl_root_path . "themes/" . $themename . "/about.txt";
	if ( is_file( $szAboutFile ) )
	{	//Read About Info!
		$aboutfile  = fopen($szAboutFile, 'r');
		if (!feof ($aboutfile)) 
		{
			while (!feof ($aboutfile))
			{
				$tmpline = fgets($aboutfile, 1024);
				if (!isset($content["theme_madeby"]) )
					$content["theme_madeby"] = substr( trim($tmpline), 0, 25);
				else if (!isset($content["theme_madebylink"]) )
					$content["theme_madebylink"] = substr( trim($tmpline), 0, 256);
				else
				{
					$content["theme_madebyenable"] = "true";
					break;
				}
			}
		}
		fclose($aboutfile);
	}
	else
		$content["theme_madebyenable"] = "false";
}

function GetLanguageDisplayName( $szLangID ) 
{
	global $content, $gl_root_path;
	$szInfoFile = $gl_root_path . "lang/" . $szLangID . "/info.txt";
	if ( is_file( $szInfoFile ) )
	{	
		//Read InfoFile!
		$infofile  = fopen($szInfoFile, 'r');
		if (!feof ($infofile)) 
		{
			while (!feof ($infofile))
			{
				// Return max 32 characters
				$tmpline = fgets($infofile, 1024);
				return substr( trim($tmpline), 0, 32);
			}
		}
		fclose($infofile);
	}
	else // No Info, return ID as DisplayName
		return $szLangID;
}


?>
