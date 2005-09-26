# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the UserPrefs table for Mysql

CREATE TABLE IF NOT EXISTS UserPrefs (
  ID int(10) unsigned NOT NULL auto_increment,
  UserLogin varchar(50) NOT NULL default '',
  Name varchar(255) NOT NULL default '',
  PropValue varchar(200) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM AUTO_INCREMENT=1
