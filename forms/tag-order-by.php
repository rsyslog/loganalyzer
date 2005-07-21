<Select name="tag_order">
	<option value="Occurences" <?php if ($_SESSION['tag_order'] == "Occurences") echo 'selected'; ?>><?php echo _MSGOccuren; ?></option>
	<option value="SysLogTag" <?php if ($_SESSION['tag_order'] == "SysLogTag") echo 'selected'; ?>><?php echo _MSGSysLogTag; ?></option>
<?php
if($_SESSION['show_methode'] == "Host")
{
?>
	<option value="Host" <?php if ($_SESSION['tag_order'] == "Host") echo 'selected'; ?>><?php echo _MSGHost; ?></option>
<?php
}
?>
</Select>