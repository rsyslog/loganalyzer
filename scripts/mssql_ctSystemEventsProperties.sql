# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the SystemEventsProperties table for MSsql

CREATE TABLE [SystemEventsProperties] (
	[ID] [int] IDENTITY (1, 1) NOT NULL ,
	[SystemEventID] [int] NULL ,
	[ParamName] [nvarchar] (255) COLLATE Latin1_General_CI_AS NULL ,
	[ParamValue] [nvarchar] (255) COLLATE Latin1_General_CI_AS NULL ,
	PRIMARY KEY  (ID)
);
