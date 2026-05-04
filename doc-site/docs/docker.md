# Docker: local install & development

This handbook page tracks the same layout as **`AGENTS.md`** in the repository: two Compose files (default versus developer), environment variables, and CI notes.

**Install checklist:** for a concise numbered procedure (prerequisites, `.env`, optional host **`LOGANALYZER_DISK_*`** mounts, reinstall), use [`docker/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/docker/README.md) together with this page.

---

## Quick install (recommended)

The default stack ([`docker/docker-compose.yml`](https://github.com/rsyslog/loganalyzer/blob/master/docker/docker-compose.yml)) bakes the PHP application into the image. **First-time database seeding creates schema, default views, and the admin user only** — no bundled sample log **sources** unless you opt in (see below). Sample files still ship under **`/samplelogs`** for optional demos.

```bash
docker compose -f docker/docker-compose.yml up --build
```

Open **`http://localhost:8080/`**.

### Configure admin & database (`.env`, not `docker build`)

**Do not** prompt for secrets during `docker build`: images should stay generic and non-interactive. Instead:

1. Create **`./.env`**: copy [**`docker/env.example`**](https://github.com/rsyslog/loganalyzer/blob/master/docker/env.example), **or** run **`bash docker/init-env-interactive.sh`** from the repo root (Windows: **`powershell -File docker\init-env-interactive.ps1 -RepoRoot .`**). The wizard shows each **`KEY=value`** line from **`env.example`**; **press Enter** to keep its default (**`*PASSWORD*`** keys are masked in Bash; masked in PowerShell **7.1+**, visible in **`5.x`** / **7.0**).
2. Keep **`LOGANALYZER_SEED_SAMPLE_SOURCES=0`** for a clean install (**default** in **`env.example`**), or set **`1`** to auto-create the two demo disk sources under **`/samplelogs`**.
3. **Real syslog / log files from the host (optional):** paths in **`LOGANALYZER_DISK_*`** env vars refer to paths **inside the web container**. Uncomment the commented **`volumes:`** line under **`web`** in [**`docker/docker-compose.yml`**](https://github.com/rsyslog/loganalyzer/blob/master/docker/docker-compose.yml) (e.g. **`/var/log:/mnt/hostlog:ro`** on Linux), then set **`LOGANALYZER_DISK_SOURCE_PATHS`** or **`LOGANALYZER_DISK_SOURCES`** as documented in **`docker/env.example`**. First MySQL seed creates those disk sources; **`docker/write-config.php`** extends **`DiskAllowed`** so PHP can browse those directories. Changing disk paths later usually needs **`LOGANALYZER_OVERWRITE_CONFIG=1`** once **or** removing the **`loganalyzer_config`** named volume plus a fresh seed if you also need new DB rows.

Docker Compose expands **`${VAR:-default}`** from that `.env` when you run `docker compose` from the repo root. **`.env` is gitignored**.

**Persisted state**

- Database files: compose volume **`loganalyzer_mysql_data`**.
- Generated config: compose volume **`loganalyzer_config`** (`/persist/config.php`, symlinked into the docroot).

Use **`docker compose -f docker/docker-compose.yml down`** to stop without dropping volumes. **`down -v`** deletes named volumes (full reset). **`docker/rebuild-consumer.[sh|bat]`** runs the wizard when **`./.env`** is missing (same behaviour as **`docker/init-env-interactive.[sh|ps1]`**): prompts follow **`docker/env.example`** (**Enter** = keep default; **`*PASSWORD*`** prompts are masked in Bash; PowerShell **7.1+** masks (**`5.x`** / **7.0** echo)). Then **`down -v`** and **`up --build`**.

!!! note "Clean restore from scratch"

    From the repository root:

    - Linux/macOS/Git Bash: `sh docker/rebuild-consumer.sh`
    - Windows cmd: `docker\rebuild-consumer.bat`

### Docker helper scripts

Small shell/cmd helpers beside the Compose files — run them from the **repository root** (paths below are under **`docker/`**).

| Script | When to use |
|--------|--------------|
| [`docker/init-env-interactive.sh`](https://github.com/rsyslog/loganalyzer/blob/master/docker/init-env-interactive.sh) | Create **`.env`** once: prompts for each **`KEY=value`** from **`docker/env.example`** (Bash; **Enter** = default; **`*PASSWORD*`** masked). Skips if **`.env`** already exists. |
| [`docker/init-env-interactive.ps1`](https://github.com/rsyslog/loganalyzer/blob/master/docker/init-env-interactive.ps1) | Same as above on Windows: **`powershell -File docker\init-env-interactive.ps1 -RepoRoot .`**. |
| [`docker/rebuild-consumer.sh`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-consumer.sh) / [`.bat`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-consumer.bat) | **Default stack:** if **`.env`** is missing, runs the interactive wizard (or copies **`env.example`** when Bash is unavailable), then **`docker compose … down -v`** and **`up --build`**. |
| [`docker/rebuild-dev.sh`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-dev.sh) / [`.bat`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-dev.bat) | **Developer stack** clean rebuild (wipe dev volumes, then **`docker-compose.dev.yml up --build`**). |
| [`docker/rebuild-e2e.sh`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-e2e.sh) / [`.bat`](https://github.com/rsyslog/loganalyzer/blob/master/docker/rebuild-e2e.bat) | **Playwright E2E** stack: fresh volumes + Compose E2E + tests (see [`e2e/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md)). |

Bootstrap PHP used by the image (**`docker/write-config.php`**, **`docker/seed-database.php`**, **`docker/env-disk-sources.php`**) runs inside the container — not invoked directly unless you are debugging the Docker image build.

!!! note "Advanced: expose MySQL to the host"

    The **default stack** keeps MySQL internal (`db` hostname inside the Compose network). To reach MySQL from the host temporarily, add **`ports`** on the `db` service (similar to **`docker-compose.dev.yml`**) and align credentials with your `.env`.

### Pre-built image (`ghcr.io`)

Tag pushes matching **`v*`** publish **`ghcr.io/<owner>/<repo>`** (`:latest` and `:v…`) via [`.github/workflows/docker-publish.yml`](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/docker-publish.yml). Use **`web.image`** (and drop **`build`**) in your own override file, or `docker run`, with the same environment variables as the Compose stack.

---

### What you see in the UI (screenshots)

PNG walkthrough assets under **`doc-site/docs/assets/user-guide/`** were captured with **demo sources seeded** (Playwright stack sets **`LOGANALYZER_SEED_SAMPLE_SOURCES=1`**). A **clean consumer install** starts without those sources; add your own under **Administration** or temporarily set **`LOGANALYZER_SEED_SAMPLE_SOURCES=1`** (then decide whether to delete demo sources in the UI).

[![Main view after Docker install](assets/user-guide/index.png)](user-guide/quick-start.md)

[![Sign in](assets/user-guide/login.png)](user-guide/quick-start.md)

Browse the **[Quick start](user-guide/quick-start.md)** tutorial for fuller UI flow (admin centre, statistics, reports).

!!! warning "Hosting note"

    The bundled container runs PHP’s **`php -S`** built‑in HTTP server (`docker/entrypoint.sh`). That is straightforward for desktops and demos; front it with nginx/Apache (+ PHP-FPM) if you expose LogAnalyzer broadly on a network.

---

## Developer workflow (live `src` mount)

Contributor workflow uses **`docker/docker-compose.dev.yml`**: **`src/`** mounts read‑write under `/var/www/html`, fixtures mount at `/samplelogs`, and **`LOGANALYZER_OVERWRITE_CONFIG=1`** regenerates **`src/config.php`** whenever the stack starts so iterative PHP edits stay aligned.

```bash
docker compose -f docker/docker-compose.dev.yml up --build
```

**Clean wipe** developer volumes + caches:

```text
docker compose -f docker/docker-compose.dev.yml down -v
```

(or `sh docker/rebuild-dev.sh` / `docker\rebuild-dev.bat`).

Expose **MySQL** on **`localhost:3306`** (database `loganalyzer`, user/pass `loganalyzer` / `loganalyzer`, unless you customised env variables).

!!! note "When to rebuild the image itself"

    Any change touching **`Dockerfile`** (PHP extensions / entry scripts) needs `docker compose ... up --build`. Pure PHP tweaks only require reloading in the browser when using the developer stack—the bind mount reflects host edits instantly.

---

## Playwright end-to-end CI

Manual run (Playwright exits with the Compose stack):

```bash
docker compose -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
```

Or use **`docker/rebuild-e2e.[sh|bat]`**.

Documentation: [`e2e/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md).

### CI on GitHub Actions

The **[Playwright E2E workflow](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/e2e.yml)** runs on pull requests, pushes to **`main`** or **`master`**, plus manual workflow dispatch (`docker-compose.e2e.yml`).

Failures can optionally open **`CI: playwright e2e failed (…)`** issues on this repository (`issues:write` gated). Fork PRs suppress issue creation (`gh issue create` guarded).

---

## Environment variables (`web`)

| Variable | Typical default compose | Typical dev compose | Meaning |
|-----------|-----------------|--------------|---------|
| `LOGANALYZER_DB_HOST` | `db` | `db` | MySQL hostname (Compose service name) |
| `LOGANALYZER_DB_PORT` | `3306` | `3306` | MySQL port |
| `(MySQL service)` `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD` | via `.env` / compose defaults (`loganalyzer`/…) | same | Passed to **`db`** bootstrap + surfaced as **`LOGANALYZER_DB_*`** in **`web`** |
| `MYSQL_ROOT_PASSWORD` | `loganalyzer_root` | `loganalyzer_root` | Must match Compose healthchecks + seed script credential |
| `LOGANALYZER_TABLE_PREFIX` | `logcon_` | `logcon_` | Table prefix |
| `LOGANALYZER_ADMIN_USER` | `admin` (+ `.env` overrides) | `admin` (+ `.env` overrides) | First administrator |
| `LOGANALYZER_ADMIN_PASSWORD` | `loganalyzer` (+ `.env` overrides) | `loganalyzer` (+ `.env` overrides) | Override before exposing the host beyond trusted networks |
| `LOGANALYZER_LOGIN_REQUIRED` | `0` | `0` | `1` ⇒ force login globally |
| `LOGANALYZER_SAMPLE_LOG` / `LOGANALYZER_SAMPLE_EVENTREPORTER` | used when demo seeding | same | Disk paths consulted only while **`LOGANALYZER_SEED_SAMPLE_SOURCES` ≠ off list** |
| `LOGANALYZER_SEED_SAMPLE_SOURCES` | `0` (clean install via compose defaults) | unset / `1` | `0`/`false`/`no`/`off` skips demo sources; other values seed `/samplelogs` fixtures |
| `LOGANALYZER_SKIP_SEED` | unset | unset | `1` skips DB seed bootstrap |
| `LOGANALYZER_OVERWRITE_CONFIG` | `0` | `1` | Regenerate **`config.php`** every start versus retain persisted file |
| `LOGANALYZER_DISK_SOURCE_PATHS` | unset (`.env`) | unset (`.env`) | Comma-separated container paths → syslog disk rows on **first seed** (`basename` → name) |
| `LOGANALYZER_DISK_SOURCES` | unset (`.env`) | unset (`.env`) | Advanced: records separated by `;;`, fields separated by `|` — name, path, **`syslog`** or **`event`**, optional description (description may include further `|`s) |
| `LOGANALYZER_DISK_ALLOWED_EXTRA` | unset (`.env`) | unset (`.env`) | Extra **`DiskAllowed`** directory prefixes (comma-separated); dirs of env paths are included automatically plus **`/var/log/`**, **`/samplelogs/`** |
| `LOGANALYZER_CONFIG_PATH` | `/persist/config.php` *(typical)* | unset | Persist config **outside container layers** |

---

## PHPUnit

```bash
composer install && composer test
```

Integration suites stay disabled unless **`LOGANALYZER_INTEGRATION=1`** aligns DB env settings with reachable MySQL endpoints.
