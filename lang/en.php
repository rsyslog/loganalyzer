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

/*
* English language file for phpLogCon
*/

define('_MSG001', 'Welcome to phpLogCon! Please login with your username and password first');
define('_MSGUsrnam', 'Username');
define('_MSGpas', 'Password');
define('_MSGLogSuc', 'Login successfully');
define('_MSGWel', 'Welcome to phpLogCon');
define('_MSGChoOpt', '! Choose an option from the menu above');
define('_MSGQuiInf', 'Quick info (filter settings apply)');
define('_MSGTop5', '5 most recent logs (filter settings apply)');
define('_MSGNoData', 'No data found');
define('_MSGDate', 'Date');
define('_MSGFac', 'Facility');
define('_MSGPri', 'Severity');
define('_MSGInfUI', 'InfoUnit');
define('_MSGMsg', 'Message');
define('_MSGSysLogTag', 'SysLogTag');
define('_MSGOccuren', 'Occurences');
define('_MSGAccDen', 'Access denied');
define('_MSGFalLog', 'You are not a subscribed user or Invalid Password');
define('_MSGBac2Ind', 'Back to Index');
define('_MSGSesExp', 'Session Expired');
define('_MSGSesExpQue', 'Session Expired. Maybe you forgot to log out');
define('_MSGReLog', 'Back to Index to re-login');
define('_MSGShwEvn', 'Show Events');
define('_MSGShwSlt', 'Show SysLogTags');
define('_MSGShwSLog', 'Show SysLog');
define('_MSGNoDBCon', 'Cannot connect to database-server');
define('_MSGChDB', 'Failed to changing database');
define('_MSGInvQur', 'Invalid query');
define('_MSGNoDBHan', 'No valid Database-Connection-Handle');
define('_MSGLogout', 'Logout');
define('_MSGSrcExp', 'Search for Expression');
define('_MSGSrc', 'Search');
define('_MSGinCol', ' in this color: ');
define('_MSGBrw', 'Browse');
define('_MSGBscSet', 'Basic Settings');
define('_MSGConSet', 'Connection Settings');
define('_MSGConMod', 'Connection Mode');
define('_MSGFilCon', 'Overall Filter conditions');
define('_MSGSltFil', 'SysLogTag Filter conditions');
define('_MSGEvnDat', 'Event&acute;s Date');
define('_MSGOrdBy', 'Order by');
define('_MSGTagSort', 'Order Ascending or Descending');
define('_MSGRef', 'Refresh');
define('_MSGOth', 'Other');
define('_MSGFilSet', 'Filter Settings');
define('_MSGUsrSet', 'User Settings');
define('_MSGFilOpt', 'Quick Filter Options');
define('_MSGSwiEvnMan', 'Select events date manually');
define('_MSGSwiEvnPre', 'Select events date predefined');
define('_MSGShwEvnDet', 'Show Events Details');
define('_MSGBck', 'back');
define('_MSGEvnID', 'EventID');
define('_MSGClickBrw', ' (Click for browsing MonitorWare database) :: (Or browse ');
define('_MSG2dy', 'today');
define('_MSGYester', 'only yesterday');
define('_MSGThsH', 'this hour');
define('_MSGLstH', 'last 1 hour');
define('_MSGL2stH', 'last 2 hours');
define('_MSGL5stH', 'last 5 hours');
define('_MSGL12stH', 'last 12 hours');
define('_MSGL2d', 'last 2 days');
define('_MSGL3d', 'last 3 days');
define('_MSGLw', 'last week');
define('_MSGFacDat', 'Facility and Date');
define('_MSGPriDat', 'Severity and Date');
define('_MSGNoRef', 'no refresh');
define('_MSGE10s', 'every 10 sec');
define('_MSGE30s', 'every 30 sec');
define('_MSGEm', 'every min');
define('_MSGE2m', 'every 2 min');
define('_MSGE15m', 'every 15 min');
define('_MSGEn', 'English');
define('_MSGDe', 'German');
define('_MSGFav', 'Favorites (Select to visit):');
define('_MSGDel', 'Delete');
define('_MSGNoFav', 'No favorites found');
define('_MSGNewFav', 'New favorite');
define('_MSGSiten', 'Sitename');
define('_MSGAdd', 'Add');
define('_MSGChg', 'Change');
define('_MSGEnv', 'Environment');
define('_MSGUsrInt', 'User Interface');
define('_MSGUEna', 'Enabled');
define('_MSGUDsa', '"Disabled');
define('_MSGNamInvChr', 'Name and/or password contained invalid characters');
define('_MSGSitInvChr', 'Sitename and/or address contained invalid characters');
define('_MSGESec', 'every second');
define('_MSGE5Sec', 'every  5 sec');
define('_MSGE20Sec', 'every  20 sec');
define('_MSGRed', 'Red');
define('_MSGBlue', 'Blue');
define('_MSGGreen', 'Green');
define('_MSGYel', 'Yellow');
define('_MSGOra', 'Orange');
define('_MSGSty', 'Style');
define('_MSGEnbQF', 'Choose Quick Filters:');
define('_MSGDisIU', 'Display Info Unit');
define('_MSGAscend', 'Ascending');
define('_MSGDescend', 'Descending');
define('_MSGColExp', 'Color an Expression');
define('_MSGFilConf', 'Filter Configuration');
define('_MSGUsrConf', 'User Configuration');
define('_MSGHost', 'Host');
define('_MSGEvnCat', 'EventCategory');
define('_MSGEvnUsr', 'EventUser');
define('_MSGFrmHos', 'FromHost');
define('_MSGNTSev', 'NTSeverity');
define('_MSGRecAt', 'ReceivedAt');
define('_MSGDevRep', 'DeviceReportedTime');
define('_MSGImp', 'Importance');
define('_MSGEvn', 'Event');
define('_MSGTo', 'to');
define('_MSGFrm', 'from');
define('_MSGLogPg', 'Logs per page');
define('_MSGHom', 'Home');
define('_MSGHlp', 'Help');
define('_MSGFOpt', 'Filter Options');
define('_MSGUOpt', 'User Options');
define('_MSGEvnLogTyp', 'EventLogType');
define('_MSGEvnSrc', 'EventSource');
define('_MSGFilHost', 'Search only for IP/Host');
define('_MSGSearchMsg', 'Message must contain');
define('_MSGPRI0', 'EMERGENCY');
define('_MSGPRI1', 'ALERT');
define('_MSGPRI2', 'CRITICAL');
define('_MSGPRI3', 'ERROR');
define('_MSGPRI4', 'WARNING');
define('_MSGPRI5', 'NOTICE');
define('_MSGPRI6', 'INFO');
define('_MSGPRI7', 'DEBUG');
define('_MSGNumSLE', 'Number of Syslog Events');
define('_MSGNumERE', 'Number of EventReporter Events');
define('_MSGNoMsg', '[No message available]');
define('_MSGMenInf1', '- You are currenty in ');
define('_MSGMenInf2', ' mode on ');
define('_MSGMenInf3', '. Database: ');
define('_MSGLang', 'Language:');
define('_MSGStyle', 'Stylesheet:');
define('_MSGAddInfo', 'Additional Informations:');
define('_MSGDebug1', 'Debug:');
define('_MSGDebug2', 'Show Debug Output');
define('_MSGSave', 'Save/- Loadoptions:');
define('_MSGFilSave1', 'Filter Settings:');
define('_MSGFilSave2', 'Save filter settings in database and load them while logging in');
define('_MSGDBOpt', 'Databaseoptions:');
define('_MSGUTC1', 'UTC Time:');
define('_MSGUTC2', 'When your database-server doesn\'t use UTC time, uncheck this!');
define('_MSGSavCook', 'Keep you logged in (Cookie)?');
define('_MSGAnd', 'and');
define('_MSGApply', 'Apply Filters');
define('_MSGDisSlt', 'Display');
define('_MSGMethSlt', 'SysLogTags');
define('_MSGMethHost', 'SysLogTags corresponding to hosts');
define('_MSGInstDir', 'The \'install\' directory does still exist! If you configured phplogcon already, please delete or rename it. This causes a high security risk! Otherwise please click <a href="install/install.php">HERE</A> to start the installation script.');

