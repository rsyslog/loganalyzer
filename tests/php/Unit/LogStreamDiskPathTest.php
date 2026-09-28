<?php
declare(strict_types=1);

// ---------------------------------------------------------------------------
// Global-namespace stub for GetAndReplaceLangStr.
// Verify() calls this helper when a path is denied. We provide a lightweight
// implementation here so the test does not need to load functions_common.php
// (which pulls in the entire application stack).
// ---------------------------------------------------------------------------
namespace {
    if (!function_exists('GetAndReplaceLangStr')) {
        function GetAndReplaceLangStr($strlang, $param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '')
        {
            $out = str_replace('%1', (string) $param1, (string) $strlang);
            if ((string) $param2 !== '') {
                $out = str_replace('%2', (string) $param2, $out);
            }
            if ((string) $param3 !== '') {
                $out = str_replace('%3', (string) $param3, $out);
            }
            if ((string) $param4 !== '') {
                $out = str_replace('%4', (string) $param4, $out);
            }
            if ((string) $param5 !== '') {
                $out = str_replace('%5', (string) $param5, $out);
            }
            return $out;
        }
    }
}

// ---------------------------------------------------------------------------
// Test class
// ---------------------------------------------------------------------------
namespace LogAnalyzer\Tests\Unit {

    use PHPUnit\Framework\TestCase;

    /**
     * Tests the disk-path allow-list validation in LogStreamDisk::Verify().
     *
     * Strategy:
     *  - A path whose directory is NOT in DiskAllowed  → ERROR_PATH_NOT_ALLOWED
     *  - A path whose directory IS     in DiskAllowed but the file does not
     *    exist on disk                                 → ERROR_FILE_NOT_FOUND
     *  - A path whose directory IS     in DiskAllowed and the file exists     → SUCCESS
     *
     * This exercises the real LogStreamDisk::Verify() method rather than a
     * duplicate of its logic.
     */
    final class LogStreamDiskPathTest extends TestCase
    {
        /** Temporary file created once for the "file exists" test. */
        private static string $tmpFile = '';
        private static string $symlinkPath = '';
        private static string $symlinkDir = '';
        private static string $symlinkTarget = '';

        public static function setUpBeforeClass(): void
        {
            global $gl_root_path;
            require_once $gl_root_path . 'classes/logstream.class.php';
            require_once $gl_root_path . 'classes/logstreamdisk.class.php';

            // Create a real, readable temporary file.
            self::$tmpFile = (string) tempnam(sys_get_temp_dir(), 'la_test_');

			self::$symlinkDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'la_allowed_' . uniqid('', true);
			mkdir(self::$symlinkDir);
			$outsideFile = (string) tempnam(sys_get_temp_dir(), 'la_outside_');
			self::$symlinkPath = self::$symlinkDir . DIRECTORY_SEPARATOR . 'escape.log';
			if ( function_exists('symlink') && @symlink($outsideFile, self::$symlinkPath) )
				self::$symlinkTarget = $outsideFile;
			else
				@unlink($outsideFile);
        }

        public static function tearDownAfterClass(): void
        {
            if (self::$tmpFile !== '' && file_exists(self::$tmpFile)) {
                unlink(self::$tmpFile);
            }
			if ( self::$symlinkPath !== '' && is_link(self::$symlinkPath) )
				unlink(self::$symlinkPath);
			if ( self::$symlinkDir !== '' && is_dir(self::$symlinkDir) )
				rmdir(self::$symlinkDir);
			if ( isset(self::$symlinkTarget) && self::$symlinkTarget !== '' && file_exists(self::$symlinkTarget) )
				unlink(self::$symlinkTarget);
        }

        /**
         * Set DiskAllowed, point a LogStreamDisk at $fileName, call Verify().
         *
         * @param string[] $allowedDirs
         */
        private function verify(string $fileName, array $allowedDirs): int
        {
			return $this->makeStream($fileName, $allowedDirs)->Verify();
		}

