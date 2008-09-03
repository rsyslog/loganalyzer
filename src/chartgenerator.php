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
if ( isset($_GET['type']) ) 
	$content['chart_type'] = intval($_GET['type']);
else
	$content['chart_type'] = CHART_CAKE;

if ( isset($_GET['width']) ) 
	$content['chart_width'] = intval($_GET['width']);
else
	$content['chart_width'] = 100;

if ( isset($_GET['byfield']) )
	$content['chart_field'] = $_GET['byfield'];
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



		}
		else
		{
			// This will disable to Main SyslogView and show an error message
			$content['error_occured'] = true;
			$content['error_details'] = GetErrorMessage($res);
			if ( isset($extraErrorDescription) )
				$content['error_details'] .= "<br><br>" . GetAndReplaceLangStr( $content['LN_SOURCES_ERROR_EXTRAMSG'], $extraErrorDescription);
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
// --- 

// --- Convert and Output
if ( $content['error_occured'] ) 
{
	// TODO PRINT ERROR ON PICTURE STREAM!

//	InitTemplateParser();
//	$page -> parser($content, "export.html");
//	$page -> output(); 
}
else
{
	// Create ChartDiagram!

	exit;

	// Create a CVS File!
	$szOutputContent = "";
	$szOutputMimeType = "text/plain";
	$szOutputCharset = "";

	$szOutputFileName = "ExportMessages";
	$szOutputFileExtension = ".txt";
	if		( $content['exportformat'] == EXPORT_CVS ) 
	{
		// Set MIME TYPE and File Extension
		$szOutputMimeType = "text/csv";
		$szOutputFileExtension = ".csv";

		// Set Column line in cvs file!
		foreach($content['Columns'] as $mycolkey)
		{
			if ( isset($fields[$mycolkey]) )
			{
				// Prepend Comma if needed
				if (strlen($szOutputContent) > 0)
					$szOutputContent .= ",";  

				// Append column name
				$szOutputContent .= $content[ $fields[$mycolkey]['FieldCaptionID'] ];
			}
		}
		
		// Append line break
		$szOutputContent .= "\n";

		// Append messages into output
		foreach ( $content['syslogmessages'] as $myIndex => $mySyslogMessage )
		{
			$szLine = "";

			// --- Process columns
			foreach($mySyslogMessage as $myColkey => $mySyslogField)
			{
				// Prepend Comma if needed
				if (strlen($szLine) > 0)
					$szLine .= ",";

				// Append field contents
				$szLine .= '"' . str_replace('"', '\\"', $mySyslogField['fieldvalue']) . '"';
			}
			// --- 

			// Append line!
			$szOutputContent .= $szLine . "\n";
		}
	}
	else if	( $content['exportformat'] == EXPORT_XML ) 
	{
		// Set MIME TYPE and File Extension
		$szOutputMimeType = "application/xml";
		$szOutputFileExtension = ".xml";
		$szOutputCharset = "charset=UTF-8";

		// Create XML Header and first node!!
		$szOutputContent .= "\xef\xbb\xbf";
		$szOutputContent .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$szOutputContent .= "<syslogmessages>\n";

		// Append messages into output
		foreach ( $content['syslogmessages'] as $myIndex => $mySyslogMessage )
		{
			$szXmlLine = "\t<syslogmsg>\n";

			// --- Process columns
			foreach($mySyslogMessage as $myColkey => $mySyslogField)
			{

//				if ( isset($content[ $fields[$mycolkey]['FieldCaptionID'] ]) ) 
//					$szNodeTitle = $content[ $fields[$mycolkey]['FieldCaptionID'] ];
//				else

				// Append field content | first run htmlentities,tnen utf8 encoding!!
				$szXmlLine .= "\t\t<" . $myColkey . ">" . utf8_encode( htmlentities($mySyslogField['fieldvalue']) ) . "</" . $myColkey . ">\n";
			}
			// --- 

			$szXmlLine .= "\t</syslogmsg>\n";

			// Append line!
			$szOutputContent .= $szXmlLine;
		}

		// End first XML Node
		$szOutputContent .= "</syslogmessages>";
	}

	// Set needed Header properties
	header('Content-type: ' . $szOutputMimeType . "; " . $szOutputCharset);
	header("Content-Length: " .  strlen($szOutputContent) );
	header('Content-Disposition: attachment; filename="' . $szOutputFileName . $szOutputFileExtension . '"');

	// Output Content!
	print( $szOutputContent );
}
// --- 

?>