define('_InsWelc1', 'Welcome to the installation of phpLogCon, the WebInterface to log data.');
define('_InsWelc2', 'The following steps will guide you through the installation and help you to install and configure phpLogCon correctly.');
define('_InsWelc3', 'Note: Fields marked with a ');
define('_InsWelc4', 'RED *');
define('_InsWelc5', ' MUST be filled out in very case!');
define('_InsDbIns1', 'First we have to check your database structure, because phpLogCon needs some tables. If the tables don\'t exist, they will be created.');
define('_InsDbIns2', 'For this, phpLogCon Installation needs some information about your database Server:');
define('_InsDbIns3', 'Database Settings');
define('_InsDbInsCon', 'Connection Type');
define('_InsDbInsConNa', 'Native');
define('_InsDbInsApp', 'Database application');
define('_InsDbInsPort', 'If standard, leave blank');
define('_InsDbInsUsr', 'User (User must have \'INSERT\' and \'CREATE\' rights!)');
define('_InsPass', 'Password');
define('_InsPassRe', 'Re-type password');
define('_InsDbInsName', 'Database/DSN name');
define('_InsDbInsTime', 'Database time format');
define('_InsPlcIns1', 'Now we have to do some settings for phpLogCon to run clearly and user optimized.');
define('_InsPlcIns2', 'Note: If you now select the UserInterface not to be installed, you can install it through a SQL-script file! See the manual for help.');
define('_InsPlcIns3', 'phpLogCon General Settings');
define('_InsPlcInsLang', 'Default language');
define('_InsLangEn', 'English');
define('_InsLangDe', 'German');
define('_InsPlcInsUi', 'Install User Interface');
define('_InsPlcInsUiCrUsr', 'Create a User');
define('_InsPlcIns4', 'Here you can create a user for the User Interface. If you already have some users in your database or you have unselected the UserInterface, you can leave these fields!');
define('_InsPlcInsUiName', 'Username');
define('_InsPlcInsUiDisName', 'Display name');
define('_InsPlcInsUiLang', 'Desired language');
define('_InsPer1', 'Checking users input...');
define('_InsPerDone', 'Done!');
define('_InsPer2', 'Creating required tables...');
define('_InsPer3', 'Inserting values into tables...');
define('_InsPer4', 'Creating your config file (config.php)...');
define('_InsPer5', 'All processes have been done clearly!');
define('_InsPer6', 'Congratulations! You\'ve successfully installed phpLogCon!');
define('_InsPer7', 'A file named \'config.php\' is stored in the root directory of phpLogCon. In this file there are the whole information you have entered before! You can edit it to your needs if you want to.');
define('_InsPer8', 'Move to \'index.php\' in root directory to start working with phpLogCon!');
define('_InsPer9', 'Don\'t forget to delete whole \'install/\' directory!');
define('_InsPer10', 'These files could be user for a DoS on your phpLogCon!');
define('_InsPer11', 'After deleting the directory, you can go to ');
define('_InsPer12', 'index');
define('_NoteMsgInFuture1', '<br><br><b>Note:</b> There are ');
define('_NoteMsgInFuture2', ' events in the database, which are in the future!');

define('_MSGdatabaseConf', 'Database Configuration');
define('_MSGdatabaseSet', 'Database Settings');
define('_MSGdatabaseChoose', 'Choose Database:');
define('_MSGDbOpt', 'Database Options');
define('_MSGwhichdb', 'You are currently working with the following database:');