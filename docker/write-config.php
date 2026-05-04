<?php
declare(strict_types=1);

/**
 * Generate config.php from config.sample.php for Docker / CI.
 * Output path: LOGANALYZER_CONFIG_PATH or LOGANALYZER_DOCROOT/config.php.
 */

$docroot = getenv('LOGANALYZER_DOCROOT') ?: '/var/www/html';
$samplePath = $docroot . '/include/config.sample.php';
$configPath = getenv('LOGANALYZER_CONFIG_PATH');
$configPath = ($configPath !== false && $configPath !== '')
    ? $configPath
    : $docroot . '/config.php';

if (!is_readable($samplePath)) {
    fwrite(STDERR, "Cannot read: $samplePath\n");
    exit(1);
}

$dbHost = getenv('LOGANALYZER_DB_HOST') ?: 'db';
$dbPort = (int) (getenv('LOGANALYZER_DB_PORT') ?: 3306);
$dbName = getenv('LOGANALYZER_DB_NAME') ?: 'loganalyzer';
$dbUser = getenv('LOGANALYZER_DB_USER') ?: 'loganalyzer';
$dbPass = getenv('LOGANALYZER_DB_PASSWORD') ?: 'loganalyzer';
$pref = getenv('LOGANALYZER_TABLE_PREFIX') ?: 'logcon_';

$loginReq = getenv('LOGANALYZER_LOGIN_REQUIRED') !== '0' ? 'true' : 'false';

require_once __DIR__ . '/env-disk-sources.php';

$body = file_get_contents($samplePath);
if ($body === false) {
    fwrite(STDERR, "Failed reading sample\n");
    exit(1);
}

$replacements = [
    '$CFG[\'UserDBEnabled\'] = false;' => '$CFG[\'UserDBEnabled\'] = true;',
    '$CFG[\'UserDBServer\'] = "";' => '$CFG[\'UserDBServer\'] = ' . var_export($dbHost, true) . ';',
    '$CFG[\'UserDBName\'] = "";' => '$CFG[\'UserDBName\'] = ' . var_export($dbName, true) . ';',
    '$CFG[\'UserDBPref\'] = "";' => '$CFG[\'UserDBPref\'] = ' . var_export($pref, true) . ';',
    '$CFG[\'UserDBUser\'] = "";' => '$CFG[\'UserDBUser\'] = ' . var_export($dbUser, true) . ';',
    '$CFG[\'UserDBPass\'] = "";' => '$CFG[\'UserDBPass\'] = ' . var_export($dbPass, true) . ';',
    '$CFG[\'UserDBLoginRequired\'] = false;' => '$CFG[\'UserDBLoginRequired\'] = ' . $loginReq . ';',
];
foreach ($replacements as $from => $to) {
    if (strpos($body, $from) === false) {
        fwrite(STDERR, "Expected snippet not found in sample: $from\n");
        exit(1);
    }
    $body = str_replace($from, $to, $body);
}

$c = 0;
$body = str_replace('$CFG[\'UserDBPort\'] = 3306;', '$CFG[\'UserDBPort\'] = ' . $dbPort . ';', $body, $c);
if ($c === 0) {
    fwrite(STDERR, "Could not set UserDBPort\n");
    exit(1);
}

$c = 0;
$needle = '$CFG[\'DiskAllowed\'][] = "/var/log/"; ';
$suffix = '';
foreach (loganalyzer_disk_allowed_directories() as $dir) {
    if (rtrim((string) $dir, '/') === '/var/log') {
        continue;
    }
    $suffix .= "\n" . '$CFG[\'DiskAllowed\'][] = ' . var_export($dir, true) . '; ';
}
$body = str_replace($needle, $needle . $suffix, $body, $c);
if ($c === 0) {
    fwrite(STDERR, "Could not extend DiskAllowed (/var/log/ marker missing)\n");
    exit(1);
}

$banner = "\n// --- Docker auto-generated (docker/write-config.php) ---\n";

$c = 0;
$body = str_replace('// --- %Insert Source Here%', $banner . '// Sources are created in MySQL by docker/seed-database.php', $body, $c);
if ($c === 0) {
    fwrite(STDERR, "Could not patch Insert Source placeholder\n");
    exit(1);
}

$parent = dirname($configPath);
if ($parent !== '' && $parent !== '.' && !is_dir($parent)) {
    if (!@mkdir($parent, 0755, true) && !is_dir($parent)) {
        fwrite(STDERR, "Cannot create directory: $parent\n");
        exit(1);
    }
}

if (file_put_contents($configPath, $body) === false) {
    fwrite(STDERR, "Cannot write $configPath\n");
    exit(1);
}

fwrite(STDOUT, "Wrote $configPath\n");
