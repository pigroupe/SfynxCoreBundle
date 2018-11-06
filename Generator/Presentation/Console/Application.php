<?php

namespace Sfynx\CoreBundle\Generator\Presentation\Console;

use Sfynx\CoreBundle\Generator\Presentation\Console\Command\GenerateCommand;
use Symfony\Component\Yaml\Command\LintCommand;

/**
 * Symfony Console Application to handle the SQLite compatinfo database.
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * @return array
     */
    protected function getDefaultCommands(): array
    {
        $defaultCommands = parent::getDefaultCommands();

        // https://symfony.com/doc/3.4/components/yaml.html#syntax-validation
        $defaultCommands[] = new LintCommand();

        $defaultCommands[] = new GenerateCommand();

        return $defaultCommands;
    }

    /**
     * @return string
     */
    public function getLongVersion(): string
    {
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {

                return sprintf(
                    '<info>%s</info> version <comment>%s</comment>',
                    $this->getName(),
                    $this->getVersion()
                );
            }

            return $this->getName();
        }

        return 'Console Tool';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return 'v2.11.3';
    }
}
