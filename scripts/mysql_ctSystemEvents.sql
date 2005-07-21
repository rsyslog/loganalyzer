# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the SystemEvents table for Mysql

CREATE TABLE IF NOT EXISTS SystemEvents (
  ID int(10) unsigned NOT NULL auto_increment,
  CustomerID int(11) NOT NULL default '0',
  ReceivedAt datetime NOT NULL default '0000-00-00 00:00:00',
  DeviceReportedTime datetime NOT NULL default '0000-00-00 00:00:00',
  Facility int(11) NOT NULL default '0',
  Priority int(11) NOT NULL default '0',
  FromHost varchar(60) NOT NULL default '',
  Message text NOT NULL,
  NTSeverity char(3) NOT NULL default '',
  Importance char(3) NOT NULL default '',
  EventSource varchar(60) default NULL,
  EventUser varchar(60) NOT NULL default '',
  EventCategory int(11) NOT NULL default '0',
  EventID int(11) NOT NULL default '0',
  EventBinaryData text NOT NULL,
  MaxAvailable int(11) NOT NULL default '0',
  CurrUsage int(11) NOT NULL default '0',
  MinUsage int(11) NOT NULL default '0',
  MaxUsage int(11) NOT NULL default '0',
  InfoUnitID int(11) NOT NULL default '0',
  SysLogTag varchar(60) default NULL,
  EventLogType varchar(60) default NULL,
  GenericFileName varchar(60) default NULL,
  SystemID int(11) NOT NULL default '0',
  Checksum int(11) NOT NULL default '0',
  PRIMARY KEY  (ID)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
