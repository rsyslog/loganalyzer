<Select name="order">
	<option value="Date" <?php if ($_SESSION['order'] == "Date") echo 'selected'; ?>><?php echo _MSGDate; ?></Option>
	<option value="Facility" <?php if ($_SESSION['order'] == "Facility") echo 'selected'; ?>><?php echo _MSGFac; ?></option>
	<option value="Priority" <?php if ($_SESSION['order'] == "Priority") echo 'selected'; ?>><?php echo _MSGPri; ?></option>
	<option value="FacilityDate" <?php if ($_SESSION['order'] == "FacilityDate") echo 'selected'; ?>><?php echo _MSGFacDat; ?></Option>
	<option value="PriorityDate" <?php if ($_SESSION['order'] == "PriorityDate") echo 'selected'; ?>><?php echo _MSGPriDat; ?></Option>
	<option value="Host" <?php if ($_SESSION['order'] == "Host") echo 'selected'; ?>><?php echo _MSGHost; ?></Option>
</Select>