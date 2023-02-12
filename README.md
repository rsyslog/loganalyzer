# loganalyzer

![loganalizer_example](https://user-images.githubusercontent.com/8426197/209875963-b7438f3b-9052-4e8f-9f22-05794e1e54a5.png)
Adiscon LogAnalyzer, a web frontend to log data from the same folks the created rsyslog

# todo
 - BUG: sometimes spinner on index page is drawn in the middle of page irrespective of it's size, but should be drawn in the middle of screen
 - GUI: add checkbox on events page to suppress duplicates in export (override default ExportSuppressDuplicatedMessages
 - GUI: add checkbox ... to override ExportAllMatchPages
 
# changes 230122
 - export: append exported range timestamp to filename
 - filter: dateto/datefrom add support for offset from current date, i.e. datefrom:T00:00:00 dateto:T01:00:00, datefrom:\-2T, datefrom:\-1T01
 - fixed BUG: "Suppress duplicated messages" + add property to control distance between duplicates
 - GUI: if suppress is enabled, then show how much records were suppressed (LN_GEN_SUPPRESSEDRECORDCOUNT)
 - $CFG[ExportSuppressDuplicatedMessages] - separate export suppressing from view
 - $CFG[ExportAllMatchPages] - allow to control how much to export by default
 - $CFG[DuplicateRecordMaxTsDistance] - check duplicates timestamp before suppressing, i.e. same message with 24h period may not be assumed as duplicate

# changes 230121
 - datelastx - keep for backward compatibility (for saved searches); add datelastxx

# changes 230114
 - fix bug: WHERE ( message LIKE '%LTE%unreachable%' ) give no result on filter page, yet works for charts, i.e. input = msg:LTE%unreachable (todo# in chart records have wrong date today merged with yesterday)
 - cfg[EventEmptySearchDefaultFilter] - config to use in case filter is empty (first load)
 - cfg[ExportUseTodayYesterday] - allow to export date in case today/yesterday enabled 
 - CFG[Default_AUTORELOAD_ID] - control default autoreload mode
 - cfg[SESSION_MAXIMIZED] - control maximized mode
 - filter: support "limit:int" tag - disable paging and query less than configured for page (LogStreamDB->_SQLcustomLimitHaltSearchAfter)
 
![limit_10](https://user-images.githubusercontent.com/8426197/212502393-d05d0cb9-4baf-4008-838b-ce078b6eeb8b.png)

 - filter: datelastx - handle as float number
 - export: allow to EXPORT_PLAIN text format	
 - custom bug fixes
 - rename SYSLOG_TRACE to CUSTOM_TRACE;
 - translations: {LN_CHART_ORDERBY, LN_CHART_FILTER, LN_GEN_EMPTYSRCHFILTR, LN_GEN_EXPORT_PLAIN, LN_GEN_EXPORT_USETODAY, LN_GEN_SESSION_MAX}
 
# changes
 - fix bug: chart double adding same key into search
 - allow to configure ORDER BY clause
 - add custom filter to chart value redirection link if such exists
 
![chart_custom](https://user-images.githubusercontent.com/8426197/210448944-9a67c91c-1ca7-4f00-99ac-a5eebd566927.png)

 - filter: support number ranges, i.e. severity:3-6 -> where severity in (3,4,5,6)
 - filter: support quoted filters, i.e. syslogtag:-="dhcp,info",-="wireless,info",-"system%,account" ->  where (syslogtag <> 'dhcp,info' AND syslogtag <> 'wireless,info' AND syslogtag NOT LIKE '%system%,account%' )
 - filter: support TRACE severity level; 
 - gui: add loglevel style colors and change color for full line; 
 - filter: change datelastx behaviour - use number as hours indicator, i.e. datelastx:3 is 3 hours limit
 
# obsolete
 - filter: allow to specify AFTER:n BEFORE:n (if possible/fast to implement) <- include records before and after match
 - export: configure columns for file export (allow to remove unnecessary columns) <- exclude list of columns
 - filter: allow to OR msg, i.e. key1 &key2 |key3; (instead use regex)
 - filter: date{from,to} - allow to use today/yesterday + short time, i.e. today 1h same as 1h, yesterday 2h, since/after/before/etc.
 - "Maximize view" - reloads page and resets search filter, hide toolbars with js instead
 - changing "Autoreload" does the same as "Max. view"
 - allow to manually configure log levels (severity) instead of using constants
 - cfg[ExportCSVDelimiter,ExportCSVQuoteValues] - allow to EXPORT_PLAIN text format
