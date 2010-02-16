<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* StreamConfig has the capability to create a specific LogStream	*
	* object depending on a configured LogStream*Config object.			*
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
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

class LogStreamConfigDisk extends LogStreamConfig {
	// Public properties
	public $FileName = '';
	public $LineParserType = "syslog"; // Default = Syslog!
	public $_lineParser = null;
	
	public function LogStreamFactory($o) 
	{
		// An instance is created, then include the logstreamdisk class as well!
		global $gl_root_path;
		require_once($gl_root_path . 'classes/logstreamdisk.class.php');

		// Create and set LineParser Instance
		$this->_lineParser = $this->CreateLineParser();
		
		// return LogStreamDisk instance
		return new LogStreamDisk($o);
	}

	private function CreateLineParser() 
	{
		// We need to include Line Parser on demand!
		global $gl_root_path;
		require_once($gl_root_path . 'classes/logstreamlineparser.class.php');
		
		// Probe if file exists then include it!
		$strIncludeFile = $gl_root_path . 'classes/logstreamlineparser' . $this->LineParserType . '.class.php';
		$strClassName = "LogStreamLineParser" . $this->LineParserType;

		if ( is_file($strIncludeFile) )
		{
			require_once($strIncludeFile);

			// TODO! Create Parser based on Source Config!

			//return LineParser Instance
			return new $strClassName();
		}
		else
			DieWithErrorMsg("Couldn't locate LineParser include file '" . $strIncludeFile . "'");
	}

	/*
	* Helper function to Set the FileName property
	*/
	public function SetFileName( $szNewVal )
	{
		// Replace dynamic variables if necessary
		if ( strpos($szNewVal, "%") !== false )
		{
			OutputDebugMessage("LogStreamConfigDisk|SetFileName: Filename before replacing: " . $szNewVal, DEBUG_DEBUG);
			
			// Create search and replace array
			$search = array ( 
						"%y", /* Year with two digits (e.g. 2002 becomes "02") */
						"%Y", /* Year with 4 digits */
						"%m", /* Month with two digits (e.g. March becomes "03") */
						"%M", /* Minute with two digits */
						"%d", /* Day of month with two digits (e.g. March, 1st becomes "01") */
						"%h", /* Hour as two digits */
						"%S", /* Seconds as two digits. It is hardly believed that this ever be used in reality.    */
						"%w", /* Weekday as one digit. 0 means Sunday, 1 Monday and so on. */
						"%W", /* Weekday as three-character string. Possible values are "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat". */
						);
			$replace = array (
						date("y"),
						date("Y"), 
						date("m"), 
						date("i"), 
						date("d"), 
						date("H"), 
						date("s"), 
						date("w"), 
						date("D"), 
						);
			
			// Do the replacing
			$szNewVal = str_replace( $search, $replace, $szNewVal );

			OutputDebugMessage("LogStreamConfigDisk|SetFileName: Filename after replacing: " . $szNewVal, DEBUG_DEBUG);
		}

		// Set Filename Property!
		$this->FileName = $szNewVal;
	}


}
?>