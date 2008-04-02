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


class LogStreamParser {
//	protected $_readDirection = EnumReadDirection::Forward;
//	protected $_filters = null;
//	protected $_current_uId = -1;
//	protected $_logStreamConfigObj = null;
//	protected $_arrProperties = null;

	// Constructor
	public function LogStreamParser() {
		return; // Nothing
	}

	/**
	* ParseSyslogMessage
	*
	* @param arrProperties string in: properties of interest. There can be no guarantee the logstream can actually deliver them.
	* @return integer Error stat
	*/
	public function ParseSyslogMessage(&$arrArguments)
	{
		/* Sample: 
		*	Mar 10 14:45:39 debandre syslogd 1.4.1#18: restart.
		*	Mar 10 14:45:44 debandre anacron[3226]: Job `cron.daily' terminated (mailing output)
		*
		*/
		
		// Typical Syslog Message
		if ( preg_match("/(... [0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}) (.*?) (.*?)\[(.*?)\]: (.*?)$/", $arrArguments[SYSLOG_MESSAGE], $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_DATE] = $out[1];
			$arrArguments[SYSLOG_HOST] = $out[2];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[3];
			$arrArguments[SYSLOG_PROCESSID] = $out[4];
			$arrArguments[SYSLOG_MESSAGE] = $out[5];
		}
		else if ( preg_match("/(... [0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}) (.*?) (.*?): (.*?)$/", $arrArguments[SYSLOG_MESSAGE], $out ) )
		{
			// Copy parsed properties!
			$arrArguments[SYSLOG_DATE] = $out[1];
			$arrArguments[SYSLOG_HOST] = $out[2];
			$arrArguments[SYSLOG_SYSLOGTAG] = $out[3];
			$arrArguments[SYSLOG_MESSAGE] = $out[4];
		}
		else
		{
			// Cannot Parse Syslog message with this pattern!
			die ("wtf - " . $arrArguments[SYSLOG_MESSAGE] );
		}
	}


}

?>