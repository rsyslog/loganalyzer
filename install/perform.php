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

include "include.php";
include "../lang/" . $_POST['lang'] . ".php";



WriteHead("phpLogCon :: Installation Progress");

?>

<center>Checking users input...

<?php

// if there are fields missing, create an error message
$strErrorMsg = "";
if($_POST["dbhost"] == "" && $_POST['dbcon'] != "odbc")
	$strErrorMsg .= "Host/IP";
if($_POST["dbport"] == "")
	$_POST["dbport"] = 0;
if($_POST["dbuser"] == "")
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "User";
}
if($_POST["dbpass"] == "")
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "Password";
}
if($_POST["dbpassre"] == "")
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "Re-type Password";
}
if($_POST["dbname"] == "")
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "Database/DSN name";
}
if($_POST["dbpass"] != "" && $_POST["dbpassre"] != "")
{
	if(strcmp($_POST["dbpass"], $_POST["dbpassre"]) != 0)
	{
		if($strErrorMsg != "")
			$strErrorMsg .= "! Also the ";
		$strErrorMsg .= "Password and Re-typed Password aren't the same";
	}
}

if($strErrorMsg != "")
	$strDbErrMsg = "Database Settings: <i>" . $strErrorMsg . "</i>";
else
	$strDbErrMsg = "";

if( isset($_POST["ui"]) )
	$_POST["ui"] = 1;
else
	$_POST["ui"] = 0;

$strErrorMsg = "";
if($_POST["ui"] == 1 )
{
	if($_POST["uiuser"] != "")
	{
		if($_POST["uidisname"] == "")
			$strErrorMsg .= "Display name";
		if($_POST["uipass"] == "")
		{
			if($strErrorMsg != "")
				$strErrorMsg .= " - ";
			$strErrorMsg .= "Password";
		}
		if($_POST["uipassre"] == "")
		{
			if($strErrorMsg != "")
				$strErrorMsg .= " - ";
			$strErrorMsg .= "Re-type Password";
		}
		if($_POST["uipass"] != "" && $_POST["uipassre"] != "")
		{
			if(strcmp($_POST["uipass"], $_POST["uipassre"]) != 0)
				$strErrorMsg .= "Password and Re-typed Password aren't the same";
		}
		if($strErrorMsg != "")
			$strUiErrMsg = "User Interface Settings: <i>" . $strErrorMsg . "</i>";
		else
			$strUiErrMsg = "";
	}
}

if($strDbErrMsg != "" || $strUiErrMsg != "")
{
	echo "</center>";
	echo "While installing phpLogCon, there caused an error (Date: ".date("d.m.Y @ H:i")."):<br><br>";
	echo "<u><b>Operation:</b></u> Check user's input!<br>";
	echo "<u><b>Error:</b></u> <font color=red>You have to fill out following fields: <b>" . $strDbErrMsg . "<br>" . $strUiErrMsg . "</b></font><br><br>Go back and correct this!<br>..:: <a href='install.php'>Go back to installation</a> ::..<br>";
	echo "<br><br>";
	exit;
}

?>

<b><font color="red">Done!</font></b></center>
<center>Creating required tables...

<?php

// include database driver
if($_POST['dbcon'] == "odbc")
	include "../db-drv/odbc_" . $_POST["dbapp"] . ".php";
else
	include "../db-drv/mysql.php";

// connect to database
$installCon = db_own_connection($_POST["dbhost"], $_POST["dbport"], $_POST["dbuser"], $_POST["dbpass"], $_POST["dbname"]);


// ***********************
//  BEGIN CREATING TABLES
// ***********************

// Get SQL Queries from File
$strQueryFile = "EventTables.sql";
$arQueries = GetSQLQueries($strQueryFile);

// Execute Queries
for($i = 0; $i < count($arQueries); $i++)
	db_exec($installCon, $arQueries[$i]);

// If user interface is enabled, create tables
if($_POST["ui"] == 1 )
{
	$strQueryFile = "UserTables.sql";
	$arQueries = GetSQLQueries($strQueryFile);

	for($i = 0; $i < count($arQueries); $i++)
		db_exec($installCon, $arQueries[$i]);
}

?>

<b><font color="red">Done!</font></b></center>
<center>Inserting values into tables...

<?php

// ***************************
//  BEGIN INSERTS INTO TABLES
// ***************************

