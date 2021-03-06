<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class PhoneNumberValidator
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class PhoneNumberValidator extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match($constraint->getRegex(), $value)) {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
