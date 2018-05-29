<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Validation\SpecHandler;

use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Logical\XorSpec;
use Sfynx\SpecificationBundle\Specification\Logical\TrueSpec;
use Sfynx\CoreBundle\Layers\Application\Command\Validation\SpecHandler\Generalisation\AbstractCommandSpecHandler;
use Sfynx\AuthBundle\Domain\Specification\Authorisation\SpecIsRoleAdmin;
use Sfynx\AuthBundle\Domain\Specification\Authorisation\SpecIsRoleUser;
use Sfynx\AuthBundle\Domain\Specification\Authorisation\SpecIsRoleAnonymous;

/**
 * Class UpdateCommandValidationHandler.
 *
 * @category   Sfynx\AuthBundle
 * @package    Application
 * @subpackage Cqrs\User\Command\Validation\SpecHandler
 */
class FormCommandSpecHandler extends AbstractCommandSpecHandler
{
    /**
     * @return XorSpec
     */
    public function initSpecifications(): InterfaceSpecification
    {
        return new TrueSpec();

//        //it is an absurd example
//        return new XorSpec(
//            new XorSpec(
//                new SpecIsRoleAdmin("authenticate permission denied, you must have admin role"),
//                new SpecIsRoleAnonymous("authenticate permission denied, you must have anonymous role")
//            ),
//            new SpecIsRoleUser("authenticate permission denied, you must have user role")
//        );
    }
}
