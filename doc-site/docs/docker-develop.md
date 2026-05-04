# Docker development & CI

Contributor tooling: bind-mounted **`src/`**, Playwright **`docker-compose.e2e.yml`**, PHPUnit integration tests, GHCR publishes, GitHub Actions. For the **default Compose install** aimed at running the app locally without a dev tree, see **[Docker install](docker-install.md)**.

---

## Docker stacks overview

| Compose file | Use case |
|:---:|:---|
| [`docker/docker-compose.yml`](https://github.com/rsyslog/loganalyzer/blob/master/docker/docker-compose.yml) | Default install — baked app. Summary: **[Docker install](docker-install.md)** |
| [`docker/docker-compose.dev.yml`](https://github.com/rsyslog/loganalyzer/blob/master/docker/docker-compose.dev.yml) | **Development.** Bind-mount [`src`](https://github.com/rsyslog/loganalyzer/tree/master/src) and [`tests/fixtures/samplelogs`](https://github.com/rsyslog/loganalyzer/tree/master/tests/fixtures/samplelogs); **`LOGANALYZER_OVERWRITE_CONFIG=1`** regenerates **`src/config.php`** each start. Host **MySQL** on **`localhost:3306`**. Named volume **`loganalyzer_dev_mysql_data`**. |

Build context for images is **repository root**; Dockerfile [**`docker/Dockerfile`**](https://github.com/rsyslog/loganalyzer/blob/master/docker/Dockerfile) (multi-stage: runtime + baked **`src`**; fixtures at **`/samplelogs`** when baked).

Operational defaults: **`http://localhost:8080/`**; admin **`LOGANALYZER_ADMIN_*`** from Compose or **`.env`** (defaults **`admin`** / **`loganalyzer`** if unset). Default consumer Compose uses **`LOGANALYZER_SEED_SAMPLE_SOURCES=0`** (**clean install**); dev/E2E may set **`1`** so Playwright has demo disk sources.

---

### Developer workflow (live `src` mount)

```bash
docker compose --project-directory . -f docker/docker-compose.dev.yml up --build
```

**Clean rebuild** for this stack (wipe dev volumes):

- Linux/macOS/Git Bash: **`sh docker/rebuild-dev.sh`**
- Windows (cmd): **`docker\rebuild-dev.bat`**

Quick volume-only reset:

```text
docker compose --project-directory . -f docker/docker-compose.dev.yml down -v
```

!!! note "When to rebuild the image"

    **`Dockerfile`** or entry scripts change ⇒ **`compose up --build`**. Plain PHP edits on the developer stack ⇒ refresh the browser — the bind mount reflects host changes.

### Docker helper scripts

| Script | When to use |
|--------|-------------|
| [`docker/rebuild-dev.sh`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-dev.sh) / [`.bat`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-dev.bat) | Developer stack: **`down -v`** and **`docker-compose.dev.yml up --build`**. |
| [`docker/rebuild-e2e.sh`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-e2e.sh) / [`.bat`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-e2e.bat) | Playwright E2E stack + tests · [`e2e/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md). |

Interactive **`.env`** and **consumer rebuild**: **[Docker install](docker-install.md#docker-helper-scripts-install-focused)**

Bootstrap PHP (**`docker/write-config.php`**, **`docker/seed-database.php`**, **`docker/env-disk-sources.php`**) runs inside the container.

---

### Playwright end-to-end CI

Manual run:

```bash
docker compose --project-directory . -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
```

Documentation: **`e2e/README.md`** and **`docker/docker-compose.e2e.yml`**.

---

### Publish image (maintainers)

Push tag **`v*`** ⇒ [`.github/workflows/docker-publish.yml`](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/docker-publish.yml): **`ghcr.io/<owner>/<repo>`** with **`v…`** and **`latest`**.

To consume from GHCR, set **`web.image`** (**`ghcr.io/<owner>/<repo>:<tag>`**) instead of **`build`**, matching env **`LOGANALYZER_CONFIG_PATH`** semantics.

---

### Environment variables (`web` container)

| Variable | Typical default stack | Typical dev stack | Meaning |
|-----------|-----------------|--------------|---------|
| `LOGANALYZER_DB_HOST` | `db` | `db` | MySQL hostname (Compose service) |
| `LOGANALYZER_DB_PORT` | `3306` | `3306` | MySQL port |
| `(MySQL service)` `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD` | via **`.env`** / compose defaults | same | Bootstrap **`db`** + **`LOGANALYZER_DB_*`** in **`web`** |
| `MYSQL_ROOT_PASSWORD` | `loganalyzer_root` | `loganalyzer_root` | Seed script; keep in sync with healthchecks (**`CMD-SHELL`** uses **`$$MYSQL_ROOT_PASSWORD`**) |
| `LOGANALYZER_TABLE_PREFIX` | `logcon_` | `logcon_` | Table prefix |
| `LOGANALYZER_ADMIN_USER` | `admin` (**`.env`** overrides) | same | First administrator |
| `LOGANALYZER_ADMIN_PASSWORD` | `loganalyzer` (**`.env`** overrides) | same | Customise before sharing the host |
| `LOGANALYZER_LOGIN_REQUIRED` | `0` | `0` | **`1`** forces login globally |
| `LOGANALYZER_SAMPLE_LOG` / `LOGANALYZER_SAMPLE_EVENTREPORTER` | consulted when demo seeding | same | Fixture paths inside the container |
| `LOGANALYZER_SEED_SAMPLE_SOURCES` | **`0`** (clean install via compose defaults) | unset / **`1`** | **`0`**/**`false`**/**`no`**/**`off`** skip demo disk sources |
| `LOGANALYZER_SKIP_SEED` | unset | unset | **`1`** skips MySQL seed |
| `LOGANALYZER_OVERWRITE_CONFIG` | **`0`** | **`1`** | Regenerate **`config.php`** each start versus persist on volume |
| `LOGANALYZER_DISK_SOURCE_PATHS` | **`.env`** / unset | same | Comma-separated container paths ⇒ syslog disk rows on first seed |
| `LOGANALYZER_DISK_SOURCES` | **`.env`** / unset | same | **`;;`**-separated records, **`|`** fields — see **`docker/env.example`** |
| `LOGANALYZER_DISK_ALLOWED_EXTRA` | **`.env`** / unset | same | Extra **`DiskAllowed`** directory prefixes |
| `LOGANALYZER_CONFIG_PATH` | **`/persist/config.php`** *(typical default stack)* | unset | Persist **`config.php`** outside the layer and symlink into docroot |

---

## PHPUnit

```bash
composer install
composer test
```

MySQL integration tests skip unless **`LOGANALYZER_INTEGRATION=1`** with DB variables aligned to reachable MySQL.

---

## E2E (Playwright) & CI

See [`e2e/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md) and **`docker/docker-compose.e2e.yml`**.

### GitHub Actions

The [Playwright E2E workflow](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/e2e.yml) runs on pull requests, pushes to **`main`** or **`master`**, and manual dispatch. It builds the same Compose stack locally, then runs Playwright.

If a job fails, the workflow may open an issue (**`CI: playwright e2e failed (…)`**) with logs when **`issues: write`** applies and the run targets **this repository** (fork PRs skip **`gh issue create`**). See **`e2e.yml`**.
