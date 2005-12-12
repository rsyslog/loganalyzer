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

// Check for speical ysql characters
function invalid_chars( $string )
{
	$bad_list = array("'",'"',"%");

	foreach( $bad_list as $needle )
	{
		if( strpos( $string, $needle ) !== FALSE )
		{
			return TRUE;
		}
	}
	return FALSE;
}

//	global _DBNAME, _DBUSERID, _DBPWD, _DBSERVER, $session_time;
	include 'include.php';

	if( !isset($_POST['save_cookies']))
		$_POST['save_cookies'] = 0;

    if( invalid_chars( $_POST['usr'] ) || invalid_chars( $_POST['pass'] ) )
	{
		WriteHead('phpLogCon :: ' , _MSGAccDen, '', '', _MSGAccDen, 0);
		print '<br><b>..:: ' . _MSGNamInvChr . ' ::..</b><br>';
		echo '<br>..:: <a href="index.php">', _MSGBac2Ind, '</a> ::..';
		db_close($global_Con);

		exit;
	}
	else
	{	
		$query  = "SELECT UserIDText, Password, DisplayName FROM Users WHERE UserIDText LIKE '" . $_POST['usr'] . "' AND Password LIKE '" . $_POST['pass'] . "'";
		$result = db_exec($global_Con, $query);// or die(db_die_with_error(_MSGInvQur . " :" . $query));
		$num    = db_num_rows($result);
		$result = db_fetch_singleresult($result);
		/*
		echo $num . "<br>";
		echo $result["UserIDText"] . "<br>";
		echo $result["phplogcon_lastlogin"] . "<br>";
		exit;
		*/

		if ($num == 0)
		{
			WriteHead("phpLogCon :: " . _MSGAccDen, "", "", _MSGAccDen, 0);
			print "<br><b>..:: " . _MSGFalLog . " ::..</b><br>";
			echo "<br>..:: <a href='index.php'>" . _MSGBac2Ind . "</a> ::..";
			db_close($global_Con);
			exit;
		}
		else
		{
			// $dat = now();
			// db_exec($global_Con, "UPDATE Users SET phplogcon_lastlogin = ".dbc_sql_timeformat($dat)." WHERE UserIDText LIKE '".$_POST["usr"]."'");
			session_register('save_cookies');
			if($_POST['save_cookies'] == 1)
			{
				$_SESSION['save_cookies'] = $_POST['save_cookies'];
				setcookie("valid", 1, _COOKIE_EXPIRE, "/");
				setcookie("usr", $result["UserIDText"], _COOKIE_EXPIRE, "/");
				setcookie("usrdis", $result["DisplayName"], _COOKIE_EXPIRE, "/");
			}
			else
				$_SESSION['save_cookies'] = 0;

			session_register("usr");
			session_register("usrdis");
			$_SESSION["usr"] = $result["UserIDText"];
			$_SESSION["usrdis"] = $result["DisplayName"];

			LoadUserConfig();
			// Loading Users Filter Config when enabled
			if($_SESSION['savefiltersettings'])
				LoadFilterConfig();

			db_close($global_Con);
			header("Location: index.php");

		}
	}
?>
