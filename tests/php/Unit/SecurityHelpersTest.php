<?php
declare(strict_types=1);

namespace LogAnalyzer\Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/include/functions_html.php';
require_once dirname(__DIR__, 3) . '/src/include/functions_chart.php';
require_once dirname(__DIR__, 3) . '/src/include/functions_db.php';

final class SecurityHelpersTest extends TestCase
{
    public function testHtmlValuesAreEscapedForTextAndAttributes(): void
    {
        $payload = '<script>alert(1)</script>"\'&';

        self::assertSame('&lt;script&gt;alert(1)&lt;/script&gt;&quot;&#039;&amp;', HtmlEscapeText($payload));
        self::assertSame('&lt;script&gt;alert(1)&lt;/script&gt;&quot;&#039;&amp;', HtmlEscapeAttribute($payload));
    }

    public function testLogoUrlAllowsOnlySafeImageSchemes(): void
    {
        self::assertSame('https://example.test/logo.png', SafeImageUrl('https://example.test/logo.png', 'fallback.png'));
        self::assertSame('/images/logo.png', SafeImageUrl('/images/logo.png', 'fallback.png'));
        self::assertSame('fallback.png', SafeImageUrl('javascript:alert(1)', 'fallback.png'));
        self::assertSame('fallback.png', SafeImageUrl('//evil.test/logo.png', 'fallback.png'));
        self::assertSame('fallback.png', SafeImageUrl('data:text/html,payload', 'fallback.png'));
    }

    public function testChartOrderIsFiniteAndSqlExpressionIsGeneratedFromKey(): void
    {
        self::assertSame('count_desc', NormalizeChartOrderKey('totalcount DESC'));
        self::assertSame('field_asc', NormalizeChartOrderKey('field_asc'));
        self::assertSame('count_desc', NormalizeChartOrderKey('totalcount DESC, sleep(1)'));
        self::assertSame('totalcount ASC', ChartOrderByExpression('count_asc', 'msggrouped'));
        self::assertSame('msggrouped ASC', ChartOrderByExpression('field_asc', 'msggrouped'));
        self::assertSame('msggrouped DESC', ChartOrderByExpression('field_desc', 'msggrouped'));
        self::assertSame('totalcount DESC', ChartOrderByExpression('unknown', 'msggrouped'));
    }

    public function testDatabaseMappingIdentifiersRejectSqlSyntax(): void
    {
        self::assertTrue(DB_IsSafeIdentifier('DeviceReportedTime'));
        self::assertTrue(DB_IsSafeIdentifier('field_1'));
        self::assertFalse(DB_IsSafeIdentifier('field` DESC'));
        self::assertFalse(DB_IsSafeIdentifier('field; DROP TABLE users'));
        self::assertFalse(DB_IsSafeIdentifier(array('field')));
    }
}