		/**
		 * @param string[] $allowedDirs
		 */
		private function makeStream(string $fileName, array $allowedDirs): \LogStreamDisk
		{
            global $content;
            $content['DiskAllowed'] = $allowedDirs;
            $content['LN_ERROR_PATH_NOT_ALLOWED_EXTRA'] = '';

            $cfg = new \stdClass();
            $cfg->FileName = $fileName;
			return new \LogStreamDisk($cfg);
        }

        public function testFileInAllowedDirectoryPassesPathCheck(): void
        {
            // Path is inside /var/log/ but the file does not physically exist.
            // Verify() should return FILE_NOT_FOUND, not PATH_NOT_ALLOWED.
            self::assertSame(ERROR_FILE_NOT_FOUND, $this->verify('/var/log/la_test_nonexistent.log', ['/var/log/']));
        }

		public function testTraversalToExistingFileIsDenied(): void
		{
			self::assertSame(ERROR_PATH_NOT_ALLOWED, $this->verify('/var/log/../../etc/passwd', ['/var/log']));
			self::assertSame(ERROR_PATH_NOT_ALLOWED, $this->verify('/var/log/../www/html/config.php', ['/var/log']));
		}

        public function testFileInSecondOfMultipleAllowedDirectoriesPassesPathCheck(): void
        {
            $tmpDir = rtrim(sys_get_temp_dir(), '/') . '/';
            self::assertSame(
                ERROR_FILE_NOT_FOUND,
                $this->verify($tmpDir . 'app.log', ['/var/log/', $tmpDir])
            );
        }

        public function testFileOutsideAllAllowedDirectoriesIsDenied(): void
        {
            self::assertSame(
                ERROR_PATH_NOT_ALLOWED,
                $this->verify('/etc/passwd', ['/var/log/', '/data/logs/'])
            );
        }

        public function testPathContainingAllowedDirButNotStartingWithItIsDenied(): void
        {
            // /evil/var/log/syslog contains "/var/log/" but does not start with it.
            self::assertSame(
                ERROR_PATH_NOT_ALLOWED,
                $this->verify('/evil/var/log/syslog', ['/var/log/'])
            );
        }

        public function testAllowedDirWithoutTrailingSlashIsNormalized(): void
        {
            // DiskAllowed entries may be stored without a trailing slash.
            self::assertSame(
                ERROR_FILE_NOT_FOUND,
                $this->verify('/var/log/la_test_nonexistent.log', ['/var/log'])
            );
        }

        public function testAllowedDirWithoutTrailingSlashDoesNotMatchLongerPrefix(): void
        {
            // /var/log2/ must not be accepted when only /var/log is allowed.
            self::assertSame(
                ERROR_PATH_NOT_ALLOWED,
                $this->verify('/var/log2/syslog', ['/var/log'])
            );
        }

        public function testExistingReadableFileInAllowedDirectoryReturnsSuccess(): void
        {
            $tmpDir = rtrim(sys_get_temp_dir(), '/') . '/';
            self::assertSame(SUCCESS, $this->verify(self::$tmpFile, [$tmpDir]));
        }

		public function testRelativeExistingFileUsesCanonicalAllowedDirectory(): void
		{
			$originalDirectory = getcwd();
			chdir(dirname(self::$tmpFile));
			try {
				self::assertSame(SUCCESS, $this->verify(basename(self::$tmpFile), ['.']));
			} finally {
				chdir($originalDirectory);
			}
		}

		public function testMissingFileUnderAllowedDirectoryRemainsFileNotFound(): void
		{
			$tmpDir = rtrim(sys_get_temp_dir(), '/\\');
			$stream = $this->makeStream($tmpDir . '/la_missing_future.log', [$tmpDir]);
			self::assertSame(ERROR_FILE_NOT_FOUND, $stream->Verify());
			self::assertTrue($stream->IsMissingFileAuthorized());
		}

		public function testSymlinkEscapeIsDenied(): void
		{
			if ( !is_link(self::$symlinkPath) )
				self::markTestSkipped('Symlink creation is unavailable in this environment.');

			self::assertSame(ERROR_PATH_NOT_ALLOWED, $this->verify(self::$symlinkPath, [self::$symlinkDir]));
		}
    }
}
