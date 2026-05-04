# Quick start

The **default Docker stack** embeds the app image and persists MySQL plus **`config.php`**. Fresh installs (**`LOGANALYZER_SEED_SAMPLE_SOURCES=0`**) create **schema + admin only** — add sources in Administration unless you enable demo seeding (**`LOGANALYZER_SEED_SAMPLE_SOURCES=1`**).

Credentials come from Compose or repo-root **`.env`**; default placeholders **`admin`** / **`loganalyzer`**. **[`docker/README.md` install](https://github.com/rsyslog/loganalyzer/blob/master/docker/README.md)** (numbered steps, optional **`LOGANALYZER_DISK_*`**); handbook **[Docker install](../docker-install.md)** and **[Docker development & CI](../docker-develop.md)**. The screenshots below use **demo sources** (Playwright/E2E).

## Run LogAnalyzer locally (default Compose)

From the repository root:

```bash
docker compose --project-directory . -f docker/docker-compose.yml up --build
```

Open **http://localhost:8080/** (container maps host `8080`).

## Typical flow

1. **Main view** (`index.php`) — browse syslog-style lines from configured sources.

    ![Main log view](../assets/user-guide/index.png)

2. **Sign in** (`login.php`) — use admin credentials when user management / restricted pages apply.

    ![Login](../assets/user-guide/login.png)

3. **Administration** (`admin/index.php`) — sources, users, and related settings.

    ![Administration](../assets/user-guide/admin.png)

4. **Statistics** (`statistics.php`) and **Reports** (`reports.php`) — summaries and report views.

    ![Statistics](../assets/user-guide/statistics.png)

    ![Reports](../assets/user-guide/reports.png)

## Screenshots in this handbook

PNG files under `doc-site/docs/assets/user-guide/` are produced with **Playwright** against the E2E stack. To refresh them after UI changes, see [`e2e/README.md` in the repository](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md) (**Handbook screenshots**).

## Go deeper

- [Interface map](interface-map.md) — how main areas fit together.
- [User guide overview](overview.md) — full list of imported chapters from `doc/`.
- [Installation](chapters/install.md) and [Basics](chapters/basics.md) for configuration and concepts.
