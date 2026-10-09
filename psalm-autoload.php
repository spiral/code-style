<?php

declare(strict_types=1);

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . '/vendor/autoload.php';

// php-cs-fixer/shim ships PHP CS Fixer only as a PHAR. Psalm resolves class files through realpath(), which fails
// for phar:// paths, so the PHAR's `src/` is unpacked to disk. Its unprefixed vendor/ is skipped: it would shadow
// Psalm's own dependencies.
$pharFile = __DIR__ . '/vendor/php-cs-fixer/shim/php-cs-fixer.phar';
$target = __DIR__ . '/runtime/php-cs-fixer/' . \md5_file($pharFile);

if (!\is_dir($target . '/src')) {
    $phar = new \Phar($pharFile);
    $prefix = $phar->getPath() . '/';
    $files = [];
    foreach (new \RecursiveIteratorIterator($phar) as $file) {
        $path = \substr($file->getPathname(), \strlen('phar://' . $prefix));
        \str_starts_with($path, 'src/') and $files[] = $path;
    }

    $phar->extractTo($target, $files, true);
}

$loader->addPsr4('PhpCsFixer\\', $target . '/src');
