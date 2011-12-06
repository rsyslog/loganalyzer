<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* Export Code File											
	*																	
	* -> Exports data from a search and site into a data format 
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
if ( isset($_GET['uid']) ) 
	$content['uid_current'] = intval($_GET['uid']);
else
	$content['uid_current'] = UID_UNKNOWN;

// Read direction parameter
if ( isset($_GET['direction']) && $_GET['direction'] == "desc" ) 
	$content['read_direction'] = EnumReadDirection::Forward;
else
	$content['read_direction'] = EnumReadDirection::Backward;

// If direction is DESC, should we SKIP one? 
if ( isset($_GET['skipone']) && $_GET['skipone'] == "true" ) 
	$content['skipone'] = true;
else
	$content['skipone'] = false;

// Init variables
$content['searchstr'] = "";
$content['error_occured'] = false;

// Check required input parameters
if (
		(isset($_GET['op']) && $_GET['op'] == "export") && 
		(isset($_GET['exporttype']) && array_key_exists($_GET['exporttype'], $content['EXPORTTYPES']))
	) 
{
	$content['exportformat'] = $_GET['exporttype'];
	
/*
	// Check for extensions 
	if ( $content['exportformat'] == EXPORT_PDF && !$content['PDF_IS_ENABLED'] ) 
	{
		$content['error_occured'] = true;
		$content['error_details'] = $content['LN_GEN_ERROR_PDFMISSINGEXTENSION'];
	}
*/

}
else
{
	$content['error_occured'] = true;
	$content['error_details'] = $content['LN_GEN_ERROR_INVALIDEXPORTTYPE'];
}
// ---

// --- BEGIN CREATE TITLE
$content['TITLE'] = InitPageTitle();

// Append custom title part!
if ( isset($content['searchstr']) && strlen($content['searchstr']) > 0 ) 
	$content['TITLE'] .= " :: Results for the search '" . $content['searchstr'] . "'";	// Append search
else
	$content['TITLE'] .= " :: Syslogmessages";
// --- END CREATE TITLE

// --- Read and process filters from search dialog!
if ( (isset($_POST['search']) || isset($_GET['search'])) || (isset($_POST['filter']) || isset($_GET['filter'])) )
{
	// Copy search over
	if		( isset($_POST['search']) )
		$mysearch = $_POST['search'];
	else if ( isset($_GET['search']) )
		$mysearch = $_GET['search'];

	if		( isset($_POST['filter']) )
		$myfilter = $_POST['filter'];
	else if ( isset($_GET['filter']) )
		$myfilter = $_GET['filter'];

	// Message is just appended
	if ( isset($myfilter) && strlen($myfilter) > 0 )
		$content['searchstr'] = $myfilter;
}
// --- 

