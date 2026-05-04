# LogAnalyzer

**LogAnalyzer** is a PHP web application for browsing syslog and related log data from **files** or **databases**. It comes from the same ecosystem as [rsyslog](https://www.rsyslog.com/).

![LogAnalyzer main view](https://user-images.githubusercontent.com/8426197/209875963-b7438f3b-9052-4e8f-9f22-05794e1e54a5.png)

## Documentation

- **Handbook** (Docker install & development, **LogAnalyzer user guide**, third-party components): [rsyslog.github.io/loganalyzer](https://rsyslog.github.io/loganalyzer/)
- **[`docker/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/docker/README.md)** — install walkthrough (`docker compose` from repo root, `.env`), file map, handbook links
- **[`AGENTS.md`](https://github.com/rsyslog/loganalyzer/blob/master/AGENTS.md)** — contributor notes: developer Docker stack (`docker-compose.dev.yml`), environment variables, PHPUnit, Playwright E2E
- **User guide chapters** (imported into the handbook) are maintained as HTML under [`doc/`](https://github.com/rsyslog/loganalyzer/tree/master/doc/)

## Quick start (Docker — default stack)

Step-by-step install: **[`docker/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/docker/README.md)** · handbook: **[Docker install & development](docker.md)**.

[`docker/docker-compose.yml`](https://github.com/rsyslog/loganalyzer/blob/master/docker/docker-compose.yml) defaults to a **clean install** (schema + admin; no demo disk sources). Optional fixture files live under **`/samplelogs`** if you enable **`LOGANALYZER_SEED_SAMPLE_SOURCES`**. MySQL and **`config.php`** persist in **named volumes**. Copy [`docker/env.example`](https://github.com/rsyslog/loganalyzer/blob/master/docker/env.example) to **`.env`** in the repo root to set passwords, optional **`LOGANALYZER_DISK_*`** paths, and seeding — or follow **[Docker helper scripts](docker.md#docker-helper-scripts)**.

From the repository root:

```bash
docker compose -f docker/docker-compose.yml up --build
```

Open **http://localhost:8080/**.

- **Credentials / DB defaults:** see [Docker: local install & development](docker.md). Dev-only stack with a bind-mounted repo `src/` uses [`docker/docker-compose.dev.yml`](https://github.com/rsyslog/loganalyzer/blob/master/docker/docker-compose.dev.yml).

**Clean reset everything** (drops MySQL and generated config volumes): `sh docker/rebuild-consumer.sh` or `docker\rebuild-consumer.bat`.

## Tests and CI

| Area | Where |
|------|--------|
| PHPUnit | `composer install` then `composer test` |
| Playwright E2E | [`e2e/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md), `docker/docker-compose.e2e.yml` |
| Workflows | [`.github/workflows/`](https://github.com/rsyslog/loganalyzer/tree/master/.github/workflows/) (PHP CI, Docker publish on tag `v*`, Playwright E2E, GitHub Pages) |

On GitHub, the Playwright workflow can open an issue when E2E fails (same repository only); see [`AGENTS.md`](https://github.com/rsyslog/loganalyzer/blob/master/AGENTS.md) and [`e2e.yml`](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/e2e.yml).

## Maintainer backlog

Informal TODOs and dated change bullets that previously lived here are archived in [`CHANGELOG.md`](https://github.com/rsyslog/loganalyzer/blob/master/CHANGELOG.md).
