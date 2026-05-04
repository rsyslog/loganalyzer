#!/bin/sh
set -e
cd /var/www/html

DB_HOST="${LOGANALYZER_DB_HOST:-db}"
DB_PORT="${LOGANALYZER_DB_PORT:-3306}"
DB_NAME="${LOGANALYZER_DB_NAME:-loganalyzer}"
DB_USER="${LOGANALYZER_DB_USER:-loganalyzer}"
DB_PASS="${LOGANALYZER_DB_PASSWORD:-loganalyzer}"
DB_ROOT_PASS="${MYSQL_ROOT_PASSWORD:-loganalyzer_root}"
TABLE_PREFIX="${LOGANALYZER_TABLE_PREFIX:-logcon_}"
ADMIN_USER="${LOGANALYZER_ADMIN_USER:-admin}"
ADMIN_PASS="${LOGANALYZER_ADMIN_PASSWORD:-loganalyzer}"
SAMPLE_LOG="${LOGANALYZER_SAMPLE_LOG:-/samplelogs/sampledata_syslog.log}"
SAMPLE_EVENTREPORTER="${LOGANALYZER_SAMPLE_EVENTREPORTER:-/samplelogs/EventReporter.log}"

export LOGANALYZER_DB_HOST="$DB_HOST"
export LOGANALYZER_DB_PORT="$DB_PORT"
export LOGANALYZER_DB_NAME="$DB_NAME"
export LOGANALYZER_DB_USER="$DB_USER"
export LOGANALYZER_DB_PASSWORD="$DB_PASS"
export LOGANALYZER_TABLE_PREFIX="$TABLE_PREFIX"
export LOGANALYZER_ADMIN_USER="$ADMIN_USER"
export LOGANALYZER_ADMIN_PASSWORD="$ADMIN_PASS"
export LOGANALYZER_SAMPLE_LOG="$SAMPLE_LOG"
export LOGANALYZER_SAMPLE_EVENTREPORTER="$SAMPLE_EVENTREPORTER"
export MYSQL_ROOT_PASSWORD="$DB_ROOT_PASS"

export LOGANALYZER_DOCROOT="${LOGANALYZER_DOCROOT:-/var/www/html}"

DOC_CONFIG="${LOGANALYZER_DOCROOT}/config.php"
RESOLVED_CONFIG="${LOGANALYZER_CONFIG_PATH:-$DOC_CONFIG}"

if [ ! -f "$RESOLVED_CONFIG" ] || [ "${LOGANALYZER_OVERWRITE_CONFIG:-0}" = "1" ]; then
  php /usr/local/share/loganalyzer/write-config.php
  echo "Wrote config for LogAnalyzer (UserDB + disk allow /samplelogs)."
fi

# When config lives outside the docroot (persisted volume), re-link each start — new containers lose the symlink.
if [ -n "${LOGANALYZER_CONFIG_PATH:-}" ] && [ "$LOGANALYZER_CONFIG_PATH" != "$DOC_CONFIG" ]; then
  mkdir -p "$(dirname "$LOGANALYZER_CONFIG_PATH")"
  rm -f "$DOC_CONFIG"
  ln -sfn "$LOGANALYZER_CONFIG_PATH" "$DOC_CONFIG"
fi

echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
i=0
while [ "$i" -lt 90 ]; do
  if php -r "\$m=@mysqli_connect('${DB_HOST}','root','${DB_ROOT_PASS}','',${DB_PORT}); if(\$m){mysqli_close(\$m);exit(0);}exit(1);" 2>/dev/null; then
    echo "MySQL is up."
    break
  fi
  i=$((i + 1))
  sleep 1
done

if ! php -r "\$m=@mysqli_connect('${DB_HOST}','root','${DB_ROOT_PASS}','',${DB_PORT}); if(\$m){mysqli_close(\$m);exit(0);}exit(1);" 2>/dev/null; then
  echo "ERROR: MySQL not reachable as root after 90s."
  exit 1
fi

if [ "${LOGANALYZER_SKIP_SEED:-0}" != "1" ]; then
  echo "Seeding database (if empty)..."
  if ! php /usr/local/share/loganalyzer/seed-database.php; then
    echo "ERROR: seed-database.php failed."
    exit 1
  fi
fi

exec php -S 0.0.0.0:8080 -t /var/www/html
