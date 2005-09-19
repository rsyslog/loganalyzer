<?php

/*#### #### #### #### #### #### #### #### #### ####
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2004-2005  Adiscon GmbH

Version 1.1

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; 
if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, 
MA  02111-1307, USA.

If you have questions about phpLogCon in general, please email info@adiscon.com. 
To learn more about phpLogCon, please visit http://www.phplogcon.com.

This Project was intiated and is maintened by Rainer Gerhards <rgerhards@hq.adiscon.com>. 
See AUTHORS to learn who helped make it become a reality.

*/#### #### #### #### #### #### #### #### #### #### 

	include 'include.php';

	WriteStandardHeader(_MSGShwSlt);

	//classes
	include _CLASSES . 'eventsnavigation.php';
	include _CLASSES . 'eventfilter.php';


// SELECT COUNT(ID) AS occurences, ID, FromHost, Message, SysLogTag FROM SystemEvents WHERE InfoUnitID=1 AND ReceivedAt >= '2004-01-01 00:00:00' AND ReceivedAt <= '2004-10-26 23:59:59' GROUP BY SysLogTag ORDER BY occurences DESC limit 0,20

// SELECT COUNT(ID) AS occurences, SysLogTag FROM SystemEvents WHERE (InfoUnitID = 1) AND (ReceivedAt >= '2004-01-01 00:00:00') AND (ReceivedAt <= '2004-10-26 23:59:59') GROUP BY SysLogTag


// TO-DO: Change "ExampleHost" to "Most Host"

	//the splitted sql statement
	// Cause MSSQL has proplems with GROUP BY clauses we need to do 2 DataBase-Queries
	// the first one gets the occurences on one syslogtag, the syslogtag itself and the host.
	// the second one only gets the message!
	// Don't try to combine these two queries! MSSQL will give you a headnut! :D
	$cmdSQLfirst_part = 'SELECT ';
	$cmdSQLmain_part_1 = "COUNT(ID) AS occurences, SysLogTag";
	$cmdSQLmain_part_2 = "ID, Message FROM "._DBTABLENAME;
	$cmdSQLlast_part_1 = ' WHERE InfoUnitID=1 AND ';
	$cmdSQLlast_part_2 = ' WHERE InfoUnitID=1 AND ';

	// define the last part of the sql statment, e.g. the where part, ordery by, etc.
	$myFilter = New EventFilter;
	// Which methode is choosed?
	if($_SESSION['show_methode'] == "Host")
	{
		$myFilter->SetSQLGroup("SysLogTagHost");
		$cmdSQLmain_part_1 .= ", FromHost FROM "._DBTABLENAME;
	}
	else
	{
		if($_SESSION['tag_order'] == "Host")
			$_SESSION['tag_order'] = "SysLogTag";
		$myFilter->SetSQLGroup("SysLogTag");
		$cmdSQLmain_part_1 .= " FROM "._DBTABLENAME;
	}
	$cmdSQLlast_part_1 .= $myFilter->GetSQLWherePart(1);
	$cmdSQLlast_part_2 .= $myFilter->GetSQLWherePart(1);

	// how much data records should be displayed on one page
	if($_SESSION['epp'] < 1 || $_SESSION['epp'] > 100)
		$myEventsNavigation = new EventsNavigation(20);
	else
		$myEventsNavigation = new EventsNavigation($_SESSION['epp']);

	// show (include) quick filters
	include "quick-filter.php";

	echo "<br>";

	// how much data records match with the filter settings
