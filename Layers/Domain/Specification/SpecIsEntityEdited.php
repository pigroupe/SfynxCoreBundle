<?php
namespace Sfynx\CoreBundle\Layers\Domain\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;

/**
 * Class SpecIsEntityEdited
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsEntityEdited extends AbstractSpecification
{
    /**
     * return true if we are in the editing process
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

        return property_exists($object, 'entityId') &&
            null !== $object->entityId &&
            '' !== $object->entityId &&
            (\is_int($object->entityId) || preg_match($UUIDv4, $object->entityId))
            ;
    }
}
