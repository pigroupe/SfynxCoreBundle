<?php
/*
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Application\Config\Validation;

use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Application\Config\Generalisation\ValidationInterface;

/**
 * Locates file. Il no file is provided, it will search for default .phpmetrics.yml file
 * @package Sfynx\CoreBundle\Generator\Application\Config\Validation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ConfigLocator implements ValidationInterface
{
    /**
     * List of concrete ResponseProfiler that can be built using this factory.
     * @var string[]
     */
    private static $defaultConf = ['sfynx-ddd-generator.yml', 'sfynx-ddd-generator.yml.dist', 'sfynx-ddd-generator-dist.yml'];

    /**
     * Default files to check
     * @var array
     */
    private $defaults = [];

    /**
     * Constructor
     * @param array $defaults
     */
    public function __construct(array $defaults = null) {
        if(is_null($defaults)) {
            $defaults = static::$defaultConf;
        }
        $this->defaults = $defaults;
    }

    /**
     * @inheritdoc
     */
    public function validate(Config $config)
    {
        $filename = null;
        if ($config->has('conf-file')) {
            $filename = $config->get('conf-file');
        }
        if(null === $filename) {
            foreach($this->defaults as $filenameToCheck) {
                $filenameToCheck = getcwd().DIRECTORY_SEPARATOR.$filenameToCheck;
                if (true === $this->isFileAccessible($filenameToCheck)) {
                    $filename = $filenameToCheck;
                }
            }
        }
        if(false === ($this->isFileAccessible($filename)
            && $this->isExtensionFileAccessible($filename))
        ) {
            throw new ConfigException('configuration file is not accessible');
        }
        $config->set('conf-file', $filename);
    }

    /**
     * @param string $filename
     *
     * @return boolean
     */
    private function isFileAccessible($filename)
    {
        return file_exists($filename) && is_readable($filename);
    }

    /**
     * @param string $filename
     *
     * @return boolean
     */
    private function isExtensionFileAccessible($filename)
    {
        return in_array(pathinfo($filename, PATHINFO_EXTENSION), ['yml']);
    }
}