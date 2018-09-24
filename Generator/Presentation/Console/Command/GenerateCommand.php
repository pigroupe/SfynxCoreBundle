<?php

namespace Sfynx\CoreBundle\Generator\Presentation\Console\Command;

use Sfynx\CoreBundle\Generator\Application\Command\GenerateConsoleCommand;
use Sfynx\CoreBundle\Generator\Presentation\Adapter\ConsoleCommandAdapter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

/**
 * Class GenerateCommand
 * @package Sfynx\CoreBundle\Generator\Presentation\Console\Command
 */
class GenerateCommand extends Command
{
    /**
     * @return void
     */
    protected function configure() : void
    {
        $this->setName('sfynx:ddd:generate')
            ->setDescription('Generate from DDD configuration files')
            ->addArgument(
                'conf-dir',
                InputArgument::OPTIONAL,
                'List of directories to parse, separated by a comma (,)'
            )
            ->addOption('namespace', null, InputOption::VALUE_REQUIRED, '')
            ->addOption('conf-file', null, InputOption::VALUE_REQUIRED, 'YAML configuration file(s)')
            ->addOption('conf-autoload', null, InputOption::VALUE_REQUIRED, 'autoload.php file that you want to include')
            ->addOption('report-dir', null, InputOption::VALUE_REQUIRED, 'Folder where artifact will be generated')
            ->addOption('report-template', null, InputOption::VALUE_REQUIRED, 'Folder name where report template files are')
            ->addOption('report-xmi', null, InputOption::VALUE_REQUIRED, ' XMI options separeted by |')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        // start
        $startTime = \microtime(true);
//        \ini_set('xdebug.max_nesting_level', 3000);

        $generateCommand = (new ConsoleCommandAdapter(GenerateConsoleCommand::class, $output))->createCommandFromRequest($input);

        $confFiles = $generateCommand->process();

        $endTime = \microtime(true);
        // end

        $output->writeln('');
        $output->writeln(
            \sprintf(
                '<info>%d configuration file%s</info> processed',
                count($confFiles),
                count($confFiles) > 1 ? 's' : ''
            )
        );

        if ($output->isVerbose()) {
            $text = sprintf(
                '%s<comment>Time: %s sec, Memory: %4.2fMb</comment>',
                PHP_EOL,
                \round($endTime - $startTime, 3),
                \memory_get_peak_usage(true) / (1024 * 1024)
            );
            $output->writeln($text);
        }

        if ($output->isDebug()) {
            $output->writeln('');
            foreach ($confFiles as $file) {
                $output->writeln(\sprintf('<info>- </info> %s', $file));
            }
        }

        $output->writeln('');
    }
}
