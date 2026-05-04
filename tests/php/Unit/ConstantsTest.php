<?php
declare(strict_types=1);

namespace LogAnalyzer\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class ConstantsTest extends TestCase
{
    public function testSourceDiskIsStringOne(): void
    {
        self::assertSame('1', SOURCE_DISK);
    }

    public function testSyslogFieldIdsNonEmpty(): void
    {
        self::assertSame('timereported', SYSLOG_DATE);
        self::assertSame('msg', SYSLOG_MESSAGE);
    }
}
