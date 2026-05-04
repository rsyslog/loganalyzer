# Docker assets

Files for building and running LogAnalyzer with **Docker Compose**. Build context is the **repository root** (parent of this directory). Run commands from the repo root and pass **`--project-directory .`** with **`-f docker/...`** so Compose loads the **repository root `.env`** for variable substitution (otherwise the project directory defaults next to the compose file and **`./.env`** is ignored).

**Full guide**: [Handbook — Docker overview](../doc-site/docs/docker.md), [Docker install](../doc-site/docs/docker-install.md), [Docker development & CI](../doc-site/docs/docker-develop.md) · contributor notes: [`AGENTS.md`](../AGENTS.md).

## Install

### Prerequisites

- **Docker** (Engine) and the **Compose plugin** (`docker compose`, v2 syntax).
- Clone this repository and open a shell in the **repository root** (`loganalyzer/`, the directory that contains `docker/`, `src/`, `README.md`).

### Default install (consumer stack)

Recommended for a local “appliance” install: app is **baked into the image**; **MySQL** and **`config.php`** live in **Compose named volumes** (`loganalyzer_mysql_data`, `loganalyzer_config`).

1. **Environment (optional but recommended)**  
   Copy [`env.example`](env.example) to **`.env`** in the **repository root** (same level as `README.md`, **not** inside `docker/`). Edit admin and database passwords, and any optional flags (see comments in `env.example`).  
   Or run the interactive wizard once:  
   - POSIX: `bash docker/init-env-interactive.sh`  
   - Windows: `powershell -File docker\init-env-interactive.ps1 -RepoRoot .`

2. **Start the stack**

   ```bash
   docker compose --project-directory . -f docker/docker-compose.yml up --build
   ```

3. **Open the app**  
   **http://localhost:8080/**  
   First-login admin user and password come from **`LOGANALYZER_ADMIN_USER`** / **`LOGANALYZER_ADMIN_PASSWORD`** in your `.env` (Compose defaults **`admin`** / **`loganalyzer`** if unset).

4. **What you get on first boot**  
   MySQL is initialized; the **`web`** service generates **`config.php`**, waits for the DB, then **seeds schema, default views, and the admin user**. By default there are **no** demo disk sources (`LOGANALYZER_SEED_SAMPLE_SOURCES=0`); enable **`1`** in `.env` if you want the bundled **`/samplelogs`** sources, or configure **`LOGANALYZER_DISK_*`** for real files (below).

### Optional: host log files under the default stack

Paths in **`LOGANALYZER_DISK_SOURCE_PATHS`** / **`LOGANALYZER_DISK_SOURCES`** must exist **inside the web container**. Typical pattern:

1. Uncomment the **`volumes`** line under **`web`** in [`docker-compose.yml`](docker-compose.yml) (example: `- /var/log:/mnt/hostlog:ro`).
2. Set the matching paths in `.env`, e.g. `LOGANALYZER_DISK_SOURCE_PATHS=/mnt/hostlog/syslog`.
3. **First seed only** creates those DB sources; changing paths later usually means resetting the **`loganalyzer_config`** volume and/or **`LOGANALYZER_OVERWRITE_CONFIG`** — see the [handbook — Docker install](https://rsyslog.github.io/loganalyzer/docker-install/).

### Stop, reset, reinstall

- **Stop without deleting data:** `docker compose --project-directory . -f docker/docker-compose.yml down`
- **Wipe DB + persisted config** and bring the stack back:  
  `sh docker/rebuild-consumer.sh` or `docker\rebuild-consumer.bat` (runs the `.env` wizard if `.env` is missing, then **`down -v`** and **`up --build`**).

### Developer install (live `src/`)

For PHP hacking with the repo **`src/`** mounted into the container and **`config.php` regenerated each start**:

```bash
docker compose --project-directory . -f docker/docker-compose.dev.yml up --build
```

MySQL on **`localhost:3306`**, wipe helper: **`docker/rebuild-dev.sh`** / **`docker\rebuild-dev.bat`**. Details: [Handbook — Docker development & CI](../doc-site/docs/docker-develop.md) (live **`src`** mount).

## What lives here

| Item | Role |
|------|------|
| [`Dockerfile`](Dockerfile) | Multi-stage image: PHP app + fixtures; copies bootstrap scripts into `/usr/local/share/loganalyzer/`. |
| [`docker-compose.yml`](docker-compose.yml) | Default “consumer” stack: baked app, named volumes for MySQL + `config.php`. |
| [`docker-compose.dev.yml`](docker-compose.dev.yml) | Dev stack: bind-mount `src/`, overwrite `config.php` each start. |
| [`docker-compose.e2e.yml`](docker-compose.e2e.yml) | Playwright CI/local E2E (see [`e2e/README.md`](../e2e/README.md)). |
| [`env.example`](env.example) | Template for repo-root **`.env`**: admin, MySQL, optional demo seeding, optional **`LOGANALYZER_DISK_*`** disk sources. |
| [`entrypoint.sh`](entrypoint.sh) | Container start: `write-config`, DB wait, `seed-database`, `php -S`. |
| [`write-config.php`](write-config.php) | Generates `config.php` from `include/config.sample.php` + env (incl. `DiskAllowed` from disk-source env). |
| [`seed-database.php`](seed-database.php) | First-run MySQL schema/views/admin and optional sources. |
| [`env-disk-sources.php`](env-disk-sources.php) | Shared helpers for **`LOGANALYZER_DISK_SOURCE_PATHS`** / **`LOGANALYZER_DISK_SOURCES`** / **`LOGANALYZER_DISK_ALLOWED_EXTRA`**. |
| `init-env-interactive.*`, `rebuild-*.sh`, `rebuild-*.bat` | Host-side helpers ([handbook » Docker install — helper scripts](https://rsyslog.github.io/loganalyzer/docker-install/#docker-helper-scripts-install-focused)). |

Published handbook: **https://rsyslog.github.io/loganalyzer/docker/** · **https://rsyslog.github.io/loganalyzer/docker-install/** (`doc-site/docs/docker*.md`).
