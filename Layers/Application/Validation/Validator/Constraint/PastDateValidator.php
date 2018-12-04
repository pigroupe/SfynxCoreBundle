<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\PastDate;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\Traits\TraitValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\{
    ConstraintDefinitionException, InvalidOptionsException, MissingOptionsException, UnexpectedTypeException
};

/**
 * Class PastDateValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class PastDateValidator extends AbstractDateValidator
{
    use TraitValidator;

    /**
     * {@inheritdoc}
     * @throws MissingOptionsException
     * @throws InvalidOptionsException
     * @throws ConstraintDefinitionException
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var PastDate $constraint */
        self::checkConstraintType($constraint, PastDate::class);

        $this->checkDateComparison($value, $constraint);
    }
}
