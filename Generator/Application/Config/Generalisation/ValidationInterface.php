<?php
namespace Sfynx\CoreBundle\Generator\Application\Config\Generalisation;

use Sfynx\CoreBundle\Generator\Application\Config\Config;

interface ValidationInterface
{
    /**
     * Validation configuration class
     *
     * @param Config $config
     * @return void
     *
     * @throws ConfigException
     */
    public function validate(Config $config);
}
