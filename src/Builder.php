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
     * Exclude directory from scan
     *
     * @param non-empty-string $path
     */
    public function exclude(string $path): self
    {
        $path = \realpath($path) or throw new \InvalidArgumentException("File or directory not found: $path");
        \is_dir($path) and $this->excludeDirs[] = $path;
        \is_file($path) and $this->excludeFiles[] = $path;
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
        $finder->exclude($this->excludeDirs);
        $finder->append($this->includeFiles);
        $this->excludeFiles === [] or $finder->filter(
            fn(\SplFileInfo $info) => !\in_array($info->getRealPath(), $this->excludeFiles, true),
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
