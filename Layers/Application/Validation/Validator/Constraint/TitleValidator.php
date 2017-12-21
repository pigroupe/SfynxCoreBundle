<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Title;
use Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\ConstraintValidator\Traits\TraitValidator;

/**
 * Class TitleValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class TitleValidator extends ConstraintValidator
{
    use TraitValidator;

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Title $constraint */
        self::checkConstraintType($constraint, Title::class);

        if (!is_string($value) || !preg_match($constraint->getRegex(), $value)) {
            $this->context->addViolation($constraint->message, ['%string%' => $value]);
        }
    }
}
