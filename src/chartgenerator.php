<?php
/*
	*********************************************************************
	* phpLogCon - http://www.phplogcon.org
	* -----------------------------------------------------------------
	* Export Code File											
	*																	
	* -> This file will create gfx of charts, and handle image caching
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

// --- READ CONTENT Vars
$content['error_occured'] = false;

if ( isset($_GET['type']) ) 
	$content['chart_type'] = intval($_GET['type']);
else
	$content['chart_type'] = CHART_CAKE;

if ( isset($_GET['width']) ) 
{
	$content['chart_width'] = intval($_GET['width']);
	
	// Limit Chart Size for now
	if		( $content['chart_width'] < 100 ) 
		$content['chart_width'] = 100;
	else if	( $content['chart_width'] > 1000 ) 
		$content['chart_width'] = 1000;
}
else
	$content['chart_width'] = 100;

if ( isset($_GET['byfield']) )
{
	if ( isset($fields[ $_GET['byfield'] ]) )
	{
		$content['chart_field'] = $_GET['byfield'];
		$content['chart_fieldtype'] = $fields[SYSLOG_UID]['FieldType'];
	}
	else
	{
		$content['error_occured'] = true;
		$content['error_details'] = $content['LN_GEN_ERROR_INVALIDFIELD'];
	}
}
else
{
	$content['error_occured'] = true;
	$content['error_details'] = $content['LN_GEN_ERROR_MISSINGCHARTFIELD'];
}
// ---

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
// --- END CREATE TITLE

// --- BEGIN Custom Code

include_once ($gl_root_path . "classes/jpgraph/jpgraph.php");
include_once ($gl_root_path . "classes/jpgraph/jpgraph_bar.php");

// Create Basic Image!
$myGraph = new Graph($content['chart_width'], $content['chart_width'], 'auto');
$myGraph->SetScale("textlin");
$myGraph->img->SetMargin(60,30,20,40);
$myGraph->yaxis->SetTitleMargin(45);
$myGraph->yaxis->scale->SetGrace(30);
$myGraph->SetShadow();

// Turn the tickmarks
$myGraph->xaxis->SetTickSide(SIDE_DOWN);
$myGraph->yaxis->SetTickSide(SIDE_LEFT);

// Get data and print on the image!
if ( !$content['error_occured'] )
{
	if ( isset($content['Sources'][$currentSourceID]) ) 
	{
		// Obtain and get the Config Object
		$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

		// Create LogStream Object 
		$stream = $stream_config->LogStreamFactory($stream_config);
		
		$res = $stream->Open( $content['Columns'], true );
		if ( $res == SUCCESS )
		{
			// Obtain data from the logstream!
			$chartData = $stream->GetCountSortedByField($content['chart_field'], $content['chart_fieldtype']);

			// If data is valid, we have an array!
			if ( is_array($chartData) )
			{
				// Sort Array, so the highest count comes first!
				array_multisort($chartData, SORT_NUMERIC, SORT_DESC);

				// Create y array!
				foreach( $chartData as $myYData)
					$YchartData[] = intval($myYData);

				//print_r ($chartData);
				//$datay=array(12,26,9,17,31);

				// Create a bar pot
				$bplot = new BarPlot($YchartData);
//				$bplot->SetFillColor("orange");

				// Use a shadow on the bar graphs (just use the default settings)
				$bplot->SetShadow();
				$bplot->value->SetFormat("%2.0f",70);
				$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,9);
				$bplot->value->SetColor("blue");
				$bplot->value->Show();

				$myGraph->Add($bplot);

				$myGraph->title->Set("Chart blabla");
				$myGraph->xaxis->title->Set("X-title");
				$myGraph->yaxis->title->Set("Y-title");


			}
			else
			{
				$content['error_occured'] = true;
				$content['error_details'] = GetErrorMessage($chartData);
				if ( isset($extraErrorDescription) )
					$content['error_details'] .= "\n\n" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
			}


//$fields[SYSLOG_UID]['FieldID']

		}
		else
		{
			// This will disable to Main SyslogView and show an error message
			$content['error_occured'] = true;
			$content['error_details'] = GetErrorMessage($res);
			if ( isset($extraErrorDescription) )
				$content['error_details'] .= "\n\n" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
		}

		// Close file!
		$stream->Close();
	}
	else
	{
		$content['error_occured'] = true;
		$content['error_details'] = GetAndReplaceLangStr( $content['LN_GEN_ERROR_SOURCENOTFOUND'], $currentSourceID);
	}
}

if ( $content['error_occured'] )
{
	// QUICK AND DIRTY!
	$myImage = imagecreatetruecolor( $content['chart_width'], $content['chart_width']);

/*	// create basic colours
	$red = ImageColorAllocate($myImage, 255, 0, 0); 
	$green = ImageColorAllocate($myImage, 0, 255, 0);
	$gray = ImageColorAllocate($myImage, 128, 128, 128);
	$black = ImageColorAllocate($myImage, 0, 0, 0);
	$white = ImageColorAllocate($myImage, 255, 255, 255);

	// Fill image with colour, and create a border
	imagerectangle( $myImage, 0, 0, $content['chart_width']-1, $content['chart_width']-1, $gray );
	imagefill( $myImage, 1, 1, $white );
*/
	$text_color = imagecolorallocate($myImage, 255, 0, 0);
	imagestring($myImage, 3, 10, 10, $content['LN_GEN_ERRORDETAILS'], $text_color);
	imagestring($myImage, 3, 10, 25, $content['error_details'], $text_color);

	header ("Content-type: image/png");
	imagepng($myImage);		// Outputs the image to the browser
	imagedestroy($myImage); // Clean Image resource

	exit;
}
// --- 


// --- Output the image

// Send back the HTML page which will call this script again to retrieve the image.
$myGraph->StrokeCSIM();
// --- 

?>