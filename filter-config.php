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



	require("include.php");
	WriteStandardHeader(_MSGFilConf);

	// If filter settings have been changed in quick filter, reload the old settings

	if(isset($_SESSION['ti_old']))
	{
		$_SESSION['ti'] = $_SESSION['ti_old'];
		$_SESSION['infounit_sl'] = $_SESSION['infounit_sl_old'];
		$_SESSION['infounit_er'] = $_SESSION['infounit_er_old'];
		$_SESSION['infounit_o'] = $_SESSION['infounit_o_old'];
		$_SESSION['order'] = $_SESSION['order_old'];
		$_SESSION['refresh'] = $_SESSION['refresh_old'];

		session_unregister('ti_old');
		session_unregister('infounit_sl_old');
		session_unregister('infounit_er_old');
		session_unregister('infounit_o_old');
		session_unregister('order_old');
		session_unregister('refresh_old');
	}

?>

<?php /* Disabled!?>
<br>
<form method="POST" action="configuration-page-process.php" name="ConnectionConfig">
<input type="hidden" name="conConf" value="ConnectionConfig">
<center><h3>..:: <?php echo _MSGBscSet; ?> ::..</h3></center>

<center>
<table border="0" cellpadding="2" cellspacing="0" width="700" align="center" Class="ConfigTable">
	<tr>
		<td Class="Header1" colspan="2"><b><?php echo _MSGConSet; ?>:</b></td>
	</tr>
	<tr>
		<td width="200" nowrap"><?php echo _MSGConMod; ?>:</td>
		<td width="100%" align="right">
<?php
			ComboBoxWithFilenames(_DB_DRV, "connection_mode");
?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><font color="red">This function has been turned off! Set this in config.php!</font></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="<?php echo _MSGChg; ?>">
		</td>
		<td></td>
	</tr>
</table>
</form>
</center>
<?php End Disabled*/ ?>

<br>

<form method="POST" action="filter-config-process.php" name="FilterConfig">
<input type="hidden" name="filConf" value="FilterConfig">

<center><h3>..:: <?php echo _MSGFilSet; ?> ::..</h3></td></center>

<center>
<table border="" cellpadding="2" cellspacing="0" width="700" align="center" Class="ConfigTable">
<tr>
	<td colspan="3" Class="Header1">
		<?php echo _MSGFilCon; ?>:
	</td>
</tr>
<tr>
	<td><?php echo _MSGEvnDat; ?>:</td>
	<td>
    <?php include _FORMS.'events-date.php'; ?>
	</td>
</tr>
<tr>
	<td><?php echo _MSGOrdBy; ?>:</td>
	<td>
    <?php include _FORMS.'order-by.php'; ?>
	</td>
</tr>
<tr>
	<td><?php echo _MSGRef; ?>:</td>
	<td>
    <?php include _FORMS.'refresh.php'; ?>
	</td>
</tr>
<tr>
	<td><?php echo _MSGInfUI; ?>:</td>
	<td>
		<input type=checkbox name="infounit_sl" value="1" <?php if ($_SESSION['infounit_sl'] == 1) echo 'checked'; ?>>SysLog<br>
		<input type=checkbox name="infounit_er" value="1" <?php if ($_SESSION['infounit_er'] == 1) echo 'checked'; ?>>EventReporter<br>
		<input type=checkbox name="infounit_o" value="1" <?php if ($_SESSION['infounit_o'] == 1) echo 'checked'; ?>><?php echo _MSGOth; ?>
	</td>
