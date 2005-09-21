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


/*
* This file validate all variable comming from the web,
* also it includes all necessary files (e.g. config, db-drv, ...)
* and provide some usefull functions
*/

// enable it only for testing purposes
error_reporting(E_ALL);

//if (!headers_sent()) { header("Pragma: no-cache"); }
header("Pragma: no-cache");
// very important to include config settings at beginning!
include 'config.php';

session_cache_expire($session_time);

//*** Begin Validate var input and/or default values ***

//The following is required for IIS! Otherwise it will cause an error "undefined index"
$_SERVER['QUERY_STRING'] = '';

if( !isset($_SESSION['save_cookies']) )
	session_start();
  
// select the language
// use the language code, only two letters are permitted
if (!isset($_SESSION['language']))
{
	$_SESSION['language'] = _DEFLANG;
}

/*
* get the stylesheet to use
* only letters are permitted
*/
if (!isset($_SESSION['stylesheet']))
{
	$_SESSION['stylesheet'] = 'phplogcon'; // default
}

if (!isset($_SESSION['debug']))
{
	$_SESSION['debug'] = 0; // default
}

if (!isset($_SESSION['savefiltersettings']))
{
	$_SESSION['savefiltersettings'] = 0; // default
}

/*
* Load the default quick filter settings,
* if the quick filter settings not configured yet.
*/
if (!isset($_SESSION['FilterInfoUnit'])) 
	$_SESSION['FilterInfoUnit'] = _FilterInfoUnit;
if (!isset($_SESSION['FilterOrderby'])) 
	$_SESSION['FilterOrderby'] = _FilterOrderby;
if (!isset($_SESSION['FilterRefresh'])) 
	$_SESSION['FilterRefresh'] = _FilterRefresh;
if (!isset($_SESSION['FilterColExp'])) 
	$_SESSION['FilterColExp'] = _FilterColExp;  
if (!isset($_SESSION['FilterHost'])) 
	$_SESSION['FilterHost'] = _FilterHost;
if (!isset($_SESSION['FilterMsg'])) 
	$_SESSION['FilterMsg'] = _FilterMsg;

/*
* Filtering by ip/host
* if a string for filter host submitted, check this string.
*/
if (isset($_POST['filhost']))
{
	$_SESSION['filhost'] = PreStrFromTxt4DB($_POST['filhost']);       
}
else
{
	if (!isset($_SESSION['filhost']))
		$_SESSION['filhost'] = '';
}


/*
* Filtering the message, msg must contain a certain string.
* if a string submitted, check this string.
*/
if (isset($_POST['searchmsg']))
{
	$_SESSION['searchmsg'] = PreStrFromTxt4DB($_POST['searchmsg']);        
}
else
{
	if (!isset($_SESSION['searchmsg']))
		$_SESSION['searchmsg'] = '';
}

/*
* Color an Expression 
*/
if (isset($_POST['regexp']))
{
	$_SESSION['regexp'] = $_POST['regexp'];
	$_SESSION['color'] = $_POST['color'];
}
else
{
	if (!isset($_SESSION['regexp']))
		$_SESSION['regexp'] = '';
	if (!isset($_SESSION['color']))
		$_SESSION['color'] = 'red';
}

if (isset($_POST['d1']))
{
	$tmp = true;
	if (!is_numeric($_POST['d1'])) { $tmp = false; }
	if (!is_numeric($_POST['m1'])) { $tmp = false; }
	if (!is_numeric($_POST['y1'])) { $tmp = false; }
	if (!is_numeric($_POST['d2'])) { $tmp = false; }
	if (!is_numeric($_POST['m2'])) { $tmp = false; }
	if (!is_numeric($_POST['y2'])) { $tmp = false; }

	if ($tmp)
	{
	  //is ok, but add a int to ensure that it is now handled as an integer
	  $_SESSION['d1'] = $_POST['d1']+0;
	  $_SESSION['m1'] = $_POST['m1']+0;
	  $_SESSION['y1'] = $_POST['y1']+0;
	  $_SESSION['d2'] = $_POST['d2']+0;
	  $_SESSION['m2'] = $_POST['m2']+0;
	  $_SESSION['y2'] = $_POST['y2']+0;
	}
	else
	   SetManuallyDateDefault(); 
}  
elseif (!isset($_SESSION['d1']))
SetManuallyDateDefault();

