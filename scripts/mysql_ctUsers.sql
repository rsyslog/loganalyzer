# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the 'Users' and 'UserPrefs' tables

CREATE TABLE IF NOT EXISTS Users (
  UserID int(10) unsigned NOT NULL auto_increment,
  UserIDText varchar(20) NOT NULL default '',
  Password varchar(50) NOT NULL default '',
  DisplayName varchar(50) NOT NULL default '',
  LastLogon datetime NOT NULL default '0000-00-00 00:00:00',
  UserType int(11) NOT NULL default '0',
  HomeTZ int(11) NOT NULL default '0',
  PrefCulture varchar(5) NOT NULL default '',
  PRIMARY KEY  (UserID)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
