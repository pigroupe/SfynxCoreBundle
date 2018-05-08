<?php
namespace Sfynx\CoreBundle\Layers\Domain\Component\Generalisation\Interfaces;

use \stdClass;

/**
 * Interface ResolverInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Component\Generalisation\Interfaces
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
