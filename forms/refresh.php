<Select name="refresh">
	<option value="0" <?php if($_SESSION['refresh'] == "0") echo 'selected'; ?>><?php echo _MSGNoRef; ?></Option>
	<option value="5" <?php if($_SESSION['refresh'] == "5") echo 'selected'; ?>><?php echo _MSGE5Sec; ?></Option>
	<option value="10" <?php if ($_SESSION['refresh'] == "10") echo 'selected'; ?>><?php echo _MSGE10s; ?></Option>
	<option value="20" <?php if($_SESSION['refresh'] == "20") echo 'selected'; ?>><?php echo _MSGE20Sec; ?></Option>
	<option value="30" <?php if ($_SESSION['refresh'] == "30") echo 'selected'; ?>><?php echo _MSGE30s; ?></option>
	<option value="60" <?php if ($_SESSION['refresh'] == "60") echo 'selected'; ?>><?php echo _MSGEm; ?></option>
	<option value="120" <?php if ($_SESSION['refresh'] == "120") echo 'selected'; ?>><?php echo _MSGE2m; ?></Option>
	<option value="900" <?php if ($_SESSION['refresh'] == "900") echo 'selected'; ?>><?php echo _MSGE15m; ?></Option>
</Select>