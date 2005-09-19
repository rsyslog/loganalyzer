<?php
	echo '<input type="text" name="regexp" size="30" value="';
		echo PreStrFromTxt4Out($_SESSION['regexp']);
	
  echo '">', _MSGinCol, '<select name="color">';
	echo '<option value="red" style="background-color:red"'; 
  
  if ($_SESSION['color'] == 'red') 
    echo ' selected';
  
  echo '>', _MSGRed, '</option>';
	echo '<option value="blue" style="background-color:blue"'; 
  
  if ($_SESSION['color'] == 'blue') 
    echo ' selected'; 
    
  echo '>', _MSGBlue, '</option>';
	echo '<option value="green" style="background-color:green"'; 
  
  if ($_SESSION['color'] == 'green') 
    echo ' selected'; 
    
  echo '>', _MSGGreen, '</option>';
	echo '<option value="yellow" style="background-color:yellow"'; 
  
  if ($_SESSION['color'] == 'yellow') 
    echo ' selected'; 
    
  echo '>', _MSGYel, '</option>';
	echo '<option value="orange" style="background-color:orange"'; 
  
  if ($_SESSION['color'] == 'orange') 
    echo ' selected'; 
    
  echo '>', _MSGOra, '</option>';
	echo '</select>';

?>