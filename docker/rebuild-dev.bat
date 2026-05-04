@echo off
setlocal EnableExtensions
set "ROOT=%~dp0.."
cd /d "%ROOT%"
docker compose --project-directory "%ROOT%" -f docker/docker-compose.dev.yml down -v
docker compose --project-directory "%ROOT%" -f docker/docker-compose.dev.yml up --build
endlocal
