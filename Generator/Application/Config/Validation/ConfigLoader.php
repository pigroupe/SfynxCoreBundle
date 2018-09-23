<?php

/*
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Application\Config\Validation;

use Symfony\Component\Config\FileLocator;
use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Domain\Component\Config\Loader;
use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;
use Sfynx\CoreBundle\Generator\Application\Config\FileLoader;

/**
 * Config factory
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigLoader implements ValidationInterface
{
    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        $configDirectories = $config->get('conf-directories');

        $fileLocator = new FileLocator($configDirectories);

        $loader = new FileLoader($fileLocator);

        $filename = $config->get('conf-file');

        if(!\file_exists($filename) || !\is_readable($filename)) {
            throw new ConfigException('configuration file is not accessible');
        }
        $array = $loader->load(realpath($filename));
        if (null === $array) {
            throw new ConfigException('configuration file is empty');
        }

        $config->set('conf-array', $array['default']);
    }
}