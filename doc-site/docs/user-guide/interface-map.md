# Interface map

LogAnalyzer is a PHP web UI. URLs below are the primary entry points; your deployment may use a subdirectory or different vhost (paths are relative to the app document root).

| Area | Typical URL | Purpose |
|------|-------------|---------|
| Main viewer | `index.php` | Primary log grid: filter, search, drill-down from sample or database sources. |
| Login | `login.php` | Authenticate when user DB / login-required mode is enabled. |
| Search | `search.php` | Dedicated search / filter UI; the main viewer also exposes search controls. |
| Statistics | `statistics.php` | Aggregated views / charts for the current context. |
| Reports | `reports.php` | Report-oriented views. |
| Administration | `admin/index.php` | Manage sources, users, and system options (administrators). |

See [Search syntax](chapters/searching.md) for query syntax used in filters and searches.

## Documentation trail

- **Operational setup:** [Docker overview](../docker.md), [Docker install](../docker-install.md), [Development & CI](../docker-develop.md); [Installation](chapters/install.md), [Troubleshooting](chapters/troubleshoot.md).
- **Using the product:** [Basics](chapters/basics.md), [Search syntax](chapters/searching.md), [Text log files](chapters/textfiles.md), [Windows Event Log](chapters/windowsevent.md).

## Screenshots

See [Quick start](quick-start.md) for captioned screenshots of the main viewer, login, admin, statistics, and reports.
