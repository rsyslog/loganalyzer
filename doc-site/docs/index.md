# Handbook overview

LogAnalyzer is a PHP web front end for browsing syslog and related log data. This site collects **operational docs** (Docker, CI, fixtures) and a **LogAnalyzer user guide**: handbook-native topics plus chapters **imported** from upstream [`doc/*.html`](https://github.com/rsyslog/loganalyzer/tree/master/doc) manuals.

- In-repo **step-by-step install** (Compose from repo root, `.env`, optional host logs via **`LOGANALYZER_DISK_*`**, reset): [`docker/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/docker/README.md).
- Full reference: **[Docker (install & development)](docker.md)** (`AGENTS.md` mirrors essentials for contributors).
- **[Quick start](user-guide/quick-start.md)** — run with Docker and skim the UI with screenshots.
- **[Interface map](user-guide/interface-map.md)** — main routes and where to read more.
- [Third-party and bundled libraries](third-party.md) lists versions and update notes.
- [User guide overview](user-guide/overview.md) indexes every imported manual page; sources remain [`doc/*.html`](https://github.com/rsyslog/loganalyzer/tree/master/doc) on GitHub.

Handbook build: `doc-site/`; published at [https://rsyslog.github.io/loganalyzer/](https://rsyslog.github.io/loganalyzer/) (`site_url` in `mkdocs.yml`).
