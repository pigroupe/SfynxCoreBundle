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
 * Config locator
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigTemplate implements ValidationInterface
{
    const DEFAULT_CONF = 'default';

    /**
     * @var array
     * @static
     */
    static $templateType = array('default');

    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        if (!$config->has('report-template')) {
            if (!empty($config->get('conf-array')['template'])
                && \in_array($config->get('conf-array')['template'], self::$templateType)
            ) {
                $config->set('report-template', $config->get('conf-array')['template']);
            } else {
                $config->set('report-template', static::DEFAULT_CONF);
            }
        }
    }
}