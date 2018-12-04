<?php
namespace Sfynx\CoreBundle\Generator\Application\Config;

use Sfynx\CoreBundle\Generator\Application\Config\Config;

/**
 * Class Parser
 * @package Sfynx\CoreBundle\Generator\Application\Config
 */
class Parser
{
    /**
     * @param $argv
     * @return Config
     */
    public function parse(array $argv): Config
    {
        $config = new Config();
        $config->fromArray($argv);

        return $config;
    }
}