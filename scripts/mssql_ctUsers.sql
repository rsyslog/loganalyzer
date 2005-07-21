# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the Users table for MSsql

CREATE TABLE [Users] (
	[UserID] [int] IDENTITY (1, 1) NOT NULL ,
	[UserIDText] [nvarchar] (20) COLLATE Latin1_General_CI_AS NULL ,
	[Password] [nvarchar] (50) COLLATE Latin1_General_CI_AS NULL ,
	[DisplayName] [nvarchar] (50) COLLATE Latin1_General_CI_AS NULL ,
	[LastLogon] [smalldatetime] NULL ,
	[UserType] [int] NULL ,
	[HomeTZ] [int] NULL ,
	[PrefCulture] [nvarchar] (5) COLLATE Latin1_General_CI_AS NULL ,
	PRIMARY KEY  (UserID)
);