//If User Interface should be installed and a user should be created:
if($_POST["ui"] == 1 && $_POST["uiuser"] != "")
{
	$strQueryFile = "UserInserts.sql";
	$arQueries = GetSQLQueries($strQueryFile);

	// Find line which inserts a user into 'Users'-table and replace placeholders with values
	for($i = 0; $i < count($arQueries); $i++)
	{
		if( stristr($arQueries[$i], "INSERT INTO Users") )
		{
			if($_POST['dbtime'] == "utc")
				$now = dbc_sql_timeformat(GetUTCtime(time()));
			else
				$now = dbc_sql_timeformat(time());
			// Edit SQL User-Query - Replace placeholders in query with values from the code
			$arQueries[$i] = ereg_replace("<username>", $_POST['uiuser'], $arQueries[$i]);
			$arQueries[$i] = ereg_replace("<password>", $_POST['uipass'], $arQueries[$i]);
			$arQueries[$i] = ereg_replace("<realname>", $_POST['uidisname'], $arQueries[$i]);
			$arQueries[$i] = ereg_replace("<date>", $now, $arQueries[$i]);
			$arQueries[$i] = ereg_replace("<lang>", $_POST['uilang'], $arQueries[$i]);

			db_exec($installCon, $arQueries[$i]);
		}
		elseif( stristr($arQueries[$i], "INSERT INTO UserPrefs") )
		{
			$arQueries[$i] = ereg_replace("<username>", $_POST['uiuser'], $arQueries[$i]);
			db_exec($installCon, $arQueries[$i]);
		}
	}
}

//close database connection
db_close($installCon);

?>

<b><font color="red">Done!</font></b></center>
<center>Creating your config file (config.php)...

<?php

// ***************************
//  BEGIN EDITING CONFIG.PHP
// ***************************

//open file handle
$hConfigSrc = fopen("scripts/config.php.ex", "r");
$hConfigDes = fopen("../config.php", "w");

while (!feof($hConfigSrc))
{
	$strLine = fgets($hConfigSrc);
	//if the current line contains the searchstring, insert values and write line
	if(stristr($strLine, "define('_DBSERVER'"))
	{
		if($_POST['dbport'] != "")
			fwrite($hConfigDes, "  define('_DBSERVER', '" . $_POST["dbhost"] . ":" . $_POST['dbport'] . "');\r\n");
		else
			fwrite($hConfigDes, "  define('_DBSERVER', '" . $_POST["dbhost"] . "');\r\n");
	}
	elseif(stristr($strLine, "define('_DBNAME'"))
		fwrite($hConfigDes, "  define('_DBNAME', '" . $_POST["dbname"] . "');\r\n");
	elseif(stristr($strLine, "define('_DBUSERID'"))
		fwrite($hConfigDes, "  define('_DBUSERID', '" . $_POST["dbuser"] . "');\r\n");
	elseif(stristr($strLine, "define('_DBPWD'"))
		fwrite($hConfigDes, "  define('_DBPWD', '" . $_POST["dbpass"] . "');\r\n");
	elseif(stristr($strLine, "define('_CON_MODE'"))
		fwrite($hConfigDes, "  define('_CON_MODE', '" . $_POST["dbcon"] . "');\r\n");
	elseif(stristr($strLine, "define('_DB_APP'"))
		fwrite($hConfigDes, "  define('_DB_APP', '" . $_POST["dbapp"] . "');\r\n");
	elseif(stristr($strLine, "define('_ENABLEUI'"))
		fwrite($hConfigDes, "  define('_ENABLEUI', " . $_POST["ui"] . ");\r\n");
	elseif(stristr($strLine, "define('_DEFLANG'"))
		fwrite($hConfigDes, "  define('_DEFLANG', '" . $_POST["lang"] . "');\r\n");
	elseif(stristr($strLine, "define('_UTCtime'"))
	{
		if($_POST["dbtime"] == "utc")
			$dbTime = 1;
		else
			$dbTime = 0;
		fwrite($hConfigDes, "  define('_UTCtime', " . $dbTime . ");\r\n");
	}
	else
		fwrite($hConfigDes, $strLine);
}

fclose($hConfigSrc);
fclose($hConfigDes);

?>

<b><font color="red">Done!</font></b></center>
<br>

<center><b>All processes have been done clearly!<br>Congratulations! You've successfully installed phpLogCon!<br>A file named 'config.php' is stored in the root directory of phpLogCon. In this file there are the whole information you have entered before! You can edit it to your needs if you want to.<br><br>Move to 'index.php' in root directory to start working with phpLogCon!<br><br><font color="red">Don't forget to delete 'install.php', 'progress.php' and 'include.php' (NOT from root directory) from the 'install'-directory!<br>These files could be user for a DoS on your phpLogCon!</font></b></center>
<br><br>

<?php

WriteFoot();

?>