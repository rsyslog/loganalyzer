<?php

/*#### #### #### #### #### #### #### #### #### ####
phpLogCon - A Web Interface to Log Data.
Copyright (C) 2004-2005  Adiscon GmbH



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


	// Set some defaults
	ini_set("register_globals", "1");


/*
**************************************************
*           Begin of config variables            *
* *                     **                     * *
*   You can change these settings to your need   *
* *                     **                     * *
*   Only change if you know what you are doing   *
**************************************************
*/
/*
***** BEGIN DATABASE SETTINGS *****
*/

	//Server name (only needed if you not use ODBC)
  define('_DBSERVER', 'localhost');

	// DSN (ODBC) or database name (Mysql)
  define('_DBNAME', 'monitorware');

	// Userid for database connection ***
  define('_DBUSERID', 'root');

	// Password for database connection ***
  define('_DBPWD', '');

	// table name
  define('_DBTABLENAME', 'SystemEvents');

	// Switch for connection mode
	// Currently only odbc and native works
  define('_CON_MODE', 'native');

	// Defines the Database Application you are using,
	// because for example thx ODBC syntax of MySQL
	// and Microsoft Access/SQL Server/etc are different
	// Currently available are:
	// with native: mysql
	// with ODBC: mysql and mssql are available
  define('_DB_APP', 'mysql');

/*
***** END DATABASE SETTINGS *****
*/
/*
***** BEGIN FOLDER SETTINGS *****
*/

	//The folder where the classes are stored
  define('_CLASSES', 'classes/');

	//The folder where the forms are stored
  define('_FORMS', 'forms/');

	//The folder where the database drivers are stored
  define('_DB_DRV', 'db-drv/');

	//The folder where the language files are stored
  define('_LANG', 'lang/');

  	//your image folder
  define('_ADLibPathImage', 'images/');

	//folder for scripts i.g. extern javascript 
  define('_ADLibPathScript', 'layout/');

/*
***** END FOLDER SETTINGS *****
*/
/*
***** BEGIN VARIOUS SETTINGS *****
*/
	//Set to 1 and the Header (image/introduce sentence) will be used! Set 0 to disable it.
  define('_ENABLEHEADER', 1);

	//Set to 1 and User Interface will be used! Set 0 to disable it.
  define('_ENABLEUI', 0);

	//This sets the default language that will be used.
  define('_DEFLANG', 'en');

	// Use UTC time
  define('_UTCtime', 0);
  
	// Get messages date by ReceivedAt or DeviceReportedTime
  define('_DATE', 'ReceivedAt');
  
	// Coloring priority
  define('_COLPriority', 1);

	// Custom Admin Message (appears on the homepage)
  define('_AdminMessage', "");

	// Version Number
  define('_VersionMajor', "1");
  define('_VersionMinor', "2");
  define('_VersionPatchLevel', "0");


/*
***** END VARIOUS SETTINGS *****
*/

/*
******************************************************
* *                        **                      * *
*   From this point you shouldn't change something   *
* *                        **                      * *
*        Only change if it is really required        *
******************************************************
*/
 
	// Show quick filter enabled = 1, disabled = 0:
  define('_FilterInfoUnit', 1);
  define('_FilterOrderby', 1);
  define('_FilterRefresh', 1);
  define('_FilterColExp', 1);
  define('_FilterHost', 1);
  define('_FilterMsg', 1);

	//Session expire time. Unix-Timestamp. To set this value:
	//call time(), that returns the seconds from 1.1.1970 (begin Unix-epoch) and add the
	//time in seconds when the cookie should expire.
  $session_time = (time()+(60*10));

	//Cookie expire time. Unix-Timestamp. How to set this value, see "session_time".
  define('_COOKIE_EXPIRE', (time()+60*60*24*30));

?>