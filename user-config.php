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
	WriteStandardHeader(_MSGUsrConf);

?>

<br>
<form method="POST" action="user-config-process.php" name="UserConfiguration">
<input type="hidden" name="userConfig" value="UserConfiguration">

<center><h3>..:: <?php echo _MSGUsrSet; ?> ::..</h3></td></center>

<center>
<table border="" cellpadding="2" cellspacing="0" width="700" align="center" Class="ConfigTable">
<tr>
	<td colspan="2" Class="Header1">
		<?php echo _MSGSty; ?>:
	</td>
	<td Class="Header1">&nbsp;</td>
</tr>
<tr>
	<td><?php echo _MSGStyle;?></td>
	<td>
		<Select name="stylesheet">
			<option value="phplogcon" <?php if($_SESSION['stylesheet'] == "phplogcon") echo 'selected'; ?>>phpLogCon</Option>
			<option value="matrix" <?php if ($_SESSION['stylesheet'] == "matrix") echo 'selected'; ?>>Matrix</Option>
		</Select>
	</td>
</tr>
<tr>
	<td><?php echo _MSGLang;?></td>
	<td>
		<Select name="language">
			<option value="en" <?php if($_SESSION['language'] == "en") echo 'selected'; ?>><?php echo _MSGEn; ?></Option>
			<option value="de" <?php if ($_SESSION['language'] == "de") echo 'selected'; ?>><?php echo _MSGDe; ?></Option>
		</Select>
	</td>
</tr>


<?php
	
	if(_ENABLEUI == 1)
	{

?>

<tr>
	<td colspan="2" Class="Header1">
		<?php echo _MSGSave; ?>
	</td>
	<td Class="Header1">&nbsp;</td>
</tr>
<tr>
	<td><?php echo _MSGFilSave1;?></td>
	<td>
		<input type=checkbox name="savefiltersettings" value="1" <?php if ($_SESSION['savefiltersettings'] == 1) echo 'checked'?>><?php echo _MSGFilSave2;?><br>
	</td>
</tr>

<?php

	}

?>
<tr>
	<td colspan="2" Class="Header1">
		<?php echo _MSGAddInfo; ?>
	</td>
	<td Class="Header1">&nbsp;</td>
</tr>
<tr>
	<td><?php echo _MSGDebug1;?></td>
	<td>
		<input type=checkbox name="debug" value="1" <?php if ($_SESSION['debug'] == 1) echo 'checked'?>><?php echo _MSGDebug2;?><br>
	</td>
</tr>
</table>
<input type="submit" name="form" value="Update Config">
</form>
</center>

<form method="POST" action="user-config-process.php" name="BookmarkConfiguration">
<input type="hidden" name="bookmarkConfig" value="BookmarkDelete">
<center>
<table border="0" cellpadding="2" cellspacing="0"" width="700" align="center" Class="ConfigTable">
<?php
	if(_ENABLEUI == 1)
	{
?>
<tr>
	<td Class="Header1">
		Bookmarks:
	</td>
	<td Class="Header1">&nbsp;</td>
</tr>

<tr>
	<td width="200" nowrap><?php echo _MSGFav; ?></td>
	<td width="100%">
<?php

		//Field 'UserID' is selected, because the odbc_fetch_array function has a bug.
		//If you select only one field, you will get only empty strings in your array.
		//So 'UserID' is only selected to solve this bug!
		$result = db_exec($global_Con, "SELECT UserLogin, PropValue FROM UserPrefs WHERE UserLogin LIKE '" . $_SESSION["usr"] . "' AND Name LIKE 'PHPLOGCON_favorites'");
		$num = db_num_rows($result);
		$result = db_fetch_singleresult($result);
		if($num != 0)
		{
			echo '<select name="favorites">';
			$sites = explode(",", $result["PropValue"]);
			$sitecntr = count($sites);
			for($i = 0; $i < $sitecntr; $i++)
			{
				$site = explode("|", $sites[$i]);
				echo "<option value='http://" . $site[0] . "'>" . $site[1] . "</option>";
			}
			echo '</select>';
			echo "\t<input type=\"button\" value=\"Go to\">\t";
			echo "\t<input type=\"submit\" value=\"" . _MSGDel . "\">\t";
			echo "Sorry, GoTo Site is disabled at the moment!";
		}
		else
			echo _MSGNoFav

?>
</form>
<form method="POST" action="user-config-process.php" name="BookmarkConfiguration">
<input type="hidden" name="bookmarkConfig" value="BookmarkAdd">
		</td>
	</tr>
	<tr>
		<td><?php echo _MSGNewFav; ?>: </td>
		<td>
			<?php echo _MSGSiten; ?>: <input type="text" name="sitename" size="25"><br>
			URL: <input type="text" name="url" size="30">
			<input type="submit" name="form" value="Add Bookmark">
		</td>
	</tr>

<?php

	}

?>

</table>
</form>
</center>

<?php

	WriteFood();

?>