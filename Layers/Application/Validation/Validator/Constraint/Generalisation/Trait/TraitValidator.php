<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\Traits;

use Symfony\Component\Validator\Constraint;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ValidationException;

/**
 * Trait TraitValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Generalisation\ConstraintValidator\Traits
 */
trait TraitValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws ValidationException
     */
    abstract public function validate($value, Constraint $constraint): void;

    /**
     * Check the type of the constraint to oblige the concrete validator to work with the good constraint object.
     *
     * @param Constraint $constraint
     * @param string $expectedConstraint
     * @throws ValidationException
     */
    public static function checkConstraintType(Constraint $constraint, string $expectedConstraint): void
    {
        if (is_a($constraint, $expectedConstraint)) {
            return;
        }
        throw ValidationException::unexpectedConstraint($constraint, $expectedConstraint);
    }
}
