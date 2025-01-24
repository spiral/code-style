<?php

declare(strict_types=1);

namespace Spiral\CodeStyle;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use Spiral\CodeStyle\Rules\DefaultRules;

class Builder
{
    /** @var non-empty-string[] */
    private array $includeDirs = [];

    /** @var non-empty-string[] */
    private array $excludeDirs = [];

    /** @var non-empty-string[] */
    private array $includeFiles = [];

    /** @var non-empty-string[] */
    private array $excludeFiles = [];

    /** @var non-empty-string|null */
    private ?string $cacheFile = './runtime/php-cs-fixer.cache';

    private bool $allowRisky = true;
    private RulesInterface $rules;

    private function __construct(
        RulesInterface $rules,
    ) {
        $this->rules = $rules;
    }

    public static function create(): self
    {
        return new self(new DefaultRules());
    }

    /**
     * Include directory or file to scan
     *
     * @param non-empty-string $path
     */
    public function include(string $path): self
    {
        $path = \realpath($path) or throw new \InvalidArgumentException("File or directory not found: $path");
        \is_dir($path) and $this->includeDirs[] = $path;
        \is_file($path) and $this->includeFiles[] = $path;
        return $this;
    }

    /**
     * Exclude a directory or a file from scan
     *
     * @param non-empty-string $path
     */
    public function exclude(string $path): self
    {
        $realPath = \realpath($path) or throw new \InvalidArgumentException("File or directory `$path` not found.");
        \is_dir($realPath) and $this->excludeDirs[] = $realPath;
        \is_file($realPath) and $this->excludeFiles[] = $realPath;
        return $this;
    }

    /**
     * Define cache file
     *
     * @param non-empty-string|null $path Set to {@see null} to use default value
     */
    public function cache(?string $path): self
    {
        $this->cacheFile = $path;
        return $this;
    }

    /**
     * Allow risky rules
     */
    public function allowRisky(bool $value = true): self
    {
        $this->allowRisky = $value;
        return $this;
    }

    public function build(): Config
    {
        $finder = (new Finder());
        $finder->in($this->includeDirs);
        $finder->append($this->includeFiles);
        /** @var object{dir: non-empty-string|null, white: bool} */
        $lastDir = (object) ['dir' => null, 'white' => false];

        $this->excludeDirs !== [] || $this->excludeFiles !== [] and $finder->filter(
            function (\SplFileInfo $file) use ($lastDir): bool {
                // Concrete files excluded by the user
                if ($file->isFile() && \in_array($file->getRealPath(), $this->excludeFiles, true)) {
                    return false;
                }

                // Directories excluded by the user
                $initDir = $file->isDir() ? $file->getRealPath() : \dirname($file->getRealPath());
                if ($lastDir->dir === $initDir) {
                    return $lastDir->white;
                }

                $dir = $initDir;
                while (!\in_array($dir, $this->includeDirs, true)) {
                    if (\in_array($dir, $this->excludeDirs, true)) {
                        $lastDir->dir = $initDir;
                        $lastDir->white = false;
                        return false;
                    }

                    $dir = \dirname($dir);
                }

                $lastDir->dir = $initDir;
                return $lastDir->white = true;
            },
        );

        $config = new Config();
        $config->setFinder($finder);
        $config->setRiskyAllowed($this->allowRisky);
        $this->cacheFile === null or $config->setCacheFile($this->cacheFile);

        $config->setRules($this->rules->getRules($this->allowRisky));
        $config->setParallelConfig(ParallelConfigFactory::detect());

        return $config;
    }
}