// --- BEGIN Custom Code
if ( !$content['error_occured'] )
{
	if ( isset($content['Sources'][$currentSourceID]) ) 
	{
		// Obtain and get the Config Object
		$stream_config = $content['Sources'][$currentSourceID]['ObjRef'];

		// Create LogStream Object 
		$stream = $stream_config->LogStreamFactory($stream_config);
		$stream->SetFilter($content['searchstr']);
		
		// Copy current used columns here!
		$content['Columns'] = $content['Views'][$currentViewID]['Columns'];

		// --- Init the fields we need
		foreach($content['Columns'] as $mycolkey)
		{
			if ( isset($fields[$mycolkey]) )
			{
				$content['fields'][$mycolkey]['FieldID'] = $mycolkey;
				$content['fields'][$mycolkey]['FieldCaption'] = $fields[$mycolkey]['FieldCaption'];
				$content['fields'][$mycolkey]['FieldType'] = $fields[$mycolkey]['FieldType'];
				$content['fields'][$mycolkey]['DefaultWidth'] = $fields[$mycolkey]['DefaultWidth'];
			}
		}
		// --- 

		$res = $stream->Open( $content['Columns'], true );
		if ( $res == SUCCESS ) 
		{
			// TODO Implement ORDER
			$stream->SetReadDirection($content['read_direction']);

			// Set current ID and init Counter
			$uID = $content['uid_current'];

			$counter = 0;

			// If uID is known, we need to init READ first - this will also seek for available records first!
			if ($uID != UID_UNKNOWN) 
			{
				// First read will also set the start position of the Stream!
				$ret = $stream->Read($uID, $logArray);
			}
			else
				$ret = $stream->ReadNext($uID, $logArray);

			// --- Check if Read was successfull!
			if ( $ret == SUCCESS )
			{
				// If Forward direction is used, we need to SKIP one entry!
				if ( $content['read_direction'] == EnumReadDirection::Forward )
				{
					if ( $content['skipone'] ) 
					{
						// Skip this entry and move to the next
						$stream->ReadNext($uID, $logArray);
					}
				}
			}
			else
			{
				// This will disable to Main SyslogView and show an error message
				$content['error_occured'] = true;
				$content['error_details'] = $content['LN_ERROR_NORECORDS'];
			}
			// ---

			// We found matching records, so continue
			if ( $ret == SUCCESS )
			{
				//Loop through the messages!
				do
				{
					// --- Extra stuff for suppressing messages
					if (
							GetConfigSetting("SuppressDuplicatedMessages", 0, CFGLEVEL_USER) == 1 
							&&
							isset($logArray[SYSLOG_MESSAGE])
						)
					{

						if ( !isset($szLastMessage) ) // Only set lastmgr
							$szLastMessage = $logArray[SYSLOG_MESSAGE];
						else
						{
							// Skip if same msg
							if ( $szLastMessage == $logArray[SYSLOG_MESSAGE] )
							{
								// Set last mgr
								$szLastMessage = $logArray[SYSLOG_MESSAGE];

								// Skip entry
								continue;
							}
						}
					}
					// --- 

					// --- Now we populate the values array!
					foreach($content['Columns'] as $mycolkey)
					{
						if ( isset($fields[$mycolkey]) && isset($logArray[$mycolkey]) )
						{
							// Set defaults
							$content['syslogmessages'][$counter][$mycolkey]['FieldColumn'] = $mycolkey;
							$content['syslogmessages'][$counter][$mycolkey]['uid'] = $uID;

							// Copy value as it is first!
							$content['syslogmessages'][$counter][$mycolkey]['fieldvalue'] = $logArray[$mycolkey];

							// Now handle fields types differently
							if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_DATE )
							{
								$content['syslogmessages'][$counter][$mycolkey]['fieldvalue'] = GetFormatedDate($logArray[$mycolkey]); 
							}
							else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_NUMBER )
							{
								// Special style classes and colours for SYSLOG_FACILITY
								if ( $mycolkey == SYSLOG_FACILITY )
								{
									if ( isset($logArray[$mycolkey][SYSLOG_FACILITY]) && strlen($logArray[$mycolkey][SYSLOG_FACILITY]) > 0)
									{
										// Set Human readable Facility!
										$content['syslogmessages'][$counter][$mycolkey]['fieldvalue'] = GetFacilityDisplayName( $logArray[$mycolkey] );
									}
								}
								else if ( $mycolkey == SYSLOG_SEVERITY )
								{
									if ( isset($logArray[$mycolkey][SYSLOG_SEVERITY]) && strlen($logArray[$mycolkey][SYSLOG_SEVERITY]) > 0)
									{
										// Set Human readable Facility!
										$content['syslogmessages'][$counter][$mycolkey]['fieldvalue'] = GetSeverityDisplayName( $logArray[$mycolkey] );
									}
								}
								else if ( $mycolkey == SYSLOG_MESSAGETYPE )
								{
									if ( isset($logArray[$mycolkey][SYSLOG_MESSAGETYPE]) )
									{
										// Set Human readable Facility!
										$content['syslogmessages'][$counter][$mycolkey]['fieldvalue'] = GetMessageTypeDisplayName( $logArray[$mycolkey] );
									}
								}
							}
							/*
							else if ( $content['fields'][$mycolkey]['FieldType'] == FILTER_TYPE_STRING )
							{
							}
							*/
						}
					}
					// ---

					// Increment Counter
					$counter++;
				} while ($counter < $content['CurrentViewEntriesPerPage'] && ($ret = $stream->ReadNext($uID, $logArray)) == SUCCESS);

				if ( $content['read_direction'] == EnumReadDirection::Forward )
				{
					// Back Button was clicked, so we need to flip the array 
					$content['syslogmessages'] = array_reverse ( $content['syslogmessages'] );
				}
// DEBUG
//print_r ( $content['syslogmessages'] );
			}
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
	InitTemplateParser();
	$page -> parser($content, "export.html");
	$page -> output(); 
}
else
{
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
				$szOutputContent .= $fields[$mycolkey]['FieldCaption'];
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