# Changelog notes (maintainer backlog)

Informal backlog and incremental notes migrated from `README.md` (2026). Prefer **GitHub Issues** for new work tracking.

---

## TODO (from README)

- export: add checkbox to export full filtered history (now exports only current page)
- export: place ts into export filename (range from-to)
- BUG: “Suppress duplicated messages” doesn’t work
- filter: allow to specify AFTER:n BEFORE:n (if possible/fast to implement); include records before and after match
- export: configure columns for file export (allow to remove unnecessary columns); exclude list of columns
- BUG: sometimes spinner on index page is drawn in the middle of page irrespective of its size, but should be drawn in the middle of screen

---

## Changes 230121

- datelastx — keep for backward compatibility (for saved searches); add datelastxx

---

## Changes 230114

- fix bug: `WHERE ( message LIKE '%LTE%unreachable%' )` gives no result on filter page, yet works for charts, i.e. input = `msg:LTE%unreachable` (records in charts may merge wrong dates)
- `cfg[EventEmptySearchDefaultFilter]` — config to use when filter is empty (first load)
- `cfg[ExportUseTodayYesterday]` — export date when today/yesterday enabled
- `CFG[Default_AUTORELOAD_ID]` — default autoreload mode
- `cfg[SESSION_MAXIMIZED]` — maximized mode
- filter: support `"limit:int"` tag — disable paging and query less than configured for page (`LogStreamDB->_SQLcustomLimitHaltSearchAfter`)

See also: https://user-images.githubusercontent.com/8426197/212502393-d05d0cb9-4baf-4008-838b-ce078b6eeb8b.png (`limit_10`).

- filter: datelastx — handle as float number
- export: allow `EXPORT_PLAIN` text format
- custom bug fixes
- rename `SYSLOG_TRACE` to `CUSTOM_TRACE`
- translations: `LN_CHART_ORDERBY`, `LN_CHART_FILTER`, `LN_GEN_EMPTYSRCHFILTR`, `LN_GEN_EXPORT_PLAIN`, `LN_GEN_EXPORT_USETODAY`, `LN_GEN_SESSION_MAX`

---

## Earlier changes

- fix bug: chart double-adding same key into search
- allow configuring `ORDER BY` clause
- add custom filter to chart value redirection link if such exists

See also: https://user-images.githubusercontent.com/8426197/210448944-9a67c91c-1ca7-4f00-99ac-a5eebd566927.png (`chart_custom`).

- filter: support number ranges, e.g. `severity:3-6` → `where severity in (3,4,5,6)`
- filter: support quoted filters
- filter: support TRACE severity level
- GUI: log level style colors and full-line color
- filter: datelastx behaviour — number as hours, e.g. `datelastx:3` is 3 hours

---

## Obsolete ideas (historical)

- filter: allow OR msg, e.g. `key1 &key2 |key3`
- filter: `date{from,to}` — today/yesterday + short time
- “Maximize view” — reload page vs hide toolbars with JS
- changing “Autoreload” same as “Max. view”
- manually configure log levels instead of constants
- `cfg[ExportCSVDelimiter,ExportCSVQuoteValues]` for `EXPORT_PLAIN`
