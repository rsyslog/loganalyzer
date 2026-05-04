<?php
declare(strict_types=1);

/**
 * One-time MySQL seed: schema + migrations + default view/source + admin user.
 * Default statistic charts come from bundled config.sample.php (`Chart1`–`Chart4`); loading DB rows with the same display names while keeping those keys would duplicate entries in `$CFG['Charts']`.
 * Idempotent: skips if logcon_config.database_installedversion is already set.
 */

function split_sql(string $sql): array
{
    $sql = str_replace("\r\n", "\n", $sql);
    $parts = preg_split('/;\s*\n/', $sql);
    if ($parts === false) {
        return [];
    }
    $out = [];
    foreach ($parts as $p) {
        $p = trim($p);
        if ($p === '' || strncmp($p, '--', 2) === 0) {
            continue;
        }
        $lines = [];
        foreach (explode("\n", $p) as $line) {
            $t = trim($line);
            if ($t !== '' && strncmp($t, '--', 2) !== 0) {
                $lines[] = $line;
            }
        }
        $stmt = trim(implode("\n", $lines));
        if ($stmt !== '') {
            $out[] = $stmt;
        }
    }
    return $out;
}

function run_statements(mysqli $m, array $stmts): void
{
    foreach ($stmts as $stmt) {
        if (!$m->query($stmt)) {
            throw new RuntimeException("SQL error: " . $m->error . "\n---\n$stmt\n");
        }
    }
}

/** Single statement after migrations; fail fast with stderr + exit(1). */
function seed_exec(mysqli $m, string $sql): void
{
    if (!$m->query($sql)) {
        fwrite(STDERR, "Seed query failed: {$m->error}\n---\n{$sql}\n");
        exit(1);
    }
}

/**
 * Align seeded admin password with LOGANALYZER_ADMIN_PASSWORD (same md5 scheme as INSERT).
 * Persistent MySQL volumes skip full seed once database_installedversion is set; stale hashes cause login failures.
 *
 * Uses a prepared statement for credential values; table name stays quoted from the configured prefix only.
 *
 * @return int -1 on query error, 0 if no row was updated (unknown user or password already matched), otherwise affected row count (typically 1)
 */
function sync_admin_password_if_exists(mysqli $m, string $tableUsersQuoted, string $adminUser, string $adminPass): int
{
    if ($adminUser === '') {
        return 0;
    }

    $passwordHash = md5($adminPass);
    $sql = 'UPDATE ' . $tableUsersQuoted . ' SET password = ? WHERE username = ? LIMIT 1';
    $stmt = $m->prepare($sql);
    if ($stmt === false) {
        fwrite(STDERR, "Warning: admin password sync prepare failed: {$m->error}\n");

        return -1;
    }

    try {
        $stmt->bind_param('ss', $passwordHash, $adminUser);
        if (!$stmt->execute()) {
            fwrite(STDERR, "Warning: admin password sync execute failed: {$stmt->error}\n");

            return -1;
        }

        return (int) $stmt->affected_rows;
    } finally {
        $stmt->close();
    }
}

$dbHost = getenv('LOGANALYZER_DB_HOST') ?: 'db';
$dbPort = (int) (getenv('LOGANALYZER_DB_PORT') ?: 3306);
$dbName = getenv('LOGANALYZER_DB_NAME') ?: 'loganalyzer';
$dbUser = getenv('LOGANALYZER_DB_USER') ?: 'loganalyzer';
$dbPass = getenv('LOGANALYZER_DB_PASSWORD') ?: 'loganalyzer';
$rootPass = getenv('MYSQL_ROOT_PASSWORD') ?: 'loganalyzer_root';
$pref = getenv('LOGANALYZER_TABLE_PREFIX') ?: 'logcon_';
$docroot = getenv('LOGANALYZER_DOCROOT') ?: '/var/www/html';

$adminUser = getenv('LOGANALYZER_ADMIN_USER') ?: 'admin';
$adminPass = getenv('LOGANALYZER_ADMIN_PASSWORD') ?: 'loganalyzer';
$sampleLog = getenv('LOGANALYZER_SAMPLE_LOG') ?: '/samplelogs/sampledata_syslog.log';
$sampleEventReporter = getenv('LOGANALYZER_SAMPLE_EVENTREPORTER') ?: '/samplelogs/EventReporter.log';

$seedSampleSourcesEnv = getenv('LOGANALYZER_SEED_SAMPLE_SOURCES');
// Unset or empty: seed demo disk sources (legacy default for dev/E2E). "0" / "false" / "no": clean install (schema + admin only).
$seedSampleSources = !($seedSampleSourcesEnv !== false && $seedSampleSourcesEnv !== '' &&
    in_array(strtolower(trim($seedSampleSourcesEnv)), ['0', 'false', 'no', 'off'], true));

