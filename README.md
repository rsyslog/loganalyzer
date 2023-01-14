# loganalyzer

![loganalizer_example](https://user-images.githubusercontent.com/8426197/209875963-b7438f3b-9052-4e8f-9f22-05794e1e54a5.png)
Adiscon LogAnalyzer, a web frontend to log data from the same folks the created rsyslog

# changes 230114
 - fix bug: WHERE ( message LIKE '%LTE%unreachable%' ) give no result on filter page, yet works for charts, i.e. input = msg:LTE%unreachable (todo# in chart records have wrong date today merged with yesterday)
 - cfg[EventEmptySearchDefaultFilter] - config to use in case filter is empty (first load)
 - cfg[ExportUseTodayYesterday] - allow to export date in case today/yesterday enabled 
 - CFG[Default_AUTORELOAD_ID] - control default autoreload mode
 - cfg[SESSION_MAXIMIZED] - control maximized mode
 - filter: support "limit:int" tag - disable paging and query less than configured for page (LogStreamDB->_SQLcustomLimitHaltSearchAfter)
 - filter: datelastx - handle as float number
 - export: allow to EXPORT_PLAIN text format	
 - custom bug fixes
 - rename SYSLOG_TRACE to CUSTOM_TRACE;
 - translations: {LN_CHART_ORDERBY, LN_CHART_FILTER, LN_GEN_EMPTYSRCHFILTR, LN_GEN_EXPORT_PLAIN, LN_GEN_EXPORT_USETODAY, LN_GEN_SESSION_MAX}
 
# changes
 - fix bug: chart double adding same key into search
 - allow to configure ORDER BY clause
 - add custom filter to chart value redirection link if such exists
 - filter: support number ranges, i.e. severity:3-6 -> where severity in (3,4,5,6)
 - filter: support quoted filters, i.e. syslogtag:-="dhcp,info",-="wireless,info",-"system%,account" ->  where (syslogtag <> 'dhcp,info' AND syslogtag <> 'wireless,info' AND syslogtag NOT LIKE '%system%,account%' )
 - filter: support TRACE severity level; 
 - gui: add loglevel style colors and change color for full line; 
 - filter: change datelastx behaviour - use number as hours indicator, i.e. datelastx:3 is 3 hours limit

# todo
 - export: add checkbox to export full filtered history (now exports selected page only)
 - export: configure columns for file export (allow to remove unnecessary columns) <- exclude list of columns
 - export: place ts into export filename (range from-to)
 
 #obsolete
 - filter: allow to OR msg, i.e. key1 &key2 |key3;
 - filter: date{from,to} - allow to use today/yesterday + short time, i.e. today 1h same as 1h, yesterday 2h, since/after/before/etc.
 - "Maximize view" - reloads page and resets search filter, hide toolbars with js instead
 - changing "Autoreload" does the same as "Max. view"
 - allow to manually configure log levels (severity) instead of using constants
 - cfg[ExportCSVDelimiter,ExportCSVQuoteValues] - allow to EXPORT_PLAIN text format
