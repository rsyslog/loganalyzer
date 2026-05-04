# Playwright E2E

Runs against a Docker stack (MySQL + PHP + seeded app).

## Local

From repo root (requires Docker):

```bash
docker compose --project-directory . -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
```

## Interactive (against dev stack — bind-mounted `src`)

```bash
docker compose --project-directory . -f docker/docker-compose.dev.yml up -d
cd e2e
npm install
```

Set `PLAYWRIGHT_BASE_URL` to your app (default in config is `http://127.0.0.1:8080`), then:

```bash
npx playwright test
```

PowerShell:

```text
$env:PLAYWRIGHT_BASE_URL="http://127.0.0.1:8080"
npx playwright test
```

Screenshots (when tests run) are written under `e2e/test-results/screenshots/` (smoke flow) and `e2e/test-results/ci-visual/` ([`tests/ci-regression.spec.ts`](tests/ci-regression.spec.ts): admin panel + syslog grid + open cell “Available searches” menu). Both trees are uploaded with the **`playwright-e2e`** GitHub Actions artifact.

## Handbook screenshots (MkDocs)

The [LogAnalyzer handbook](https://rsyslog.github.io/loganalyzer/) embeds PNGs from `doc-site/docs/assets/user-guide/`. Regenerate them with Playwright after UI changes.

From the repository root, run the E2E stack (runs all specs, including the handbook capture):

```bash
docker compose --project-directory . -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
```

[`tests/handbook-screenshots.spec.ts`](tests/handbook-screenshots.spec.ts) writes **`doc-site/docs/assets/user-guide/*.png`** (the Playwright service mounts that directory at `HANDBOOK_USER_GUIDE_ASSETS`; see `docker/docker-compose.e2e.yml`). Commit updated PNGs together with doc changes.

**Local dev stack** (`docker-compose.dev.yml`) with Playwright on the host:

```bash
cd e2e
npm install
# PowerShell
$env:PLAYWRIGHT_BASE_URL="http://127.0.0.1:8080"
npx playwright test tests/handbook-screenshots.spec.ts
```

```bash
# bash
PLAYWRIGHT_BASE_URL=http://127.0.0.1:8080 npx playwright test tests/handbook-screenshots.spec.ts
```
