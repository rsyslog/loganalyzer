<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* LogStream Parser is used to split Syslog-NG messages into fields	*
	*																	*
	* Credits go to: Mulyadi Santosa(mulyadi.santosa@gmail.com). 
	*
	* In order to do this, your logger must put the facility and severity 
	* as numbers in front of the rest of the log data, e.g.
	* 2 2 Mar 10 14:45:44 debandre anacron[3226]: Job `cron.daily' 
	* terminated (mailing output).
	* ^ ^
	* | |
	* | +-------> severity
	* +--------> facility
	* 
	* destination messages { file("/var/log/messages"
	*         template("$FACILITY_NUM $LEVEL_NUM $DATE $FULLHOST $MESSAGE\n")
	*         );
	* };
	* 
	* Referensi macro:
	* http://www.balabit.com/sites/default/files/documents/syslog-ng-ose-3.3
	* -guides/en/syslog-ng-ose-v3.3-guide-admin-en/html/reference_macros.html
	* 
	* 
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


class LogStreamLineParsersyslogng extends LogStreamLineParser {
//	protected $_arrProperties = null;

	// Constructor
	public function __construct () {
		return; // Nothing
	}
	public function LogStreamLineParsersyslogng() {
		self::__construct();
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

		// MULYADI: add code to parse facility and level number (each 1 digit decimal)
		// Sample (Syslog): 2 2 Mar 10 14:45:44 debandre anacron[3226]: Job `cron.daily' terminated (mailing output)
		if ( preg_match("/([0-9]) ([0-9]) (...)(?:.|..)([0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}) ([a-zA-Z0-9_\-\.]{1,256}) ([A-Za-z0-9_\-\/\.]{1,32})\[(.*?)\]:(.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_FACILITY] = $out[1];
			$arrArguments[SYSLOG_SEVERITY] = $out[2];
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[3] . " " . $out[4]);
			$arrArguments[SYSLOG_HOST] = $out[5];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[6];
			$arrArguments[SYSLOG_PROCESSID] = $out[7];
			$arrArguments[SYSLOG_MESSAGE] = $out[8];
		}
		// Sample (Syslog): 2 2 Mar 10 14:45:39 debandre syslogd 1.4.1#18: restart
		else if ( preg_match("/([0-9]) ([0-9]) (...)(?:.|..)([0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}) ([a-zA-Z0-9_\-\.]{1,256}) ([A-Za-z0-9_\-\/\.]{1,32}):(.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_FACILITY] = $out[1];
			$arrArguments[SYSLOG_SEVERITY] = $out[2];
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[3] . " " . $out[4]);
			$arrArguments[SYSLOG_HOST] = $out[5];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[6];
			$arrArguments[SYSLOG_MESSAGE] = $out[7];
		}
		// Sample (Syslog): 2 2 Mar 10 14:45:39 debandre syslogd restart
		else if ( preg_match("/([0-9]) ([0-9]) (...)(?:.|..)([0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}) ([a-zA-Z0-9_\-\.]{1,256}) ([A-Za-z0-9_\-\/\.]{1,32}) (.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_FACILITY] = $out[1];
			$arrArguments[SYSLOG_SEVERITY] = $out[2];
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[3] . " " . $out[4]);
			$arrArguments[SYSLOG_HOST] = $out[5];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[6];
			$arrArguments[SYSLOG_MESSAGE] = $out[7];
		}
		// Sample (Syslog): 2 2 Mar 7 17:18:35 debandre exiting on signal 15
		else if ( preg_match("/([0-9]) ([0-9]) (...)(?:.|..)([0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}) (.*?) (.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_FACILITY] = $out[1];
			$arrArguments[SYSLOG_SEVERITY] = $out[2];
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[3] . " " . $out[4]);
			$arrArguments[SYSLOG_HOST] = $out[5];
			$arrArguments[SYSLOG_MESSAGE] = $out[6];
		}
		// Sample (RSyslog): 2008-03-28T11:07:40+01:00 localhost rger: test 1
		else if ( preg_match("/([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2}T[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}.[0-9]{1,2}:[0-9]{1,2}) (.*?) (.*?):(.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[1]);
			$arrArguments[SYSLOG_HOST] = $out[2];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[3];
			$arrArguments[SYSLOG_MESSAGE] = $out[4];
		}
		// Sample (RSyslog): 2008-03-28T11:07:40.591633+01:00 localhost rger: test 1
		else if ( preg_match("/([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2}T[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\.[0-9]{1,6}.[0-9]{1,2}:[0-9]{1,2}) (.*?) (.*?):(.*?)$/", $szLine, $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[1]);
			$arrArguments[SYSLOG_HOST] = $out[2];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[3];
			$arrArguments[SYSLOG_MESSAGE] = $out[4];
		}
		// Sample: 2008-03-28T15:17:05.480876+01:00,**NO MATCH**
		else if ( preg_match("/([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2}T[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\.[0-9]{1,6}.[0-9]{1,2}:[0-9]{1,2}),(.*?)$/", $szLine, $out ) )
		{
			// Some kind of debug message or something ...
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[1]);
			$arrArguments[SYSLOG_MESSAGE] = $out[2];
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
