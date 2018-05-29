<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces;

use \stdClass;
use Sfynx\CoreBundle\Layers\Domain\Component\Generalisation\Interfaces\ResolverInterface;

/**
 * Class ExtensionInterface
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Templater\Generalisation\Interfaces
 * @interface
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface ExtensionInterface extends ResolverInterface
{
    /**
     * @return stdClass
     */
    public function getClassExtention(): stdClass;
}
