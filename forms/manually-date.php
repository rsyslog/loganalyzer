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

?>



All Events between:
<Select name="y1">

<?php

	FOR ($i=2000;$i<=2012;$i++)
    {
        echo '<option value="'.$i.'"';
        if ($_SESSION['y1']==$i)
			echo " selected";
        echo '>'.$i.'</option>';
    }

?>

</select>&nbsp;-
<Select name="m1">

<?php

	FOR ($i=1;$i<=12;$i++)
    {
		echo '<option value="'.$i.'"';
        if ($_SESSION['m1']==$i)
			echo " selected";
        echo '>'.$i.'</option>';
    }

?>

</select>&nbsp;-
<Select name="d1">

<?php

	FOR ($i=1;$i<=31;$i++)
	{
		echo '<option value="'.$i.'"';
        if ($_SESSION['d1']==$i)
			echo " selected";
        echo '>'.$i.'</option>';
    }

?>

</select>&nbsp;&nbsp;<?php echo _MSGAnd;?>&nbsp;
<Select name="y2">

<?php

	FOR ($i=2000;$i<=2012;$i++)
    {
        echo '<option value="'.$i.'"';
        if ($_SESSION['y2']==$i)
			echo " selected";
        echo '>'.$i.'</option>';
    }

?>

</select>&nbsp;-
<Select name="m2">

<?php
	FOR ($i=1;$i<=12;$i++)
    {
        echo '<option value="'.$i.'"';
        if ($_SESSION['m2']==$i)
			echo " selected";
        echo '>'.$i.'</option>';
    }

?>
				
<% FOR P = 1 TO 12 %>
</select>&nbsp;-
<Select name="d2">

<?php

	FOR ($i=1;$i<=31;$i++)
    {
        echo '<option value="'.$i.'"';
        if ($_SESSION['d2']==$i)
			echo " selected";
        echo '>'.$i.'</option>';
    }

?>

</select>