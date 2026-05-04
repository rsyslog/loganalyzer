#!/usr/bin/env sh
# Wipe MySQL volume, rebuild images, and start the dev stack (foreground). Run from anywhere.
set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
docker compose -f docker/docker-compose.yml down -v
docker compose -f docker/docker-compose.yml up --build
