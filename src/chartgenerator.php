<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Export Code File											
	*																	
	* -> This file will create gfx of charts, and handle image caching
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
if ( !defined('IN_PHPLOGCON') )
	define('IN_PHPLOGCON', true);
$gl_root_path = './';

// Now include necessary include files!
include_once($gl_root_path . 'include/functions_common.php');
include_once($gl_root_path . 'include/functions_frontendhelpers.php');
include_once($gl_root_path . 'include/functions_filters.php');

// Include LogStream facility
include_once($gl_root_path . 'classes/logstream.class.php');

// Include basic jpgraph lib
require_once ($gl_root_path . "classes/jpgraph/jpgraph.php");

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
		$content['chart_fieldtype'] = $fields[ $content['chart_field'] ]['FieldType'];
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

if ( isset($_GET['maxrecords']) ) 
{
	// read and verify value
	$content['maxrecords'] = intval($_GET['maxrecords']);
	if ( $content['maxrecords'] < 2 || $content['maxrecords'] > 100 ) 
		$content['maxrecords'] = 10;
}
else
	$content['maxrecords'] = 10;

if ( isset($_GET['showpercent']) ) 
{
	// read and verify value
	$content['showpercent'] = intval($_GET['showpercent']);
	if ( $content['showpercent'] >= 1 ) 
		$content['showpercent'] = 1;
	else
		$content['showpercent'] = 0;
}
else
	$content['showpercent'] = 0;

if ( isset($_GET['defaultfilter']) )
	$content['chart_defaultfilter'] = $_GET['defaultfilter'];
else
	$content['chart_defaultfilter'] = "";
// ---

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();
// --- END CREATE TITLE

// --- BEGIN Custom Code

// Do not BLOCK other Site Calls
WriteClosePHPSession();

