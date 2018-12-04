<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\TraitRegex;
use Symfony\Component\Validator\Constraint;

/**
 * Class OccurrenceRegex
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class OccurrenceRegex extends Constraint
{
    use TraitRegex;

    /** @var string Code of the error when occurrence of valid characters is less than the minimum required. */
    public const MIN_ERROR = 'MIN_ERROR';

    /** @var string Code of the error when occurrence of valid characters is more than the maximum required. */
    public const MAX_ERROR = 'MAX_ERROR';

    /**
     * @var string[] List of default messages available for this constraint.
     */
    public $messages = [
        self::MIN_ERROR => 'String "%value%" must contains at least "%min%" valid characters',
        self::MAX_ERROR => 'String "%value%" must contains at most "%max%" valid characters',
    ];

    /**
     * @var string Specific message that can be defined to overwrite possible default message.
     */
    public $message = 'The given value is not a string';

    /**
     * @var int|null If defined, minimum occurrence of a character matching a pattern in the value.
     */
    protected $min;

    /**
     * @var int|null If defined, maximum occurrence of a character matching a pattern in the value.
     */
    protected $max;

    /**
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * Returns true if the number given in argument respects the minimum limitation, if there is one. False otherwise.
     *
     * @param int $nb
     * @return bool
     */
    public function respectMin(int $nb): bool
    {
        return (null === $this->min || $nb >= $this->min);
    }

    /**
     * Returns true if the number given in argument respects the maximum limitation, if there is one. False otherwise.
     *
     * @param int $nb
     * @return bool
     */
    public function respectMax(int $nb): bool
    {
        return (null === $this->max || $nb <= $this->max);
    }
}
