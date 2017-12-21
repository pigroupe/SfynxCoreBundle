<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\FutureDate;
use Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\ConstraintValidator\Traits\TraitValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\{
    ConstraintDefinitionException, InvalidOptionsException, MissingOptionsException, UnexpectedTypeException
};

/**
 * Class FutureDateValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class FutureDateValidator extends AbstractDateValidator
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
        /** @var FutureDate $constraint */
        self::checkConstraintType($constraint, FutureDate::class);

        $this->checkDateComparison($value, $constraint);
    }
}
