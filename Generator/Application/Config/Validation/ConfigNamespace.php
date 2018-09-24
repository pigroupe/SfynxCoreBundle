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
 * Config namespace
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigNamespace implements ValidationInterface
{
    const DEFAULT_CONF = 'TestContext';

    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        if ($config->has('namespace')) {
            $config->set('namespace', $config->get('namespace'));
        } elseif (!empty($config->get('conf-array')['namespace'])) {
            $config->set('namespace', $config->get('conf-array')['namespace']);
        } else {
            $config->set('namespace', static::DEFAULT_CONF);
        }
    }
}