<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* LogStream Parser is used to split syslog messages into fields		*
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

// --- Basic Includes
require_once($gl_root_path . 'classes/enums.class.php');
require_once($gl_root_path . 'include/constants_errors.php');
require_once($gl_root_path . 'include/constants_logstream.php');
// --- 


class LogStreamLineParserwinsyslog extends LogStreamLineParser {
//	protected $_arrProperties = null;

	// Constructor
	public function LogStreamLineParserwinsyslog() {
		return; // Nothing
	}

	/**
	* ParseLine
	*
	* @param arrArguments array in&out: properties of interest. There can be no guarantee the logstream can actually deliver them.
	* @return integer Error stat
	*/
	public function ParseLine($szLine, &$arrArguments)
	{
		global $content; 

		// Sample (WinSyslog/EventReporter): 2008-04-02,15:19:06,2008-04-02,15:19:06,127.0.0.1,16,5,EvntSLog: Performance counters for the RSVP (QoS RSVP) service were loaded successfully. 
		if ( preg_match("/([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2},[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}),([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2},[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}),(.*?),([0-9]{1,2}),([0-9]{1,2}),(.*?):(.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_DATE] = $this->GetEventTime($out[1]);
			$arrArguments[SYSLOG_HOST] = $out[3];
			$arrArguments[SYSLOG_FACILITY] = $out[4];
			$arrArguments[SYSLOG_SEVERITY] = $out[5];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[6];
			$arrArguments[SYSLOG_MESSAGE] = $out[7];

			// Expand SYSLOG_FACILITY and SYSLOG_SEVERITY
			$arrArguments[SYSLOG_FACILITY_TEXT] = GetFacilityDisplayName( $arrArguments[SYSLOG_FACILITY] );
			$arrArguments[SYSLOG_SEVERITY_TEXT] = GetSeverityDisplayName( $arrArguments[SYSLOG_SEVERITY] );
		}
		else if ( preg_match("/([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2},[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}),([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2},[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}),(.*?),([0-9]{1,2}),([0-9]{1,2}),(.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_DATE] = $this->GetEventTime($out[1]);
			$arrArguments[SYSLOG_HOST] = $out[3];
			$arrArguments[SYSLOG_FACILITY] = $out[4];
			$arrArguments[SYSLOG_SEVERITY] = $out[5];
			$arrArguments[SYSLOG_MESSAGE] = $out[6];

			// Expand SYSLOG_FACILITY and SYSLOG_SEVERITY
			$arrArguments[SYSLOG_FACILITY_TEXT] = GetFacilityDisplayName( $arrArguments[SYSLOG_FACILITY] );
			$arrArguments[SYSLOG_SEVERITY_TEXT] = GetSeverityDisplayName( $arrArguments[SYSLOG_SEVERITY] );
		}
		else
		{
			if ( strlen($arrArguments[SYSLOG_MESSAGE]) > 0 ) 
			{
				// TODO: Cannot Parse Syslog message with this pattern!
				die ("wtf winsyslog - '" . $arrArguments[SYSLOG_MESSAGE] . "'");
			}
		}
		
		// Return success!
		return SUCCESS;
	}


}

?>