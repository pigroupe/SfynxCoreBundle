<?php
namespace Sfynx\CoreBundle\Layers\Domain\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;

/**
 * Class SpecIsEntityCreated
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsEntityCreated extends AbstractSpecification
{
    /**
     * return true if we are in the creative process
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return property_exists($object, 'entityId') && (
                '' === $object->entityId || null === $object->entityId
            );
    }
}
