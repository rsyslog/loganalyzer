<?php

/*#### #### #### #### #### #### #### #### #### #### 
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2003  Adiscon GmbH

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

If you have questions about phpLogCon in general, please email info@adiscon.com. To learn more about phpLogCon, please visit 
http://www.phplogcon.com.

This Project was intiated and is maintened by Rainer Gerhards <rgerhards@hq.adiscon.com>. See AUTHORS to learn who helped make 
it become a reality.

*/#### #### #### #### #### #### #### #### #### #### 

	if( !isset($_SESSION['change']) )
		$_SESSION['change'] = "Predefined";

	/*!
	* This is the filter for the events.
	*
	*/

	//error_reporting(E_ALL);
	class EventFilter
	{
		var $TimeInterval; /*!< String coming from the URL describing the selected time interval.
						 * The variable from the url is "ti"
						 * Four scenarios are possible:
						 * Case 1: ti = the string "min" + the number of minutes of the selected
						 * time interval.
						 * Case 2: ti = the string "thishour"
						 * Case 3: ti = the string "today" 
 						 * Case 4: ti = the string "yesterday" 
						 */
		var $BeginTime; //!< Timestamp when the interval begins
		var $EndTime; //!< Timestamp when the interval ends
		var $TimeInMinutes; //!< Actual time interval in minutes
		var $UserDefaultTimeInterval; //!< The time interval set as default in the user's options menu
		var $SQLWhereTime; //!< Contains the where part of the SQL statement
		var $OrderBy; //!< Sorting argument
		var $GroupBy; //!< Grouping Argument
		var $Sort; //!< Ascending/Descending
		var $SQLWherePart; //!< Contains the whole SQL where part
		var $SQLWhereInfoUnit; //!< Restriction of InfoUnit type
		var $SQLWherePriority; //!< Restriction of Priority
		var $SQLWhereHost; //!< Restriction of ip/host
		var $SQLWhereMsg; //!< Message must contain a certain string
	 
		/*! Constructor
		* 
		* Get filter settings form url or if not available, get the user's default filter settings
		* from the database.
		*/ 
		function EventFilter()
		{      
			// get the selected mode (time period)
			$this->TimeInterval = $_SESSION['ti'];
      
			//Order argument
			$this->OrderBy = $_SESSION['order'];

			if($_SESSION['change'] == 'Predefined')
			{
				$this->SelectTimeMode();
			}
			else if ($_SESSION['change'] == 'Manually')
			{
				$this->ManuallyTime();
			}
		}

		/*!
		* Set $BeginTime and $EndTime if the user has selected manually events date
		*/
		function ManuallyTime()
		{
			$tmp_endtime = mktime(23, 59, 59, $_SESSION['m2'], $_SESSION['d2'], $_SESSION['y2']);
			$tmp_endtime > 0 ? $this->EndTime = $tmp_endtime : 0;
			$tmp_begintime = mktime(0, 0, 0, $_SESSION['m1'], $_SESSION['d1'], $_SESSION['y1']);
			$tmp_begintime > 0 ? $this->BeginTime = $tmp_begintime : 0;
		}

		/*!
		* Get the default Time Interval from the user profil. This is stored in the database.
		*/
		function GetDefaultTimeInterval()
		{
			// instead of this read from session/database-->
			$this->UserDefaultTimeInterval = "today"; 
		}

		/*!
		* SelectMode decide on the TimeInterval variable which functions have to call to set the time interval.
		* Possible modes are "thishour", "today" and an indication in minutes. This function also prove if 
		* TimeInterval valid. If invalid it used the default setting for TimeInterval from the user profil getting from 
		* the database.
		*
		*/
		function SelectTimeMode()
		{
			// if TimeInterval is not transmitted by url, get the TimeInterval from the UserProfil
			if (empty($this->TimeInterval))
			$this->GetDefaultTimeInterval();
	 
			switch ($this->TimeInterval)
			{
				case "thishour":
					$this->SetTimeIntervalThisHour();
					break;
				case "today":
					$this->SetTimeIntervalToday();
					break; 
				case "yesterday":
					$this->SetTimeIntervalYesterday();
					break; 
				default:
					if (substr($this->TimeInterval, 0, 3) == "min")
					{
						//This is to convert the string after the "min"(from the URL) into an integer
						$tmpTi = substr($this->TimeInterval, 3);
						if (!is_numeric($tmpTi))
							die(" $tmpTi is no number");
            //string + int = int! this is required, because is numeric don't prove /-- etc.
						$this->TimeInMinutes = $tmpTi+0;   
						$this->SetTimeIntervalMin($this->TimeInMinutes);
					}
					else
					{
						//this occurs only if an invalid value comes from the url
						switch ($this->UserDefaultTimeInterval)
						{
							//if user has thishour set as default
							case "thishour":
								$this->SetTimeIntervalThisHour();
								break;
							//if user has today set as default
							case "today":
								$this->SetTimeIntervalToday();
								break;
							case "yesterday":
								$this->SetTimeIntervalYesterday();
								break;
							//if user has his own number of minutes(e.g. 60) set as default 
							default:
								$this->SetTimeInterval($this->UserDefaultTimeInterval);     
						}
					}
			}
		}

		/*!
		* Calculate the time interval for this hour and set EndTime and EndTime. EndTime of the time interval is now,
		* BeginTime is the start time of the current hour. You get the current unix timestamp
		* with time(), wich is equel to EndTime. To get the BeginTime, easily take the current timestamp
		* and set the minutes and seconds to 0..
		*
		* \remarks An example: Current time is 2003-05-05 11:53:21. In this case EndTime = 2003-05-05 11:53:21
		* and BeginTime = 2003-05-05 11:00:00.
		*
		*/
		function SetTimeIntervalThisHour()
		{
			$mytime = time();
			$y = date("Y", $mytime);
			$m = date("m", $mytime);
			$d = date("d", $mytime);
			$h = date("H", $mytime);
	  
			$this->EndTime = $mytime;
			$this->BeginTime = mktime($h, 0, 0, $m, $d, $y);
	  
			//$this->EndTime =  date("Y-m-d H:i:s", time());
			//$this->BeginTime = date("Y-m-d H", time()) . ":00:00"; 
		}

		/*!
		* Calculate the time interval for today and set BeginTime and EndTime. EndTime of the time interval is now, 
		* BeginTime is the date of today with hour 00:00:00. You get the current unix timestamp 
		* with time(), which is equal to EndTime. To get the BeginTime take the date of the current
		* timestamp and set the hour to 00:00:00.
		*
		* \remarks An example: Current time is 2003-05-05 11:53:21. In this case EndTime = 2003-05-05 11:53:21
		* and BeginTime = 2003-05-05 00:00:00.
		*/
		function SetTimeIntervalToday()
		{
			$mytime = time();
			$y = date("Y", $mytime);
			$m = date("m", $mytime);
			$d = date("d", $mytime);

			$this->EndTime = $mytime;
			$this->BeginTime = mktime(0, 0, 0, $m, $d, $y);
		
			//$this->EndTime =  date("Y-m-d H:i:s", time());
			//$this->BeginTime = date("Y-m-d ", time()) . "00:00:00"; 
		}

		/*!
		* Calculate the time interval for yesterday and set BeginTime and EndTime. EndTime of the time interval is now, 
		* BeginTime is the date of today with hour 00:00:00. You get the current unix timestamp 
		* with time(), which is equal to EndTime. To get the BeginTime take the date of the current
		* timestamp and set the hour to 00:00:00.
		*
		* \remarks An example: Current time is 2003-05-05 11:53:21. In this case EndTime = 2003-05-04 23:59:59
		* and BeginTime = 2003-05-04 00:00:00.
		*/
		function SetTimeIntervalYesterday()
		{
			$mytime = time();
			$y = date("Y", $mytime);
			$m = date("m", $mytime);
			$d = date("d", $mytime);

			$d--;
	  
			$this->EndTime = mktime(23, 59, 59, $m, $d, $y);
			$this->BeginTime = mktime(0, 0, 1, $m, $d, $y);
		
			//$this->EndTime =  date("Y-m-d H:i:s", time());
			//$this->BeginTime = date("Y-m-d ", time()) . "00:00:00"; 
		}


		/*! 
		* Calculates the time in minutes from now to the beginning of the interval and set EndTime and EndTime.
		* To do this, get the current timestamp with time(), which is equal to EndTime, and take from it TimeInMinutes off.
		*
		* \remarks An example: Current time is 2003-05-05 11:53:21 and TimeInMinutes are 60. In this case
		* EndTime is 2003-05-05 11:53:21 and BeginTime = 2003-05-05 10:53:21.
		*
		* \param TimeInMinutes describe how many minutes are between the BeginTime and EndTime
		*/
		function SetTimeIntervalMin($TimeInMinutes)
		{
			$mytime = time();
			$this->BeginTime = $mytime - $TimeInMinutes*60;
			$this->EndTime =  $mytime; 
		}


		// generate HTML to display in quick menu bar
		// returns: string, html to be displayed 
		function GetHTMLQuickDisplay()
		{
		}

		/*!
		 * To calculate the the UTC Timestamp
		 * \param timestamp of the local system	
		 * \return timestamp, UTC time
		 */
		function GetUTCtime($iTime)
		{ 
			if ( $iTime == 0 ) $iTime = time();
			$ar = localtime ( $iTime );

			$ar[5] += 1900; $ar[4]++;
			$iTztime = gmmktime ( $ar[2], $ar[1], $ar[0],
		   $ar[4], $ar[3], $ar[5], $ar[8] );
			return ( $iTime - ($iTztime - $iTime) );
		}

		/*! 
		* Use this to set SQLWhereTime which is part of the sql where clause. 
		* This is responsilbe for the limitation of the requested data by time.
		*/
		function SetSQLWhereTime()
		{
			if (_UTCtime)
				$this->SQLWhereTime = _DATE . ' >= ' . dbc_sql_timeformat($this->GetUTCtime($this->BeginTime)) . ' AND ' . _DATE . ' <= ' . dbc_sql_timeformat($this->GetUTCtime($this->EndTime));
			else
				$this->SQLWhereTime = _DATE . ' >= ' . dbc_sql_timeformat($this->BeginTime) . ' AND ' . _DATE . ' <= ' . dbc_sql_timeformat($this->EndTime);        
		}

		/*! 
		* Use this to get a part of the sql where clause. 
		* This is responsilbe for the limitation of the requested data by time. 
		* \return A string, part of the SQL where clause (time argument)
		*/
		function GetSQLWhereTime()
		{
			$this->SetSQLWhereTime();
			return $this->SQLWhereTime;
		}

		/*! 
		* Use this to set SQLWhereInfoUnit which is part of the sql where clause. 
		* This set the InfoUnit restriction.
		*/
		function SetSQLWhereInfoUnit()
		{   
		  // sl = 1, er = 3, o
		  // sl-er-o (matrix)
		  // 0-0-0 -> all InfoUnit  #0
		  // 0-0-1 -> only o        #1
		  // 0-1-0 -> only er       #2
		  // 0-1-1 -> not sl        #3
		  // 1-0-0 -> only sl       #4
		  // 1-0-1 -> not er        #5
		  // 1-1-0 -> only sl and er#6
		  // 1-1-1 -> all InfoUnit  #7
		  $tmpSQL[0][0][0]= '';
		  $tmpSQL[0][0][1]= ' AND (InfoUnitID<>1 AND InfoUnitID<>3) ';
		  $tmpSQL[0][1][0]= ' AND InfoUnitID=3 ';
		  $tmpSQL[0][1][1]= ' AND InfoUnitID<>1 ';
		  $tmpSQL[1][0][0]= ' AND InfoUnitID=1 ';
		  $tmpSQL[1][0][1]= ' AND InfoUnitID<>3 ';
		  $tmpSQL[1][1][0]= ' AND (InfoUnitID=1 or InfoUnitID=3) ';
		  $tmpSQL[1][1][1]= '';
		  $this->SQLWhereInfoUnit = $tmpSQL[$_SESSION['infounit_sl']][$_SESSION['infounit_er']][$_SESSION['infounit_o']];
     
/*      
      if ($_SESSION['infounit_sl'] == 1)
      {
        if ($_SESSION['infounit_er'] == 1)
        {
          if ($_SESSION['infounit_o'] == 1) { $tmpSQL = ''; } // #7
          else { $tmpSQL = ' AND (InfoUnitID=1 or InfoUnitID=3) '; } // #6
        }
        else 
        {
          if ($_SESSION['infounit_o'] == 1) 
          { $tmpSQL = ' AND InfoUnitID<>3 '; } // #5
          else 
          { $tmpSQL = ' AND InfoUnitID=1 '; } // #4        
        }
      }
      else
      {
        if ($_SESSION['infounit_er'] == 1)
        {
          if ($_SESSION['infounit_o'] == 1) { $tmpSQL = ' AND InfoUnitID<>1 '; } // #3
          else { $tmpSQL = ' AND InfoUnitID=3 '; } // #2
        }
        else 
        {
          if ($_SESSION['infounit_o'] == 1) { $tmpSQL = ' AND (InfoUnitID<>1 AND InfoUnitID<>3) '; } // #1
          else { $tmpSQL = ''; } // #0        
        }        
      }
 */
                    
		}

		/*!
		* Use this to get a part of the sql where clause.
		* This sort out the InfoUnit type.
		* \return A string, part of the SQL where clause (InfoUnit type restriction)
		*/
		function GetSQLWhereInfoUnit()
		{
			$this->SetSQLWhereInfoUnit();
			return $this->SQLWhereInfoUnit;
		}

		function SetSQLWherePriority()
		{
			//Optimizing Query...
			if ($_SESSION['priority_0'] == 1 and $_SESSION['priority_1'] == 1 and $_SESSION['priority_2'] == 1 and $_SESSION['priority_3'] == 1 and $_SESSION['priority_4'] == 1 and $_SESSION['priority_5'] == 1 and $_SESSION['priority_6'] == 1 and $_SESSION['priority_7'] == 1)
			{
				$this->SQLWherePriority = "";
			}
			else
			{
				if ($_SESSION['priority_0'] == 1 or $_SESSION['priority_1'] == 1 or $_SESSION['priority_2'] == 1 or $_SESSION['priority_3'] == 1 or $_SESSION['priority_4'] == 1 or $_SESSION['priority_5'] == 1 or $_SESSION['priority_6'] == 1 or $_SESSION['priority_7'] == 1)
				{
					$tmpSQL = ' AND (';
					$orFlag = false;
					if ($_SESSION['priority_0'] == 1)
					{
						$tmpSQL.='Priority=0';
						$orFlag=true;
					}
					if ($_SESSION['priority_1'] == 1)
					{
						if ($orFlag)
							$tmpSQL.=' or ';

					$tmpSQL.='Priority=1';
					$orFlag=true;
					}
					if ($_SESSION['priority_2'] == 1)
					{
						if ($orFlag)
							$tmpSQL.=' or ';

					$tmpSQL.='Priority=2';
					$orFlag=true;
					}
					if ($_SESSION['priority_3'] == 1)
					{
						if ($orFlag)
							$tmpSQL.=' or ';

					$tmpSQL.='Priority=3';
					$orFlag=true;
					}
					if ($_SESSION['priority_4'] == 1)
					{
						if ($orFlag)
							$tmpSQL.=' or ';

					$tmpSQL.='Priority=4';
					$orFlag=true;
					}
					if ($_SESSION['priority_5'] == 1)
					{
						if ($orFlag)
							$tmpSQL.=' or ';

					$tmpSQL.='Priority=5';
					$orFlag=true;
					}
					if ($_SESSION['priority_6'] == 1)
					{
						if ($orFlag)
							$tmpSQL.=' or ';

					$tmpSQL.='Priority=6';
					$orFlag=true;
					}
					if ($_SESSION['priority_7'] == 1)
					{
						if ($orFlag)
							$tmpSQL.=' or ';

					$tmpSQL.='Priority=7';
					$orFlag=true;
					}

					$tmpSQL.=')';
				}
				else
				{
					$tmpSQL = '';
				}
				$this->SQLWherePriority = $tmpSQL;
			}
		}

		/*!
		* Use this to get a part of the sql where clause.
		* This sort out the priority.
		* \return A string, part of the SQL where clause (Priority restriction)
		*/
		function GetSQLWherePriority()
		{
			$this->SetSQLWherePriority();
			return $this->SQLWherePriority;
		}
    
    
    /*!
		* Use this to get a part of the sql where clause.
		* This search only for a single ip or host.
		* \return A string, part of the SQL where clause (Host restriction)
		*/
		function GetSQLWhereHost()
		{
			$this->SetSQLWhereHost();
			return $this->SQLWhereHost;
		}

		/*! 
		* Use this to get a part of the sql where clause. 
		* This is responsilbe for the limitation of the requested data by ip/host. 
		*/
		function SetSQLWhereHost()
		{
      $tmpSQL='';
      // filhost must be validate in include!
      if (isset($_SESSION['filhost']))
      {
        if (!empty($_SESSION['filhost']))
          $tmpSQL.=" AND FromHost='".$_SESSION['filhost']."'";
      }
      $this->SQLWhereHost = $tmpSQL;     

		}
    
    /*!
		* Use this to get a part of the sql where clause.
		* This search only for a single ip or host.
		* \return A string, part of the SQL where clause (Host restriction)
		*/
		function GetSQLWhereMsg()
		{
			$this->SetSQLWhereMsg();
			return $this->SQLWhereMsg;
		}

    
		/*! 
		* Use this to get a part of the sql where clause. 
		* This is responsilbe for the limitation of the requested data by ip/host. 
		*/
		function SetSQLWhereMsg()
		{
      $tmpSQL='';
      // filhost must be validate in include!
      if (isset($_SESSION['searchmsg']))
      {
        if (!empty($_SESSION['searchmsg']))
          $tmpSQL.=" AND Message like '".db_get_wildcut().$_SESSION['searchmsg'].db_get_wildcut()."'";
      }
      $this->SQLWhereMsg = $tmpSQL;     

		}    
    
		/*! 
		* Use this to get a part of the sql where clause. 
		* This is responsilbe for the limitation of the requested data by time. 
		*/
		function SetSQLWherePart($whereMode)
		{
			//Mode 0 => I.e for events-display
			if($whereMode == 0)
			{
				$this->SQLWherePart = $this->GetSQLWhereTime().$this->GetSQLWhereInfoUnit().$this->GetSQLWherePriority().$this->GetSQLWhereHost().$this->GetSQLWhereMsg();
			}
			elseif($whereMode == 1)
			{
				$this->SQLWherePart = $this->GetSQLWhereTime().$this->GetSQLWherePriority().$this->GetSQLWhereHost().$this->GetSQLWhereMsg();
			}
			
		}
        

		/*! 
		* Use this to get a part of the sql where clause. 
		* This is responsilbe for the limitation of the requested data by time. 
		* \return A string, the SQL where part
		*/
		function GetSQLWherePart($time_only)
		{
			if($time_only == 1)
			{
				$this->SetSQLWherePart(1);
			}
			else
			{
				$this->SetSQLWherePart(0);
			}
			return $this->SQLWherePart;
		}

		/*!
		 * Use this to get the part of the sql part, responsible for the sorting argument.
		 * \return A string, part of the SQL where clause (sorting argument)
		 */
		function GetSQLSort()
		{
			switch ($this->OrderBy)
			{
				case "Date":
					$tmpSQL = ' ORDER BY '._DATE.' DESC';
					break;
				case "Facility":
					$tmpSQL = ' ORDER BY Facility';
					break;
				case "Priority":
					$tmpSQL = ' ORDER BY Priority';
					break;
				case "FacilityDate":
					$tmpSQL = ' ORDER BY Facility, '._DATE.' DESC';
					break;
				case "PriorityDate":
					$tmpSQL = ' ORDER BY Priority, '._DATE.' DESC';
					break;
				case "Host":
					$tmpSQL = ' ORDER BY FromHost';
					break;
				default:
					$tmpSQL = ' ORDER BY '._DATE.' DESC';
					break;
			}
			return $tmpSQL;
		}

		function GetSysLogTagSQLSort()
		{
			$this->OrderBy = $_SESSION['tag_order'];
			switch ($this->OrderBy)
			{
				case "SysLogTag":
					$tmpSQL = ' ORDER BY SysLogTag ' . $_SESSION['tag_sort'];
					break;
				case "Occurences":
					$tmpSQL = ' ORDER BY occurences ' . $_SESSION['tag_sort'];
					break;
				case "Host":
					$tmpSQL = ' ORDER BY FromHost ' . $_SESSION['tag_sort'];
					break;
				default:
					$tmpSQL = ' ORDER BY SysLogTag ' . $_SESSION['tag_sort'];
					break;
			}
			return $tmpSQL;
		}

		function SetSQLGroup($groupBy)
		{
			$this->GroupBy = $groupBy;
		}

		function GetSQLGroup()
		{
			switch($this->GroupBy)
			{
				case "SysLogTag":
					$tmpSQL = " GROUP BY SysLogTag";
					break;
				case "SysLogTagHost":
					$tmpSQL = " GROUP BY SysLogTag, FromHost";
					break;
				default:
					$tmpSQL = " GROUP BY SysLogTag";
					break;
			}
			return $tmpSQL;
		}
	}

?>