<?php
	echo '<input type="text" name="regexp" size="30" value="';
	if( isset($_POST['regexp']) )
		echo PreStrFromTxt4Out($_POST['regexp']);
	
  echo '">', _MSGinCol, '<select name="color">';
	echo '<option value="red" style="background-color:red"'; 
  
  if (isset($_POST['color']) AND $_POST['color'] == 'red') 
    echo ' selected'; 
  
  echo '>', _MSGRed, '</option>';
	echo '<option value="blue" style="background-color:blue"'; 
  
  if (isset($_POST['color']) AND $_POST['color'] == 'blue') 
    echo ' selected'; 
    
  echo '>', _MSGBlue, '</option>';
	echo '<option value="green" style="background-color:green"'; 
  
  if (isset($_POST['color']) AND $_POST['color'] == 'green') 
    echo ' selected'; 
    
  echo '>', _MSGGreen, '</option>';
	echo '<option value="yellow" style="background-color:yellow"'; 
  
  if (isset($_POST['color']) AND $_POST['color'] == 'yellow') 
    echo ' selected'; 
    
  echo '>', _MSGYel, '</option>';
	echo '<option value="orange" style="background-color:orange"'; 
  
  if (isset($_POST['color']) AND $_POST['color'] == 'orange') 
    echo ' selected'; 
    
  echo '>', _MSGOra, '</option>';
	echo '</select>';

?>