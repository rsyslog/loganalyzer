<?php
declare(strict_types=1);

// Repo root: tests/fixtures/check-size.php → fixtures live in ./samplelogs

$dir = __DIR__ . '/samplelogs';
$max = 50 * 1024 * 1024;

if (!is_dir($dir)) {
    fwrite(STDERR, "Missing directory: $dir\n");
    exit(1);
}

$sum = 0;
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    /** @var SplFileInfo $f */
    if ($f->isFile()) {
        $sum += $f->getSize();
    }
}

echo "fixtures/samplelogs total bytes: $sum (max $max)\n";
if ($sum > $max) {
    fwrite(STDERR, "ERROR: fixtures exceed 50MB cap.\n");
    exit(1);
}
