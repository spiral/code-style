<?php

declare(strict_types=1);

namespace Spiral\CodeStyle\Tests\Unit;

use Spiral\CodeStyle\Builder;
use Testo\Assert;
use Testo\Codecov\Covers;
use Testo\Lifecycle\AfterTest;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
#[Covers(Builder::class)]
final class BuilderExcludeTest
{
    private const FILES = [
        'a.php',
        'b.php',
        'excluded/c.php',
        'excluded/nested/d.php',
        'kept/e.php',
        'kept/f.php',
    ];

    /** @var non-empty-string */
    private string $dir;

    #[BeforeTest]
    public function createTree(): void
    {
        $this->dir = \sys_get_temp_dir() . '/spiral-code-style-' . \uniqid();
        foreach (self::FILES as $file) {
            $path = $this->dir . '/' . $file;
            \is_dir(\dirname($path)) or \mkdir(\dirname($path), recursive: true);
            \file_put_contents($path, "<?php\n");
        }
    }

    #[AfterTest]
    public function removeTree(): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $file) {
            $file->isDir() ? \rmdir($file->getPathname()) : \unlink($file->getPathname());
        }

        \rmdir($this->dir);
    }

    public function testWithoutExclusionsFindsAllFiles(): void
    {
        $builder = Builder::create()->include($this->dir);

        Assert::same($this->foundFiles($builder), self::FILES);
    }

    public function testExcludeDirectorySkipsItsWholeSubtree(): void
    {
        $builder = Builder::create()
            ->include($this->dir)
            ->exclude($this->dir . '/excluded');

        Assert::same($this->foundFiles($builder), ['a.php', 'b.php', 'kept/e.php', 'kept/f.php']);
    }

    public function testExcludeFileSkipsOnlyThatFile(): void
    {
        $builder = Builder::create()
            ->include($this->dir)
            ->exclude($this->dir . '/kept/e.php');

        Assert::same($this->foundFiles($builder), [
            'a.php',
            'b.php',
            'excluded/c.php',
            'excluded/nested/d.php',
            'kept/f.php',
        ]);
    }

    public function testExcludeDirectoryAndFileTogether(): void
    {
        $builder = Builder::create()
            ->include($this->dir)
            ->exclude($this->dir . '/excluded/nested')
            ->exclude($this->dir . '/a.php');

        Assert::same($this->foundFiles($builder), ['b.php', 'excluded/c.php', 'kept/e.php', 'kept/f.php']);
    }

    public function testIncludedFileIsAddedNextToDirectories(): void
    {
        $builder = Builder::create()
            ->include($this->dir . '/excluded')
            ->include($this->dir . '/a.php');

        Assert::same($this->foundFiles($builder), ['a.php', 'excluded/c.php', 'excluded/nested/d.php']);
    }

    public function testCacheCanBeReset(): void
    {
        $config = Builder::create()->include($this->dir)->cache(null)->build();

        Assert::same($config->getCacheFile(), '.php-cs-fixer.cache');
    }

    /**
     * @return list<string> Paths relative to the temporary tree, sorted.
     */
    private function foundFiles(Builder $builder): array
    {
        $root = \realpath($this->dir);
        $files = [];
        foreach ($builder->build()->getFinder() as $file) {
            $files[] = \str_replace('\\', '/', \substr((string) $file->getRealPath(), \strlen($root) + 1));
        }

        \sort($files);
        return $files;
    }
}
