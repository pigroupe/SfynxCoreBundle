<?php

/*
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Application\Config\Validation;

use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;

/**
 * Config Autoload
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigAutoload implements ValidationInterface
{
    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        if ($config->has('conf-autoload')) {
            $loader = self::includeIfExists($config->get('conf-autoload'));
        }
    }

    /**
     * @param string $file
     * @return mixed
     * @static
     */
    public static function includeIfExists(string $file)
    {
        if (\file_exists($file)) {
            return include $file;
        }
    }
}