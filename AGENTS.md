# LogAnalyzer agent / contributor notes

Handbook (expanded): **`doc-site/docs/docker-install.md`** (Compose users), **`docker-develop.md`** (dev / E2E / CI / env table).

## Docker stacks

| Compose file | Use case |
|----------------|----------|
| [`docker/docker-compose.yml`](docker/docker-compose.yml) | **Default/local install.** Application code is baked into the image. By default (**`LOGANALYZER_SEED_SAMPLE_SOURCES=0`**) MySQL seed creates **schema + admin only** — no bundled disk sources unless you set **`LOGANALYZER_DISK_*`** (first seed) or add sources in Administration. Fixtures remain under **`/samplelogs`** in the image so you can still opt into demo sources. MySQL (`loganalyzer_mysql_data`) and `config.php` (`loganalyzer_config`) use volumes. **`LOGANALYZER_OVERWRITE_CONFIG=0`**. |
| [`docker/docker-compose.dev.yml`](docker/docker-compose.dev.yml) | **Development.** Bind-mount [`src`](src) and [`tests/fixtures/samplelogs`](tests/fixtures/samplelogs); `LOGANALYZER_OVERWRITE_CONFIG=1` regenerates `src/config.php` on each container start so editor + DB drift is minimized. Host **MySQL** port `3306` is exposed like before. Separate volume label `loganalyzer_dev_mysql_data`. |

Build context for images is **repository root**; see [`docker/Dockerfile`](docker/Dockerfile) (multi-stage: runtime + baked `src`; optional fixture tree at `/samplelogs`). Install walkthrough (**`.env`**, volumes, **`LOGANALYZER_DISK_*`**, reset): [`docker/README.md`](docker/README.md).

Credentials and bootstrap flags normally come **at runtime**, not **`docker build`**: copy [`docker/env.example`](docker/env.example) to **`.env` in the repository root** (same folder as `README.md`) and adjust `LOGANALYZER_ADMIN_*`, `MYSQL_*`, `LOGANALYZER_SEED_SAMPLE_SOURCES`, and optional **`LOGANALYZER_DISK_*`** before **`docker compose`**. Invoke **`docker compose --project-directory . -f docker/docker-compose.yml …`** from the repo root (or rely on **`docker/rebuild-*.sh` / `.bat`**, which set **`--project-directory`** explicitly) so the root **`.env`** is loaded for **`${VAR}`** interpolation — otherwise Compose’s default project directory follows the **`docker/`** compose path and skips that file.

**Real disk sources at first seed:** set **`LOGANALYZER_DISK_SOURCE_PATHS`** (comma-separated file paths *inside the web container*) and/or **`LOGANALYZER_DISK_SOURCES`** (see comments in [`docker/env.example`](docker/env.example)). Bind-mount host directories on the **`web`** service (commented example in [`docker/docker-compose.yml`](docker/docker-compose.yml)). `docker/write-config.php` extends **`DiskAllowed`** from those paths plus **`LOGANALYZER_DISK_ALLOWED_EXTRA`**. After the first boot, updating disk paths in `.env` requires either **`LOGANALYZER_OVERWRITE_CONFIG=1`** once or wiping the **`loganalyzer_config`** volume so **`config.php`** regenerates with the new prefixes (and usually **`docker compose … down -v`** if you must re-run the seed for new rows).

```bash
docker compose --project-directory . -f docker/docker-compose.yml up --build
```

**Clean reset** (wipe DB + persisted config):

- Linux/macOS/Git Bash: `sh docker/rebuild-consumer.sh`
- Windows (cmd): `docker\rebuild-consumer.bat`

