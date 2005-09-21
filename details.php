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



	require("include.php");

	if( !isset($_GET['lid']) )
	{
		header("Location: events-display.php");
		exit;
	}

	WriteStandardHeader(_MSGShwEvnDet);

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<br /><a href="<? echo  $_SERVER['HTTP_REFERER']; ?>">&lt;&lt; <?php echo _MSGBck; ?></a><br />

<?php

	//SQL statement
	//Row Index
	//EventLogType 0, EventSource 1, EventID 2, EventCategory 3, EventUser 4, FromHost 5, NTSeverity 6, ReceivedAt 7, DeviceReportedTime 8, Message 9, Facility 10, Priority 11, Importance 12
	$lid = is_numeric($_GET['lid']) ? $_GET['lid']+0 : 0; // prevent manipulation
	$sql = 'SELECT * FROM '._DBTABLENAME.' WHERE id='.$lid;
	 
	$res = db_exec($global_Con, $sql);
	$row = db_fetch_singleresult($res);

	function NTSeverityText($ntseverity)
	{
	  switch ($ntseverity) //NTSeverity
	  {
		case 0:
			 $ntseverity_txt = 'Success';
			 break;
		case 1:
			 $ntseverity_txt = 'Error';
			 break;
		case 2:
			 $ntseverity_txt = 'Warning';
			 break;
		case 4:
			 $ntseverity_txt = 'Informational';
			 break;
		case 8:
			 $ntseverity_txt = 'Audit Success';
			 break;
		case 16:
			 $ntseverity_txt = 'Audit Failure';
			 break;
		default:
			 die('Error Invalid NTSeverity number!');
	  }
	  return ($ntseverity_txt);
	}
	
	
?>

<br>
<table border="0" cellspacing="1" cellpadding="4" CLASS="EventTable">

<?php

if($row['InfoUnitID'] == 3)
{

?>

	<tr><td CLASS=TDHEADER><b><?php echo _MSGEvnLogTyp; ?></b></td><td CLASS=TD1><?php echo $row['EventLogType']; ?></td></tr>
	<tr><td CLASS=TDHEADER><b><?php echo _MSGEvnSrc; ?></b></td><td CLASS=TD2><?php echo $row['EventSource']; ?></td></tr>


	<tr>
		<td CLASS=TDHEADER><b><?php echo _MSGEvnID; ?></b></td>
		<td CLASS=TD1><a href="http://www.monitorware.com/en/events/details.asp?L2=<?php echo $row['EventLogType']; ?>&L3=<? echo $row['EventSource']; ?>&event_id=<?php echo $row['EventID']; ?>" target="_blank"><?php echo $row['EventID']; ?></a><?php echo _MSGClickBrw; ?><a href="http://groups.google.com/groups?hl=en&lr=&ie=ISO-8859-1&q=<?php echo $row['EventSource'] . " " . $row['EventID']; ?>" target="_blank">Google-Groups</a>)</td>
	</tr>

<?php

	if (intval($row['EventCategory']) != 0)
	{

?>

	<tr><td CLASS=TDHEADER><b><?php echo _MSGEvnCat; ?></b></td><td CLASS=TD2><?php echo $row['EventCategory']; ?></td></tr>

<?php
	}
?>

	<tr><td CLASS=TDHEADER><b><?php echo _MSGEvnUsr; ?></b></td><td CLASS=TD1><?php echo $row['EventUser']; ?></td></tr>

<?php

}

?>

<tr><td CLASS=TDHEADER><b><?php echo _MSGFrmHos; ?></b></td><td CLASS=TD2><?php echo $row['FromHost']; ?></td></tr>

<?php

if($row['InfoUnitID'] == 3)
{

?>

<tr><td CLASS=TDHEADER><b><?php echo _MSGNTSev; ?></b></td><td CLASS=TD1><?php echo NTSeverityText($row['NTSeverity']); ?></td></tr>

<?php

}

?>

<tr><td CLASS=TDHEADER><b><?php echo _MSGRecAt; ?></b></td><td CLASS=TD2><?php echo $row['ReceivedAt']; ?></td></tr>
<tr><td CLASS=TDHEADER><b><?php echo _MSGDevRep; ?></b></td><td CLASS=TD1><?php echo $row['DeviceReportedTime']; ?></td></tr>

