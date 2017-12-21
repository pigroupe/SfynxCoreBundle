<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Name
use Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\ConstraintValidator\Traits\TraitValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NameValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class NameValidator extends ConstraintValidator
{
    use TraitValidator;

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Name $constraint */
        self::checkConstraintType($constraint, Name::class);

        if (!is_string($value) || !preg_match($constraint->getRegex(), $value)) {
            $this->context->addViolation($constraint->message, ['%string%' => $value]);
        }
    }
}
