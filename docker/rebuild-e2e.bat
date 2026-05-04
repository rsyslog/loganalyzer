@echo off
REM Wipe E2E MySQL volume, rebuild, run Playwright stack until tests finish.
setlocal EnableExtensions
set "ROOT=%~dp0.."
cd /d "%ROOT%"
docker compose --project-directory "%ROOT%" -f docker/docker-compose.e2e.yml down -v
docker compose --project-directory "%ROOT%" -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
endlocal
