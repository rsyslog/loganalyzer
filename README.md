![loganalizer_example](https://user-images.githubusercontent.com/8426197/209875963-b7438f3b-9052-4e8f-9f22-05794e1e54a5.png)
# loganalyzer
Adiscon LogAnalyzer, a web frontend to log data from the same folks the created rsyslog

# changes
 - support number ranges in filter, i.e. severity:3-6 -> where severity in (3,4,5,6)
 - support quoted filters, i.e. syslogtag:-="dhcp,info",-="wireless,info",-"system%,account" ->  where (syslogtag <> 'dhcp,info' AND syslogtag <> 'wireless,info' AND syslogtag NOT LIKE '%system%,account%' )
 - support TRACE syslog level; 
 - add loglevel style colors and change color for full line; 
 - limit empty search to 6h; 
 - change datelastx behaviour - use number as hours indicator, i.e. datelastx:3 is 3 hours limit

# todo
 - "Maximize view" - reloads page and resets search filter, hide toolbars with js instead
 - changing "Autoreload" does the same as "Max. view"
 - allow to manually configure log levels (severity) instead of using constants
