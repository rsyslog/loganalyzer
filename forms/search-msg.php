<?php
  // search the message fild for a string
	echo '<input type="text" name="searchmsg" size="30" value="';
	if( isset($_POST['searchmsg']) )
		echo $_POST['searchmsg'];
	
  echo '">';
?>