</tr>
<tr>
	<td><?php echo _MSGPri; ?>:</td>
	<td>
		<!-- 0=>Emergency ; 1=>Alert ; 2=>Critical ; 3=>Error ; 4=>Warning ; 5=>Notice ; 6=>Info ; 7=>Debug -->
		<input type=checkbox name="priority_0" value="1" <?php if ($_SESSION['priority_0'] == 1) echo 'checked'; ?>>Emergency (0)<br>
		<input type=checkbox name="priority_1" value="1" <?php if ($_SESSION['priority_1'] == 1) echo 'checked'; ?>>Alert (1)<br>
		<input type=checkbox name="priority_2" value="1" <?php if ($_SESSION['priority_2'] == 1) echo 'checked'; ?>>Critical (2)<br>
		<input type=checkbox name="priority_3" value="1" <?php if ($_SESSION['priority_3'] == 1) echo 'checked'; ?>>Error (3)<br>
		<input type=checkbox name="priority_4" value="1" <?php if ($_SESSION['priority_4'] == 1) echo 'checked'; ?>>Warning (4)<br>
		<input type=checkbox name="priority_5" value="1" <?php if ($_SESSION['priority_5'] == 1) echo 'checked'; ?>>Notice (5)<br>
		<input type=checkbox name="priority_6" value="1" <?php if ($_SESSION['priority_6'] == 1) echo 'checked'; ?>>Info (6)<br>
		<input type=checkbox name="priority_7" value="1" <?php if ($_SESSION['priority_7'] == 1) echo 'checked'; ?>>Debug (7)<br>
	</td>
	<td>
  
  <?php /* This is not implemented yet! 
		<select name='color'>
			<option value='red' style="background-color:red"<?php if ($_GET["color"] == "red") echo " selected";?>><?php echo $msg105;?></option>
			<option value='blue' style="background-color:blue"<?php if ($_GET["color"] == "blue") echo " selected";?>><?php echo $msg106;?></option>
			<option value='green' style="background-color:green"<?php if ($_GET["color"] == "green") echo " selected";?>><?php echo $msg107;?></option>
			<option value='yellow' style="background-color:yellow"<?php if ($_GET["color"] == "yellow") echo " selected";?>><?php echo $msg108;?></option>
			<option value='orange' style="background-color:orange"<?php if ($_GET["color"] == "orange") echo " selected";?>><?php echo $msg109;?></option>
		</select>
    End this is not implemented yet! */ ?>
    
    
    
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>
	<td colspan="2" Class="Header1">
		<?php echo _MSGEnbQF; ?>:
	</td>
	<td Class="Header1">&nbsp;</td>
<tr>
	<td><?php echo _MSGDisIU; ?>:</td>
	<td>
			<input type="checkbox" name="FilterInfoUnit" value="1" <?php if ($_SESSION['FilterInfoUnit'] == 1) echo 'checked'; ?>>
	</td>
</tr>
<tr>
	<td><?php echo _MSGOrdBy; ?>:</td>
	<td>
			<input type="checkbox" name="FilterOrderby" value="1" <?php if ($_SESSION['FilterOrderby'] == 1) echo 'checked'; ?>>
	</td>
</tr>
<tr>
	<td><?php echo _MSGRef; ?>:</td>
	<td>
			<input type="checkbox" name="FilterRefresh" value="1" <?php if ($_SESSION['FilterRefresh'] == 1) echo 'checked'; ?>>
	</td>
</tr>
<tr>
	<td><?php echo _MSGColExp; ?>:</td>
	<td>
			<input type="checkbox" name="FilterColExp" value="1" <?php if ($_SESSION['FilterColExp'] == 1) echo 'checked'; ?>>
	</td>
</tr>
<tr>
	<td><?php echo _MSGFilHost; ?>:</td>
	<td>
			<input type="checkbox" name="FilterHost" value="1" <?php if ($_SESSION['FilterHost'] == 1) echo 'checked'; ?>>
	</td>
</tr>
<tr>
	<td><?php echo _MSGSearchMsg; ?>:</td>
	<td>
			<input type="checkbox" name="FilterMsg" value="1" <?php if ($_SESSION['FilterMsg'] == 1) echo 'checked'; ?>>
	</td>
</tr>
</table>

<input type="submit" name="form" value="Update Config">
</form>
</center>

<?php

	WriteFood();

?>