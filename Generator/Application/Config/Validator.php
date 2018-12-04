<?php

namespace Sfynx\CoreBundle\Generator\Application\Config;

use Sfynx\CoreBundle\Generator\Application\Config\Validation;
use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;


/**
 * Class Validator
 * @package Sfynx\CoreBundle\Generator\Application\Config
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Validator implements ValidationInterface
{
    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected $handlerList = [
        Validation\ConfigAutoload::class,
        Validation\ConfigLocator::class,
        Validation\ConfigLoader::class,
        Validation\ConfigNamespace::class,
        Validation\ConfigCqrs::class,
        Validation\ConfigIndentation::class,
        Validation\ConfigWidgets::class,
        Validation\ConfigIndex::class,
        Validation\ConfigTemplate::class,
        Validation\ConfigMapping::class,
        Validation\ConfigXmi::class,
        Validation\ConfigValidator::class,
    ];

    /**
     * @param Config $config
     * @throws ConfigException
     */
    public function validate(Config $config)
    {
        foreach ($this->handlerList as $handler) {
            (new $handler())->validate($config);
        }
    }
}
