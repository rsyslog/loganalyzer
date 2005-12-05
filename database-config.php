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
	WriteStandardHeader(_MSGdatabaseConf);
?>

<br>
<form method="POST" action="database-config-process.php" name="DatabaseConfiguration">
<input type="hidden" name="databaseConfig" value="DatabaseConfiguration">

<table align="center"><tr><td><h3>..:: <?php echo _MSGdatabaseSet; ?> ::..</h3></td></tr></table>

<center>
<table border="" cellpadding="2" cellspacing="0" width="400" align="center" Class="ConfigTable">
<tr>
	<td colspan="2" Class="Header1">
		<?php echo _MSGdatabaseSet; ?>		
	</td>	
</tr>
<tr>
	<td><?php echo _MSGdatabaseChoose; ?></td>
	<td align = "center">
	<?php		
		$db_list = mysql_list_dbs($global_Con);
		echo "<Select name=\"database\">";
		while ($row = mysql_fetch_object($db_list)) 
		{
			$temp = $row->Database;
			$status_selected = '';
			if($_SESSION['database'] == $temp)
			{
				$status_selected = ' selected';
			}
			echo "<option value=\"".$temp."\"".$status_selected.">".$temp."</Option>";
		}
		echo "</Select>";
	?> 
	</td>
</table>
<br>
<input type="submit" name="form" value="Update Config">
</form>
</center>


<?php

	WriteFooter();

?>