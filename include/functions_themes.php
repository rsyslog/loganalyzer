<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Theme specific functions											*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
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

function list_directories($directory) 
{
	$result = array();
	if (! $directoryHandler = @opendir ($directory)) 
		DieWithFriendlyErrorMsg( "list_directories: directory \"$directory\" doesn't exist!");

	while (false !== ($fileName = @readdir ($directoryHandler))) 
	{
		if	( is_dir( $directory . $fileName ) && ( $fileName != "." && $fileName != ".." ))
			@array_push ($result, $fileName);
	}

	if ( @count ($result) === 0 ) 
		DieWithFriendlyErrorMsg( "list_directories: no directories in \"$directory\" found!");
	else 
	{
		sort ($result);
		return $result;
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

?>