# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file creates the SystemEvents table for MSsql

CREATE TABLE [SystemEvents] (
	[ID] [int] IDENTITY (1, 1) NOT NULL ,
	[CustomerID] [int] NOT NULL ,
	[ReceivedAt] [datetime] NOT NULL ,
	[DeviceReportedTime] [datetime] NOT NULL ,
	[Facility] [int] NOT NULL ,
	[Priority] [int] NOT NULL ,
	[FromHost] [varchar] (60) COLLATE Latin1_General_CI_AS NOT NULL ,
	[Message] [text] COLLATE Latin1_General_CI_AS NOT NULL ,
	[NTSeverity] [char] (3) COLLATE Latin1_General_CI_AS NOT NULL ,
	[Importance] [char] (3) COLLATE Latin1_General_CI_AS NOT NULL ,
	[EventSource] [varchar] (60) COLLATE Latin1_General_CI_AS NULL ,
	[EventUser] [varchar] (60) COLLATE Latin1_General_CI_AS NOT NULL ,
	[EventCategory] [int] NOT NULL ,
	[EventID] [int] NOT NULL ,
	[EventBinaryData] [text] COLLATE Latin1_General_CI_AS NOT NULL ,
	[MaxAvailable] [int] NOT NULL ,
	[CurrUsage] [int] NOT NULL ,
	[MinUsage] [int] NOT NULL ,
	[MaxUsage] [int] NOT NULL ,
	[InfoUnitID] [int] NOT NULL ,
	[SysLogTag] [varchar] (60) COLLATE Latin1_General_CI_AS NULL ,
	[EventLogType] [varchar] (60) COLLATE Latin1_General_CI_AS NULL ,
	[GenericFileName] [varchar] (60) COLLATE Latin1_General_CI_AS NULL ,
	[SystemID] [int] NOT NULL ,
	[Checksum] [int] NOT NULL ,
	PRIMARY KEY  (ID)
);
