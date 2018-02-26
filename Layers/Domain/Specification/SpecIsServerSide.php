<?php
namespace Sfynx\CoreBundle\Layers\Domain\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;

/**
 * Class SpecIsServerSide
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsServerSide extends AbstractSpecification
{
    /**
     * return true if the command is validated
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return $object->handler->query->getIsServerSide();
    }
}
