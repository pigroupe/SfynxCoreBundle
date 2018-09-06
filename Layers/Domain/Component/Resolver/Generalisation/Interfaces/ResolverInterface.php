<?php
namespace Sfynx\CoreBundle\Layers\Domain\Component\Resolver\Generalisation\Interfaces;

use \stdClass;

/**
 * Interface ResolverInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Component\Resolver\Generalisation\Interfaces
 */
interface ResolverInterface
{
    /**
     * @param bool $toStdClass
     * @param array $options
     * @return array|stdClass
     */
    public function getResolverParameters(bool $toStdClass = false, array $options = []);
}
