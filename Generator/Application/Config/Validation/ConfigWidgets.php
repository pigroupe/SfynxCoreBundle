<?php

/*
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Application\Config\Validation;

use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;
use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Domain\Widget\WidgetParser;
use Sfynx\CoreBundle\Generator\Domain\Component\Config\Loader;
use Symfony\Component\Console\Output\NullOutput;

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
        $conf = false;
        $config->set('conf-widget', $conf);

        if (!$config->has('conf-array')) {
            return;
        }

        $array = $config->get('conf-array');

        if (!isset($array['widgets']) || !is_array($array['widgets'])) {
            return;
        }

        $output = new NullOutput();

        $widgetParser = new WidgetParser($config, $output);

        foreach ($widgetParser->handlerList() as $widget) {
            if (\array_key_exists($widget->getCategory(), $array['widgets'])
                && \array_key_exists($widget::TAG, $array['widgets'][$widget->getCategory()])
                && (null !== $array['widgets'][$widget->getCategory()][$widget::TAG])
            ) {
                $conf[$widget::TAG] = $array['widgets'][$widget->getCategory()][$widget::TAG];
            }
        }
        $config->set('conf-widget', $conf);
    }
}