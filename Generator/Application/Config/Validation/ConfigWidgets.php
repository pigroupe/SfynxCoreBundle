<?php

/*
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Application\Config\Validation;

use Symfony\Component\Yaml\Yaml;

use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;
use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Domain\Widget\WidgetParser;
use Sfynx\CoreBundle\Generator\Domain\Component\Config\Loader;

/**
 * Config ConfigWidgets
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigWidgets implements ValidationInterface
{
    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        $array = $config->get('conf-array');

        $conf = false;
        foreach (WidgetParser::handlerList($config) as $widget) {
            if (array_key_exists($widget->getCategory(), $array['widgets'])
                && array_key_exists($widget::TAG, $array['widgets'][$widget->getCategory()])
                && (null !== $array['widgets'][$widget->getCategory()][$widget::TAG])
            ) {
                $conf[$widget::TAG] = $array['widgets'][$widget->getCategory()][$widget::TAG];
            }
        }
        $config->set('conf-widget', $conf);
    }
}