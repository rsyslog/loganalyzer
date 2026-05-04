# Curated log fixtures for dev, PHPUnit, and Playwright

**Total size budget:** 50 MB (currently ~6 MB).

These files were copied from the phpLogCon / legacy `samplelogs` tree. Large files (multi‑100 MB IIS / mail dumps, `iislog-sample-big.log`, etc.) are **intentionally excluded**.

| File | Role |
|------|------|
| `sampledata_syslog.log` | Default Docker disk source (syslog) |
| `EventReporter.log` | Second Docker disk source (LogLineType `winsyslog`, parser `eventlog`) |
| `auth.log` | Auth-style lines |
| `messages.Protocol23Format.log` | RFC5424-style sample |
| `sampledata_eventlog.log` | Event-style sample |
| `iislog-sample.log` | IIS (modest size) |
| `WebServer.log` | Web / Apache-style |
| `syslog`, `syslog.0` | Generic syslog |
| `rsyslog`, `winsyslog`, `wireless.log`, `wireless2.log` | Misc formats |
| `syslogdatetest.log` | Date edge cases |
| `ngsyslog`, `testlog` | Tiny samples |

## Regenerating from upstream

If you have `phplogcon` checked out nearby, re-copy only the files listed above and run:

```bash
php tests/fixtures/check-size.php
```

The script lives at [tests/fixtures/check-size.php](check-size.php) and exits with status `1` if the directory exceeds **52428800** bytes.

Original source path (author’s machine / optional): `D:\!cvsroot\phplogcon\code\src\samplelogs`.
