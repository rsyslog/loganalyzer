<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Report Helper functions											*
	*																	*
	* -> 		*
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008-2011 Adiscon GmbH.
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

// --- 
// --- BEGIN Helper functions 
// --- 
function CreateCronCommand( $myReportID, $mySavedReportID = null )
{
	global $content, $gl_root_path, $myReport; 

	if ( isset($mySavedReportID) ) 
	{
		// Get Reference to report!
		$myReport = $content['REPORTS'][ $myReportID ];

		// Get reference to savedreport
		$mySavedReport = $myReport['SAVEDREPORTS'][ $mySavedReportID ]; 

		// Get configured Source for savedreport
		$myReportSource = null; 
		if ( isset($content['Sources'][ $mySavedReport['sourceid'] ]) ) 
			$myReportSource = $content['Sources'][ $mySavedReport['sourceid'] ]; 

		$pos = strpos( strtoupper(PHP_OS), "WIN");
		if ($pos !== false) 
		{	
			// Running on Windows
			$phpCmd = PHP_BINDIR . "\\php.exe"; 
			$phpScript = realpath($gl_root_path) . "\\cron\\cmdreportgen.php";
		} 
		else 
		{
			// Running on LINUX
			$phpCmd = PHP_BINDIR . "/php"; 
			$phpScript = realpath($gl_root_path) . "/cron/cmdreportgen.php";
		}
		
		// Enable display of report command
		$content['enableCronCommand'] = true;
		$szCommand = $phpCmd . " " . $phpScript . " runreport " . $myReportID . " " . $mySavedReportID;

		// --- Check for user or group sources 
		if ( $myReportSource['userid'] != null ) 
		{
			$szCommand .= " " . "userid=" . $myReportSource['userid']; 
		}
		else if ( $myReportSource['groupid'] != null ) 
		{
			$szCommand .= " " . "groupid=" . $myReportSource['groupid']; 
		}
		// --- 
	}
	else
	{
		// Disable display of report command
		$content['enableCronCommand'] = false;
		$szCommand = "";
	}

	// return result 
	return $szCommand; 
}

function InitOnlineReports()
{
	global $content; 

	$xmlArray = xml2array( URL_ONLINEREPORTS ); 
	if ( is_array($xmlArray) && isset($xmlArray['reports']['report']) && count($xmlArray['reports']['report']) > 0 ) 
	{
		foreach( $xmlArray['reports']['report'] as $myOnlineReport ) 
		{
			// Copy to OnlineReports Array
			$content['ONLINEREPORTS'][] = $myOnlineReport; 
		}

		// Success!
		return true;
	}
	else
		// Failure
		return false;
}

// Helper function from php doc
function xml2array($url, $get_attributes = 1, $priority = 'tag')
{
    $contents = "";
    if (!function_exists('xml_parser_create'))
    {
        return false;
    }
    $parser = xml_parser_create('');
    if (!($fp = @ fopen($url, 'rb')))
    {
        return false;
    }
    while (!feof($fp))
    {
        $contents .= fread($fp, 8192);
    }
    fclose($fp);
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return; //Hmm...
    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    $current = & $xml_array;
    $repeated_tag_index = array (); 
    foreach ($xml_values as $data)
    {
        unset ($attributes, $value);
        extract($data);
        $result = array ();
        $attributes_data = array ();
        if (isset ($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset ($attributes) and $get_attributes)
        {
            foreach ($attributes as $attr => $val)
            {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open")
        { 
            $parent[$level -1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current))))
            {
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else
            {
                if (isset ($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                { 
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    ); 
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset ($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset ($current[$tag][0]) and is_array($current[$tag]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data)
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    ); 
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset ($current[$tag . '_attr']))
                        { 
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if ($attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close')
        {
            $current = & $parent[$level -1];
        }
    }
    return ($xml_array);
}


?>