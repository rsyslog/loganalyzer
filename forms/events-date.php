<Select name="ti">
	<option value="today" <?php if ($_SESSION['ti'] == 'today') echo 'selected'; ?>><?php echo _MSG2dy; ?></option>
	<option value="yesterday" <?php if ($_SESSION['ti'] == 'yesterday') echo 'selected'; ?>><?php echo _MSGYester; ?></option>
	<option value="thishour" <?php if ($_SESSION['ti'] == 'thishour') echo 'selected'; ?>><?php echo _MSGThsH; ?></option>
	<option value="min60" <?php if ($_SESSION['ti'] == 'min60') echo 'selected'; ?>><?php echo _MSGLstH; ?></option>
	<option value="min120" <?php if ($_SESSION['ti'] == "min120") echo 'selected'; ?>><?php echo _MSGL2stH; ?></option>
	<option value="min300" <?php if ($_SESSION['ti'] == "min300") echo 'selected'; ?>><?php echo _MSGL5stH; ?></option>
	<option value="min720" <?php if ($_SESSION['ti'] == "min720") echo 'selected'; ?>><?php echo _MSGL12stH; ?></option>
	<option value="min1440" <?php if ($_SESSION['ti'] == "min1440") echo 'selected'; ?>><?php echo _MSGL2d; ?></option>
	<option value="min2880" <?php if ($_SESSION['ti'] == "min2880") echo 'selected'; ?>><?php echo _MSGL3d; ?></option>
	<option value="min8640" <?php if ($_SESSION['ti'] == "min8640") echo 'selected'; ?>><?php echo _MSGLw; ?></option>
</Select>