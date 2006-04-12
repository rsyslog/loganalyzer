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



// General variables
$szRedirectLink = "";
$szDescription = "";

if( !isset($_POST['databaseConfig']) )
	$_POST['databaseConfig'] = "";

if($_POST['databaseConfig'] == "DatabaseConfiguration")
{
	/*!
	 * Update database name setting 
	!*/
	if ($_POST['database'] != "")
		$_SESSION['database'] = $_POST['database'];
	else
		$_SESSION['database'] = _DBNAME;
}

$szRedirectLink = "database-config.php";
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