// quick-filter.php
// manually or predefined
if (isset($_POST['change']))
{
if ($_POST['change'] == 'Predefined')
  $_SESSION['change'] = 'Predefined';
else
  $_SESSION['change'] = 'Manually';
}
elseif (!isset($_SESSION['change']))
 $_SESSION['change'] = 'Predefined';

// Apply changed quick filter settings
if( isset($_POST['quickFilter']) && $_POST['quickFilter'] == 'change' )
{
// save current settings. Because:
// the quick filter and the filter config are using the same variables.
// when you change the quick filter settings, the filter settings
// would be changed to.
// settings must be reloaded in filter-config.php
$_SESSION['ti_old'] = $_SESSION['ti'];
$_SESSION['infounit_sl_old'] = $_SESSION['infounit_sl'];
$_SESSION['infounit_er_old'] = $_SESSION['infounit_er'];
$_SESSION['infounit_o_old'] = $_SESSION['infounit_o'];
$_SESSION['order_old'] = $_SESSION['order'];
$_SESSION['tag_order_old'] = $_SESSION['tag_order'];
$_SESSION['tag_sort_old'] = $_SESSION['tag_sort'];
$_SESSION['refresh_old'] = $_SESSION['refresh'];

if( isset($_POST['ti']) )
	$_SESSION['ti'] = $_POST['ti'];
$_SESSION['infounit_sl'] = (isset($_POST['infounit_sl'])) ? 1 : 0;
$_SESSION['infounit_er'] = (isset($_POST['infounit_er'])) ? 1 : 0;
$_SESSION['infounit_o'] = (isset($_POST['infounit_o'])) ? 1 : 0;
if( !isset($_POST['order']) )
{
	$_POST['order'] = '';
	$_SESSION['tag_order'] = $_POST['tag_order'];
}
else
{
	$_POST['tag_order'] = '';
	$_SESSION['order'] = $_POST['order'];
}
if( isset($_POST['tag_sort']) )
	$_SESSION['tag_sort'] = $_POST['tag_sort'];
$_SESSION['refresh'] = $_POST['refresh'];
if( isset($_POST['show_methode']) )
	$_SESSION['show_methode'] = $_POST['show_methode'];
}


if (!isset($_SESSION['infounit_sl']))
$_SESSION['infounit_sl'] = 1;
if (!isset($_SESSION['infounit_er']))
$_SESSION['infounit_er'] = 1;
if (!isset($_SESSION['infounit_o']))
$_SESSION['infounit_o'] = 1; 

if (!isset($_SESSION['priority_0']))
$_SESSION['priority_0'] = 1;
if (!isset($_SESSION['priority_1']))
$_SESSION['priority_1'] = 1;
if (!isset($_SESSION['priority_2']))
$_SESSION['priority_2'] = 1;
if (!isset($_SESSION['priority_3']))
$_SESSION['priority_3'] = 1;
if (!isset($_SESSION['priority_4']))
$_SESSION['priority_4'] = 1;
if (!isset($_SESSION['priority_5']))
$_SESSION['priority_5'] = 1;
if (!isset($_SESSION['priority_6']))
$_SESSION['priority_6'] = 1;
if (!isset($_SESSION['priority_7']))
$_SESSION['priority_7'] = 1;


// forms/events-date.php
// selected time interval, validation check of ti in eventfilter.php
if (!isset($_SESSION['ti']))
$_SESSION['ti'] = 'today'; // default

// forms/order-by.php
// validation in eventfilter.php
if (!isset($_SESSION['order']))
$_SESSION['order'] = 'date';

