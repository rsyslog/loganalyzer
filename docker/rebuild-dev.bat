@echo off
REM Wipe MySQL volume, rebuild images, and start the dev stack (foreground). Run from anywhere.
setlocal
cd /d "%~dp0\.."
docker compose -f docker/docker-compose.yml down -v
docker compose -f docker/docker-compose.yml up --build
endlocal
