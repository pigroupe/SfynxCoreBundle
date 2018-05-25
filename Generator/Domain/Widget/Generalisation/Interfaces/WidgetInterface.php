<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces;

use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Domain\Widget\WidgetParser;

/**
 * Class WidgetInterface
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Generalisation\Interfaces
 * @interface
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface WidgetInterface
{
    const CAT_ARCHI_COM = 'common';
    const CAT_ARCHI_PRES = 'presentation';
    const CAT_ARCHI_APP = 'application';
    const CAT_ARCHI_DOM = 'domain';
    const CAT_ARCHI_INFRA = 'infrastructure';

    const KEY_CATEGORY = 'category';
    const KEY_NAME = 'name';
    const KEY_DESCRIPTION = 'desc';
    const KEY_TAG = 'tag';
    const KEY_NAMESPACE = 'namespace';
    const KEY_CLASS = 'class';
    const KEY_CLASSNAME = 'classname';

    /**
     * @param WidgetParser $parser
     * @return void
     */
    public function apply(WidgetParser $parser): void;

    /**
     * @return Config
     */
    public function getConfig(): Config;

    /**
     * @return WidgetParser
     */
    public function getParser(): WidgetParser;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return integer
     */
    public function getCategory(): string;

    /**
     * @return string
     */
    public function getDescription(): string;
}