// Get data and print on the image!
if ( !$content['error_occured'] )
{
	if ( isset($content['Sources'][$currentSourceID]) ) 
	{
		// Obtain and get the Config Object
		$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

		// Create LogStream Object 
		$stream = $stream_config->LogStreamFactory($stream_config);
		$stream->SetFilter($content['chart_defaultfilter']);


		// Set Columns we want to open!
		$content['ChartColumns'] = array();
		// First add filter fields to array
		$aFilterFields = $stream->ReturnFieldsByFilters(); 
		if ( $aFilterFields != null ) 
			$content['ChartColumns'] = $aFilterFields; 

		// Append mandetory fields
		if ( !in_array(SYSLOG_UID, $content['ChartColumns']) )
			$content['ChartColumns'][] = SYSLOG_UID;
		if ( !in_array($content['chart_field'], $content['ChartColumns']) )
			$content['ChartColumns'][] = $content['chart_field'];

		// Append fields from defaultfilter as well !
		if ( strlen($content['chart_defaultfilter']) > 0 ) 
		{
			$tmpFilters = explode(" ", $content['chart_defaultfilter']);
			foreach($tmpFilters as $myFilter) 
			{
				if ( strlen(trim($myFilter)) <= 0 ) 
					continue;

				if (	($pos = strpos($myFilter, ":")) !== false 
							&&
						($pos > 0 && substr($myFilter, $pos-1,1) != '\\') /* Only if character before is no backslash! */ )
				{
					// Split key and value
					$tmpArray = explode(":", $myFilter, 2);

					// Check if keyfield is valid and add to our Columns array if available
					$newField = $stream->ReturnFilterKeyBySearchField($tmpArray[FILTER_TMP_KEY]);
					if ( $newField )
						$content['ChartColumns'][] = $newField;
				}
			}
		}
		
		// Open Data Connection!
		$res = $stream->Open( $content['ChartColumns'], true );
		if ( $res == SUCCESS )
		{
			// Obtain data from the logstream!
			$chartData = $stream->GetCountSortedByField($content['chart_field'], $content['chart_fieldtype'], $content['maxrecords']);

			// If data is valid, we have an array!
			if ( is_array($chartData) && count($chartData) > 0 )
			{
				// Create Y array!
				foreach( $chartData as $myKey => $myData)
				{
					// Convert into filter format for submenus
					$szEncodedKeyStr = str_replace(' ', '+', $myKey);

//					echo $myKey . "<br>";
					$YchartData[] = intval($myData);
					$XchartData[] = strlen($myKey) > 0 ? $myKey : "Unknown";
					if ( isset($fields[$content['chart_field']]['SearchField']) && strlen($myKey) > 0 ) 
						$chartImageMapLinks[] = $content['BASEPATH'] . "index.php?filter=" . $fields[$content['chart_field']]['SearchField'] . "%3A%3D" . urlencode($szEncodedKeyStr) . "&search=Search";
					else
						$chartImageMapLinks[] = "";

					$chartImageMapAlts[] =  $fields[$content['chart_field']]['FieldCaption'] . ": " . $myKey;
					$chartImageMapTargets[] ="_top";
				}

				if ( $content['chart_type'] == CHART_CAKE )
				{
					// Include additional code filers for this chart!
					include_once ($gl_root_path . "classes/jpgraph/jpgraph_pie.php");
					include_once ($gl_root_path . "classes/jpgraph/jpgraph_pie3d.php");

					// Create Basic Image, and set basic properties!
					$graph = new PieGraph($content['chart_width'], $content['chart_width'], 'auto');
					$graph->SetMargin(30,20,30,30);	// Adjust margin area
					$graph->SetScale("textlin");
					$graph->SetMarginColor('white');
					$graph->SetBox();					// Box around plotarea

					// Set up the title for the graph
//					$graph->title->Set('Messagecount sorted by "' . $content[ $fields[$content['chart_field']]['FieldCaption'] ] . '"');
//					$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
//					$graph->title->SetColor("darkred");

					// Setup the tab title
					$graph->tabtitle->Set( GetAndReplaceLangStr($content['LN_STATS_CHARTTITLE'], $content['maxrecords'], $fields[$content['chart_field']]['FieldCaption']) );
					$graph->tabtitle->SetFont(FF_VERA,FS_BOLD,9);
					$graph->tabtitle->SetPos('left'); 
					
					// Set Graph footer
					$graph->footer->left->Set ("LogAnalyzer v" . $content['BUILDNUMBER'] . "\n" . GetAndReplaceLangStr($content['LN_STATS_GENERATEDAT'], date("Y-m-d"))  );
					$graph->footer->left->SetFont( FF_VERA, FS_NORMAL, 7); 
//					$graph->footer->right->Set ( GetAndReplaceLangStr($content['LN_STATS_GENERATEDAT'], date("Y-m-d")) ); 
//					$graph->footer->right->SetFont( FF_VERA, FS_NORMAL, 8); 
//					$graph->footer->left->Set ("LogAnalyzer v" . $content['BUILDNUMBER'] . "\n" . GetAndReplaceLangStr($content['LN_STATS_GENERATEDAT'], date("Y-m-d")) ); 
//					$graph->footer->left->SetFont( FF_VERA, FS_NORMAL, 8); 
//					$graph->footer->right->SetColor("darkred");

					// Show 0 label on Y-axis (default is not to show)
					$graph->yscale->ticks->SupressZeroLabel(false);

					// Set Fonts for graph!
					$graph->xaxis->SetFont(FF_VERA,FS_NORMAL,8);
					$graph->yaxis->SetFont(FF_VERA,FS_NORMAL,8);
					$graph->legend->SetFont(FF_VERA,FS_NORMAL,8);

					// Create
					$p1 = new PiePlot3D($YchartData);
					$p1->SetLegends($XchartData);
					$p1->SetEdge('#333333', 1);
					$p1->SetTheme('earth'); /*   "earth"    * "pastel"    * "sand"    * "water" */
					$p1->SetCSIMTargets($chartImageMapLinks, $chartImageMapAlts, $chartImageMapTargets);

					// Set label format
					if ( $content['showpercent'] == 1 )
					{
						$p1->SetLabelType(0);
						$p1->value->SetFormat("%d%%");
					}
					else
					{
						$p1->SetLabelType(1);
						$p1->value->SetFormat("%d");
					}

					// Set label properties
					$p1->SetLabelPos(1.0);
					$p1->SetSliceColors(array('#FFF584','#CBFF84','#FF6B9E','#FF9584','#EAFF84','#7BFF51','#51FFA6','#51FF52','#6BCFFF','#5170FF','#519CFF','#EAE3AD','#FFF184','#8584FF','#E698FF','#C384FF','#FF84EC','#FF98A3','#E5C285','#FFDA98' ));
					$p1->value->SetFont(FF_VERA, FS_NORMAL, 8);
					$p1->value->SetColor("black");

					// Adjust other Pie Properties
					$p1->SetLabelMargin(5);
					$p1->SetCenter(0.4,0.65);
					$p1->SetSize(0.3); 
					$p1->SetAngle(60); 

					$graph->Add($p1);
				}
				else if ( $content['chart_type'] == CHART_BARS_VERTICAL )
				{
					// Include additional code filers for this chart!
					include_once ($gl_root_path . "classes/jpgraph/jpgraph_bar.php");
					include_once ($gl_root_path . "classes/jpgraph/jpgraph_line.php");

					// Create Basic Image, and set basic properties!
					$graph = new Graph($content['chart_width'], $content['chart_width'], 'auto');
					$graph->SetMargin(60,20,30,50);	// Adjust margin area
					$graph->SetScale("textlin");
					$graph->SetMarginColor('white');
					$graph->SetBox();					// Box around plotarea

					// Setup X-AXIS
//					$graph->xaxis->SetFont(FF_VERA,FS_NORMAL,10);
					$graph->xaxis->SetTickLabels($XchartData);
					
					if ( count($XchartData) > 5 ) 
					{
						$graph->SetMargin(60,20,30,80);	// Adjust margin area
						$graph->xaxis->SetLabelAngle(45);
						$graph->xaxis->SetLabelMargin(2);
					}
					else
						$graph->xaxis->SetLabelAngle(0);

//					$graph->xaxis->scale->SetGrace(30); // So the value is readable

					// Setup Y-AXIS
					$graph->yaxis->scale->SetGrace(10); // So the value is readable
//					$graph->yaxis->SetLabelFormat('%d %%'); 

					// Show 0 label on Y-axis (default is not to show)
					$graph->yscale->ticks->SupressZeroLabel(false);

					// Set Fonts for graph!
					$graph->xaxis->SetFont(FF_VERA,FS_NORMAL,7);
					$graph->yaxis->SetFont(FF_VERA,FS_NORMAL,8);

					// Setup the tab title
					$graph->tabtitle->Set( GetAndReplaceLangStr($content['LN_STATS_CHARTTITLE'], $content['maxrecords'], $fields[$content['chart_field']]['FieldCaption']) );
					$graph->tabtitle->SetFont(FF_VERA,FS_BOLD,9);
					$graph->tabtitle->SetPos('left'); 

					// Set Graph footer
					$graph->footer->left->Set ("LogAnalyzer v" . $content['BUILDNUMBER'] . "\n" . GetAndReplaceLangStr($content['LN_STATS_GENERATEDAT'], date("Y-m-d"))  );
					$graph->footer->left->SetFont( FF_VERA, FS_NORMAL, 7); 
//					$graph->footer->right->Set ( GetAndReplaceLangStr($content['LN_STATS_GENERATEDAT'], date("Y-m-d")) ); 
//					$graph->footer->right->SetFont( FF_VERA, FS_NORMAL, 8); 

					// Setup the X and Y grid
					$graph->ygrid->SetFill(true,'#DDDDDD@0.5','#BBBBBB@0.5');
					$graph->ygrid->SetLineStyle('dashed');
					$graph->ygrid->SetColor('gray');
					$graph->xgrid->Show();
					$graph->xgrid->SetLineStyle('dashed');
					$graph->xgrid->SetColor('gray');

					// Create and Add bar pot
					$bplot = new BarPlot($YchartData);
					$bplot->SetWidth(0.6);
					$fcol='#440000';
					$tcol='#FF9090';
					$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);
					$graph->Add($bplot);

					// Display value in bars
					$bplot->value->Show();
					$bplot->value->SetFont(FF_VERA,FS_NORMAL,8);
//					$bplot->value->SetAlign('left','center');
//					$bplot->value->SetColor("black","darkred");
					$bplot->value->SetFormat('%d');
					
					// Add links
					$bplot->SetCSIMTargets($chartImageMapLinks, $chartImageMapAlts, $chartImageMapTargets);


// TODO: Make Optional!
					// Create and Add filled line plot
					$lplot = new LinePlot($YchartData);
					$lplot->SetFillColor('skyblue@0.7');
					$lplot->SetColor('navy@0.7');
					$lplot->SetBarCenter();
					$lplot->mark->SetType(MARK_SQUARE);
					$lplot->mark->SetColor('blue@0.7');
					$lplot->mark->SetFillColor('lightblue');
					$lplot->mark->SetSize(6);
					$graph->Add($lplot);
				}
				else if ( $content['chart_type'] == CHART_BARS_HORIZONTAL )
				{
					// Include additional code filers for this chart!
					include_once ($gl_root_path . "classes/jpgraph/jpgraph_bar.php");
					include_once ($gl_root_path . "classes/jpgraph/jpgraph_line.php");

					// Create Basic Image, and set basic properties!
					$graph = new Graph($content['chart_width'], $content['chart_width'], 'auto');
//					$graph->SetMargin(60,20,30,50);	
					$graph->SetScale("textlin");
					$graph->Set90AndMargin(80,30,30,50); // Adjust margin area
					$graph->SetMarginColor('white');
					$graph->SetBox();					// Box around plotarea

					// Setup X-AXIS
					$graph->xaxis->SetTickLabels($XchartData);
					$graph->xaxis->SetLabelAngle(0);
//					$graph->xaxis->SetLabelAlign('center','top');
					$graph->xaxis->SetPos('min');
					$graph->xaxis->SetLabelMargin(5);
					$graph->xaxis->SetLabelAlign('right','center');

					// Setup Y-AXIS
					$graph->yaxis->scale->SetGrace(20); // So the value is readable
					$graph->yaxis->SetLabelAlign('center','top');
					$graph->yaxis->SetLabelFormat('%d');
					$graph->yaxis->SetLabelSide(SIDE_RIGHT);
					$graph->yaxis->SetTickSide(SIDE_LEFT);
//					$graph->yaxis->SetTitleSide(SIDE_RIGHT);
//					$graph->yaxis->SetTitleMargin(35);
					$graph->yaxis->SetPos('max');
					$graph->yaxis->SetTextLabelInterval(2);

					// Show 0 label on Y-axis (default is not to show)
					$graph->yscale->ticks->SupressZeroLabel(false);

					// Set Fonts for graph!
					$graph->xaxis->SetFont(FF_VERA,FS_NORMAL,7);
					$graph->yaxis->SetFont(FF_VERA,FS_NORMAL,8);

					// Setup the tab title
					$graph->tabtitle->Set( GetAndReplaceLangStr($content['LN_STATS_CHARTTITLE'], $content['maxrecords'], $fields[$content['chart_field']]['FieldCaption']) );
					$graph->tabtitle->SetFont(FF_VERA,FS_BOLD,9);
					$graph->tabtitle->SetPos('right');
					$graph->tabtitle->SetTabAlign('right');

					// Set Graph footer
					$graph->footer->left->Set ("LogAnalyzer v" . $content['BUILDNUMBER'] . "\n" . GetAndReplaceLangStr($content['LN_STATS_GENERATEDAT'], date("Y-m-d"))  );
					$graph->footer->left->SetFont( FF_VERA, FS_NORMAL, 7); 
//					$graph->footer->right->Set ( GetAndReplaceLangStr($content['LN_STATS_GENERATEDAT'], date("Y-m-d")) ); 
//					$graph->footer->right->SetFont( FF_VERA, FS_NORMAL, 8); 

					// Setup the X and Y grid
					$graph->ygrid->SetFill(true,'#DDDDDD@0.5','#BBBBBB@0.5');
					$graph->ygrid->SetLineStyle('dashed');
					$graph->ygrid->SetColor('gray');
					$graph->xgrid->Show();
					$graph->xgrid->SetLineStyle('dashed');
					$graph->xgrid->SetColor('gray');

					// Create and Add bar pot
					$bplot = new BarPlot($YchartData);
					$bplot->SetWidth(0.6);
					$fcol='#440000';
					$tcol='#FF9090';
					$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);
					$graph->Add($bplot);

					// Display value in bars
					$bplot->value->Show();
					$bplot->value->SetFont(FF_VERA,FS_NORMAL, 8);
//					$bplot->value->SetAlign('left','center');
//					$bplot->value->SetColor("black","darkred");
					$bplot->value->SetFormat('%d');

					// Add links
					$bplot->SetCSIMTargets($chartImageMapLinks, $chartImageMapAlts, $chartImageMapTargets);

// TODO: Make Optional!
					// Create and Add filled line plot
					$lplot = new LinePlot($YchartData);
					$lplot->SetFillColor('skyblue@0.7');
					$lplot->SetColor('navy@0.7');
					$lplot->SetBarCenter();
					$lplot->mark->SetType(MARK_SQUARE);
					$lplot->mark->SetColor('blue@0.7');
					$lplot->mark->SetFillColor('lightblue');
					$lplot->mark->SetSize(6);
					$graph->Add($lplot);
				}
				else
				{
					$content['error_occured'] = true;
					$content['error_details'] = $content['LN_GEN_ERROR_INVALIDTYPE'];
				}
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
	// Use JpGraph to display errors!
	$myError = new JpGraphErrObjectImg();
	$myError->SetTitle($content['LN_GEN_ERRORDETAILS']);

	if ( GetConfigSetting("MiscShowDebugMsg", 0, CFGLEVEL_USER) == 1 )
		$myError->Raise($content['error_details'] . "\n\nDebug Details: \n" . var_export($content['DEBUGMSG'], true), true);
	else
		$myError->Raise($content['error_details'], true);

	// Exit in any case
	exit;
}
// --- 

// --- Output the image
//$graph->Stroke();
$graph->StrokeCSIM( basename(__FILE__), '', 0); 
// --- 

?>