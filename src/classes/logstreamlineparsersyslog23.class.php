<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* LogStream Parser is used to split syslog messages into fields		*
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

// --- Basic Includes
require_once($gl_root_path . 'classes/enums.class.php');
require_once($gl_root_path . 'include/constants_errors.php');
require_once($gl_root_path . 'include/constants_logstream.php');
// --- 


class LogStreamLineParsersyslog23 extends LogStreamLineParser {
//	protected $_arrProperties = null;

	// Constructor
	public function LogStreamLineParsersyslog23() {
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
		// Set IUT Property first!
		$arrArguments[SYSLOG_MESSAGETYPE] = IUT_Syslog;

		// Sample: <22>1 2011-03-03T15:27:06+01:00 debian507x64 postfix 2454 - -  daemon started -- version 2.5.5, configuration /etc/postfix
		// Sample: <46>1 2011-03-03T15:27:05+01:00 debian507x64 rsyslogd - - -  [origin software="rsyslogd" swVersion="4.6.4" x-pid="2344" x-info="http://www.rsyslog.com"] (re)start
		// Sample (RSyslog): 2008-03-28T11:07:40+01:00 localhost rger: test 1
		if ( preg_match("/<([0-9]{1,3})>([0-9]) ([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2}T[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}.[0-9]{1,2}:[0-9]{1,2}) (.*?) (.*?) (.*?) (.*?) (.*?) (.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_FACILITY] = $out[1] >> 3;
			$arrArguments[SYSLOG_SEVERITY] = $out[1] & 0x0007;
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[3]);
			$arrArguments[SYSLOG_HOST] = $out[4];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[5];
			$arrArguments[SYSLOG_PROCESSID] = $out[6];
			$arrArguments[SYSLOG_MESSAGE] = $out[9];
		}
		// Sample: <22>1 2011-03-03T15:27:06.501740+01:00 debian507x64 postfix 2454 - -  daemon started -- version 2.5.5, configuration /etc/postfix
		// Sample: <46>1 2011-03-03T15:27:05.366981+01:00 debian507x64 rsyslogd - - -  [origin software="rsyslogd" swVersion="4.6.4" x-pid="2344" x-info="http://www.rsyslog.com"] (re)start
		else if ( preg_match("/<([0-9]{1,3})>([0-9]) ([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2}T[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\.[0-9]{1,6}.[0-9]{1,2}:[0-9]{1,2}) (.*?) (.*?) (.*?) (.*?) (.*?) (.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_FACILITY] = $out[1] >> 3;
			$arrArguments[SYSLOG_SEVERITY] = $out[1] & 0x0007;
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[3]);
			$arrArguments[SYSLOG_HOST] = $out[4];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[5];
			$arrArguments[SYSLOG_PROCESSID] = $out[6];
			$arrArguments[SYSLOG_MESSAGE] = $out[9];
		}
		else
		{
			if ( isset($arrArguments[SYSLOG_MESSAGE]) && strlen($arrArguments[SYSLOG_MESSAGE]) > 0 ) 
				OutputDebugMessage("Unparseable syslog msg - '" . $arrArguments[SYSLOG_MESSAGE] . "'", DEBUG_ERROR);
		}

		// If SyslogTag is set, we check for MessageType!
		if ( isset($arrArguments[SYSLOG_SYSLOGTAG]) )
		{
			if ( strpos($arrArguments[SYSLOG_SYSLOGTAG], "EvntSLog" ) !== false ) 
				$arrArguments[SYSLOG_MESSAGETYPE] = IUT_NT_EventReport;
		}
		
		// Return success!
		return SUCCESS;
	}


}

?>