<?php

namespace Sfynx\CoreBundle\Generator\Application\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

use Sfynx\CoreBundle\Generator\Domain\Report\Exception\ReportException;
use Sfynx\CoreBundle\Generator\Domain\Widget\Exception\WidgetException;
use Sfynx\CoreBundle\Generator\Domain\Widget\WidgetParser;
use Sfynx\CoreBundle\Generator\Application\Config\Validator;
use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Application\Config\Parser;

class GenerateConsoleCommand implements ConsoleCommandInterface
{
    /** @var array  */
    protected $arguments;

    /** @var OutputInterface */
    protected $output;

    public function __construct(array $args, OutputInterface $output)
    {
        $this->arguments = $args;
        $this->output = $output;
    }

    public function process()
    {
        $confDir = $this->arguments['conf-dir'];

        if (empty($confDir)) {
            // use only a single file
            $confFile = realpath($this->arguments['conf-file']);

            if (!file_exists($confFile)) {
                throw new \InvalidArgumentException(
                    sprintf('Configuration file %s does not exists.', $confFile)
                );
            }

            $confDir = dirname($confFile);
            $isFolderScan = false;
        } else {
            // scan a folder to search for yaml configuration file(s)
            if (!is_dir($confDir) || !file_exists($confDir)) {
                throw new \InvalidArgumentException(
                    sprintf('Configuration folder %s does not exists or is not readable.', $confFolder)
                );
            }
            $isFolderScan = true;
        }

        $additionalDir = $confDir . 'widgets';
        $configDirectories = array_map('realpath', [$confDir, $additionalDir]);

        $confFiles = [];

        $confFile = $isFolderScan ? null : $confFile;

        $config = (new Parser())->parse($this->arguments);
        $config->set('conf-directories', $configDirectories);


        foreach ($this->fileProvider($configDirectories[0], $confFile) as $yamlFile) {
            $confFiles[] = $yamlFile;

            try {
                (new Validator())->validate($config);
            } catch (ConfigException $e) {
                $this->output->writeln(\sprintf("<error>%s</error>", $e->getMessage()));
                exit(1);
            }

            // parse widgets
            try {
                $reporter = (new WidgetParser($config, $this->output))->run();
            } catch (WidgetException $e) {
                $this->output->writeln(\sprintf('<error>%s</error>', $e->getMessage()));
                exit(1);
            }
            $this->output->writeln('<info>++</info> Executing system analyzes...');

            // create artifact
            try {
                $reporter->generate();
            } catch (ReportException $e) {
                $this->output->writeln(\sprintf('<error>%s</error>', $e->getMessage()));
                exit(1);
            }
        }

        return $confFiles;
    }

    protected function fileProvider($configDirectory, $confFile = null)
    {
        if (empty($confFile)) {
            $finder = new Finder();
            $finder
                ->ignoreUnreadableDirs()
                ->in($configDirectory)
                ->depth('== 0')
                ->files()
                ->name('*.yml');

            foreach ($finder as $file) {
                $confFile = $file->getRealPath();
                yield $confFile;
            }
        } else {
            yield $confFile;
        }
    }
}
