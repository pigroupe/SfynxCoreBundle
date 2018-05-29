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
 * Config ConfigIndex
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigIndex implements ValidationInterface
{
    /** @var bool */
    protected $conf = false;

    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        $widgets = $config->get('conf-widget');

        if ($widgets) {
            $this->setIndex($widgets);
        }
        $config->set('conf-index', $this->conf);
    }

    /**
     * @param array $widget
     * @return void
     */
    protected function setIndex(array $widgets): void
    {
        foreach ($widgets as $widget) {
            if (array_key_exists('class', $widget)) {
                $class = $widget['class'];
                $arguments = [];
                if (array_key_exists('arguments', $widget)) {
                    $arguments = $widget['arguments'];
                }
                if (is_array($class)) {
                    foreach ($class as $cl) {
                        $this->conf[$cl] = $arguments;
                    }
                } else {
                    $this->conf[$class] = $arguments;
                }
            } elseif (is_array($widget)) {
                foreach ($widget as $w) {
                    $this->setIndex($w);
                }
            }
        }
    }
}