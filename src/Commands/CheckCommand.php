<?php
declare(strict_types=1);

namespace Spiral\CodeStyle\Commands;

use PHP_CodeSniffer\Runner;
use Spiral\CodeStyle\CodeStyleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    protected static $defaultName = 'check';

    /** @var CodeStyleHelper */
    private $helper;

    public function __construct(CodeStyleHelper $helper)
    {
        $this->helper = $helper;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $_SERVER['argv'] = array_merge([
            'placeholder',
            '--report=code',
            '--standard=' . $input->getOption('ruleset'),
        ], $this->helper->wrapPaths($input->getArgument('paths')));

        $runner = new Runner();
        $exitCode = $runner->runPHPCS();

        if ($exitCode === Command::SUCCESS) {
            $output->writeln('<info>No codestyle issues</info>');
        }

        return $exitCode;
    }

    protected function configure()
    {
        $this->setDescription('Code style correctness check command');

        $this
            ->addArgument(
                'paths',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Enumerate directories or files to check'
            )
            ->addOption(
                'ruleset',
                'r',
                InputOption::VALUE_OPTIONAL,
                'Change default ruleset file path',
                $this->helper->getPhpCsConfigPath()
            );
    }
}