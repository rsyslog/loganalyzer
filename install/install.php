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

if(!isset($_POST['instLang']))
{
	echo "<center><b>Welcome to the installation of phpLogCon, the WebInterface to log data.</b><br>";
	echo "<form method=\"POST\" action=\"install.php\" name=\"Language\">";
	echo "<input type=\"hidden\" name=\"instLang\" value=\"Language\">";
	echo "Please select your language for Installation progress: ";
	echo "<Select name=\"language\">";
	echo "<option value=\"en\">English</Option>";
	echo "<option value=\"de\">German</Option>";
	echo "</Select>";
	echo "<br><input type=\"submit\" value=\"Submit\">";
	echo "</center>";
}
else
{

	include "../lang/" . $_POST['language'] . ".php";

?>

<!-- Body creation -->
		<center><b><?php echo _InsWelc1; ?><br><?php echo _InsWelc2; ?><br><i><?php echo _InsWelc3; ?><font color="red"><?php echo _InsWelc4; ?></font><?php echo _InsWelc5; ?></i></b></center>
		<br><br>
		<center><?php echo _InsDbIns1; ?><br><?php echo _InsDbIns2; ?></center>
		<br>
		<form method="POST" action="perform.php" name="Installation">
			<input type="hidden" name="install" value="Installation">
			<table border="" cellpadding="2" cellspacing="0" width="600" align="center" Class="ConfigTable">
				<tr>
					<td colspan="2" align="center" Class="Header1">.: <?php echo _InsDbIns3; ?> :.</td>
					<td Class="Header1">&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo _InsDbInsCon; ?>: <font color="red">*</font></td>
					<td>
						<select name="dbcon">
							<option value="nativemysql" selected>MySQL</Option>
							<option value="odbcmysql">MySQL via ODBC</Option>
							<option value="odbcmssql">MSSQL/MSAccess via ODBC</Option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Host/IP: <font color="red">*</font></td>
					<td><input type="text" name="dbhost" size="25"></td>
				</tr>
				<tr>
					<td>Port (<?php echo _InsDbInsPort; ?>):</td>
					<td><input type="text" name="dbport" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsDbInsUsr; ?>:</td>
					<td><input type="text" name="dbuser" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsPass; ?>:</td>
					<td><input type="password" name="dbpass" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsPassRe; ?>:</td>
					<td><input type="password" name="dbpassre" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsDbInsName; ?>: <font color="red">*</font></td>
					<td><input type="text" name="dbname" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsDbInsTime; ?>: <font color="red">*</font></td>
					<td>
						<select name="dbtime">
							<option value="utc" selected>UTC</Option>
							<option value="local">Localtime</Option>
						</select>
					</td>
				</tr>
			</table>

			<br><br>
			<center><?php echo _InsPlcIns1; ?><br><i><?php echo _InsPlcIns2; ?></center>
			<br>
			<table border="" cellpadding="2" cellspacing="0" width="600" align="center" Class="ConfigTable">
				<tr>
					<td colspan="2" align="center" Class="Header1">.: <?php echo _InsPlcIns3; ?> :.</td>
					<td Class="Header1">&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo _InsPlcInsLang; ?>:</td>
					<td>
						<select name="lang">
							<option value="en" <?php if($_POST['instLang'] == "en") echo 'selected'; ?>><?php echo _InsLangEn; ?></Option>
							<option value="de" <?php if($_POST['instLang'] == "de") echo 'selected'; ?>><?php echo _InsLangDe; ?></Option>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo _InsPlcInsUi; ?>:</td>
					<td>
						<input type="checkbox" name="ui" value="1">
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left" Class="Header1"><?php echo _InsPlcInsUiCrUsr; ?>:</td>
					<td Class="Header1">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2"><?php echo _InsPlcIns4; ?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo _InsPlcInsUiName; ?>:</td>
					<td><input type="text" name="uiuser" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsPlcInsUiDisName; ?>:</td>
					<td><input type="text" name="uidisname" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsPass; ?>:</td>
					<td><input type="password" name="uipass" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsPassRe; ?>:</td>
					<td><input type="password" name="uipassre" size="25"></td>
				</tr>
				<tr>
					<td><?php echo _InsPlcInsUiLang; ?>:</td>
					<td>
						<select name="uilang">
							<option value="en" <?php if($_POST['instLang'] == "en") echo 'selected'; ?>><?php echo _InsLangEn; ?></Option>
							<option value="de" <?php if($_POST['instLang'] == "de") echo 'selected'; ?>><?php echo _InsLangDe; ?></Option>
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

}

WriteFoot();

?>