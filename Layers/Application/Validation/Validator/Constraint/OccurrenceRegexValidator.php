<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\OccurrenceRegex;
use Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\ConstraintValidator\Traits\TraitValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class OccurrenceRegexValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class OccurrenceRegexValidator extends ConstraintValidator
{
    use TraitValidator;

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var OccurrenceRegex $constraint */
        self::checkConstraintType($constraint, OccurrenceRegex::class);

        $regex = $constraint->getRegex();
        if (empty($regex)) {
            return;
        }

        $nbOccurrence = preg_match_all($regex, $value);

        // Check the minimum length.
        if (!$constraint->respectMin($nbOccurrence)) {
            $this->context->addViolation(
                $constraint->messages[OccurrenceRegex::MIN_ERROR],
                ['%value%', $value, '%min%', $constraint->getMin()]
            );
        }

        // Check the maximum length.
        if (!$constraint->respectMax($nbOccurrence)) {
            $this->context->addViolation(
                $constraint->messages[OccurrenceRegex::MAX_ERROR],
                ['%value%', $value, '%max%', $constraint->getMax()]
            );
        }
    }
}
