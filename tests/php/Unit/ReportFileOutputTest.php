<?php
declare(strict_types=1);

namespace {
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
    require_once dirname(__DIR__, 3) . '/src/classes/reports/report.class.php';

    final class ReportFileOutputTest extends TestCase
    {
        private string $directory;

        protected function setUp(): void
        {
            $this->directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'la-report-' . bin2hex(random_bytes(8));
            self::assertTrue(mkdir($this->directory, 0700));
            $GLOBALS['testReportOutputDirectory'] = $this->directory;
        }

        protected function tearDown(): void
        {
            foreach (scandir($this->directory) ?: [] as $entry) {
                if ($entry !== '.' && $entry !== '..') {
                    $path = $this->directory . DIRECTORY_SEPARATOR . $entry;
                    if (is_file($path) || is_link($path)) {
                        unlink($path);
                    }
                }
            }
            rmdir($this->directory);
            unset($GLOBALS['testReportOutputDirectory']);
        }

        private function report(string $filename, string $format = REPORT_OUTPUT_HTML): \Report
        {
            $report = new class extends \Report {
                public function startDataProcessing() { return SUCCESS; }
                public function validateLicense() { return SUCCESS; }
                public function InitReport() { return SUCCESS; }
                public function RemoveReport() { return SUCCESS; }
                public function InitAdvancedSettings() { return SUCCESS; }
                public function CheckLogStreamSource($mySourceID) { return SUCCESS; }
                public function CreateLogStreamIndexes($mySourceID) { return SUCCESS; }
                public function CreateLogStreamTrigger($mySourceID) { return SUCCESS; }
            };
            $report->SetOutputFormat($format);
            $report->SetOutputTarget(REPORT_TARGET_FILE);
            $report->SetOutputTargetDetails('filename=>' . $filename);
            return $report;
        }

        public function testValidHtmlReportIsSavedAndReplacesExistingFile(): void
        {
            $error = '';
            $report = $this->report('daily_report.html');
            self::assertSame(SUCCESS, $report->OutputReport('first', $error));
            self::assertSame(SUCCESS, $report->OutputReport('second', $error));
            self::assertSame('second', file_get_contents($this->directory . '/daily_report.html'));
        }

        public function testUnsafeNamesAndExtensionsAreRejected(): void
        {
            foreach (['../escape.html', 'report.php', 'report.php.html', 'nested/report.html', 'php://filter'] as $name) {
                $error = '';
                self::assertSame(ERROR, $this->report($name)->OutputReport('unsafe', $error), $name);
            }
            $error = '';
            self::assertSame(ERROR, $this->report('report.html', REPORT_OUTPUT_PDF)->OutputReport('unsafe', $error));
        }

        public function testMissingOrWebrootConfigurationFailsClosed(): void
        {
            $error = '';
            $GLOBALS['testReportOutputDirectory'] = '';
            self::assertSame(ERROR, $this->report('report.html')->OutputReport('unsafe', $error));
            $GLOBALS['testReportOutputDirectory'] = dirname(__DIR__, 3) . '/src';
            self::assertSame(ERROR, $this->report('report.html')->OutputReport('unsafe', $error));
        }

        public function testWorldWritableOutputDirectoryIsRejected(): void
        {
            if (DIRECTORY_SEPARATOR === '\\') {
                self::markTestSkipped('POSIX directory mode check is not applicable on Windows.');
            }
            chmod($this->directory, 0777);
            clearstatcache(true, $this->directory);
            try {
                $error = '';
                self::assertSame(ERROR, $this->report('unsafe.html')->OutputReport('unsafe', $error));
            } finally {
                chmod($this->directory, 0700);
            }
        }

        public function testExistingSymlinkIsReplacedWithoutFollowingIt(): void
        {
            $outside = tempnam(sys_get_temp_dir(), 'la-outside-');
            self::assertNotFalse($outside);
            file_put_contents($outside, 'original');
            $link = $this->directory . '/linked.html';
            if (!function_exists('symlink') || !@symlink($outside, $link)) {
                unlink($outside);
                self::markTestSkipped('Symlinks are unavailable on this host.');
            }
            try {
                $error = '';
                self::assertSame(SUCCESS, $this->report('linked.html')->OutputReport('replacement', $error));
                self::assertSame('original', file_get_contents($outside));
                self::assertSame('replacement', file_get_contents($link));
            } finally {
                unlink($outside);
            }
        }

        public function testReportTitleAndCommentAreEscapedForHtmlTemplates(): void
        {
            global $content;
            $report = $this->report('safe.html');
            $report->SetCustomTitle('<script>title</script>');
            $report->SetCustomComment('<img src=x onerror=alert(1)>');
            $report->_streamObj = new class {
                public function ReturnFiltersArray() { return null; }
            };
            $report->SetCommonContentVariables();
            self::assertSame('&lt;script&gt;title&lt;/script&gt;', $content['report_title']);
            self::assertSame('&lt;img src=x onerror=alert(1)&gt;', $content['report_comment']);
        }
    }
}
