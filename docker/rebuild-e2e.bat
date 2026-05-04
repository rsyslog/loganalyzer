@echo off
REM Wipe E2E MySQL volume, rebuild, run Playwright stack until tests finish.
setlocal
cd /d "%~dp0\.."
docker compose -f docker/docker-compose.e2e.yml down -v
docker compose -f docker/docker-compose.e2e.yml up --build --abort-on-container-exit --exit-code-from playwright
endlocal
