<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* EventLog MSG Parser is used to split EventLog fields if found 
	* in the msg 
	*																	*
	* All directives are explained within this file						*
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

// --- Basic Includes
require_once($gl_root_path . 'classes/enums.class.php');
require_once($gl_root_path . 'classes/msgparser.class.php');
require_once($gl_root_path . 'include/constants_errors.php');
require_once($gl_root_path . 'include/constants_logstream.php');
// --- 

class MsgParser_eventlog extends MsgParser {
//	protected $_arrProperties = null;

	// Constructor
	public function MsgParser_eventlog() {
		return; // Nothing
	}

	/**
	* ParseLine
	*
	* @param arrArguments array in&out: properties of interest. There can be no guarantee the logstream can actually deliver them.
	* @return integer Error stat
	*/
	public function ParseMsg($szMsg, &$arrArguments)
	{
		global $content; 

		// Sample (WinSyslog/EventReporter):	7035,XPVS2005\Administrator,Service Control Manager,System,[INF],0,The Adiscon EvntSLog service was successfully sent a start control.
		// Source:								%id%,%user%,%sourceproc%,%NTEventLogType%,%severity%,%category%,%msg%%$CRLF%
		if ( preg_match("/([0-9]{1,12}),(.*?),(.*?),(.*?),(.*?),([0-9]{1,12}),(.*?)$/", $szMsg, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_EVENT_ID] = $out[1];
			$arrArguments[SYSLOG_EVENT_USER] = $out[2];
			$arrArguments[SYSLOG_EVENT_SOURCE] = $out[3];
			$arrArguments[SYSLOG_EVENT_LOGTYPE] = $out[4];
///			$arrArguments[SYSLOG_SEVERITY] = $out[5];
			$arrArguments[SYSLOG_EVENT_CATEGORY] = $out[6];
			$arrArguments[SYSLOG_MESSAGE] = $out[7];
		}
		else
		{
			// return no match in this case!
			return ERROR_MSG_NOMATCH;
		}
		
		// Set IUT Property if success!
		$arrArguments[SYSLOG_MESSAGETYPE] = IUT_NT_EventReport;

		// If we reached this position, return success!
		return SUCCESS;
	}
}

?>