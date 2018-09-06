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
    protected static $handlerList = [
        0 => Validation\ConfigAutoload::class,
        1 => Validation\ConfigLocator::class,
        2 => Validation\ConfigLoader::class,
        3 => Validation\ConfigNamespace::class,
        4 => Validation\ConfigCqrs::class,
        5 => Validation\ConfigIndentation::class,
        6 => Validation\ConfigWidgets::class,
        7 => Validation\ConfigIndex::class,
        8 => Validation\ConfigTemplate::class,
        9 => Validation\ConfigMapping::class,
        10 => Validation\ConfigValidator::class,
        11 => Validation\ConfigXmi::class,
    ];

    /**
     * @param Config $config
     * @throws ConfigException
     */
    public function validate(Config $config)
    {
        foreach (static::$handlerList as $key => $handler) {
            (new $handler())->validate($config);
        }
    }

    /**
     * @return string
     */
    public function help()
    {
        return <<<EOT
Usage:

    dashboard [...options...] <directories>

Required:

    <directories>                       List of directories to parse, separated by a comma (,)

Optional:

    --namespace
    --report-dir=<directory>            Folder where artifact will be generated
    --report-template=<directory>       Folder name where report template files are 
    --report-xmi="<XMIoptions>"         XMI options separeted by space. exemple --report-xmi="--output=/var/www/src/generator/MyContext/MyContext.xmi --autoload=vendor --recursive /var/www/src/generator/MyContext" 
    --conf-file                         Complet path of the configuration file
    --conf-autoload=<filePath>          autoload.php file that you want to include           
    --quiet                             Quiet mode
    --version                           Display current version


XMIoptions:
    --autoload=folder           Folder for the autoload
    --no-private                do not output private methods and attributes
    --no-protected              do not output protected methods and attributes
    --no-public                 do not output public methods and attributes
    --strict                    activate E_STRICT error_reporting
    --help                      shows this help
    --recursive                 look for .php files in specified directories
    --output=<output.xmi>       select xmi output file
        
Examples:

    sfynx-ddd-generator --namespace=MyContext --conf-file=sfynx-ddd-generator.yml --report-dir="./src" --report-template=default

        Generate the artifact on the "./src" folder with the default template

EOT;
    }
}
