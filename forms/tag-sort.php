<Select name="tag_sort">
	<option value="Asc" <?php if ($_SESSION['tag_sort'] == "Asc") echo 'selected'; ?>><?php echo _MSGAscend; ?></Option>
	<option value="Desc" <?php if ($_SESSION['tag_sort'] == "Desc") echo 'selected'; ?>><?php echo _MSGDescend; ?></option>
</Select>