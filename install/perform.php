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

include "include.php";
include "../lang/" . $_POST['lang'] . ".php";



WriteHead("phpLogCon :: Installation Progress");

?>

<center><?php echo _InsPer1; ?>

<?php

// if there are fields missing, create an error message
$strErrorMsg = "";

/*
 * 2004-12-13 by mm
 * Check if we use odbc, this is the case if the dbcon starts with
 * the string "odbc". 
 * 
 * Using ODBC we need also the database type.
 */
if(substr($_POST['dbcon'], 0, 4) == 'odbc')
{
	$bIsODBC = true;
	$dbcon = 'odbc';
	if (substr($_POST['dbcon'], 4)=='mysql')
		$dbapp = 'mysql';
	elseif(substr($_POST['dbcon'], 4)=='mssql')
		$dbapp = 'mssql';
	else
		die('connection type unspecified');
}
else
{
	$bIsODBC = false;
	// currently only mysql is supported in non-odbc mode
	$dbcon = 'native';
	$dbapp = 'mysql';
}


if($_POST["dbhost"] == "" && !$bIsODBC)
	$strErrorMsg .= "Host/IP";
if($_POST["dbport"] == "")
	$_POST["dbport"] = 0;
if($_POST["dbuser"] == "" && !$bIsODBC)
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "User";
}
/*
 * 2004-12-13 by mm
 * Also with an mysql connection you can use a 
 * user without password. In fact, this is not
 * really a good idea, but that's not our choise.
 * 
if($_POST["dbpass"] == "" && !$bIsODBC)
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "Password";
}
if($_POST["dbpassre"] == "" && !$bIsODBC)
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "Re-type Password";
}
*/
if($_POST["dbname"] == "")
{
	if($strErrorMsg != "")
		$strErrorMsg .= " - ";
	$strErrorMsg .= "Database/DSN name";
}
if($_POST["dbpass"] != "" && $_POST["dbpassre"] != "" && !$bIsODBC)
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
if($_POST["ui"] == 1)
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
	else
		$strUiErrMsg = "";
}
else
	$strUiErrMsg = "";

if($strDbErrMsg != "" || $strUiErrMsg != "")
{
	echo "</center>";
	echo "While installing phpLogCon, there caused an error (Date: ".date("d.m.Y @ H:i")."):<br><br>";
	echo "<u><b>Operation:</b></u> Check user's input!<br>";
	echo "<u><b>Error:</b></u> <font color=red>You have to fill out following fields: <b>" . $strDbErrMsg . "<br>" . $strUiErrMsg . "</b></font><br><br>Go back and correct this!<br>..:: <a href=\"javascript:history.back()\">Go back to installation</a> ::..<br>";
	echo "<br><br>";
	exit;
}

?>

<b><font color="red"><?php echo _InsPerDone; ?></font></b></center>
<center><?php echo _InsPer2; ?>

<?php

// include database driver
if($bIsODBC)
{
	include '../db-drv/odbc_' . $dbapp . '.php';
	$tableIndex = 'TABLE_NAME';
}
else
{
	include "../db-drv/mysql.php";
	$tableIndex = 0;
}

// connect to database
$installCon = db_own_connection($_POST["dbhost"], $_POST["dbport"], $_POST["dbuser"], $_POST["dbpass"], $_POST["dbname"]);


// ***********************
//  BEGIN CREATING TABLES
// ***********************

//Create an Array with all tablenames. If there are a new table assigned to phplogcon, add it here, too.
$arTables[0] = "SystemEvents";
$arTables[1] = "SystemEventsProperties";
$arTables[2] = "Users";
$arTables[3] = "UserPrefs";

// Check which tables are currently existing
$tableRes = db_get_tables($installCon, $_POST["dbname"]);
for($i = 0; $res = db_fetch_array($tableRes); $i++)
	$arCurTables[$i] = $res[$tableIndex];

if(!isset($arCurTables))
	$arCurTables = array();

for($i = 0; $i < count($arTables); $i++)
{
	if( array_search($arTables[$i], $arCurTables) == FALSE )
	{
		if( !isset($arNewTables) )
			$arNewTables[0] = $arTables[$i];
		else
			$arNewTables[count($arNewTables)] = $arTables[$i];
	}
}

//Create not existing tables
if(isset($arNewTables))
{
	for($i = 0; $i < count($arNewTables); $i++)
	{
		//Get queries from file
		$strQueryFile = $dbapp . '_ct' . $arNewTables[$i] . '.sql';
		$arQueries = GetSQLQueries($strQueryFile);

		// Execute Queries
		for($j = 0; $j < count($arQueries); $j++)
			db_exec($installCon, $arQueries[$j]);
	}
}

?>

<b><font color="red"><?php echo _InsPerDone; ?></font></b></center>
<center><?php echo _InsPer3; ?>

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
			$arQueries[$i] = ereg_replace("<lang>", $_POST['uilang'], $arQueries[$i]);
			db_exec($installCon, $arQueries[$i]);
		}
		elseif( stristr($arQueries[$i], "SET IDENTITY_INSERT UserPrefs") )
			db_exec($installCon, $arQueries[$i]);
	}
}

//close database connection
db_close($installCon);

?>

<b><font color="red"><?php echo _InsPerDone; ?></font></b></center>
<center><?php echo _InsPer4; ?>

<?php

// ***************************
//  BEGIN EDITING CONFIG.PHP
// ***************************

// First check if the File is writeable!
if (is_writable("../config.php") == FALSE)
{
	$strDbErrMsg = "Database Settings: ";
	echo "<BR><u><b>Error:</b></u> <font color=red>The file '../config.php' is not writeable. Please check the permissions</b></font><br><br>Go back and correct this!<br>..:: <a href=\"javascript:history.back()\">Go back to installation</a> ::..<br>";
	echo "<br><br></body></html>";
	exit;
}


//open file handle
$hConfigSrc = fopen("../scripts/config.php.ex", "r");
$hConfigDes = @fopen("../config.php", "w");

if ($hConfigDes == FALSE)
{
	$strDbErrMsg = "Database Settings: ";
	echo "<BR><u><b>Error:</b></u> <font color=red>The file '../config.php' is not writeable. Please check the permissions</b></font><br><br>Go back and correct this!<br>..:: <a href=\"javascript:history.back()\">Go back to installation</a> ::..<br>";
	echo "<br><br></body></html>";
	exit;

}

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
		fwrite($hConfigDes, "  define('_CON_MODE', '" . $dbcon . "');\r\n");
	elseif(stristr($strLine, "define('_DB_APP'"))
		fwrite($hConfigDes, "  define('_DB_APP', '" . $dbapp . "');\r\n");
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

<b><font color="red"><?php echo _InsPerDone; ?></font></b></center>
<br>

<center><b><?php echo _InsPer5; ?><br><?php echo _InsPer6; ?><br><?php echo _InsPer7; ?><br><br><?php echo _InsPer8; ?><br><br><font color="red"><?php echo _InsPer9; ?><br><?php echo _InsPer10; ?></font><br><br><?php echo _InsPer11; ?><a href="../index.php"><?php echo _InsPer12; ?></a>.</b></center>
<br><br>

<?php

WriteFoot();

?>