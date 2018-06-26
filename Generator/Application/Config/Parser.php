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
    public function parse($argv): Config
    {
        $config = new Config();

        if (sizeof($argv) === 0) {
            return $config;
        }

        if (preg_match('!\.php$!', $argv[0]) || preg_match('!dddgenerator$!', $argv[0]) || preg_match('!dddgenerator.phar$!', $argv[0])) {
            array_shift($argv);
        }

        // arguments with options
        foreach ($argv as $k => $arg) {
            if (preg_match('!\-\-([\w\-]+)=(.*)!', $arg, $matches)) {
                list(, $parameter, $value) = $matches;
                $config->set($parameter, trim($value, ' "\''));
                unset($argv[$k]);
            }
        }

        // arguments without options
        foreach ($argv as $k => $arg) {
            if (preg_match('!\-\-([\w\-]+)$!', $arg, $matches)) {
                list(, $parameter) = $matches;
                $config->set($parameter, true);
                unset($argv[$k]);
            }
        }

        return $config;
    }
}