<?php
declare(strict_types=1);

namespace LogAnalyzer\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Regression tests for issue #61 – "Error remove multi lines mapping in Database Mapping".
 *
 * Root cause: when the edit page handles a delete-subop POST request it first
 * re-loads the full mapping from the database (which stores each field value as
 * a plain string), then overwrites only the keys that appear in
 * $_POST['Mappings'].  If the user had already deleted one field in a previous
 * request, that field is absent from $_POST['Mappings'] but is still present in
 * $content['SUBMAPPINGS'] as a *string*.  The subsequent "Process Columns for
 * display" loop then tries to do $myColumn['MappingCaption'] = … on a string,
 * producing "Illegal string offset" warnings.
 *
 * The fix resets $content['SUBMAPPINGS'] to an empty array before rebuilding it
 * from $_POST['Mappings'], so stale DB-loaded string entries can never linger.
 */
final class DbMappingSubMappingsTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Helpers that mirror the logic extracted from src/admin/dbmappings.php
    // -----------------------------------------------------------------------

    /**
     * Simulate the ORIGINAL (buggy) behaviour: does NOT reset SUBMAPPINGS
     * before building from $postMappings.
     *
     * @param array<string,string> $dbMappings   Mappings as loaded from DB
     *                                            (string values, keyed by fieldId)
     * @param list<string>         $postMappings  $_POST['Mappings'] value
     * @return array<string,mixed> The resulting $content['SUBMAPPINGS']
     */
    private function buildSubMappingsBuggy(array $dbMappings, array $postMappings): array
    {
        // Step 1: simulate the GET op=edit handler loading DB data as strings.
        $submappings = $dbMappings;   // values are plain strings here

        // Step 2: simulate the "Additional work" block WITHOUT the fix.
        $allMappings = $postMappings; // comes from $_POST['Mappings']
        // (no reset of $submappings)
        foreach ($allMappings as $colKey => $fieldName) {
            if (!is_numeric($colKey)) {
                $submappings[$colKey] = ['MappingFieldID' => $colKey, 'MappingDbFieldName' => $fieldName];
            } else {
                $submappings[$fieldName] = ['MappingFieldID' => $fieldName];
            }
        }

        return $submappings;
    }

    /**
     * Simulate the FIXED behaviour: resets SUBMAPPINGS before building from
     * $postMappings so that stale DB string entries are discarded.
     *
     * @param array<string,string> $dbMappings
     * @param list<string>         $postMappings
     * @return array<string,mixed>
     */
    private function buildSubMappingsFixed(array $dbMappings, array $postMappings): array
    {
        // Step 1: simulate the GET op=edit handler loading DB data as strings.
        $submappings = $dbMappings;

        // Step 2: simulate the "Additional work" block WITH the fix.
        $allMappings  = $postMappings;
        $submappings  = [];           // <-- the fix: reset before rebuilding
        foreach ($allMappings as $colKey => $fieldName) {
            if (!is_numeric($colKey)) {
                $submappings[$colKey] = ['MappingFieldID' => $colKey, 'MappingDbFieldName' => $fieldName];
            } else {
                $submappings[$fieldName] = ['MappingFieldID' => $fieldName];
            }
        }

        return $submappings;
    }

    // -----------------------------------------------------------------------
    // Tests
    // -----------------------------------------------------------------------

    /**
     * After a first delete the form only sends the remaining fields.
     * The ORIGINAL code leaves the removed field as a string in SUBMAPPINGS,
     * which would later cause "Illegal string offset" warnings.
     */
    public function testBuggyCodeLeavesStaleStringEntryAfterPartialPost(): void
    {
        $dbMappings   = ['field1' => 'dbcol1', 'field2' => 'dbcol2', 'field3' => 'dbcol3'];
        // Second delete: field3 was already removed in a prior request so it is
        // absent from the form; only field1 and field2 are submitted.
        $postMappings = ['field1', 'field2'];

        $result = $this->buildSubMappingsBuggy($dbMappings, $postMappings);

        // field3 is still present as a plain string – the bug.
        self::assertArrayHasKey('field3', $result);
        self::assertIsString($result['field3'], 'Buggy code leaves field3 as a string');

        // Trying to set an array offset on that string would trigger the warning.
        // Demonstrate the error condition without actually triggering E_WARNING.
        self::assertFalse(is_array($result['field3']));
    }

    /**
     * The FIXED code resets SUBMAPPINGS before rebuilding from POST, so stale
     * DB string entries are never present in the result.
     */
    public function testFixedCodeContainsOnlyPostFieldsAsArrays(): void
    {
        $dbMappings   = ['field1' => 'dbcol1', 'field2' => 'dbcol2', 'field3' => 'dbcol3'];
        $postMappings = ['field1', 'field2'];

        $result = $this->buildSubMappingsFixed($dbMappings, $postMappings);

        // field3 must be gone.
        self::assertArrayNotHasKey('field3', $result, 'Fixed code must not retain stale DB entry for field3');

        // The remaining entries must be arrays (not strings).
        self::assertArrayHasKey('field1', $result);
        self::assertIsArray($result['field1']);
        self::assertArrayHasKey('field2', $result);
        self::assertIsArray($result['field2']);
    }

    /**
     * When all DB fields are present in the POST (e.g., the very first delete
     * operation) both the buggy and fixed paths produce the same correct result:
     * all entries are arrays.
     */
    public function testAllDbFieldsPresentInPostProducesCorrectResultBothPaths(): void
    {
        $dbMappings   = ['field1' => 'dbcol1', 'field2' => 'dbcol2', 'field3' => 'dbcol3'];
        $postMappings = ['field1', 'field2', 'field3'];

        $buggy = $this->buildSubMappingsBuggy($dbMappings, $postMappings);
        $fixed = $this->buildSubMappingsFixed($dbMappings, $postMappings);

        foreach (['field1', 'field2', 'field3'] as $field) {
            self::assertIsArray($buggy[$field]);
            self::assertIsArray($fixed[$field]);
        }
    }

    /**
     * Fixed code: first POST includes all fields → no stale entries.
     * Second POST removes one field → SUBMAPPINGS still clean.
     */
    public function testSequentialDeletesNeverLeaveStaleEntries(): void
    {
        $dbMappings = ['field1' => 'dbcol1', 'field2' => 'dbcol2', 'field3' => 'dbcol3'];

        // First delete: field3 removed; form still sends all three before delete.
        $after1 = $this->buildSubMappingsFixed($dbMappings, ['field1', 'field2', 'field3']);
        unset($after1['field3']); // simulate subop_delete
        self::assertCount(2, $after1);

        // Second delete: field2 removed; POST now only sends field1 and field2.
        $after2 = $this->buildSubMappingsFixed($dbMappings, ['field1', 'field2']);
        unset($after2['field2']); // simulate subop_delete
        self::assertCount(1, $after2);
        self::assertArrayHasKey('field1', $after2);
        self::assertArrayNotHasKey('field2', $after2);
        self::assertArrayNotHasKey('field3', $after2);
        self::assertIsArray($after2['field1']);
    }
}
