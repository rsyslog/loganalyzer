<?php
declare(strict_types=1);

namespace LogAnalyzer\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Requires Docker MySQL (or compatible) and env:
 *   LOGANALYZER_INTEGRATION=1
 */
final class DatabaseSeedPatternTest extends TestCase
{
    protected function setUp(): void
    {
        if (getenv('LOGANALYZER_INTEGRATION') !== '1') {
            self::markTestSkipped('Set LOGANALYZER_INTEGRATION=1 to run MySQL integration tests.');
        }
    }

    public function testRootCanReachMysql(): void
    {
        $host = getenv('LOGANALYZER_DB_HOST') ?: '127.0.0.1';
        $port = (int) (getenv('LOGANALYZER_DB_PORT') ?: 3306);
        $rootPass = getenv('MYSQL_ROOT_PASSWORD') ?: 'loganalyzer_root';

        $m = @new \mysqli($host, 'root', $rootPass, '', $port);
        if ($m->connect_error) {
            self::fail('MySQL root connect failed: ' . $m->connect_error);
        }
        $m->close();
    }
}