Those scripts invoke **`docker/init-env-interactive.sh`** (POSIX path requires **bash** on `PATH`; if none, they fall back to copying **`docker/env.example`**) or **`docker/init-env-interactive.ps1`** (called from **`.bat`**) whenever **`.env`** is absent. Each **`KEY=value`** line from **`docker/env.example`** is prompted with **Enter = default**. **`*PASSWORD*`** prompts are masked in Bash and PowerShell **`7.1+`**, echoed in **`5.x`** / **`7.0`**. **`docker compose`** is run with **`--project-directory`** set to the repo root (**[`docker/rebuild-consumer.sh`](docker/rebuild-consumer.sh)** / **[`.bat`](docker/rebuild-consumer.bat)**) so **`${VAR}`** interpolation loads **`.env`** from the repository root even though **`-f docker/docker-compose.yml`** lives under **`docker/`**.

### Developer stack

```bash
docker compose --project-directory . -f docker/docker-compose.dev.yml up --build
```

**Clean rebuild** (`src/config.php` + MySQL wiped for this stack):

- Linux/macOS/Git Bash: `sh docker/rebuild-dev.sh`
- Windows (cmd): `docker\rebuild-dev.bat`

### E2E clean run (Playwright)

- `sh docker/rebuild-e2e.sh` or `docker\rebuild-e2e.bat`

Operational details mirror the handbook: **`http://localhost:8080/`**, **fresh admin** defaults to **`LOGANALYZER_ADMIN_*`** from Compose or `.env` (compose defaults **`admin`** / **`loganalyzer`** if unset).

Developer stack exposes **MySQL** on **`localhost:3306`**.

The default consumer compose sets **`LOGANALYZER_SEED_SAMPLE_SOURCES=0`**, giving a **clean first install**: schema + views + admin only (add sources manually). Fixtures still exist under **`/samplelogs`** if you bump that env var to **`1`**. Contributor/E2E stacks omit it or pin **`1`**, restoring the seeded demo disk sources Playwright relies on.

Reset the developer stack volumes: `docker compose --project-directory . -f docker/docker-compose.dev.yml down -v`.

### Publish image (maintainers)

Pushing tag **`v*`** triggers [`.github/workflows/docker-publish.yml`](.github/workflows/docker-publish.yml): image **`ghcr.io/<owner>/<repo>`** tagged with **`v…`** and **`latest`**.

To pull from GHCR, point **`web.image`** at **`ghcr.io/<owner>/<repo>:<tag>`** (matching the publish workflow outputs) alongside appropriate env/`LOGANALYZER_CONFIG_PATH` settings, instead of **`build`**.

### Environment variables (web container)

| Variable | Typical default stack | Typical dev stack | Purpose |
|------------------|----------------|-------------------|---------|
| `LOGANALYZER_DB_HOST` | `db` | `db` | MySQL host |
| `LOGANALYZER_DB_PORT` | `3306` | `3306` | MySQL port |
| `(MySQL service)` `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD` | `loganalyzer`/… compose defaults (`${…:-…}` pulls from repo-root **`.env`**) | same | Passed to **`db`** bootstrap + surfaced as **`LOGANALYZER_DB_*`** inside **`web`** |
| `MYSQL_ROOT_PASSWORD` | `loganalyzer_root` | `loganalyzer_root` | Seed script credential; keep synced with Compose healthchecks (`CMD-SHELL` uses **`$$MYSQL_ROOT_PASSWORD`**) |
| `LOGANALYZER_TABLE_PREFIX` | `logcon_` | `logcon_` | Table prefix |
| `LOGANALYZER_ADMIN_USER` | compose default `admin`; override `.env` | compose default `.env`/Compose | First admin username |
| `LOGANALYZER_ADMIN_PASSWORD` | compose default `loganalyzer`; override `.env` | same | First admin password (**customise before sharing host**) |
| `LOGANALYZER_LOGIN_REQUIRED` | `0` | `0` | Set `1` to force login on all pages |
| `LOGANALYZER_SAMPLE_LOG` / `LOGANALYZER_SAMPLE_EVENTREPORTER` | paths only when demo seeding | same | Used when **`LOGANALYZER_SEED_SAMPLE_SOURCES` enables demo disk sources |
| `LOGANALYZER_SEED_SAMPLE_SOURCES` | `0` (compose default, clean install) | unset / `1` | `0`/`false`/`no`/`off` skips demo sources; anything else seeds fixtures |
| `LOGANALYZER_SKIP_SEED` | unset | unset | Set `1` to skip MySQL seed |
| `LOGANALYZER_OVERWRITE_CONFIG` | `0` | `1` | Regenerate `config.php` on start when `1`; default stack persists config on the `/persist` volume |
| `LOGANALYZER_DISK_SOURCE_PATHS` | `.env`/unset | `.env`/unset | Comma-separated container paths → syslog disk sources seeded on first DB init (`basename` → display name) |
| `LOGANALYZER_DISK_SOURCES` | `.env`/unset | `.env`/unset | Records separated by `;;`, fields separated by `|` (see `docker/env.example`): name, path, kind `syslog` or `event`, optional description (description may contain `|` — only the first three delimiters split fields); deduped with `PATHS` by path |
| `LOGANALYZER_DISK_ALLOWED_EXTRA` | `.env`/unset | `.env`/unset | Extra `DiskAllowed` directory prefixes (comma-separated); auto-includes dirs of env disk paths plus `/var/log/` and `/samplelogs/` |
| `LOGANALYZER_CONFIG_PATH` | `/persist/config.php` | unset | When set (and not equal to `LOGANALYZER_DOCROOT/config.php`), config is stored there and symlinked into the docroot |

