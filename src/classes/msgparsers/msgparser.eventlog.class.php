<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* EventLog MSG Parser is used to split EventLog fields if found 
	* in the msg 
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

// --- Basic Includes
require_once($gl_root_path . 'classes/enums.class.php');
require_once($gl_root_path . 'classes/msgparser.class.php');
require_once($gl_root_path . 'include/constants_errors.php');
require_once($gl_root_path . 'include/constants_logstream.php');
// --- 

class MsgParser_eventlog extends MsgParser {

	// Public Information properties 
	public $_ClassName = 'Adiscon Eventlog Format';
	public $_ClassDescription = 'This is a parser for a special format which can be created with Adiscon Eventreporter or MonitorWare Agent.';
	public $_ClassRequiredFields = null;
	public $_ClassHelpArticle = "http://www.monitorware.com/en/Articles/";

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
		global $content, $fields; 

		//trim the msg first to remove spaces from begin and end
		$szMsg = trim($szMsg);

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

			if ( $this->_MsgNormalize == 1 ) 
			{
				//Init tmp msg
				$szTmpMsg = "";

				// Create Field Array to prepend into msg! Reverse Order here
				$myFields = array( SYSLOG_MESSAGE, SYSLOG_EVENT_CATEGORY, SYSLOG_EVENT_LOGTYPE, SYSLOG_EVENT_SOURCE, SYSLOG_EVENT_USER, SYSLOG_EVENT_ID );

				foreach ( $myFields as $myField )
				{
					// Set Field Caption
					if ( isset($fields[$myField]['FieldCaption']) )
						$szFieldName = $fields[$myField]['FieldCaption'];
					else
						$szFieldName = $myField;

					// Append Field into msg
					$szTmpMsg = $szFieldName . ": '" . $arrArguments[$myField] . "'\n" . $szTmpMsg;
				}

				// copy finished MSG back!
				$arrArguments[SYSLOG_MESSAGE] = $szTmpMsg;

			}
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