// forms/tag-order-by.php
// validation in eventfilter.php
if (!isset($_SESSION['tag_order']))
$_SESSION['tag_order'] = 'Occurences';

// forms/tag-sort.php
// check sort ascending/descending
if (!isset($_SESSION['tag_sort']))
$_SESSION['tag_sort'] = 'Asc';

// forms/refresh.php
if (!isset($_SESSION['refresh']))
$_SESSION['refresh'] = 0; // default

//syslog-index.php
if( !isset($_SESSION['show_methode']) )
$_SESSION['show_methode'] = "SysLogTag";

// forms/logs-per-page.php
// number of lines to be displayed, only numbers are allowed
if (isset($_POST['epp']))
{
if (is_numeric($_POST['epp']))
  $_SESSION['epp'] = $_POST['epp']+0; //+0 makes sure that is an int
else
  $_SESSION['epp'] = 20;
}
elseif (!isset($_SESSION['epp']))
$_SESSION['epp'] = 20;
//*** End Validate var input and/or default values ***

//***Begin including extern files***
// include the language file
include _LANG . $_SESSION['language'] . '.php';
//design things
include 'layout/theme.php';


// --- Added here, the Install direcgtory has to be removed before we do anything else
CheckInstallDir();
// --- 


//include required database driver
if(strtolower(_CON_MODE) == "native")
	include _DB_DRV . _DB_APP . ".php";
else
	include _DB_DRV . _CON_MODE . "_" . _DB_APP . ".php";
//***End including extern files***

//***Global used variables
// Used to hold the global connection handle
$global_Con = db_connection();
 

//***Begin usefull functions***

/*
* Function prepare strings from textboxes for using it for db queries.
*/
function PreStrFromTxt4DB($var)
{
	if (get_magic_quotes_gpc())
	  $var = stripslashes($var);
	  
	return str_replace("'", "''", trim($var));
}

/*
* Function prepare strings from textboxes for output
*/
function PreStrFromTxt4Out($var)
{
	if (get_magic_quotes_gpc())
	  $var = stripslashes($var);
	  
	return htmlspecialchars(trim($var));
}

/*
* For CLASS EventFilter (classes/eventfilter.php)
* get/set the "manually events date"
* only numbers are permitted
*/
function SetManuallyDateDefault()
{
	$_SESSION['d1'] = 1;
	$_SESSION['m1'] = 1;
	$_SESSION['y1'] = 2004;

	$_SESSION['d2'] = date("d");
	$_SESSION['m2'] = date("m");
	$_SESSION['y2'] = date("Y");
}

/************************************************************************/
/* expect a path to a folder ('.' for current) and return all       */
/* filenames order by name
/************************************************************************/
function GetFilenames($dir)
{
  $handle = @opendir($dir);
  while ($file = @readdir ($handle))
  {
	  if (eregi("^\.{1,2}$",$file))
	  {
		  continue;
	  }

	  if(!is_dir($dir.$file))
	  {
		$info[] = $file;          
	  }

  }
  @closedir($handle);
  sort($info);
  
  return $info;
}

/*!
 * Remove the parameter $Arg from the given $URL
 * \r Returns the url without the $Arg parameter
 */

function RemoveArgFromURL($URL,$Arg)
{
  while($Pos = strpos($URL,"$Arg="))
  {
   if ($Pos)
	{
	  if ($URL[$Pos-1] == "&")
	  {
		$Pos--;
	  }
	  $nMax = strlen($URL);
	  $nEndPos = strpos($URL,"&",$Pos+1);

	  if ($nEndPos === false)
	  {
	   $URL = substr($URL,0,$Pos);
	  }
	else
	  {
		$URL = str_replace(substr($URL,$Pos,$nEndPos-$Pos), '', $URL);
	  }
	}
  }
  return $URL;
}

//***End usefull functions***



// encodes a string one way
function encrypt($txt)
{
	return crypt($txt,"vI").crc32($txt);
}

