<Select name="show_methode">
	<option value="SysLogTag" <?php if ($_SESSION['show_methode'] == 'SysLogTag') echo 'selected'; ?>><?php echo _MSGMethSlt; ?></option>
	<option value="Host" <?php if ($_SESSION['show_methode'] == 'Host') echo 'selected'; ?>><?php echo _MSGMethHost; ?></option>
</Select>