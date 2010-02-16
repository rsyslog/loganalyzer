<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------	*
	* Microsoft IIS Logfile Parser used to split WebLog fields if found 
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

class MsgParser_iis extends MsgParser {

	// Public Information properties 
	public $_ClassName = 'Microsoft IIS Weblogs';
	public $_ClassDescription = 'Parses the common weblog format used by the Microsoft IIS webserver.';
	public $_ClassRequiredFields = null;
	public $_ClassHelpArticle = "http://www.monitorware.com/en/Articles/";

	// Constructor
	public function MsgParser_iis() {
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
		
//		$iSharpPos = strpos($szMsg, "#");
//		if ( $iSharpPos !== false && $iSharpPos == 0 )
//			return ERROR_MSG_SKIPMESSAGE; 
		
		// Special case here, if loglines start with #, they are comments and have to be skipped!
		if ( ($iSharpPos = strpos($szMsg, "#")) !== false && $iSharpPos == 0 )
		{
			// Only init fields then 
			// Set generic properties
			$arrArguments[SYSLOG_DATE] = "";
			$arrArguments[SYSLOG_HOST] = "";

			// Set weblog specific properties!
			$arrArguments[SYSLOG_WEBLOG_METHOD] = "";
			$arrArguments[SYSLOG_WEBLOG_URL] = "";
			$arrArguments[SYSLOG_WEBLOG_QUERYSTRING] = "";
			$arrArguments[SYSLOG_WEBLOG_USER] = "";
			$arrArguments[SYSLOG_WEBLOG_PVER] = "";
			$arrArguments[SYSLOG_WEBLOG_USERAGENT] = "";
			$arrArguments[SYSLOG_WEBLOG_REFERER] = "";
			$arrArguments[SYSLOG_WEBLOG_STATUS] = "";
			$arrArguments[SYSLOG_WEBLOG_BYTESSEND] = "";

			// Set msg to whole logline 
			$arrArguments[SYSLOG_MESSAGE] = $szMsg;
		}
		// LogFormat: date time cs-method cs-uri-stem cs-uri-query cs-username c-ip cs-version cs(User-Agent) cs(Referer) sc-status sc-bytes 
		// Sample: 2008-09-17 00:15:24 GET /Include/MyStyleV2.css - - 208.111.154.249 HTTP/1.0 Mozilla/5.0+(X11;+U;+Linux+i686+(x86_64);+en-US;+rv:1.8.1.11)+Gecko/20080109+(Charlotte/0.9t;+http://www.searchme.com/support/) http://www.adiscon.com/Common/en/News/MWCon-2005-09-12.php 200 1812
		if ( preg_match('/([0-9]{4,4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}) (.*?) (.|.*?) (.|.*?) (.|.*?) (.|.*?) (.|.*?) (.|.*?) (.|.*?) (.|.*?) (.|.*?)$/', $szMsg, $out ) )
		{
//			print_r ( $out );
//			exit;

			// Set generic properties
			$arrArguments[SYSLOG_DATE] = GetEventTime($out[1]);
			$arrArguments[SYSLOG_HOST] = $out[6];

			// Set weblog specific properties!
			$arrArguments[SYSLOG_WEBLOG_METHOD] = $out[2];
			$arrArguments[SYSLOG_WEBLOG_URL] = $out[3];
			$arrArguments[SYSLOG_WEBLOG_QUERYSTRING]= $out[4];
			$arrArguments[SYSLOG_WEBLOG_USER] = $out[5];
			$arrArguments[SYSLOG_WEBLOG_PVER] = $out[7];
			$arrArguments[SYSLOG_WEBLOG_USERAGENT] = $out[8];
			$arrArguments[SYSLOG_WEBLOG_REFERER] = $out[9];
			$arrArguments[SYSLOG_WEBLOG_STATUS] = $out[10];
			$arrArguments[SYSLOG_WEBLOG_BYTESSEND] = $out[11];

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