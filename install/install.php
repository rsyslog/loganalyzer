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



WriteHead("phpLogCon :: Installation");

?>

<!-- Body creation -->
		<center><b>Welcome to the installation of phpLogCon, the WebInterface to log data.<br>The following steps will guide you through the installation and help you to install and configure phpLogCon correctly.<br><i><font color="red">Note: Fields marked with a * MUST be filled out! 'Host/IP' can be leaved blank in ODBC mode.</font></i></b></center>
		<br><br>
		<center>First we have to check your database structure, because phpLogCon needs some tables. If the tables don't exist, they will be created.<br>For this, phpLogCon Installation needs some information about your database Server:</center>
		<br>
		<form method="POST" action="perform.php" name="Installation">
			<input type="hidden" name="install" value="Installation">
			<table border="" cellpadding="2" cellspacing="0" width="600" align="center" Class="ConfigTable">
				<tr>
					<td colspan="2" align="center" Class="Header1">.: Database Settings :.</td>
					<td Class="Header1">&nbsp;</td>
				</tr>
				<tr>
					<td>Connection Type: <font color="red">*</font></td>
					<td>
						<select name="dbcon">
							<option value="native" selected>Native</Option>
							<option value="odbc">ODBC</Option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Database application: <font color="red">*</font></td>
					<td>
						<select name="dbapp">
							<option value="mysql" selected>MySql</Option>
							<option value="mssql">MSSql</Option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Host/IP: <font color="red">*</font></td>
					<td><input type="text" name="dbhost" size="25"></td>
				</tr>
				<tr>
					<td>Port (If standard, leave blank):</td>
					<td><input type="text" name="dbport" size="25"></td>
				</tr>
				<tr>
					<td>User (User must have "INSERT" and "CREATE" rights!): <font color="red">*</font></td>
					<td><input type="text" name="dbuser" size="25"></td>
				</tr>
				<tr>
					<td>Password: <font color="red">*</font></td>
					<td><input type="password" name="dbpass" size="25"></td>
				</tr>
				<tr>
					<td>Re-type password: <font color="red">*</font></td>
					<td><input type="password" name="dbpassre" size="25"></td>
				</tr>
				<tr>
					<td>Database/DSN name: <font color="red">*</font></td>
					<td><input type="text" name="dbname" size="25"></td>
				</tr>
				<tr>
					<td>Database time format: <font color="red">*</font></td>
					<td>
						<select name="dbtime">
							<option value="utc" selected>UTC</Option>
							<option value="local">Localtime</Option>
						</select>
					</td>
				</tr>
			</table>

			<br><br>
			<center>Now we have to do some settings for phpLogCon to run clearly and user optimized.<br><i>Note: If you now select the UserInterface not to be installed, you can install it through a SQL-script file! See the manual for help.</center>
			<br>
			<table border="" cellpadding="2" cellspacing="0" width="600" align="center" Class="ConfigTable">
				<tr>
					<td colspan="2" align="center" Class="Header1">.: phpLogCon General Settings :.</td>
					<td Class="Header1">&nbsp;</td>
				</tr>
				<tr>
					<td>Default language:</td>
					<td>
						<select name="lang">
							<option value="en" selected>English</Option>
							<option value="de">German</Option>
						</select>
					</td>
				</tr>
				<tr>
					<td>User Interface:</td>
					<td>
						<input type="checkbox" name="ui" value="1"> Configure?
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left" Class="Header1">Create a User:</td>
					<td Class="Header1">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">Here you can create a user for the User Interface. If you already have some users in your database or you have unselected the UserInterface, you can leave these fields!<br><i>Note: You only have to fill out the fields marked with a <font color="red">*</font> if you have entered a username!</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Username:</td>
					<td><input type="text" name="uiuser" size="25"></td>
				</tr>
				<tr>
					<td>Display name: <font color="red">*</font></td>
					<td><input type="text" name="uidisname" size="25"></td>
				</tr>
				<tr>
					<td>Password: <font color="red">*</font></td>
					<td><input type="password" name="uipass" size="25"></td>
				</tr>
				<tr>
					<td>Re-type password: <font color="red">*</font></td>
					<td><input type="password" name="uipassre" size="25"></td>
				</tr>
				<tr>
					<td>Desired language:</td>
					<td>
						<select name="uilang">
							<option value="en" selected>English</Option>
							<option value="de">German</Option>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="right"><input type="submit" value="Install phpLogCon"></td>
				</tr>
			</table>
		</form>

<?php

WriteFoot();

?>