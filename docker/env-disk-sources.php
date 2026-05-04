<?php
declare(strict_types=1);

/**
 * ENV-driven disk log sources / DiskAllowed prefixes for Docker (write-config + seed-database).
 */

/**
 * Parsed disk specs for seed-database.php inserts.
 *
 * @return list<array{name:string,path:string,kind:string,description:string}>
 */
function loganalyzer_disk_source_specs_from_env(): array
{
    $out = [];
    $seen = [];

    $pathsEnv = getenv('LOGANALYZER_DISK_SOURCE_PATHS');
    if ($pathsEnv !== false && trim($pathsEnv) !== '') {
        foreach (explode(',', $pathsEnv) as $raw) {
            $p = trim($raw);
            if ($p === '') {
                continue;
            }
            $pNorm = str_replace('\\', '/', $p);
            if (isset($seen[$pNorm])) {
                continue;
            }
            $seen[$pNorm] = true;
            $name = basename($pNorm);
            if ($name === '' || $name === '/') {
                $name = $pNorm;
            }
            $out[] = ['name' => $name, 'path' => $pNorm, 'kind' => 'syslog', 'description' => ''];
        }
    }

    $specEnv = getenv('LOGANALYZER_DISK_SOURCES');
    if ($specEnv !== false && trim($specEnv) !== '') {
        foreach (explode(';;', $specEnv) as $segment) {
            $segment = trim($segment);
            if ($segment === '') {
                continue;
            }
            // Limit splits so descriptions may contain '|' (e.g. Auth | PAM notes).
            $parts = array_map('trim', explode('|', $segment, 4));
            if (count($parts) < 3) {
                fwrite(STDERR, "Skipping LOGANALYZER_DISK_SOURCES record (need name|path|syslog|optional description): {$segment}\n");

                continue;
            }
            $name = $parts[0];
            $path = $parts[1];
            $kind = strtolower($parts[2]);
            $desc = $parts[3] ?? '';
            if ($name === '' || $path === '') {
                continue;
            }
            if (!in_array($kind, ['syslog', 'event'], true)) {
                fwrite(STDERR, "Skipping disk source '$name': LOGANALYZER_DISK_SOURCES kind must be 'syslog' or 'event' (got '{$parts[2]}').\n");

                continue;
            }
            $pathNorm = str_replace('\\', '/', $path);
            if (isset($seen[$pathNorm])) {
                continue;
            }
            $seen[$pathNorm] = true;
            $out[] = ['name' => $name, 'path' => $pathNorm, 'kind' => $kind, 'description' => $desc];
        }
    }

    return $out;
}

/**
 * Paths LogAnalyzer PHP may scan for disk sources (trailing slashes).
 *
 * @return list<string>
 */
function loganalyzer_disk_allowed_directories(): array
{
    $uniq = ['/var/log/' => true, '/samplelogs/' => true];

    foreach (loganalyzer_disk_source_specs_from_env() as $s) {
        $dir = dirname($s['path']);
        $dir = str_replace('\\', '/', $dir);
        if ($dir === '' || $dir === '.' || $dir === '/') {
            continue;
        }
        $uniq[rtrim($dir, '/') . '/'] = true;
    }

    $extra = getenv('LOGANALYZER_DISK_ALLOWED_EXTRA');
    if ($extra !== false && trim($extra) !== '') {
        foreach (explode(',', $extra) as $raw) {
            $dir = trim($raw);
            if ($dir === '') {
                continue;
            }
            $dir = str_replace('\\', '/', $dir);
            $uniq[rtrim($dir, '/') . '/'] = true;
        }
    }

    return array_keys($uniq);
}
