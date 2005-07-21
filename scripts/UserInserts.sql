# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file inserts values into 'Users' and 'UserPrefs' tables

INSERT INTO Users (UserIDText, Password, DisplayName, LastLogon, UserType, PrefCulture) VALUES ('<username>', '<password>', '<realname>', <date>, 1, '<lang>');

INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_infounit_sl', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_infounit_er', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_infounit_o', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_0', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_1', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_2', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_3', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_4', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_5', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_6', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_priority_7', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_ti', 'today');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_order', 'Date');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_refresh', '0');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_FilterInfoUnit', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_FilterOrderby', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_FilterRefresh', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_FilterColExp', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_FilterHost', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_FilterMsg', '1');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_favorites', 'www.adiscon.com|Adiscon,www.monitorware.com|MonitorWare,www.winsyslog.com|WinSysLog');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_tag_order', 'Occurences');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_tag_sort', 'Asc');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_uStylesheet', 'phplogcon');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_uLanguage', '<lang>');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_uSaveFilterSettings', '0');
INSERT INTO UserPrefs (UserLogin, Name, PropValue) VALUES ('<username>', 'PHPLOGCON_uDebug', '0');