## Releases & ChangeLog

- Root **[`ChangeLog`](ChangeLog)** (dash-delimited **`Version X.Y.Z, YYYY-MM-DD`** blocks) is the canonical prose for shipped work. GitHub Releases paste the matching block plus auto-generated comparison notes via [`.github/scripts/build_release_body.py`](.github/scripts/build_release_body.py)—**do not insert extra `--------------------------------------------------------------------------------` lines inside one version section** or the extractor stops at the wrong boundary.
- Prefer **detailed, themed bullets** (cluster by area; sub-bullets for specifics) so operators and reviewers can skim without spelunking `git log`. PR/`#NN` references are welcome for traceability.
- Git history can land broad merges (e.g. Docker/docs) *after* a prose-only changelog commit; when cutting a release, reconcile **`ChangeLog` text** with the **actual tree** you are tagging so operators see the full delta, not only the last merge on `main`.
- **Before pushing tag `vX.Y.Z`** (workflows: [`.github/workflows/release-on-tag.yml`](.github/workflows/release-on-tag.yml), [`.github/workflows/docker-publish.yml`](.github/workflows/docker-publish.yml)):
  - Add or extend the **`Version X.Y.Z`** block in **`ChangeLog`** with everything user-facing in that release.
  - Set **BUILDNUMBER** in [`src/include/functions_common.php`](src/include/functions_common.php) to the **same semver** (`5.0.1` style, no `v`) as the changelog heading the release script will match.
  - Confirm **`vX.Y.Z`** points at (or **`git tag --points-at <commit>`** resolves to) the commit that contains both the changelog entry and **`BUILDNUMBER`** bump—annotated vs lightweight tags should both peel to that tree when you publish.

## PHPUnit

```bash
composer install
composer test
```

MySQL-dependent integration tests are skipped unless `LOGANALYZER_INTEGRATION=1` and DB env vars match Docker defaults or your own host.

## E2E (Playwright)

See [`e2e/README.md`](https://github.com/rsyslog/loganalyzer/blob/master/e2e/README.md) and `docker/docker-compose.e2e.yml`.

### CI on GitHub Actions

The [Playwright E2E workflow](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/e2e.yml) runs on pull requests, pushes to `main` or `master`, and manual dispatch. It builds and runs the same Docker Compose stack as locally (`docker/docker-compose.e2e.yml`), then executes Playwright.

If the job **fails**, the workflow can open a **GitHub issue** titled like `CI: playwright e2e failed (…)` with a link to the run and a **tail of the Docker/compose log**. That only happens when the workflow has `issues: write` and the run is for **this repository** (for pull requests from forks, issue creation is skipped). See the “Open issue on failure” step in [`.github/workflows/e2e.yml`](https://github.com/rsyslog/loganalyzer/blob/master/.github/workflows/e2e.yml) (`gh issue create`).
