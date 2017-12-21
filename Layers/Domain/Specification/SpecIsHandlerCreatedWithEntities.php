<?php
namespace Sfynx\CoreBundle\Layers\Domain\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;

use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;

/**
 * Class SpecIsHandlerCreatedWithEntities
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsHandlerCreatedWithEntities extends AbstractSpecification
{
    /**
     * return true if the command is validated
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return property_exists($object->handler, 'entities') && (
                null === $object->handler->entities
                || count($object->handler->entities) == 0
                || (
                    count($object->handler->entities) > 0 && $object->handler->entities[0] instanceof EntityInterface
                )
            );
    }
}
