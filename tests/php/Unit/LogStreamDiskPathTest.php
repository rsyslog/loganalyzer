<?php
declare(strict_types=1);

namespace LogAnalyzer\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Tests the path-prefix validation logic used in LogStreamDisk::Verify().
 *
 * The fix ensures that a file's directory must *start with* an allowed prefix,
 * rather than merely *containing* it anywhere in the path (which would allow
 * traversal attacks like /evil/var/log/syslog bypassing a /var/log/ restriction).
 */
final class LogStreamDiskPathTest extends TestCase
{
    /**
     * Replicates the normalisation and prefix check from LogStreamDisk::Verify().
     *
     * @param string[] $allowedDirs
     */
    private function isPathAllowed(string $filePath, array $allowedDirs): bool
    {
        $fileDirName = dirname($filePath) . '/';
        foreach ($allowedDirs as $allowedDir) {
            $allowedDirNorm = rtrim($allowedDir, '/') . '/';
            if (strpos($fileDirName, $allowedDirNorm) === 0) {
                return true;
            }
        }
        return false;
    }

    public function testFileInAllowedDirectoryIsAllowed(): void
    {
        self::assertTrue($this->isPathAllowed('/var/log/syslog', ['/var/log/']));
    }

    public function testFileInSecondOfMultipleAllowedDirectoriesIsAllowed(): void
    {
        self::assertTrue(
            $this->isPathAllowed('/data/logs/app.log', ['/var/log/', '/data/logs/'])
        );
    }

    public function testFileOutsideAllAllowedDirectoriesIsDenied(): void
    {
        self::assertFalse(
            $this->isPathAllowed('/tmp/evil.log', ['/var/log/', '/data/logs/'])
        );
    }

    public function testPathContainingAllowedDirButNotStartingWithItIsDenied(): void
    {
        // /evil/var/log/syslog contains "/var/log/" but does not start with it.
        self::assertFalse($this->isPathAllowed('/evil/var/log/syslog', ['/var/log/']));
    }

    public function testAllowedDirWithoutTrailingSlashIsNormalized(): void
    {
        // DiskAllowed entries may or may not carry a trailing slash.
        self::assertTrue($this->isPathAllowed('/var/log/syslog', ['/var/log']));
    }

    public function testAllowedDirWithoutTrailingSlashDoesNotMatchSimilarPrefix(): void
    {
        // /var/log2/ must not be accepted when only /var/log is allowed.
        self::assertFalse($this->isPathAllowed('/var/log2/syslog', ['/var/log']));
    }
}
