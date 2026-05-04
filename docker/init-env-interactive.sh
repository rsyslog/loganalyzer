#!/usr/bin/env bash
# Create repo-root .env interactively using docker/env.example for defaults / comments.
set -euo pipefail

REPO_ROOT="${1:-$(cd "$(dirname "$0")/.." && pwd)}"
EXAMPLE="$REPO_ROOT/docker/env.example"
OUT="$REPO_ROOT/.env"

if [[ -f "$OUT" ]]; then
	echo ".env already exists: $OUT" >&2
	exit 0
fi
if [[ ! -r "$EXAMPLE" ]]; then
	echo "Missing or unreadable: $EXAMPLE" >&2
	exit 1
fi

echo >&2 ''
echo >&2 '[.env missing] Press Enter at each prompt to keep the default from docker/env.example.'
echo >&2 ''

if command -v mktemp >/dev/null 2>&1 && tmp="$(mktemp "${TMPDIR:-/tmp}/loganalyzer-env-init.XXXXXX" 2>/dev/null)"; then
	:
else
	tmp="${OUT}.tmp.$$.${RANDOM:-0}"
fi
trap 'rm -f "$tmp"' EXIT
: >"$tmp"

# Read template from fd 3 so stdin stays the TTY (otherwise read would consume env.example lines).
while IFS= read -r -u 3 __line || [[ -n $__line ]]; do
	line="${__line%$'\r'}"
	if [[ $line =~ ^[[:space:]]*# ]] || [[ -z ${line//[[:space:]]/} ]]; then
		printf '%s\n' "$line" >>"$tmp"
		continue
	fi
	if [[ $line =~ ^([A-Za-z_][A-Za-z0-9_]*)=(.*)$ ]]; then
		key="${BASH_REMATCH[1]}"
		def="${BASH_REMATCH[2]}"
		val=""
		if [[ $key == *PASSWORD* ]]; then
			read -rsp "${key} (Enter keeps default '${def}'): " val || true
			echo >&2
		else
			read -rp "${key} [${def}]: " val || true
		fi
		[[ -z $val ]] && val="$def"
		printf '%s=%s\n' "$key" "$val" >>"$tmp"
	else
		printf '%s\n' "$line" >>"$tmp"
	fi

done 3<"$EXAMPLE"

mv "$tmp" "$OUT"
trap - EXIT
echo >&2 ''
echo >&2 "[.env written] $OUT"
