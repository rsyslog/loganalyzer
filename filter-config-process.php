<?php

/*#### #### #### #### #### #### #### #### #### #### 
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2004  Adiscon GmbH

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

If you have questions about phpLogCon in general, please email info@adiscon.com. To learn more about phpLogCon, please visit 
http://www.phplogcon.com.

This Project was intiated and is maintened by Rainer Gerhards <rgerhards@hq.adiscon.com>. See AUTHORS to learn who helped make 
it become a reality.

*/#### #### #### #### #### #### #### #### #### #### 


include 'include.php';



	/*
	 * Read all settings from cookie. If it not set, display a login screen.
	 * If the user authentified, read the settings from database. The user can
	 * also alter it's oven personal settings. For security reaseon the password
	 * is not store in the cookie. Therefore you must enter your password each time,
	 * if you want to change the settings.
	 */


// General variables
$szRedirectLink = "";
$szDescription = "";

global $global_Con;

if( !isset($_POST['filConf']) )
	$_POST['filConf'] = "";

if($_POST['filConf'] == "FilterConfig")
{
	// configure filter settings
	$_SESSION['ti'] = $_POST['ti'];
    $_SESSION['order'] = $_POST['order'];
	$_SESSION['tag_order'] = $_POST['tag_order'];
	$_SESSION['tag_sort'] = $_POST['tag_sort'];
	$_SESSION['refresh'] = $_POST['refresh']+0; // +0 make sure that is numeric

	// enable/disable quick filter options
	$_SESSION['FilterInfoUnit'] = (isset($_POST['FilterInfoUnit'])) ? 1 : 0;
	$_SESSION['FilterOrderby'] = (isset($_POST['FilterOrderby'])) ? 1 : 0;
	$_SESSION['FilterRefresh'] = (isset($_POST['FilterRefresh'])) ? 1 : 0;
	$_SESSION['FilterColExp'] = (isset($_POST['FilterColExp'])) ? 1 : 0;
	$_SESSION['FilterHost'] = (isset($_POST['FilterHost'])) ? 1 : 0;
	$_SESSION['FilterMsg'] = (isset($_POST['FilterMsg'])) ? 1 : 0;

	// Set new info unit filter options
	if ($_SESSION['FilterInfoUnit'] == 1 or isset($_POST['fromConfig']))
    {
		$_SESSION['infounit_sl'] = (isset($_POST['infounit_sl'])) ? 1 : 0;
		$_SESSION['infounit_er'] = (isset($_POST['infounit_er'])) ? 1 : 0;
		$_SESSION['infounit_o'] = (isset($_POST['infounit_o'])) ? 1 : 0;
    }

	// Set new priority filter options
	$_SESSION['priority_0'] = (isset($_POST['priority_0'])) ? 1 : 0;
	$_SESSION['priority_1'] = (isset($_POST['priority_1'])) ? 1 : 0;
	$_SESSION['priority_2'] = (isset($_POST['priority_2'])) ? 1 : 0;
	$_SESSION['priority_3'] = (isset($_POST['priority_3'])) ? 1 : 0;
	$_SESSION['priority_4'] = (isset($_POST['priority_4'])) ? 1 : 0;
	$_SESSION['priority_5'] = (isset($_POST['priority_5'])) ? 1 : 0;
	$_SESSION['priority_6'] = (isset($_POST['priority_6'])) ? 1 : 0;
	$_SESSION['priority_7'] = (isset($_POST['priority_7'])) ? 1 : 0;

	// If all infounits are unchecked it makes no sense,
	// because in this case, no messages were displayed.
	// So, activate all infounit types
	if($_SESSION['infounit_sl'] == 0 && $_SESSION['infounit_er'] == 0 && $_SESSION['infounit_o'] == 0)
	{
		$_SESSION['infounit_sl'] = 1;
		$_SESSION['infounit_er'] = 1;
		$_SESSION['infounit_o'] = 1;
	}

	// If all priorities are unchecked it makes no sense,
	// because in this case, no messages were displayed.
	// So, activate all priority types
	if($_SESSION['priority_0'] == 0 && $_SESSION['priority_1'] == 0 && $_SESSION['priority_2'] == 0 && $_SESSION['priority_3'] == 0 && $_SESSION['priority_4'] == 0 && $_SESSION['priority_5'] == 0 && $_SESSION['priority_6'] == 0 && $_SESSION['priority_7'] == 0)
	{
		$_SESSION['priority_0'] = 1;
	    $_SESSION['priority_1'] = 1;
		$_SESSION['priority_2'] = 1;
	    $_SESSION['priority_3'] = 1;
		$_SESSION['priority_4'] = 1;
	    $_SESSION['priority_5'] = 1;
		$_SESSION['priority_6'] = 1;
		$_SESSION['priority_7'] = 1;
	}

	if(_ENABLEUI == 1)
	{
		//Save filter settings to database
		if($_SESSION['savefiltersettings'])
		{
			$query = GetFilterConfigArray();
			for($i = 0; $i < count($query); $i++)
				db_exec($global_Con, $query[$i]);
		}
	}
}

$szRedirectLink = "filter-config.php";
$szDescription = "Your Personal filter settings have been updated";



?>

<html>
<head>
<?php
	echo "<meta HTTP-EQUIV=\"REFRESH\" CONTENT=\"1; URL=".$szRedirectLink."\">";
?>
</head>
<title>Redirecting</title>
<Body>
<br><br><br><br>
<center>
<?php

echo "<h3>".$szDescription."</h3><br><br>";
echo "You will be redirected to <A HREF=\"$szRedirectLink\">this page</A> in 1 second.";

?>
</center>
</body>
</html>