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



	/*!
	* EventsNavigation Class gernerates a navigation menu to handle the 
	* result output. 
	*
	* Tasks:
	* - distribute the result output on several sides 
	* - generate navigation elements (menu) to walk through the sides
	*/


	class EventsNavigation
	{
		var $PageSize;                        //! number of lines to be displayed
		var $PageNumber;                      //! number of current page
		var $PageBegin;                       //! 1st recordset of page
		var $PageEnd;                         //! last recordset of page
		var $EventCount;                      //! total number of pages
		var $NavigationLeftArrow;             //! << link to the previous page
		var $NavigationRightArrow;            //! >> link to the next page
		var $NavigationFirstPage;             //! <<<< link to the start page
		var $NavigationLastPage;              //! >>>> link to the last page
		  
		//! Constructor
		function EventsNavigation($size)
		{
			$this->PageSize=$size;
			$this->PageNumber = (!isset($_GET["pagenum"]) || $_GET["pagenum"] == 0 || empty($_GET["pagenum"])) ? 1 : $_GET["pagenum"];
			$this->EventCount = 0;
		}
		  
		//! Returns how many lines to be displayed per page
		function GetPageSize()
		{
			return $this->PageSize;
		}
		  
		//! points to the first line, which is to be indicated on the current side 
		function GetLimitLower()
		{
			$limitlower = ($this->PageNumber-1) * $this->PageSize + 1;
			$limitlower = ($limitlower > $this->EventCount) ? $this->EventCount - $this->PageSize : $limitlower;
			$limitlower = ($limitlower <= 0) ? $limitlower = 1 : $limitlower;
			return $limitlower;
		}
		  
		//! points to the last line, which is to be indicated on the current side
		function GetLimitUpper()
		{
			$limitupper = $this->PageNumber * $this->PageSize;
			$limitupper = ($limitupper > $this->EventCount) ? $this->EventCount : $limitupper;
			return $limitupper;  
		}
		  
		//! get the number of the current page
		function GetPageNumber()
		{
			return $this->PageNumber;
		}
		  
		  
		//! genreate the html output to display the ne
		function SetNavigation($url_query)
		{
			//for displaying purposes ( page list ) 
			$page = ($this->EventCount < $this->GetPageSize()) ? 1 : ceil($this->EventCount / $this->GetPageSize()); 
			if($this->GetPageNumber() > 1)
			{
				$this->NavigationLeftArrow = "<a href=\"" .$_SERVER['PHP_SELF']."?pagenum=".($this->GetPageNumber()-1)."&" . $url_query . "\" class=\"searchlink\"> &laquo; </a>"; 
				$this->NavigationFirstPage = "<a href=\"" .$_SERVER['PHP_SELF']."?pagenum=1&" . $url_query . "\" class=\"searchlink\"> &laquo;&laquo; </a>"; 
			}
			else
			{
				$this->NavigationLeftArrow = "<span class=\"diseablesearchlink\"> &laquo; </span>";//unable
				$this->NavigationFirstPage = "<span class=\"diseablesearchlink\"> &laquo;&laquo; </span>";//enable
			}
			
			if($this->GetPageNumber() < $page)
			{
				$this->NavigationRightArrow = "<a href=\"" .$_SERVER['PHP_SELF']."?pagenum=".($this->GetPageNumber()+1)."&" . $url_query . "\" class=\"searchlink\"> &raquo; </a>"; 
				$this->NavigationLastPage = "<a href=\"" .$_SERVER['PHP_SELF']."?pagenum=".$page. "&" . $url_query . "\" class=\"searchlink\"> &raquo;&raquo; </a>"; 
			}
			else
			{
				$this->NavigationRightArrow = "<span class=\"diseablesearchlink\"> &raquo; </span>"; 
				$this->NavigationLastPage = "<span class=\"diseablesearchlink\"> &raquo;&raquo; </span>"; 
			}
			
			return $page;
		}
		  
		//! genreate the navigation menu output
		function ShowNavigation()
		{
			//query string without pagenum
			$url_para = RemoveArgFromURL($_SERVER['QUERY_STRING'], "pagenum");
			if(isset($_GET['slt']))
				$url_para = "slt=" . $_GET['slt'];

			$page = $this->SetNavigation($url_para);
			echo $this->NavigationFirstPage." ".$this->NavigationLeftArrow;
			for($a=$this->GetPageNumber()-3;$a<=$this->GetPageNumber()+3;$a++)
			{
				if($a > 0 && $a <= $page)
				{
					if($a==$this->GetPageNumber())
						echo "&nbsp;<span class=\"thissite\">$a</span>";
					else
						echo "&nbsp;<a href=\"" .$_SERVER['PHP_SELF']."?pagenum=".$a."&" . $url_para . "\" class=\"searchlink\">".$a."</a>";
			}
			}
			echo $this->NavigationRightArrow." ".$this->NavigationLastPage;
		}
		  
		//! send a database query to get the total number of events which are available
		//! save the result in $EventCount
		function SetEventCount($db_con, $restriction)
		{
			//get the counter result without limitation
			$result = db_exec($db_con, db_num_count($restriction));
			$row = db_fetch_array($result);
			$num = db_num_rows($result);
			// If you have a group clause in your query, the COUNT(*) clause doesn't
			// calculates the grouped rows; you get the number of all affected rows!
			// db_num_rows() gives the correct number in this case!
			if($num <= 1)
				$this->EventCount = $row['num']; //so many data records were foundy
			else
				$this->EventCount = $num;
		}

		//! returns the total number of available events
		function GetEventCount()
		{
			return $this->EventCount;
		}

		function SetPageNumber($val)
		{
			$this->PageNumber = $val;
		}
	}

?>
