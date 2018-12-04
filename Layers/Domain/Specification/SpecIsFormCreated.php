<?php
namespace Sfynx\CoreBundle\Layers\Domain\Specification;

use stdClass;
use Sfynx\SpecificationBundle\Specification\AbstractSpecification;
use Symfony\Component\Form\FormInterface;

/**
 * Class SpecIsFormCreated
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Specification
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecIsFormCreated extends AbstractSpecification
{
    /**
     * return true if we are in the creative process
     *
     * @param stdClass $object
     * @return bool
     */
    public function isSatisfiedBy(stdClass $object): bool
    {
        return property_exists($object, 'data')
            && property_exists($object->data, 'form')
            && ($object->data->form instanceof FormInterface);
    }
}
