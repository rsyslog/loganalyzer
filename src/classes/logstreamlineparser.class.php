<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* LogStream LineParser abstract basic class							*
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
require_once($gl_root_path . 'include/constants_errors.php');
require_once($gl_root_path . 'include/constants_logstream.php');
// --- 


abstract class LogStreamLineParser {
//	protected $_arrProperties = null;

	/**
	* ParseLine
	*
	* @param arrArguments array in&out: properties of interest. There can be no guarantee the logstream can actually deliver them.
	* @return integer Error stat
	*/
	public abstract function ParseLine($szLine, &$arrArguments);

	/*
	*	GetEventTime
	*
	*	Helper function to parse and obtain a valid EventTime Array from the input string.
	*	Return value: EventTime Array!
	*
	*/
	protected function GetEventTime($szTimStr)
	{
		// Sample: Mar 10 14:45:44
		if ( preg_match("/(...) ([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
		{
			// RFC 3164 typical timestamp
			$eventtime[EVTIME_TIMESTAMP] = mktime($out[3], $out[4], $out[5], $this->GetMonthFromString($out[1]), $out[2]);
			$eventtime[EVTIME_TIMEZONE] = date_default_timezone_get(); // WTF TODO!
			$eventtime[EVTIME_MICROSECONDS] = 0;

//			echo gmdate(DATE_RFC822, $eventtime[EVTIME_TIMESTAMP]) . "<br>";
//			print_r ( $eventtime );
//			exit;
		}
		// Sample: 2008-04-02T11:12:32+02:00
		else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})\+([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
		{
			// RFC 3164 typical timestamp
			$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
			$eventtime[EVTIME_TIMEZONE] = $out[7]; 
			$eventtime[EVTIME_MICROSECONDS] = 0;
		}
		// Sample: 2008-04-02T11:12:32.380449+02:00
		else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})\.([0-9]{1,6})\+([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
		{
			// RFC 3164 typical timestamp
			$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
			$eventtime[EVTIME_TIMEZONE] = $out[8]; 
			$eventtime[EVTIME_MICROSECONDS] = $out[7];
		}
		// Sample: 2008-04-02,15:19:06
		else if ( preg_match("/([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2}),([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $szTimStr, $out ) )
		{
			// RFC 3164 typical timestamp
			$eventtime[EVTIME_TIMESTAMP] = mktime($out[4], $out[5], $out[6], $out[2], $out[3], $out[1]);
			$eventtime[EVTIME_TIMEZONE] = date_default_timezone_get(); // WTF TODO!
			$eventtime[EVTIME_MICROSECONDS] = 0;
		}
		else
		{
			die ("wtf GetEventTime unparsable time - " . $szTimStr );
		}

		// return result!
		return $eventtime;
	}

	/*
	*	GetMonthFromString
	*	
	*	Simple Helper function to obtain the numeric represantation of the month
	*/
	private function GetMonthFromString($szMonth)
	{
		switch($szMonth)
		{
			case "Jan":
				return 1;
			case "Feb":
				return 2;
			case "Mar":
				return 3;
			case "Apr":
				return 4;
			case "May":
				return 5;
			case "Jun":
				return 6;
			case "Jul":
				return 7;
			case "Aug":
				return 8;
			case "Sep":
				return 9;
			case "Oct":
				return 10;
			case "Nov":
				return 11;
			case "Dez":
				return 12;
		}

	}


}

?>
