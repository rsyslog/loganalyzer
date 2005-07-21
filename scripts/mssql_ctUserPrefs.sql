# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the UserPrefs table for MSsql

CREATE TABLE [UserPrefs] (
	[ID] [int] IDENTITY (1, 1) NOT NULL ,
	[UserLogin] [nvarchar] (50) COLLATE Latin1_General_CI_AS NULL ,
	[Name] [nvarchar] (255) COLLATE Latin1_General_CI_AS NULL ,
	[PropValue] [nvarchar] (200) COLLATE Latin1_General_CI_AS NULL ,
	PRIMARY KEY  (ID)
);
