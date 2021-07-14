<?php
declare(strict_types=1);

namespace Spiral\CodeStyle;

use Symfony\Component\Finder\Finder;

class CodeStyleHelper
{
    public const PACKAGE_NAME             = 'code-style';
    public const PHP_CS_CONFIG_FILE       = 'ruleset.xml';
    public const PHP_CS_FIXER_CONFIG_FILE = '.php_cs';


    public static function init(string $rootDir): self
    {
        self::defineConfigFolderLocation($rootDir);

        return new self();
    }

    private function __construct()
    {
    }

    private static function defineConfigFolderLocation(string $rootDir): void
    {
        foreach (
            [
                VENDOR_DIR . DIRECTORY_SEPARATOR . 'spiral' . DIRECTORY_SEPARATOR . self::PACKAGE_NAME,
                $rootDir,
                $rootDir . DIRECTORY_SEPARATOR . '..'
            ] as $directory
        ) {
            if (
                is_dir($directory) &&
                is_dir($configDir = $directory . DIRECTORY_SEPARATOR . 'config') &&
                file_exists($configDir . DIRECTORY_SEPARATOR . self::PHP_CS_FIXER_CONFIG_FILE) &&
                file_exists($configDir . DIRECTORY_SEPARATOR . self::PHP_CS_CONFIG_FILE)
            ) {
                define('CONFIG_DIR', $configDir);
                break;
            }
        }

        if (!defined('CONFIG_DIR')) {
            fwrite(
                STDERR,
                'Unable to find code style config locations' . PHP_EOL
            );
            die(1);
        }
    }

    public function getPhpCsConfigPath(): string
    {
        return CONFIG_DIR . DIRECTORY_SEPARATOR . self::PHP_CS_CONFIG_FILE;
    }

    public function getPhpCsFixerConfigPath(): string
    {
        return CONFIG_DIR . DIRECTORY_SEPARATOR . self::PHP_CS_FIXER_CONFIG_FILE;
    }

    public function wrapPaths(array $paths): array
    {
        array_walk($paths, function (&$path) {
            $path = PROJECT_ROOT . DIRECTORY_SEPARATOR . $path;
        });
        return $paths;
    }

    public function normalizeEndings(array $paths)
    {
        $finder = new Finder();

        foreach ($paths as $path) {
            if (is_file($path)) {
                $finder->append([$path]);
            } else {
                $finder->in($path);
            }
        }

        foreach ($finder->files() as $path) {
            $this->normalizeContent((string)$path);
        }
    }

    private function normalizeContent(string $filename)
    {
        $lines = file($filename);
        foreach ($lines as &$line) {
            $line = rtrim($line, "\n\r ");
            unset($line);
        }

        file_put_contents($filename, join("\n", $lines));
    }
}