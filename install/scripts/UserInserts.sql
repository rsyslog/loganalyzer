# phpLogCon - A Web Interface to Log Data.
# Copyright (C) 2003 - 2004 Adiscon GmbH
#
# This SQL file inserts values into 'Users' and 'UserPrefs' tables

INSERT INTO Users VALUES ('', '<username>', '<password>', '<realname>', <date>, 1, 100026, '<lang>');

INSERT INTO userprefs VALUES (1, '<username>', 'PHPLOGCON_infounit_sl', '1');
INSERT INTO userprefs VALUES (2, '<username>', 'PHPLOGCON_infounit_er', '1');
INSERT INTO userprefs VALUES (3, '<username>', 'PHPLOGCON_infounit_o', '1');
INSERT INTO userprefs VALUES (4, '<username>', 'PHPLOGCON_priority_0', '1');
INSERT INTO userprefs VALUES (5, '<username>', 'PHPLOGCON_priority_1', '1');
INSERT INTO userprefs VALUES (6, '<username>', 'PHPLOGCON_priority_2', '1');
INSERT INTO userprefs VALUES (7, '<username>', 'PHPLOGCON_priority_3', '1');
INSERT INTO userprefs VALUES (8, '<username>', 'PHPLOGCON_priority_4', '1');
INSERT INTO userprefs VALUES (9, '<username>', 'PHPLOGCON_priority_5', '1');
INSERT INTO userprefs VALUES (10, '<username>', 'PHPLOGCON_priority_6', '1');
INSERT INTO userprefs VALUES (11, '<username>', 'PHPLOGCON_priority_7', '1');
INSERT INTO userprefs VALUES (12, '<username>', 'PHPLOGCON_ti', 'today');
INSERT INTO userprefs VALUES (13, '<username>', 'PHPLOGCON_order', 'Date');
INSERT INTO userprefs VALUES (14, '<username>', 'PHPLOGCON_refresh', '0');
INSERT INTO userprefs VALUES (15, '<username>', 'PHPLOGCON_FilterInfoUnit', '1');
INSERT INTO userprefs VALUES (16, '<username>', 'PHPLOGCON_FilterOrderby', '1');
INSERT INTO userprefs VALUES (17, '<username>', 'PHPLOGCON_FilterRefresh', '1');
INSERT INTO userprefs VALUES (18, '<username>', 'PHPLOGCON_FilterColExp', '1');
INSERT INTO userprefs VALUES (19, '<username>', 'PHPLOGCON_FilterHost', '1');
INSERT INTO userprefs VALUES (20, '<username>', 'PHPLOGCON_FilterMsg', '1');
INSERT INTO userprefs VALUES (24, '<username>', 'PHPLOGCON_favorites', 'www.adiscon.com|Adiscon,www.monitorware.com|MonitorWare,www.winsyslog.com|WinSysLog');
INSERT INTO userprefs VALUES (21, '<username>', 'PHPLOGCON_uStylesheet', 'matrix');
INSERT INTO userprefs VALUES (22, '<username>', 'PHPLOGCON_uLanguage', 'en');
INSERT INTO userprefs VALUES (23, '<username>', 'PHPLOGCON_uSaveFilterSettings', '1');
INSERT INTO userprefs VALUES (25, '<username>', 'PHPLOGCON_uDebug', '0');
