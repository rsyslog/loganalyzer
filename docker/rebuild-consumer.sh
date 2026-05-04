#!/usr/bin/env sh
# Consumer stack: prompts for .env when missing (see docker/init-env-interactive.sh), then clean rebuild + up.
set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
if [ ! -f "$ROOT/.env" ]; then
	echo >&2 ''
	echo >&2 '[.env missing] Starting interactive setup (defaults from docker/env.example)...'
	if command -v bash >/dev/null 2>&1; then
		bash "$ROOT/docker/init-env-interactive.sh" "$ROOT"
	else
		echo >&2 'bash not found on PATH; copying docker/env.example → .env non-interactively. Install Git Bash or bash, or create .env manually.' >&2
		cp "$ROOT/docker/env.example" "$ROOT/.env"
	fi
fi
docker compose --project-directory "$ROOT" -f docker/docker-compose.yml down -v
docker compose --project-directory "$ROOT" -f docker/docker-compose.yml up --build
