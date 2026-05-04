#!/usr/bin/env sh
# Wipe MySQL volume, rebuild images, and start the dev stack (bind-mounted src).
set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
docker compose --project-directory "$ROOT" -f docker/docker-compose.dev.yml down -v
docker compose --project-directory "$ROOT" -f docker/docker-compose.dev.yml up --build