// returns current date and time
function now()
{
	$dat = getdate(strtotime("now"));
	return "$dat[year]-$dat[mon]-$dat[mday] $dat[hours]:$dat[minutes]:00";
}

// it makes the authentification
function auth()
{
	global $session_time;

	// if no session is available, but a cookie => the session will be set and the settings loaded
	// if no session and no cookie is available => link to index.php to login will be displayed
	if( !isset($_SESSION['usr']) )
	{
		if( !isset($_COOKIE['valid']) || $_COOKIE['valid'] == "0" )
		{
			header("Location: index.php");
			exit;
		}
		else
		{
			session_register('usr');
			session_register('usrdis');
			$_SESSION['usr'] = $_COOKIE['usr'];
			$_SESSION['usrdis'] = $_COOKIE['usrdis'];
			LoadUserConfig();
		}
	}

	/*
	//*** FOR SESSION EXPIRE ***
	//if(diff("now", $result["phplogcon_dtime"]) > $session_time)
	if( !isset($_COOKIE["valid"]) )
	{
		WriteHead("phpLogCon :: " . $msg030, "", "", $msg030, 0);
		echo "<br><b>..:: " . _MSGSesExp . " ::..</b><br>";
		echo "<br>..:: <a href='index.php'>" . _MSGReLog . "</a> ::..";
		exit;
	}
	*/
	//refresh cookies
	if($_SESSION['save_cookies'])
	{
		setcookie("valid", $_COOKIE["valid"], _COOKIE_EXPIRE, "/");
		setcookie("usr", $_COOKIE["usr"], _COOKIE_EXPIRE, "/");
	}
}

/*
// generates a unique string
function gen()
{
	mt_srand((double)microtime() * 1000000);
	return mt_rand(1000, 9999) . "-" . mt_rand(1000, 9999) . "-" . mt_rand(1000, 9999) . "-" . mt_rand(1000, 9999);
}
*/

// Calculates the different between the given times
function diff($date1, $date2)
{
	$a1 = getdate(strtotime($date1));
	$a2 = getdate(strtotime($date2));

	return ($a1["year"]-$a2["year"])*525600 + ($a1["mon"]-$a2["mon"])*43200 + ($a1["mday"]-$a2["mday"])*1440 + ($a1["hours"]-$a2["hours"])*60 + ($a1["minutes"]-$a2["minutes"]);
}

/*!
 * This function create a combobox with all filenames (without extension
 * if it '.php') from the given folder. Firt param is the path to the
 * folder, second is the name of of the combobox.
 */
function ComboBoxWithFilenames($dir, $combobox)
{
  $handle = @opendir($dir);
  while ($file = @readdir($handle))
  {
	  if (eregi("^\.{1,2}$",$file))
		  continue;

	  if(!is_dir($dir.$file))
		  $info[] = ereg_replace(".php","",$file);
  }
  @closedir($handle);
  sort($info);
  
  echo "<select name=".$combobox.">";
  foreach($info as $name)
  {
	  if($_COOKIE["connection_mode"] == $name)
	  echo "<option value='" . $name . "' selected>".$name."</option>";
	  else
	  echo "<option value='" . $name . "'>".$name."</option>";
  }
  echo "</select>";
}

function CheckSQL($SQLcmd)
{
	if( stristr($SQLcmd, "'") || stristr($SQLcmd, "&quot;"))
		return FALSE;
	else
		return TRUE;
}

