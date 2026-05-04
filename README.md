# LogAnalyzer

**LogAnalyzer** is a PHP web application for browsing syslog and related log data from **files** or **databases**. It comes from the same ecosystem as [rsyslog](https://www.rsyslog.com/).

![LogAnalyzer main view](https://user-images.githubusercontent.com/8426197/209875963-b7438f3b-9052-4e8f-9f22-05794e1e54a5.png)

## Documentation

- **Handbook** (Docker & CI, **LogAnalyzer user guide**, third-party components): [rsyslog.github.io/loganalyzer](https://rsyslog.github.io/loganalyzer/)
- **[`AGENTS.md`](https://github.com/rsyslog/loganalyzer/blob/master/AGENTS.md)** — Docker development, environment variables, PHPUnit, Playwright E2E; start here for a working local stack
- **User guide chapters** (imported into the handbook) are maintained as HTML under [`doc/`](https://github.com/rsyslog/loganalyzer/tree/master/doc/)

## Quick start (Docker)

From the repository root:

```bash
docker compose -f docker/docker-compose.yml up --build
```

Open **http://localhost:8080/**. Default dev credentials and MySQL settings are documented in [`AGENTS.md`](https://github.com/rsyslog/loganalyzer/blob/master/AGENTS.md).

## Tests and CI

| Area | Where |
|------|--------|
| PHPUnit | `composer install` then `composer test` |
| Playwright E2E | [`e2e/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md), `docker/docker-compose.e2e.yml` |
| Workflows | [`.github/workflows/`](https://github.com/rsyslog/loganalyzer/tree/master/.github/workflows/) (PHP CI, Playwright E2E, GitHub Pages) |

On GitHub, the Playwright workflow can open an issue when E2E fails (same repository only); see [`AGENTS.md`](https://github.com/rsyslog/loganalyzer/blob/master/AGENTS.md) and [`e2e.yml`](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/e2e.yml).

## Maintainer backlog

Informal TODOs and dated change bullets that previously lived here are archived in [`CHANGELOG.md`](https://github.com/rsyslog/loganalyzer/blob/master/CHANGELOG.md).
