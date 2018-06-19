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
 * Config cqrs
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigCqrs implements ValidationInterface
{
    const DEFAULT_CONF = 'Test';

    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        $config->set('conf-cqrs', static::DEFAULT_CONF);

        if (!empty($config->get('conf-array')['cqrs'])) {
            $config->set('conf-cqrs', $config->get('conf-array')['cqrs']);
        }
    }
}