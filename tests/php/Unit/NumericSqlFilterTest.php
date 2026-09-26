<?php
declare(strict_types=1);

namespace {
    if (!function_exists('OutputDebugMessage')) {
        function OutputDebugMessage($message, $level = null) {}
    }
    if (!function_exists('GetConfigSetting')) {
        function GetConfigSetting($name, $default = '', $level = null)
        {
            return $name === 'ReportOutputDirectory'
                ? ($GLOBALS['testReportOutputDirectory'] ?? '') : $default;
        }
    }
}

namespace LogAnalyzer\Tests\Unit {
    use PHPUnit\Framework\TestCase;

    require_once dirname(__DIR__) . '/bootstrap.php';
    global $gl_root_path;
    require_once $gl_root_path . 'include/functions_db.php';
    require_once $gl_root_path . 'classes/logstream.class.php';
    require_once $gl_root_path . 'classes/logstreamdb.class.php';
    require_once $gl_root_path . 'classes/logstreampdo.class.php';
    require_once $gl_root_path . 'classes/logstreamclickhouse.class.php';

    final class NumericSqlFilterTest extends TestCase
    {
        public function testEverySqlBackendRejectsMalformedNumericValuesBeforeBuildingWhere(): void
        {
            global $dbmapping;
            $dbmapping['security-test']['DBMAPPINGS'][SYSLOG_FACILITY] = 'facility';
            $config = (object) ['DBType' => DB_MYSQL, 'DBTableType' => 'security-test'];

            foreach ([\LogStreamDB::class, \LogStreamPDO::class, \LogStreamClickHouse::class] as $class) {
                $stream = new $class($config);
                $base = new \ReflectionClass(\LogStream::class);
                $base->getProperty('_arrProperties')->setValue($stream, [SYSLOG_FACILITY]);
                $base->getProperty('_filters')->setValue($stream, [SYSLOG_FACILITY => [
                    [FILTER_TYPE => FILTER_TYPE_NUMBER, FILTER_MODE => FILTER_MODE_INCLUDE, FILTER_VALUE => '16'],
                    [FILTER_TYPE => FILTER_TYPE_NUMBER, FILTER_MODE => FILTER_MODE_INCLUDE, FILTER_VALUE => '16 OR 1=1'],
                    [FILTER_TYPE => FILTER_TYPE_NUMBER, FILTER_MODE => FILTER_MODE_EXCLUDE, FILTER_VALUE => '20'],
                    [FILTER_TYPE => FILTER_TYPE_NUMBER, FILTER_MODE => FILTER_MODE_EXCLUDE, FILTER_VALUE => '0) OR 1=1'],
                ]]);

                $reflection = new \ReflectionClass($class);
                $reflection->getMethod('CreateSQLWhereClause')->invoke($stream);
                $where = $reflection->getProperty('_SQLwhereClause')->getValue($stream);
                self::assertStringContainsString('16', $where, $class);
                self::assertStringContainsString('20', $where, $class);
                self::assertStringNotContainsString('1=1', $where, $class);
            }
        }

        public function testInvalidNumericListDoesNotDiscardTheFollowingFilter(): void
        {
            $config = (object) ['DBType' => DB_MYSQL, 'DBTableType' => 'security-test', '_defaultfilter' => ''];
            $stream = new \LogStreamDB($config);

            $stream->SetFilter('eventid:10bad,11bad source:server01');

            $filters = $stream->ReturnFiltersArray();
            self::assertArrayNotHasKey(SYSLOG_EVENT_ID, $filters);
            self::assertSame('server01', $filters[SYSLOG_HOST][0][FILTER_VALUE]);
        }
    }
}
