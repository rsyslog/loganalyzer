<?php
declare(strict_types=1);

namespace LogAnalyzer\Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__, 3) . '/src/include/functions_html.php';
require_once dirname(__DIR__, 3) . '/src/include/functions_chart.php';
require_once dirname(__DIR__, 3) . '/src/include/functions_db.php';
global $gl_root_path;
require_once dirname(__DIR__, 3) . '/src/include/functions_config.php';
require_once dirname(__DIR__, 3) . '/src/classes/logstream.class.php';

final class SecurityHelpersTest extends TestCase
{
    public function testHtmlValuesAreEscapedForTextAndAttributes(): void
    {
        $payload = '<script>alert(1)</script>"\'&';

        self::assertSame('&lt;script&gt;alert(1)&lt;/script&gt;&quot;&#039;&amp;', HtmlEscapeText($payload));
        self::assertSame('&lt;script&gt;alert(1)&lt;/script&gt;&quot;&#039;&amp;', HtmlEscapeAttribute($payload));
    }

    public function testHtmlEscapingUsesConfiguredCharset(): void
    {
        $hadContent = array_key_exists('content', $GLOBALS);
        $originalContent = $hadContent ? $GLOBALS['content'] : null;
        if ( !$hadContent || !is_array($GLOBALS['content']) )
            $GLOBALS['content'] = array();

        try
        {
            $GLOBALS['content']['HeaderDefaultEncoding'] = 'ISO-8859-1';
            self::assertSame('e920266c743b', bin2hex(HtmlEscapeText("\xE9 <")));
            self::assertSame('e920266c743b', bin2hex(HtmlEscapeAttribute("\xE9 <")));

            $GLOBALS['content']['HeaderDefaultEncoding'] = 'utf-8';
            self::assertSame('c3a920266c743b', bin2hex(HtmlEscapeText("\xC3\xA9 <")));
            self::assertSame('c3a920266c743b', bin2hex(HtmlEscapeAttribute("\xC3\xA9 <")));
        }
        finally
        {
            if ( $hadContent )
                $GLOBALS['content'] = $originalContent;
            else
                unset($GLOBALS['content']);
        }
    }

    public function testErrorDetailsEscapeHtmlAndPreserveLineBreaks(): void
    {
        $payload = '<script>alert(1)</script><br>Database details: "quoted" & more';
        $escaped = HtmlEscapeErrorDetails($payload);

        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $escaped);
        self::assertStringContainsString('<br>', $escaped);
        self::assertStringContainsString('&quot;quoted&quot; &amp; more', $escaped);
        self::assertStringNotContainsString('<script>', $escaped);
        self::assertStringNotContainsString('&lt;br', $escaped);
    }

    public function testQueryParametersAreEncodedAndUrlIsSafeForHtmlAttributes(): void
    {
        $url = HtmlSafeUrlAttributeFromQuery('/admin/reports.php', array(
            'id' => 12,
            'x" onmouseover="alert(1)' => '<script>&',
            'nested' => array('ignored'),
        ));

        self::assertStringStartsWith('/admin/reports.php?id=12&amp;', $url);
        self::assertStringContainsString('%22%20onmouseover%3D%22alert%281%29=%3Cscript%3E%26', $url);
        self::assertStringNotContainsString('" onmouseover=', $url);
        self::assertStringNotContainsString('<script>', $url);
        self::assertStringNotContainsString('nested=', $url);

        self::assertSame(
            '/admin/reports.php?op=addsavedreport&amp;id=auditsummary&amp;optimize=indexes',
            HtmlSafeUrlAttributeFromQuery('/admin/reports.php', array('op' => 'addsavedreport', 'id' => 'auditsummary', 'optimize' => 'indexes'))
        );

        $postVariables = HtmlSafePostVariables(array(
            'name" autofocus onfocus="alert(1)' => '<img src=x onerror=alert(1)>',
            'array' => array('ignored'),
        ));
        self::assertCount(1, $postVariables);
        self::assertStringNotContainsString('" autofocus', $postVariables[0]['varname']);
        self::assertStringNotContainsString('<img', $postVariables[0]['varvalue']);
        self::assertStringContainsString('&quot;', $postVariables[0]['varname']);
        self::assertStringContainsString('&lt;img', $postVariables[0]['varvalue']);
    }

    public function testSecureRedirectRejectsBackslashAndEncodedBackslashTargets(): void
    {
        $backslash = chr(92);
        foreach (array(
            $backslash . 'evil.test',
            $backslash . $backslash . 'evil.test',
            'safe' . $backslash . $backslash . 'evil.test',
            'safe/%5c%5cevil.test',
            'safe%5Cpath',
            '//evil.test',
            '%2f%2fevil.test',
            ' //evil.test',
            ' javascript:alert(1)',
            'reports.php?view=summary ',
        ) as $target) {
            self::assertSame('index.php', SecureRedirect($target), $target);
        }

        self::assertSame('reports.php?view=summary', SecureRedirect('reports.php?view=summary'));
        self::assertSame('/admin/reports.php?view=summary', SecureRedirect('/admin/reports.php?view=summary'));
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

    public function testStoredDatabaseMappingsRejectUnsafeIdentifiers(): void
    {
        $availableFields = array(
            'message' => true,
            'date' => true,
            'host' => true,
            'facility' => true,
        );

        self::assertSame(
            array('message' => 'Message', 'date' => 'ReceivedAt'),
            DB_ParseStoredMappingString(
                'message=>Message,date=>ReceivedAt,host=>Host` DESC,facility=>Facility; DROP TABLE logcon_systemevents',
                $availableFields
            )
        );
    }

    public function testNumericSqlFiltersAcceptOnlyDecimalIntegers(): void
    {
        self::assertSame('16', \LogStream::NormalizeNumericFilterValue('16'));
        self::assertSame('00016', \LogStream::NormalizeNumericFilterValue('00016'));
        self::assertNull(\LogStream::NormalizeNumericFilterValue('16 OR 1=1'));
        self::assertNull(\LogStream::NormalizeNumericFilterValue('1e1'));
        self::assertNull(\LogStream::NormalizeNumericFilterValue('-1'));
        self::assertNull(\LogStream::NormalizeNumericFilterValue(''));
        self::assertNull(\LogStream::NormalizeNumericFilterValue(['16']));
    }
}
