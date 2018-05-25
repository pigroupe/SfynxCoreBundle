<?php

/*
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Application\Config\Validation;

use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;

/**
 * Config checker
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigValidator implements ValidationInterface
{
    /**
     * List of concrete configuration targets which are to be there
     * @var string[]
     */
    private static $defaultConf = ['conf-cqrs', 'conf-indentation', 'conf-file', 'conf-array', 'namespace', 'report-dir', 'report-template', 'files'];

    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        foreach (static::$defaultConf as $key) {
            $value = $config->get($key);
            if ($config->has($key) && empty($value) || true === $value) {
                throw new ConfigException(sprintf('%s option requires a value', $key));
            }
        }
    }
}