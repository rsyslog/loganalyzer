<?php

/*#### #### #### #### #### #### #### #### #### ####
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2004-2005  Adiscon GmbH

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
	include _CLASSES . 'eventsnavigation.php';

	
	if(_ENABLEUI == 1)
	{
		// *** WHEN TRUE, LOGOUT USER ***
		if (isset($_GET['do']))
		{
			if ($_GET['do'] == 'logout')
			{
				setcookie("usr", "|", _COOKIE_EXPIRE, "/");
				setcookie("usrdis", "|", _COOKIE_EXPIRE, "/");
				setcookie("valid", "0", _COOKIE_EXPIRE, "/");
				session_unset();
				header("Location: index.php");
				exit;
			}
		}
		// ****************************************
	}
	
	// if no session is available, but a cookie => the session will be set and the settings loaded
	// if no session and no cookie is available => the login screen will be displayed
	if( _ENABLEUI && !isset($_SESSION['usr']) )
	{
		if(!isset($_COOKIE['valid']) || $_COOKIE['valid'] == "0")
		{
			WriteHead("phpLogCon :: Index", "", "", "phpLogCon");
			echo "<br>";
			CheckInstallDir();
			echo "<br>";

			echo '<font><b>', _MSG001, '.</b></font>';
			echo '<form action="submit.php" method="POST">';
			echo _MSGUsrnam, ': <input type="text" name="usr" size="15"><br>';
			echo _MSGpas, ': <input type="password" name="pass" size="15"><br>';
			echo _MSGSavCook, '<input type=checkbox name="save_cookies" value="1"><br>';
			echo '<input type="submit" value="Login">';
			echo '</form>';
			exit;
		}
		else
		{
			// reload
			session_register('usr');
			session_register('usrdis');
			$_SESSION['usr'] = $_COOKIE['usr'];
			$_SESSION['usrdis'] = $_COOKIE['usrdis'];
			LoadUserConfig();
			header("Location: index.php");
			exit;
		}

		if($_SESSION['save_cookies'])
		{
			setcookie("valid", $_COOKIE["valid"], _COOKIE_EXPIRE, "/");
			setcookie("usr", $_COOKIE["usr"], _COOKIE_EXPIRE, "/");
		}
	}

	WriteStandardHeader('Index');

	// Show current Version Number (configurable in config.php):
	echo "<br><b>phpLogCon version "._VersionMajor."."._VersionMinor."."._VersionPatchLevel."</b><br>";


	echo '<br>';
	include _CLASSES . 'eventfilter.php';

	//the splitted sql statement
	$cmdSQLfirst_part = "SELECT ";
	//$cmdSQLmain_part = "* FROM "._DBTABLENAME;
	$cmdSQLmain_part = 'ID, '._DATE.', Facility, Priority, FromHost, Message, InfoUnitID FROM '._DBTABLENAME;
	$cmdSQLlast_part = " WHERE ";

	//define the last part of the sql statment, e.g. the where part, ordery by, etc.
	$myFilter = New EventFilter;
	$cmdSQLlast_part .= $myFilter->GetSQLWherePart(0);
	$cmdSQLlast_part .= $myFilter->GetSQLSort();

	$myEventsNavigation = new EventsNavigation(5);
	$myEventsNavigation->SetPageNumber(1);

	$myEventsNavigation->SetEventCount($global_Con, $cmdSQLlast_part);

	$num = $myEventsNavigation->GetEventCount();

	if(_ENABLEUI)
		echo _MSGWel . ", <font color=\"blue\">" . $_SESSION["usrdis"] . "</font>" . _MSGChoOpt;
	else
		echo  _MSGWel . _MSGChoOpt;
	
	$SQLcmdfirst_part = "SELECT DISTINCT ";
	$SQLcmdmain_part = "(*) FROM "._DBTABLENAME;
	$SQLcmdlast_part = " WHERE ";
	$myFilter = New EventFilter;
	$SQLcmdlast_part .= $myFilter->GetSQLWherePart(1);

	$result_sl = db_exec($global_Con, "SELECT DISTINCT COUNT(*) as num" . " FROM "._DBTABLENAME . $SQLcmdlast_part . " AND InfoUnitID=1");
	$row_sl = db_fetch_array($result_sl);
	db_free_result($result_sl);

	$result_er = db_exec($global_Con, "SELECT DISTINCT COUNT(*) as num" . " FROM "._DBTABLENAME . $SQLcmdlast_part . " AND InfoUnitID=3");
	$row_er = db_fetch_array($result_er);
	db_free_result($result_er);

	if(strtolower(_DB_APP) == "mssql")
		$rowIndex = 'num';
	else
		$rowIndex = 0;
	
	if (_AdminMessage != "")
	{
		echo "<br><br><b>"._AdminMessage."</b><br>";
	}

	echo "<br><br><b>" . _MSGQuiInf . "</b>:";
	echo "<table border='0' cellspacing='0' class=\"EventTable\"><br>";
	echo "<tr><td CLASS=TDHEADER>" . _MSGNumSLE . "</td><td CLASS=TD1>" . $row_sl['num'] . "</td></tr>";
	echo "<tr><td CLASS=TDHEADER>" . _MSGNumERE . "</td><td CLASS=TD2>" . $row_er['num'] . "</td></tr>";
	echo "</table>";
	echo "<br><b>" . _MSGTop5 . ":</b><br><br>";
	if($num == 0)
	{
		  //output if no data exists for the search string
		  echo "<br><b>" . _MSGNoData . "!</b>";
	}
	else
	{
		echo '<table class="EventTable">';
		echo "<tr CLASS=TDHEADER>";
		echo "<td>" . _MSGDate . "</td>";
		echo "<td>" . _MSGFac . "</td>";
		echo "<td>" . _MSGPri . "</td>";
		echo "<td>" . _MSGInfUI . "</td>";
		echo "<td>" . _MSGHost . "</td>";
		echo "<td>" . _MSGMsg . "</td>";
		echo "</tr>";

		$res = db_exec_limit($global_Con, $cmdSQLfirst_part, $cmdSQLmain_part, $cmdSQLlast_part, 1, 5, $myFilter->OrderBy);
		$i = 0;
		while($row = db_fetch_array($res))
		{
			if (db_errno() != 0)
			{
				echo db_errno() . ": " . db_error(). "\n";
			}
	
			//choose InfoUnitdType  1 = SL = Syslog, 3 = Eventreport, O = Other
			switch ($row['InfoUnitID']) 
			{
			  case 1:
				$infounit = "SL";
				break;
			  case 3:
				$infounit = "ER";
				break;
			  default:
				$infounit = "O";
			}
			static $tc = 1;
			echo '<tr>';
			echo '<td CLASS=TD', $tc, '><nobr>',$row[_DATE],'</nobr></td>'; //date
			echo '<td CLASS=TD', $tc, '>',$row['Facility'],'</td>'; //facility
			
			// get the description of priority (and get the the right color, if enabled)
			$pricol = 'TD' . $tc;
			$priword = FormatPriority($row['Priority'], $pricol);
			echo '<td CLASS=', $pricol, '>', $priword, '</td>'; 		
			
			echo "<td CLASS=TD" . $tc . ">".$infounit."</td>"; //InfoUnit
			echo "<td CLASS=TD" . $tc . ">".$row['FromHost']."</td>"; //host
			$message = $row['Message'];
			$message = str_replace("<", "&lt;", $message);
			$message = str_replace(">", "&gt;", $message);

			echo '<td CLASS=TD', $tc, '><a CLASS="Msg" href="details.php?lid=', $row['ID'], '">', $message,  '</a></td>'; //message

			//for changing colors
			if($tc == 1) $tc = 2;
			else $tc = 1;
			echo "</tr>";
		}
		echo "</table>";
		echo "<a href=\"events-display.php\"><b>[more...]</b></a>";

		// 2005-08-17 by therget --->
		// If any date is in the future, show a message on the homepage.		

		/* 2005-09-19 by mm
		 * $now = date("Y-m-d g:i:s"); // <-- this is a bug use H for 0 - 23 hours.
		 * Furthermore, use the database driver for date/time stuff!
		 */		
		$sqlstatement = "SELECT COUNT(*) AS datecount FROM "._DBTABLENAME ." WHERE "._DATE." > ".dbc_sql_timeformat(time());

		$result = db_exec($global_Con,$sqlstatement);				
		$db_datecount = db_fetch_array($result, "datecount");		

		if ($db_datecount[0] > 0)
		{
			echo _NoteMsgInFuture1, $db_datecount[0], _NoteMsgInFuture2;
		}
		// <--- End 2005-08-17 by therget
	}

	WriteFooter();
?>