require_once __DIR__ . '/env-disk-sources.php';
$diskEnvSpecs = loganalyzer_disk_source_specs_from_env();
$tableConfig = '`' . $pref . 'config`';
$tableViews = '`' . $pref . 'views`';
$tableSources = '`' . $pref . 'sources`';
$tableUsers = '`' . $pref . 'users`';

$m = @new mysqli($dbHost, 'root', $rootPass, '', $dbPort);
if ($m->connect_error) {
    fwrite(STDERR, 'Connect failed: ' . $m->connect_error . "\n");
    exit(1);
}
$m->set_charset('utf8mb4');

$dbNameEsc = $m->real_escape_string($dbName);
seed_exec($m, "CREATE DATABASE IF NOT EXISTS `$dbNameEsc` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
if (!$m->select_db($dbName)) {
    fwrite(STDERR, 'select_db failed: ' . $m->error . "\n");
    exit(1);
}

$cfgTableBare = $pref . 'config';
$tst = $m->query("SHOW TABLES LIKE '" . $m->real_escape_string($cfgTableBare) . "'");
if ($tst === false) {
    fwrite(STDERR, 'SHOW TABLES failed: ' . $m->error . "\n");
    exit(1);
}
if ($tst->num_rows > 0) {
    $chk = $m->query('SELECT propvalue FROM ' . $tableConfig . " WHERE propname = 'database_installedversion' AND is_global = 1 LIMIT 1");
    if ($chk === false) {
        fwrite(STDERR, 'SELECT database_installedversion failed: ' . $m->error . "\n");
        exit(1);
    }
    if (($row = $chk->fetch_assoc()) && strlen((string) ($row['propvalue'] ?? '')) > 0) {
        fwrite(STDOUT, "Database already seeded (database_installedversion=" . ($row['propvalue'] ?? '') . ").\n");
        $syncResult = sync_admin_password_if_exists($m, $tableUsers, $adminUser, $adminPass);
        if ($syncResult > 0) {
            fwrite(STDOUT, "Updated admin password hash for user '$adminUser' from environment.\n");
        } elseif ($syncResult === 0) {
            fwrite(STDOUT, "Admin password sync: no row changed (user not found or password already matches environment).\n");
        }
        exit(0);
    }
}

$template = $docroot . '/include/db_template.txt';
if (!is_readable($template)) {
    fwrite(STDERR, "Missing $template\n");
    exit(1);
}
$sql = file_get_contents($template);
$sql = str_replace('`logcon_', '`' . $pref, $sql);
run_statements($m, split_sql($sql));

for ($v = 0; $v <= 14; $v++) {
    $path = sprintf('%s/include/db_update_v%d.txt', $docroot, $v);
    if (!is_readable($path)) {
        continue;
    }
    $patch = file_get_contents($path);
    $patch = str_replace('`logcon_', '`' . $pref, $patch);
    try {
        run_statements($m, split_sql($patch));
    } catch (RuntimeException $e) {
        $msg = $e->getMessage();
        if (strpos($msg, 'Duplicate column') !== false ||
            strpos($msg, 'Duplicate key') !== false ||
            strpos($msg, 'already exists') !== false) {
            fwrite(STDOUT, "Note: skipping migration v$v (already applied)\n");
        } else {
            throw $e;
        }
    }
}

$version = '14';
seed_exec($m, "INSERT INTO " . $tableConfig . " (propname, propvalue, is_global) VALUES ('database_installedversion', '" . $m->real_escape_string($version) . "', 1)");

$cols = 'timereported,syslogfacility,syslogseverity,FROMHOST,syslogtag,procid,IUT,msg';
seed_exec($m, 'INSERT INTO ' . $tableViews . " (DisplayName, Columns, userid, groupid) VALUES (" .
    "'Syslog Fields', '" . $m->real_escape_string($cols) . "', NULL, NULL)");
$viewIdSyslog = (string) $m->insert_id;

$colsEvt = 'timereported,FROMHOST,syslogseverity,NTEventLogType,sourceproc,id,user,msg';
seed_exec($m, 'INSERT INTO ' . $tableViews . " (DisplayName, Columns, userid, groupid) VALUES (" .
    "'Event Log Fields', '" . $m->real_escape_string($colsEvt) . "', NULL, NULL)");
$viewIdEvent = (string) $m->insert_id;

$sourceId = '';
$sourceIdEvent = '';

$firstSeededDiskSourceId = null;
foreach ($diskEnvSpecs as $spec) {
    $nameEsc = $m->real_escape_string($spec['name']);
    $pathEsc = $m->real_escape_string($spec['path']);
    $descEsc = $m->real_escape_string($spec['description']);
    if ($spec['kind'] === 'syslog') {
        seed_exec(
            $m,
            'INSERT INTO ' . $tableSources .
            " (Name, Description, SourceType, MsgParserList, MsgNormalize, MsgSkipUnparseable, ViewID, LogLineType, DiskFile, userid, groupid) VALUES (" .
            "'$nameEsc', '$descEsc', 1, '', 0, 0, '" . $m->real_escape_string($viewIdSyslog) . "', 'syslog', '$pathEsc', NULL, NULL)",
        );
    } else {
        $parserEsc = $m->real_escape_string('eventlog');
        seed_exec(
            $m,
            'INSERT INTO ' . $tableSources .
            " (Name, Description, SourceType, MsgParserList, MsgNormalize, MsgSkipUnparseable, ViewID, LogLineType, DiskFile, userid, groupid) VALUES (" .
            "'$nameEsc', '$descEsc', 1, '$parserEsc', 0, 0, '" . $m->real_escape_string($viewIdEvent) . "', 'winsyslog', '$pathEsc', NULL, NULL)",
        );
    }
    $newId = (string) $m->insert_id;
    if ($firstSeededDiskSourceId === null) {
        $firstSeededDiskSourceId = $newId;
    }
    fwrite(STDOUT, "Seeded ENV disk source: ID $newId / {$spec['name']} → {$spec['path']}\n");
}

if ($seedSampleSources) {
    $diskEsc = $m->real_escape_string($sampleLog);
    $nameEsc = $m->real_escape_string('Docker sample (syslog)');

    seed_exec($m, 'INSERT INTO ' . $tableSources . " (Name, Description, SourceType, MsgParserList, MsgNormalize, MsgSkipUnparseable, ViewID, LogLineType, DiskFile, userid, groupid) VALUES (" .
        "'$nameEsc', '', 1, '', 0, 0, '" . $m->real_escape_string($viewIdSyslog) . "', 'syslog', '$diskEsc', NULL, NULL)");

    $sourceId = (string) $m->insert_id;

    $diskEv = $m->real_escape_string($sampleEventReporter);
    $nameEv = $m->real_escape_string('Docker sample (Windows EventReporter / EvntSLog)');
    $parserEv = $m->real_escape_string('eventlog');

    seed_exec($m, 'INSERT INTO ' . $tableSources . " (Name, Description, SourceType, MsgParserList, MsgNormalize, MsgSkipUnparseable, ViewID, LogLineType, DiskFile, userid, groupid) VALUES (" .
        "'$nameEv', 'Adiscon EventReporter-format file; LogLineType winsyslog + eventlog parser.', 1, '$parserEv', 0, 0, '" . $m->real_escape_string($viewIdEvent) . "', 'winsyslog', '$diskEv', NULL, NULL)");

    $sourceIdEvent = (string) $m->insert_id;
}

$hash = md5($adminPass);
seed_exec($m, 'INSERT INTO ' . $tableUsers . " (username, password, is_admin, is_readonly) VALUES (" .
    "'" . $m->real_escape_string($adminUser) . "', '" . $m->real_escape_string($hash) . "', 1, 0)");

$defaultSeedSourceId = null;
if ($firstSeededDiskSourceId !== null) {
    $defaultSeedSourceId = $firstSeededDiskSourceId;
} elseif ($seedSampleSources && $sourceId !== '') {
    $defaultSeedSourceId = $sourceId;
}

if ($defaultSeedSourceId !== null) {
    seed_exec(
        $m,
        'INSERT INTO ' . $tableConfig . " (propname, propvalue, is_global) VALUES ('DefaultSourceID', '" . $m->real_escape_string($defaultSeedSourceId) . "', 1)",
    );
}
seed_exec($m, "INSERT INTO " . $tableConfig . " (propname, propvalue, is_global) VALUES ('DefaultViewsID', 'SYSLOG', 1)");

if ($diskEnvSpecs !== [] && $seedSampleSources) {
    fwrite(STDOUT, "Done: admin '$adminUser' / ENV disk sources: " . count($diskEnvSpecs) . " / demo syslog $sourceId / demo EventReporter $sourceIdEvent\n");
} elseif ($diskEnvSpecs !== []) {
    fwrite(STDOUT, "Done: admin '$adminUser' / ENV disk sources: " . count($diskEnvSpecs) . "\n");
} elseif ($seedSampleSources) {
    fwrite(STDOUT, "Done: admin '$adminUser' / demo syslog ID $sourceId / EventReporter ID $sourceIdEvent\n");
} else {
    fwrite(STDOUT, "Done: admin '$adminUser' — no disk sources (set LOGANALYZER_DISK_* in .env or add in Administration).\n");
}
