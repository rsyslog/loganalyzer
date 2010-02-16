<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Apache Logfile Parser used to split WebLog fields if 
	* found in the msg. 
	*
	* This Parser is for the default apache "combined" format!
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

class MsgParser_apache2 extends MsgParser {

	// Public Information properties 
	public $_ClassName = 'Apache 2 Combined Format';
	public $_ClassDescription = 'Parses the combined logfile format from Apache2 webservers.';
	public $_ClassRequiredFields = null;
	public $_ClassHelpArticle = "http://www.monitorware.com/Common/en/Articles/setup_mwagent_webserverlogging_phplogcon_mysql.php";

	// Constructor
	public function MsgParser_apache2() {
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

//return ERROR_MSG_NOMATCH;

		// LogFormat "%h %l %u %t \"%r\" %>s %b" common
		// LogFormat "%{Referer}i -> %U" referer
		// LogFormat "%{User-agent}i" agent
		// LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" combined

		// Sample (apache2):	127.0.0.1 - - [14/Sep/2008:06:50:15 +0200] "GET / HTTP/1.0" 200 19023 "-" "VoilaBot link checker"
		// Sample: 65.55.211.112 - - [16/Sep/2008:13:37:47 +0200] "GET /index.php?name=News&file=article&sid=1&theme=Printer HTTP/1.1" 200 4908 "-" "msnbot/1.1 (+http://search.msn.com/msnbot.htm)"
		if ( preg_match('/(.|.*?) (.|.*?) (.|.*?) \[(.*?)\] "(.*?) (.*?) (.*?)" (.|[0-9]{1,12}) (.|[0-9]{1,12}) "(.|.*?)" "(.*?)("|)$/', $szMsg, $out ) )
		{
//			print_r ( $out );
//			exit;

			// Set generic properties
			$arrArguments[SYSLOG_HOST] = $out[1];
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[4]);

			// Set weblog specific properties!
			$arrArguments[SYSLOG_WEBLOG_USER] = $out[3];
			$arrArguments[SYSLOG_WEBLOG_METHOD] = $out[5];
			if ( strpos($out[6], "?") === false ) 
			{
				$arrArguments[SYSLOG_WEBLOG_URL] = $out[6];
				$arrArguments[SYSLOG_WEBLOG_QUERYSTRING]= "";
			}
			else
			{
				$arrArguments[SYSLOG_WEBLOG_URL]		= substr( $out[6], 0, strpos($out[6], "?"));
				$arrArguments[SYSLOG_WEBLOG_QUERYSTRING]= substr( $out[6], strpos($out[6], "?")+1 );
			}

			// Number based fields
			$arrArguments[SYSLOG_WEBLOG_PVER] = $out[7];
			$arrArguments[SYSLOG_WEBLOG_STATUS] = $out[8];
			$arrArguments[SYSLOG_WEBLOG_BYTESSEND] = $out[9];
			$arrArguments[SYSLOG_WEBLOG_REFERER] = $out[10];
			$arrArguments[SYSLOG_WEBLOG_USERAGENT] = $out[11];

			// Set msg to whole logline 
			$arrArguments[SYSLOG_MESSAGE] = $out[0];

			if ( $this->_MsgNormalize == 1 ) 
			{
				//Init tmp msg
				$szTmpMsg = "";

				// Create Field Array to prepend into msg! Reverse Order here
				$myFields = array( SYSLOG_WEBLOG_USER, SYSLOG_WEBLOG_PVER, SYSLOG_WEBLOG_USERAGENT, SYSLOG_WEBLOG_BYTESSEND, SYSLOG_WEBLOG_STATUS, SYSLOG_WEBLOG_REFERER, SYSLOG_WEBLOG_METHOD, SYSLOG_WEBLOG_QUERYSTRING, SYSLOG_WEBLOG_URL );

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
		$arrArguments[SYSLOG_MESSAGETYPE] = IUT_WEBSERVERLOG;

		// If we reached this position, return success!
		return SUCCESS;
	}
}

?>