//	if(strtolower(_CON_MODE) == "odbc" && strtolower(_DB_APP) == "mssql")
//		$myEventsNavigation->EventCount = odbc_record_count($global_Con, $cmdSQLfirst_part . $cmdSQLmain_part_1 . $cmdSQLlast_part_1);
//	else
		$myEventsNavigation->SetEventCount($global_Con, $cmdSQLlast_part_1);
	$num = $myEventsNavigation->GetEventCount();
	$cmdSQLlast_part_1 .= $myFilter->GetSQLGroup();
	$cmdSQLlast_part_1 .= $myFilter->GetSysLogTagSQLSort();

	// SQL statement to get result with limitation
	$res1 = db_exec_limit($global_Con, $cmdSQLfirst_part, $cmdSQLmain_part_1, $cmdSQLlast_part_1, $myEventsNavigation->GetLimitLower(), $myEventsNavigation->GetPageSize(), $myFilter->OrderBy);

	if($num == 0)
	{
	  // output if no data exit for the search string
	  echo '<br><b>', _MSGNoData, '</b>';
	}
	else
	{
		echo '<table>';
		echo '<tr><td align="left">';
		echo _MSGEvn, ' ', $myEventsNavigation->GetLimitLower(), ' ', _MSGTo, ' ', $myEventsNavigation->GetLimitUpper(), ' ', _MSGFrm, ' ', $myEventsNavigation->GetEventCount();
		echo '</td><td align="right">';

		$myEventsNavigation->ShowNavigation();

?>

		</td>
		</tr>
		</table>

		<table border="0" cellspacing="0" cellpadding="0" CLASS="EventTable">
			<tr CLASS=TDHEADER>
				<?php if($_SESSION['show_methode'] == "Host") echo "<td>Example Host</td>";?>
				<td>SysLogTag</td>
				<td>Occurences</td>
				<td>Example Message - Click for full list</td>
			</tr>

<?php

		//Read out words from phplogcon.ini which shouldn't
		//be displayed and replaced by '*'
		$file = file('phplogcon.ini');
		if($file != FALSE)
		{
			$numarrayfile = count($file);
			for($i = 0; $i < $numarrayfile; $i++)
			{
				$file[$i] = trim($file[$i]);
				if($file[$i] != '#')
				{
					if($file[$i] == '[phplogcon]')
					{
						for($j = $i+1; $j < $numarrayfile; $j++)
						{
							if( stristr($file[$j], 'wordsdontshow=') != FALSE )
							{
								$words = explode("=", $file[$j]);
								$words = explode(",", $words[1]);
							}
						}
					}
				}
			}
			$numarraywords = count($words);
		}

		$tc = 1;
		while($row1 = db_fetch_array($res1))
		{
			// here we get the message corresponding to a syslogtag!
			// for better performance we optimize the query with a TOP(Mssql) or LIMIT(MySql)
			$cmdTmpFirst = $cmdSQLfirst_part;
			$cmdTmpLast = $cmdSQLlast_part_2;
			$cmdSQLlast_part_2 .= " AND SysLogTag LIKE '" . $row1['SysLogTag'] . "'";
			if(strtolower(_CON_MODE) == "odbc" && strtolower(_DB_APP) == "mssql")
				$cmdSQLfirst_part .= "TOP 1 ";
			else
				$cmdSQLlast_part_2 .= " LIMIT 0,1";
			$res2 = db_exec($global_Con, $cmdSQLfirst_part . $cmdSQLmain_part_2 . $cmdSQLlast_part_2);
			$cmdSQLlast_part_2 = $cmdTmpLast;
			$cmdSQLfirst_part = $cmdTmpFirst;
			$row2 = db_fetch_array($res2);

			if($row2['Message'] == "")
				$message = _MSGNoMsg;
			else
				$message = $row2['Message'];

			echo '<tr>';
			if($_SESSION['show_methode'] == "Host")
				echo '<td CLASS=TD' . $tc . '>' . $row1['FromHost'] . '</td>';
			echo '<td CLASS=TD' . $tc . '>' . $row1['SysLogTag'] . '</td>';
			echo '<td CLASS=TD' . $tc . '>' . $row1['occurences'] . '</td>';
			
			$message = htmlspecialchars($message);
			
			if(isset($_SESSION['regexp']) && $_SESSION['regexp'] != "")
			{
				$_SESSION['regexp'] = trim($_SESSION['regexp']);
				$messageUp = strtoupper($message);
				$regexpUp = strtoupper($_SESSION['regexp']);
				$search_pos = strpos($messageUp, $regexpUp);
				if($search_pos !== FALSE)
				{
					$regexpLng = strlen($_SESSION['regexp']);
					$strCount = substr_count($messageUp, $regexpUp);
					$strTmp = $message;

					$message = '';
					for($i = 0; $i < $strCount; $i++)
					{
						$messageUp = strtoupper($strTmp);
						$search_pos = strpos($messageUp, $regexpUp);
						$subStrSt = substr($strTmp, 0 , $search_pos);
						$subStrExp = substr($strTmp, $search_pos, $regexpLng);
						$subStrEnd = substr($strTmp, ($search_pos + $regexpLng));
						$message .= $subStrSt . '<font color="' . $_SESSION['color'] . '">' . $subStrExp . '</font>';
						if($i == ($strCount - 1))
							$message .= $subStrEnd;

						$strTmp = $subStrEnd;
					}
				}
			}

			//Replace the words that had been read out from the ini file
			if($file != FALSE)
			{
				for($i = 0; $i < $numarraywords; $i++)
				{
					$repstr = '';
					$words[$i] = trim($words[$i]);
					for($j = 0; $j < strlen($words[$i]); $j++) $repstr .= '*';
					if($words[$i] != '')
						$message = eregi_replace($words[$i], $repstr, $message);
				}
			}

			echo '<td CLASS=TD', $tc, '><a CLASS="Msg" href="syslog-display.php?slt=' . $row1['SysLogTag'] . '">', $message, '</a></td>'; //message

			//for changing colors
			if($tc == 1) $tc = 2;
			else $tc = 1;
			/*
			echo "<td>".$row['Priority']."</td>";
			*/
			echo '</tr>';
		}
		echo "</table>";


	}

	WriteFooter();
?>