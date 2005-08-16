<?php

/*#### #### #### #### #### #### #### #### #### ####
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2004-2005  Adiscon GmbH

Version 1.1

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; 
if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, 
MA  02111-1307, USA.

If you have questions about phpLogCon in general, please email info@adiscon.com. 
To learn more about phpLogCon, please visit http://www.phplogcon.com.

This Project was intiated and is maintened by Rainer Gerhards <rgerhards@hq.adiscon.com>. 
See AUTHORS to learn who helped make it become a reality.

*/#### #### #### #### #### #### #### #### #### #### 

?>

<Select name="epp">
	<option value="5"  <?php if ($_SESSION["epp"] == 5) echo "selected"; ?>>5 per page</option>
	<option value="10" <?php if ($_SESSION["epp"] == 10) echo "selected"; ?>>10 per page</option>
	<option value="15" <?php if ($_SESSION["epp"] == 15) echo "selected"; ?>>15 per page</option>
	<option value="20" <?php if ($_SESSION["epp"] == 20) echo "selected"; ?>>20 per page</option>
	<option value="25" <?php if ($_SESSION["epp"] == 25) echo "selected"; ?>>25 per page</option>
	<option value="50" <?php if ($_SESSION["epp"] == 50) echo "selected"; ?>>50 per page</option>
  <option value="100" <?php if ($_SESSION["epp"] == 100) echo "selected"; ?>>100 per page</option>
</Select>
