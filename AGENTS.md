# LogAnalyzer agent / contributor notes

## Docker development

From the repository root:

```bash
docker compose -f docker/docker-compose.yml up --build
```

**Clean rebuild** (drops MySQL data and re-seeds; `src/config.php` is regenerated on web container start):

- Linux/macOS/Git Bash: `sh docker/rebuild-dev.sh`
- Windows (cmd): `docker\rebuild-dev.bat`

**E2E clean run** (fresh E2E DB + Playwright):

- `sh docker/rebuild-e2e.sh` or `docker\rebuild-e2e.bat`

- **Web UI:** http://localhost:8080/
- **MySQL:** `localhost:3306`, database `loganalyzer`, user `loganalyzer` / `loganalyzer`, root password `loganalyzer_root`
- **Admin login:** `admin` / `pass` (seeded once per fresh MySQL volume)
- **Sample logs** are mounted read-only at `/samplelogs` from [tests/fixtures/samplelogs](tests/fixtures/samplelogs). The default seeded source reads `sampledata_syslog.log`.

Reset the database and config: `docker compose -f docker/docker-compose.yml down -v` then `up --build` again.

### Environment variables (web container)

| Variable | Default | Purpose |
|----------|---------|---------|
| `LOGANALYZER_DB_HOST` | `db` | MySQL host |
| `LOGANALYZER_DB_PORT` | `3306` | MySQL port |
| `LOGANALYZER_DB_NAME` | `loganalyzer` | Database name |
| `LOGANALYZER_DB_USER` | `loganalyzer` | App DB user |
| `LOGANALYZER_DB_PASSWORD` | `loganalyzer` | App DB password |
| `MYSQL_ROOT_PASSWORD` | `loganalyzer_root` | Root (used by seed script only) |
| `LOGANALYZER_TABLE_PREFIX` | `logcon_` | Table prefix |
| `LOGANALYZER_ADMIN_USER` | `admin` | First admin username |
| `LOGANALYZER_ADMIN_PASSWORD` | `pass` | First admin password |
| `LOGANALYZER_LOGIN_REQUIRED` | `0` | Set `1` to force login on all pages |
| `LOGANALYZER_SAMPLE_LOG` | `/samplelogs/sampledata_syslog.log` | First seeded disk source (syslog) |
| `LOGANALYZER_SAMPLE_EVENTREPORTER` | `/samplelogs/EventReporter.log` | Second seeded disk source (Windows EventReporter / EvntSLog) |
| `LOGANALYZER_SKIP_SEED` | unset | Set `1` to skip MySQL seed |
| `LOGANALYZER_OVERWRITE_CONFIG` | `1` | Overwrite `src/config.php` on each start in dev |

## PHPUnit

```bash
composer install
composer test
```

MySQL-dependent integration tests are skipped unless `LOGANALYZER_INTEGRATION=1` and DB env vars match Docker defaults or your own host.

## E2E (Playwright)

See [e2e/README.md](e2e/README.md) and `docker/docker-compose.e2e.yml`.