function WriteStandardHeader($myMsg)
{
	if(_ENABLEUI == 1)
	{
		// *** AUTH ID | WHEN TRUE, LOGOUT USER ***
		auth();
		if(isset($_GET["do"]))
		{
			if ($_GET["do"] == "logout")
			{
				setcookie("usr", "|", _COOKIE_EXPIRE, "/");
				setcookie("valid", "0", _COOKIE_EXPIRE, "/");
				session_unset();
				header("Location: index.php");
			}
		}

		// ****************************************
		/*
		if( !isset($_COOKIE["valid"]) || $_COOKIE["valid"] == "0" )
			WriteHead("phpLogCon :: " . $myMsg , "", "", $myMsg, 0);
		else
			WriteHead("phpLogCon :: " . $myMsg, "", "", $myMsg, $_COOKIE["valid"]);
		*/
		WriteHead("phpLogCon :: " . $myMsg , "", "", $myMsg);

		echo "<br>";

		// If a user is logged in, display logout text
		if( isset($_COOKIE["usr"]) || $_COOKIE["usr"] != "|")
		{
			echo '<table align="right">';
			echo '<tr>';
			echo '<td><a href="index.php?do=logout">' . _MSGLogout . '</a></td>';
			echo '</tr>';
			echo '</table>';
		}
	}
	else
	{
		/*
		if(isset($_COOKIE["valid"]))
			WriteHead("phpLogCon :: " . $myMsg, "", "", $myMsg, $_COOKIE["sesid"]);
		else
			WriteHead("phpLogCon :: " . $myMsg, "", "", $myMsg, 0);
		*/
		WriteHead("phpLogCon :: " . $myMsg , "", "", $myMsg);
	}
	CheckInstallDir();
}

/*!
 * Format the priority for displaying purposes.
 * Get the number of the priority and change it to a word,
 * also the default css style for design format is required.
 * If coloring priority enabled, this function change the given
 * param to the right color of the priority.
 * 
 * /param pri - priority (number!)
 * /param col - css style class (as a reference!)
 * /ret priword - returns priority as a word
 */
 function FormatPriority($pri, &$col)
 {
	$priword ='';
	$tmpcol = '';
	switch($pri){
		case 0: 
			$priword = _MSGPRI0;
			$tmpcol = 'PriorityEmergency';
			break;
		case 1: 
			$priword = _MSGPRI1;
			$tmpcol = 'PriorityAlert';
			break;
		case 2: 
			$priword = _MSGPRI2;
			$tmpcol = 'PriorityCrit';
			break;
		case 3: 
			$priword = _MSGPRI3;
			$tmpcol = 'PriorityError';
			break;
		case 4: 
			$priword = _MSGPRI4;
			$tmpcol = 'PriorityWarning';
			break;
		case 5: 
			$priword = _MSGPRI5;
			$tmpcol = 'PriorityNotice';
			break;
		case 6: 
			$priword = _MSGPRI6;
			$tmpcol = 'PriorityInfo';
			break;
		case 7: 
			$priword = _MSGPRI7;
			$tmpcol = 'PriorityDebug';
			break;
		default:
			die('priority is false');
	}	
	
	// if coloring enabled
	if (_COLPriority) {
		$col = $tmpcol;
	}
	return $priword;
 }



 function CheckInstallDir()
 {
	 if(file_exists("install/"))
	 {
		 echo "<html><head><link rel=\"stylesheet\" href=\"" . _ADLibPathScript . $_SESSION['stylesheet'] .".css\" type=\"text/css\"></head><body><h3><font color=\"red\"><BR><center>" . _MSGInstDir . "</center></font></h3></body></html>";
		 exit;
	 }
	 clearstatcache();
 }

/*!
Loads the Users Filter Configuration from database
!*/
function LoadFilterConfig()
{
	global $global_Con;

	$query = "SELECT Name, PropValue FROM UserPrefs WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_%'";
	$result = db_exec($global_Con, $query);

	while($value = db_fetch_array($result))
	{
		$sValName = explode("PHPLOGCON_", $value['Name']);
		$_SESSION["$sValName[1]"] = $value['PropValue'];
	}
}

function LoadUserConfig()
{
	global $global_Con;

	$query = "SELECT Name, PropValue FROM UserPrefs WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_u%'";
	$result = db_exec($global_Con, $query);
	while($value = db_fetch_array($result))
	{
		$sValName = explode("PHPLOGCON_u", $value['Name']);
		$_SESSION[strtolower($sValName[1])] = $value['PropValue'];
	}
}

