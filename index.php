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
			$_SESSION['usr'] = $_COOKIE['usr'];
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

	WriteHead("phpLogCon :: Index", "", "", "phpLogCon");
	echo "<br>";

	include _CLASSES . 'eventfilter.php';

	//the splitted sql statement
	$cmdSQLfirst_part = "SELECT ";
	//$cmdSQLmain_part = "* FROM "._DBTABLENAME;
	$cmdSQLmain_part = 'ID, '._DATE.', Facility, Priority, FromHost, Message, InfoUnitID FROM '._DBTABLENAME;
	$cmdSQLlast_part = " WHERE ";

	//define the last part of the sql statment, e.g. the where part, ordery by, etc.
	$myFilter = New EventFilter;
	$cmdSQLlast_part .= $myFilter->GetSQLWherePart(1);
	$cmdSQLlast_part .= $myFilter->GetSQLSort();

	$myEventsNavigation = new EventsNavigation(5);
	$myEventsNavigation->SetPageNumber(1);

	$myEventsNavigation->SetEventCount($global_Con, $cmdSQLlast_part);

	$num = $myEventsNavigation->GetEventCount();

	if(_ENABLEUI)
	{
		echo '<table align="right">';
		echo '<tr>';
		echo '<td><a href="index.php?do=logout">' . _MSGLogout . '</a></td>';
		echo '</tr>';
		echo '</table>';
		echo '..:: ',  _MSGLogSuc , ' ::..<br><br>';
	}
	if(_ENABLEUI)
		echo _MSGWel . ", <font color=\"blue\">" . $_SESSION["usr"] . "</font>" . _MSGChoOpt;
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

	echo "<br><br><b>" . _MSGQuiInf . "</b>:";
	echo "<table border='0' cellspacing='0' class=\"EventTable\"><br>";
	echo "<tr><td CLASS=TDHEADER>" . _MSGNumSLE . "</td><td CLASS=TD1>" . $row_sl[0] . "</td></tr>";
	echo "<tr><td CLASS=TDHEADER>" . _MSGNumERE . "</td><td CLASS=TD2>" . $row_er[0] . "</td></tr>";
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

		$res = db_exec_limit($global_Con, $cmdSQLfirst_part, $cmdSQLmain_part, $cmdSQLlast_part, 1, 5);

		while($row = db_fetch_array($res))
		{
			if (db_errno() != 0)
			{
				echo db_errno() . ": " . db_error(). "\n";
			}
	
			//choose InfoUnitdType  1 = SL = Syslog, 3 = Eventreport, O = Other
			switch ($row[6]) 
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
			echo '<td CLASS=TD', $tc, '><nobr>',$row[1],'</nobr></td>'; //date
			echo '<td CLASS=TD', $tc, '>',$row[2],'</td>'; //facility
			
			// get the description of priority (and get the the right color, if enabled)
			$pricol = 'TD' . $tc;
			$priword = FormatPriority($row[3], $pricol);
			echo '<td CLASS=', $pricol, '>', $priword, '</td>'; 		
			
			echo "<td CLASS=TD" . $tc . ">".$infounit."</td>"; //InfoUnit
			echo "<td CLASS=TD" . $tc . ">".$row[4]."</td>"; //host
			$message = $row[5];
			$message = str_replace("<", "&lt;", $message);
			$message = str_replace(">", "&gt;", $message);

			echo '<td CLASS=TD', $tc, '><a CLASS="Msg" href="details.php?lid=', $row[0], '">', $message,  '</a></td>'; //message

			//for changing colors
			if($tc == 1) $tc = 2;
			else $tc = 1;
			/*
			echo "<tr bgcolor=\"#ffffcc\"><td>".$row['ReceivedAt']."</td>";
			echo "<td>".$row['Facility']."</td>";
			echo "<td>".$row['Priority']."</td>";
			echo "<td>".$row['FromHost']."</td>";
			echo "<td>".$row['Message']."</td>";
			*/
			echo "</tr>";
		}
		echo "</table>";
	}

	WriteFood();
?>