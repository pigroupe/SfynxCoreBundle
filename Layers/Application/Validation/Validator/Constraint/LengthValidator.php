<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Length;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\Traits\TraitValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class LengthValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class LengthValidator extends ConstraintValidator
{
    use TraitValidator;

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Length $constraint */
        self::checkConstraintType($constraint, Length::class);

        $limitMin = $constraint->getMin();
        $limitMax = $constraint->getMax();
        $size = mb_strlen($value);

        // Check the minimum length.
        if (null !== $limitMin && $size < $constraint->getMin()) {
            $this->context->addViolation(
                $constraint->messages[Length::MIN_ERROR],
                ['%value%', $value, '%minLength%', $constraint->getMin()]
            );
        }

        // Check the maximum length.
        if (null !== $limitMax && $size > $constraint->getMax()) {
            $this->context->addViolation(
                $constraint->messages[Length::MAX_ERROR],
                ['%value%', $value, '%maxLength%', $constraint->getMax()]
            );
        }
    }
}
