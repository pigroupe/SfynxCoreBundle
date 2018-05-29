<?php
namespace Sfynx\CoreBundle\Layers\Domain\Specification;

use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use stdClass;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

/**
 * Class SpecIsHandlerCreatedWithQueryInterface
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsHandlerCreatedWithQueryInterface extends AbstractSpecification
{
    /**
     * return true if the command is validated
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return property_exists($object->handler, 'query') &&
            ($object->handler->query instanceof QueryInterface);
    }
}
