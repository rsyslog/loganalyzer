@echo off
setlocal EnableExtensions
set "ROOT=%~dp0.."
cd /d "%ROOT%"

if not exist "%ROOT%\.env" (
    echo.
    echo [.env missing] Starting interactive setup ^(defaults from docker\env.example^)...
    powershell -NoProfile -ExecutionPolicy Bypass -File "%ROOT%\docker\init-env-interactive.ps1" -RepoRoot "%ROOT%"
    if errorlevel 1 (
        echo ERROR: Interactive .env setup failed.
        exit /b 1
    )
)

docker compose -f docker/docker-compose.yml down -v
docker compose -f docker/docker-compose.yml up --build