/*!
Creates the array for saving the Filter Settings to database
!*/
function GetFilterConfigArray()
{
	if( !isset($_POST['infounit_sl']) )
		$query[0] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_infounit_sl'";
	else
		$query[0] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_infounit_sl'";
	if( !isset($_POST['infounit_er']) )
		$query[1] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_infounit_er'";
	else
		$query[1] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_infounit_er'";
	if( !isset($_POST['infounit_o']) )
		$query[2] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_infounit_o'";
	else
		$query[2] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_infounit_o'";

	if( !isset($_POST['priority_0']) )
		$query[3] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_0'";
	else
		$query[3] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_0'";
	if( !isset($_POST['priority_1']) )
		$query[4] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_1'";
	else
		$query[4] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_1'";
	if( !isset($_POST['priority_2']) )
		$query[5] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_2'";
	else
		$query[5] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_2'";
	if( !isset($_POST['priority_3']) )
		$query[6] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_3'";
	else
		$query[6] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_3'";
	if( !isset($_POST['priority_4']) )
		$query[7] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_4'";
	else
		$query[7] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_4'";
	if( !isset($_POST['priority_5']) )
		$query[8] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_5'";
	else
		$query[8] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_5'";
	if( !isset($_POST['priority_6']) )
		$query[9] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_6'";
	else
		$query[9] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_6'";
	if( !isset($_POST['priority_7']) )
		$query[10] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_7'";
	else
		$query[10] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_priority_7'";

	$query[11] = "UPDATE UserPrefs SET PropValue='" . $_POST['ti'] . "' WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_ti'";
	$query[12] = "UPDATE UserPrefs SET PropValue='" . $_POST['order'] . "' WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_order'";
	$query[13] = "UPDATE UserPrefs SET PropValue='" . $_POST['tag_order'] . "' WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_tag_order'";
	$query[14] = "UPDATE UserPrefs SET PropValue='" . $_POST['tag_sort'] . "' WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_tag_sort'";
	$query[15] = "UPDATE UserPrefs SET PropValue='" . $_POST['refresh'] . "' WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_refresh'";

	if( !isset($_POST['FilterInfoUnit']) )
		$query[16] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterInfoUnit'";
	else
		$query[16] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterInfoUnit'";
	if( !isset($_POST['FilterOrderby']) )
		$query[17] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterOrderby'";
	else
		$query[17] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterOrderby'";
	if( !isset($_POST['FilterRefresh']) )
		$query[18] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterRefresh'";
	else
		$query[18] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterRefresh'";
	if( !isset($_POST['FilterColExp']) )
		$query[19] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterColExp'";
	else
		$query[19] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterColExp'";
	if( !isset($_POST['FilterHost']) )
		$query[20] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterHost'";
	else
		$query[20] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterHost'";
	if( !isset($_POST['FilterMsg']) )
		$query[21] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterMsg'";
	else
		$query[21] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_FilterMsg'";

	return $query;
}

function GetUserConfigArray()
{
	$query[0] = "UPDATE UserPrefs SET PropValue='" . $_POST['stylesheet'] . "' WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_uStylesheet'";
	$query[1] = "UPDATE UserPrefs SET PropValue='" . $_POST['language'] . "' WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_uLanguage'";

	if( !isset($_POST['savefiltersettings']) )
		$query[2] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_uSaveFilterSettings'";
	else
		$query[2] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_uSaveFilterSettings'";
	if( !isset($_POST['debug']) )
		$query[3] = "UPDATE UserPrefs SET PropValue=0 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_uDebug'";
	else
		$query[3] = "UPDATE UserPrefs SET PropValue=1 WHERE UserLogin LIKE '" . $_SESSION['usr'] . "' AND Name LIKE 'PHPLOGCON_uDebug'";

	return $query;
}

?>
