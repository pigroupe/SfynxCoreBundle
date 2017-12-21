<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Color;
use Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\ConstraintValidator\Traits\TraitValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ColorValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class ColorValidator extends ConstraintValidator
{
    use TraitValidator;

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        self::checkConstraintType($constraint, Color::class);

        /** @var Color $constraint */
        if (!is_string($value) || !preg_match($constraint->getRegex(), $value)) {
            $this->context->addViolation($constraint->message, ['%string%' => $value]);
        }
    }
}
