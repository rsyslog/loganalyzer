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
		//$this->FileName = $szNewVal;
		$this->Display($szNewVal);

		/*$str=shell_exec("ls");
		$arr = explode(' ',$str);
		print_r($arr);*/
	}

	private function Display( $szNewVal ){
		global $content;

		$pattern = "([\d]+)";
		preg_match_all($pattern, $szNewVal, $ret, PREG_SET_ORDER);
		foreach ($ret as $r) {
			$sortVal = $r[0];
		}
		$dir = str_replace($sortVal . ".log", "", $szNewVal);
		if(isset($_GET['date'])){
			$sortVal = $_GET['date'];
		}

		$this->FileName = $dir .$sortVal . ".log";

		$show = "<font style='font-weight: bold'>Current Dir : </font><font color='#4169e1' style='font-weight: bold'>" . $dir . "</font>";;
		$show = $show . "</br><font style='font-weight: bold'>Current Log : </font><font color='#4169e1' style='font-weight: bold'>" . $sortVal . "</font>";;
		$show = $show . "</br>" . $this->GetLogList($dir);
		$content['Display_Dir'] = $show;
	}

	private function GetLogList($dir){
		//$dir = dirname(__FILE__) . $dir;
		$handle = opendir($dir.".");
		$array_file = array();
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != "..") {
				$array_file[] = $file;
			}
		}
		closedir($handle);
		sort($array_file);
		return $this->ShowTable($array_file);
	}

	private function ShowTable($array_file){
		$table = "<table border='1' width='100%'>";
		$size = count($array_file);

		for ($x = 0; $x < $size; ) {
			$table = $table . "<tr>";
			for ($c = 0; $c <= 11; $c++) {
				if($x < $size){
					$log_date = str_replace(".log", "", $array_file[$x]);
					$log_link = "<a href='index.php?date=" . $log_date . "'>" . $log_date . "</a>";
					$table = $table . "<td style='font-weight: bold;color: #982D00'>" . $log_link . "</td>";
					$x++;
				} else{
					$table = $table . "<td></td>";
				}
			}
			$table = $table . "</tr>";
		}
		$table = $table . "</table>";
		return $table;
	}

}
?>