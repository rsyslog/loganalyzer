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



// General variables
$szRedirectLink = "";
$szDescription = "";

if( !isset($_POST['userConfig']) )
	$_POST['userConfig'] = "";

if($_POST['userConfig'] == "UserConfiguration")
{
	/*!
	 * Update language setting 
	!*/
	if ((eregi("^[a-z]+$", $_POST['language'])) and (strlen($_POST['language']) == 2))
		$_SESSION['language'] = $_POST['language'];
	else
		$_SESSION['language'] = 'en'; // default

	/*!
	 * Update style setting 
	!*/
	if ((eregi("^[a-z]+$", $_POST['stylesheet'])))
		$_SESSION['stylesheet'] = $_POST['stylesheet'];
	else
		$_SESSION['stylesheet'] = 'phplogcon'; // default

	/*!
	 * Update debug setting 
	!*/
	$_SESSION['debug'] = (isset($_POST['debug'])) ? 1 : 0;

	/*!
	 * Update filter save setting 
	!*/
	$_SESSION['savefiltersettings'] = (isset($_POST['savefiltersettings'])) ? 1 : 0;

	if(_ENABLEUI == 1)
	{
		//Save filter settings to database
		$query = GetUserConfigArray();

		for($i = 0; $i < count($query); $i++)
			db_exec($global_Con, $query[$i]);
	}
}
elseif($_POST['bookmarkConfig'] == "BookmarkDelete")
{
	$delstr = explode("//", $_POST["favorites"]);
  
	$result = db_exec($global_Con, "SELECT UserLogin, PropValue FROM UserPrefs WHERE UserLogin LIKE '" . $_SESSION["usr"] . "' AND Name LIKE 'PHPLOGCON_favorites'");
	$result = db_fetch_singleresult($result);
	$result = explode(",", $result["PropValue"]);
	$rowcntr = count($result);
	for($i = 0; $i < $rowcntr ; $i++)
	{
		if(stristr($result[$i], $delstr[1]))
	  		$delval = $i;
	}
	$delstr = "";
	for($j = 0; $j < $rowcntr ; $j++)
	{
  		if($j == $delval)
  			continue;
		else
		{
  			if($rowcntr == 2 || $j == ($rowcntr - 1))
  				$delstr .= $result[$j];
  			else
  				$delstr .= $result[$j] . ",";
		}
	}
	db_exec($global_Con, "UPDATE UserPrefs SET PropValue='" . $delstr . "' WHERE UserLogin LIKE '" . $_SESSION["usr"] . "' AND Name LIKE 'PHPLOGCON_favorites'");
	db_close($global_Con);
}
elseif($_POST['bookmarkConfig'] == "BookmarkAdd")
{
	if( stristr($_POST["sitename"], "'") || stristr($_POST["sitename"], "&quot;") || stristr($_POST["url"], "'") || stristr($_POST["url"], "&quot;"))
		$szDescription = _MSGSitInvChr;
	else
	{
  		$addstr = explode("http://", $_POST["url"]);
		if(isset($addstr[1]))
			$addstr[0] = $addstr[1];
  
		$result = db_exec($global_Con, "SELECT UserLogin, PropValue FROM UserPrefs WHERE UserLogin LIKE '" . $_SESSION["usr"] . "' AND Name LIKE 'PHPLOGCON_favorites'");
		$result = db_fetch_singleresult($result);
		if($result["PropValue"] == "")
			$addstr[0] = $result["PropValue"] . $addstr[0] . "|" . $_POST["sitename"];
		else
			$addstr[0] = $result["PropValue"] . "," . $addstr[0] . "|" . $_POST["sitename"];
		db_exec($global_Con, "UPDATE UserPrefs SET PropValue='" . $addstr[0] . "' WHERE UserLogin LIKE '" . $_SESSION["usr"] . "' AND Name LIKE 'PHPLOGCON_favorites'");
		db_close($global_Con);
	}
}

$szRedirectLink = "user-config.php";
$szDescription = "Your Personal user settings have been updated";

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