<?php
	
	//Read out words from phplogcon.ini which shouldn't be displayed
	//and replace them by '*'
	if($row['Message'] == "")
		$tmpmsg = _MSGNoMsg;
	else
		$tmpmsg = $row['Message'];
  $tmpmsg = htmlspecialchars($tmpmsg);
	$file = file("phplogcon.ini");
	if($file != FALSE)
	{
		$numarrayfile = count($file);
		for($i = 0; $i < $numarrayfile; $i++)
		{
			$file[$i] = trim($file[$i]);
			if($file[$i] != "#")
			{
				if($file[$i] == "[phplogcon]")
				{
					for($j = $i+1; $j < $numarrayfile; $j++)
					{
						if( stristr($file[$j], "wordsdontshow=") != FALSE )
						{
							$words = explode("=", $file[$j]);
							$words = explode(",", $words[1]);
						}
					}
				}
			}
		}
		$numarraywords = count($words);
		for($i = 0; $i < $numarraywords; $i++)
		{
			$repstr = "";
			$words[$i] = trim($words[$i]);
			for($j = 0; $j < strlen($words[$i]); $j++) $repstr .= "*";
			if($words[$i] != "")
				$tmpmsg = eregi_replace($words[$i], $repstr, $tmpmsg);
		}
	}

// by therget - for renaming of severity states
// --->
function SeverityText($severity)
{
  switch ($severity) //Severity
  {
	case 0:
		 $severity_txt = 'EMERGENCY';
		 break;
	case 1:
		 $severity_txt = 'ALERT';
		 break;
	case 2:
		 $severity_txt = 'CRITICAL';
		 break;
	case 3:
		 $severity_txt = 'ERROR';
		 break;
	case 4:
		 $severity_txt = 'WARNING';
		 break;
	case 5:
		 $severity_txt = 'NOTICE';
		 break;
	case 6:
		 $severity_txt = 'INFO';
		 break;
	case 7:
		 $severity_txt = 'DEBUG';
		 break;
	default:
		 die('Error Invalid Severity number!');
  }
  return ($severity_txt);
}
// <---- end

// backgroundcolor regarding severity state
$severity = SeverityText($row['Priority']);
if ($severity == "EMERGENCY")
{
	$class = "CLASS=PriorityEmergency";
}
elseif ($severity == "ALERT")
{
	$class = "CLASS=PriorityAlert";
}
elseif ($severity == "CRITICAL")
{
	$class = "CLASS=PriorityCritical";
}
elseif ($severity == "ERROR")
{
	$class = "CLASS=PriorityError";
}
elseif ($severity == "WARNING")
{
	$class = "CLASS=PriorityWarning";
}
elseif ($severity == "NOTICE")
{
	$class = "CLASS=PriorityNotice";
}
elseif ($severity == "INFO")
{
	$class = "CLASS=PriorityInfo";
}
elseif ($severity == "DEBUG")
{
	$class = "CLASS=PriorityDebug";
}


$importance = $row['Importance'];
?>

<tr><td CLASS=TDHEADER><b><?php echo _MSGMsg; ?></b></td><td CLASS=TD2><?php echo $tmpmsg; ?></td></tr>
<tr><td CLASS=TDHEADER><?php echo _MSGFac; ?></td><td CLASS=TD1><?php echo $row['Facility']; ?></td></tr>
<tr><td CLASS=TDHEADER><?php echo _MSGPri; ?></td><td <?php echo $class ?> ><?php echo $severity ?></td></tr>

<tr><td CLASS=TDHEADER><?php echo "SysLogTag"; ?></td><td CLASS=TD1><?php echo $row['SysLogTag']; ?></td></tr>
<?php 
if ($importance != "")
{
	echo "<tr><td CLASS=TDHEADER>", _MSGImp," </td><td CLASS=TD2> $importance </td></tr>";
}
?>
</table>

<?php
	echo "</table>";
	WriteFooter();
?>