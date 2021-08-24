<?php
declare(strict_types=1);

namespace Spiral\CodeStyle\Commands;

use PHP_CodeSniffer\Runner;
use PhpCsFixer\Console\Application;
use Spiral\CodeStyle\CodeStyleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixCommand extends Command
{
    protected static $defaultName = 'fix';
    /** @var CodeStyleHelper */
    private $helper;

    public function __construct(CodeStyleHelper $helper)
    {
        $this->helper = $helper;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = $this->helper->wrapPaths($input->getArgument('paths'));

        if (!$input->getOption('preserve-endings')) {
            $this->helper->normalizeEndings($paths);
        }

        $this->runPHPCBF($paths, $input->getOption('ruleset'));

        // PHPCS-Fixer call
        $application = new Application();
        $application->setAutoExit(false);

        foreach ($paths as $path) {
            $_SERVER['argv'] = [
                'placeholder',
                'fix',
                $path,
                '--config=' . $input->getOption('config'),
            ];

            $application->run();
        }

        return Command::SUCCESS;
    }

    private function runPHPCBF(array $paths, string $standardFilePath): void
    {
        // PHPCBF call
        $_SERVER['argv'] = array_merge([
            'placeholder',
            '--report=code',
            '--standard=' . $standardFilePath,
        ], $paths);

        $runner = new Runner();
        $runner->runPHPCBF();
    }

    protected function configure()
    {
        $this->setDescription('Code style fix command');

        $this
            ->addArgument(
                'paths',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Enumerate directories or files to check'
            )
            ->addOption(
                'config',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Change default config file path',
                $this->helper->getPhpCsFixerConfigPath()
            )
            ->addOption(
                'ruleset',
                'r',
                InputOption::VALUE_OPTIONAL,
                'Change default ruleset file path',
                $this->helper->getPhpCsConfigPath()
            )
            ->addOption(
                'preserve-endings',
                'l',
                InputOption::VALUE_NONE,
                'Preserve original line-endings, otherwise forced into LF'
            );
    }
}