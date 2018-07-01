<?php
namespace Sfynx\CoreBundle\Generator\Application;

use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Application\Config\Validator;
use Sfynx\CoreBundle\Generator\Application\Config\Parser;
use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Domain\Widget\Exception\WidgetException;
use Sfynx\CoreBundle\Generator\Domain\Report\Exception\ReportException;
use Sfynx\CoreBundle\Generator\Domain\Widget\WidgetParser;
use Sfynx\CoreBundle\Generator\Domain\Component\File\Finder;
use Sfynx\CoreBundle\Generator\Domain\Component\Output\CliOutput;
use Sfynx\CoreBundle\Generator\Domain\Component\Issue\Issuer;

/**
 * Application start
 * @package Sfynx\CoreBundle\Generator\Application
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Application
{
    /**
     * @param $argv
     *
     * @return void
     * @access public
     * @throws ConfigException
     * @throws WidgetException
     * @throws ReportException
     */
    public function run($argv)
    {
        // start
        $startTime = microtime(true);

        // config
        ini_set('xdebug.max_nesting_level', 3000);
        // formatter
        $output = new CliOutput();
        // issues and debug
        $issuer = new Issuer($output);
        // config
        $config = (new Parser())->parse($argv);

        if ($config->has('conf-dir')) {
            $finder = new Finder(['yml']);
            $files = $finder->fetch(explode(',', $config->get('conf-dir')));

            foreach ($files as $file) {
                $config->set('conf-file', $file);

                $configNew = clone($config);
                $this->_run($output, $issuer, $configNew);
            }
        } elseif ($config->has('conf-file')) {
            $this->_run($output, $issuer, $config);
        }

        $endTime = microtime(true);
        // end

        $output->writeln('');
        $output->writeln(sprintf('<info>++</info> generated in (%ss) times', round($endTime - $startTime, 3)));
        $output->writeln('');
        $output->clearln();
    }

    /**
     * @param CliOutput $output
     * @param Issuer $issuer
     * @param Config $config
     */
    protected function _run(CliOutput $output, Issuer $issuer, Config $config)
    {
        // Set configurations and validate theme
        try {
            (new Validator())->validate($config);
        } catch (ConfigException $e) {
            if ($config->has('help')) {
                $output->writeln((new Validator())->help());
                exit(0);
            }
            if ($config->has('version')) {
                $output->writeln(sprintf("<info>++</info> Ddd Generator %s \nby PI-GROUPE <http://www.pi-groupe.net>", $this->getVersion()));
                exit(0);
            }
            $output->writeln(sprintf("<error>%s</error>", $e->getMessage()));
            $output->writeln((new Validator())->help());
            exit(1);
        }
        // set Quiet Mode
        if ($config->has('quiet')) {
            $output->setQuietMode(true);
        }
        // parse widgets
        try {
            $reporter = (new WidgetParser($config, $output, $issuer))->run();
        } catch (WidgetException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            exit(1);
        }
        // create artifact
        try {
            $reporter->generate();
        } catch (ReportException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            exit(1);
        }
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return 'v2.9.14';
    }
}