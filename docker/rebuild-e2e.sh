#!/usr/bin/env sh
# Wipe E2E MySQL volume, rebuild, run Playwright stack until tests finish. Run from repo root via path or cwd.
set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
docker compose -f docker/docker-compose.e2e.yml down -v
docker compose -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
