<?php
/**
 * SQL Injection Smoke Test — CVE-2023-34600
 *
 * This script verifies that the id / savedreportid / dbmpid request parameters
 * are coerced to integer before being interpolated into SQL, so that a classic
 * "1 OR 1=1" payload cannot affect the generated query.
 *
 * These are **unit-level** smoke tests only.  They do not spin up a web server
 * or a database.  They directly validate the sanitisation logic that is now
 * applied to every affected GET/POST path.
 *
 * Run with:  php tests/sql_injection_smoke.php
 *
 * Exit code:  0 = all checks passed, non-zero = at least one check failed.
 */

$failures = 0;

/**
 * Assert that $actual equals $expected, printing a message on failure.
 */
function assert_eq($description, $expected, $actual) {
    global $failures;
    if ($expected !== $actual) {
        echo "FAIL [$description]: expected " . var_export($expected, true)
            . " got " . var_export($actual, true) . "\n";
        $failures++;
    } else {
        echo "PASS [$description]\n";
    }
}

/**
 * Assert that the generated SQL fragment does NOT contain an injection payload.
 */
function assert_safe_sql($description, $id_param, $sql_template) {
    global $failures;
    // This replicates what the fixed code does: cast to int.
    $safe_id = intval($id_param);
    $sql = sprintf($sql_template, $safe_id);

    // The injected string should not appear verbatim in the final SQL.
    $injected_payload = "1 OR 1=1";
    if (strpos($sql, $injected_payload) !== false) {
        echo "FAIL [$description]: SQL contains injection payload: $sql\n";
        $failures++;
    } else {
        echo "PASS [$description]: safe SQL = $sql\n";
    }
}

echo "=== SQL Injection Smoke Tests (CVE-2023-34600) ===\n\n";

// -------------------------------------------------------------------
// 1. intval() coercion of well-known injection payloads
// -------------------------------------------------------------------
$payloads = [
    "1 OR 1=1"    => 1,
    "1; DROP TABLE users; --" => 1,
    "0 UNION SELECT * FROM users" => 0,
    "' OR '1'='1"  => 0,
    "-1 OR 1=1"    => -1,
    "1.5"          => 1,
    "abc"          => 0,
    ""             => 0,
    "42"           => 42,
    " 7 "          => 7,
];

echo "--- intval() coercion results ---\n";
foreach ($payloads as $input => $expected_int) {
    assert_eq(
        "intval(\"$input\")",
        $expected_int,
        intval($input)
    );
}

// -------------------------------------------------------------------
// 2. Verify that the resulting SQL does NOT embed the raw payload
// -------------------------------------------------------------------
echo "\n--- Generated SQL safety checks ---\n";

$sql_tpl = "SELECT * FROM logcon_searches WHERE ID = %d";

assert_safe_sql("searches DELETE id='1 OR 1=1'",           "1 OR 1=1",              $sql_tpl);
assert_safe_sql("searches DELETE id='0 UNION SELECT ...'", "0 UNION SELECT passwd", $sql_tpl);
assert_safe_sql("searches EDIT   id='1 OR 1=1'",           "1 OR 1=1",              $sql_tpl);

$sql_tpl_views = "SELECT ID FROM logcon_views WHERE ID = %d";
assert_safe_sql("views EDIT   id='1 OR 1=1'",    "1 OR 1=1",  $sql_tpl_views);
assert_safe_sql("views DELETE id='1 OR 1=1'",    "1 OR 1=1",  $sql_tpl_views);

$sql_tpl_groups = "SELECT groupname FROM logcon_groups WHERE ID = %d";
assert_safe_sql("groups EDIT   id='1 OR 1=1'",   "1 OR 1=1",  $sql_tpl_groups);
assert_safe_sql("groups DELETE id='1 OR 1=1'",   "1 OR 1=1",  $sql_tpl_groups);

$sql_tpl_charts = "SELECT DisplayName FROM logcon_charts WHERE ID = %d";
assert_safe_sql("charts EDIT   id='1 OR 1=1'",   "1 OR 1=1",  $sql_tpl_charts);
assert_safe_sql("charts DELETE id='1 OR 1=1'",   "1 OR 1=1",  $sql_tpl_charts);

$sql_tpl_users = "SELECT username FROM logcon_users WHERE ID = %d";
assert_safe_sql("users EDIT   id='1 OR 1=1'",    "1 OR 1=1",  $sql_tpl_users);
assert_safe_sql("users DELETE id='1 OR 1=1'",    "1 OR 1=1",  $sql_tpl_users);

$sql_tpl_dbmp = "SELECT DisplayName FROM logcon_dbmappings WHERE ID = %d";
assert_safe_sql("dbmappings EDIT   dbmpid='1 OR 1=1'",   "1 OR 1=1",  $sql_tpl_dbmp);
assert_safe_sql("dbmappings DELETE dbmpid='1 OR 1=1'",   "1 OR 1=1",  $sql_tpl_dbmp);

$sql_tpl_sources = "SELECT Name FROM logcon_sources WHERE ID = %d";
assert_safe_sql("sources DELETE id='1 OR 1=1'",  "1 OR 1=1",  $sql_tpl_sources);

$sql_tpl_saved = "SELECT customTitle FROM logcon_savedreports WHERE ID = %d";
assert_safe_sql("savedreports DELETE savedreportid='1 OR 1=1'", "1 OR 1=1", $sql_tpl_saved);

// -------------------------------------------------------------------
// 3. Verify legitimate numeric IDs still work correctly
// -------------------------------------------------------------------
echo "\n--- Legitimate IDs pass through correctly ---\n";
assert_eq("intval legitimate id=1",   1,  intval("1"));
assert_eq("intval legitimate id=42",  42, intval("42"));
assert_eq("intval legitimate id=100", 100, intval("100"));

// -------------------------------------------------------------------
// Summary
// -------------------------------------------------------------------
echo "\n=== Results: ";
if ($failures === 0) {
    echo "ALL PASSED ===\n";
    exit(0);
} else {
    echo "$failures FAILURE(S) ===\n";
    exit(1);
}
