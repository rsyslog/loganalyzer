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

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="quickFilter" value="change">
<?php

function ShowVarFilter()
{
    if ($_SESSION['FilterInfoUnit']==1) 
    { 
      echo '&nbsp;', _MSGDisIU, ': ';
      include _FORMS.'display-infounit.php'; 
    }    
    
    if ($_SESSION['FilterOrderby']==1)
    { 
      echo '&nbsp;', _MSGOrdBy, ': ';
      include _FORMS.'order-by.php'; 
    }
    
    if ($_SESSION['FilterRefresh']==1)
    {
      echo '&nbsp;', _MSGRef, ': '; 
      include _FORMS.'refresh.php'; 
    }
    
    if ($_SESSION['FilterColExp']==1)
    {
      echo '&nbsp;', _MSGColExp, '&nbsp;';  
      include _FORMS.'color-expression.php'; 
    }
    
    if ($_SESSION['FilterHost']==1)
    {
      echo '&nbsp;', _MSGFilHost, ': ';
      include _FORMS.'filter-host.php';
    }
    
    if ($_SESSION['FilterMsg']==1)
    {
      echo '&nbsp;', _MSGSearchMsg, ': ';
      include _FORMS.'search-msg.php';
    }
    

}

	echo '<b>', _MSGFilOpt, '</b>';
 
	// this switch is only a temporarry solution. Which forms are displayed should be configureable in the user profil in the future!
	if ($_SESSION['change'] == 'Predefined')
	{
    echo '&nbsp;', _MSGEvnDat, '&nbsp;';
	  include _FORMS.'events-date.php';
    echo '&nbsp;', _MSGLogPg, ': ';
    include _FORMS.'logs-per-page.php'; 

    ShowVarFilter();
      
	  echo '<input type="hidden" name="change" value="Predefined">';
	}
	else
	{
    echo '&nbsp;';
	  include _FORMS.'manually-date.php';
    echo '&nbsp;';
    include _FORMS.'logs-per-page.php';

    ShowVarFilter();
    
	  echo '<input type="hidden" name="change" value="Manually">';
	}

?>

<table><tr><td>
<input type="submit" name="form" value="Submit"></form></td><td>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php

	if ($_SESSION['change'] == "Predefined")
    echo '<input type="submit" name="change" value="Manually">';
	  //echo "<a href='events-display.php?change=Manually'>" . _MSGSwiEvnMan . "</a>";
	else
    echo '<input type="submit" name="change" value="Predefined">';
	  //echo "<a href='events-display.php?change=Predefined'>" . _MSGSwiEvnPre . '</a>';

?>
</form></td></tr></table>


