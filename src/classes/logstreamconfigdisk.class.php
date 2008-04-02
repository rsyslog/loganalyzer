<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* StreamConfig has the capability to create a specific LogStream	*
	* object depending on a configured LogStream*Config object.			*
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

class LogStreamConfigDisk extends LogStreamConfig {
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
		$strIncludeFile = 'classes/logstreamlineparser' . $this->LineParserType . '.class.php';
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

}
?>