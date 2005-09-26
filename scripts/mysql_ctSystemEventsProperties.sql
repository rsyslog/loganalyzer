# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the SystemEventsProperties table for Mysql

CREATE TABLE IF NOT EXISTS SystemEventsProperties (
	ID int unsigned not null auto_increment primary key,
	SystemEventID int NULL ,
	ParamName varchar (255)  NULL ,
	ParamValue varchar (255)  NULL 
)
