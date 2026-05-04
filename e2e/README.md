# Playwright E2E

Runs against a Docker stack (MySQL + PHP + seeded app).

## Local

From repo root (requires Docker):

```bash
docker compose -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
```

## Interactive (against dev stack)

```bash
docker compose -f docker/docker-compose.yml up -d
cd e2e
npm install
set PLAYWRIGHT_BASE_URL=http://127.0.0.1:8080
npx playwright test
```

Screenshots (when tests run) are written under `e2e/test-results/screenshots/`.
