<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class Password
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class Password extends Constraint
{
    /** @var string */
    public const LENGTH_ERROR = 'a299fc78-6548-45a3-9823-898c07f76c9b';

    /** @var string */
    public const UPPER_ERROR = 'a299fc78-6548-45a3-9823-898c07f76c9c';

    /** @var string */
    public const LOWER_ERROR = 'a299fc78-6548-45a3-9823-898c07f76c9d';

    /** @var string */
    public const DIGIT_ERROR = 'a299fc78-6548-45a3-9823-898c07f76c9e';

    /** @var string */
    public const SPECIAL_CHARS_ERROR = 'special_chars';

    /** @var string */
    public const SPECIAL_CHARS_OR_DIGITS_ERROR = 'special_chars_or_digits';

    /** @var string */
    public const SPECIAL_CHARS_OR_DIGITS_SIMPLE_ERROR = 'special_chars_or_digits_simple';

    /** @var string[] */
    protected static $errorNames = [
        self::LENGTH_ERROR => 'LENGTH_ERROR',
        self::UPPER_ERROR => 'UPPER_ERROR',
        self::LOWER_ERROR => 'LOWER_ERROR',
        self::DIGIT_ERROR => 'DIGIT_ERROR',
        self::SPECIAL_CHARS_ERROR => 'SPECIAL_CHARS_ERROR',
        self::SPECIAL_CHARS_OR_DIGITS_ERROR => 'SPECIAL_CHARS_OR_DIGITS_ERROR',
        self::SPECIAL_CHARS_OR_DIGITS_SIMPLE_ERROR => 'SPECIAL_CHARS_OR_DIGITS_SIMPLE_ERROR'
    ];

    /** @var string[] */
    public $messages = [
        self::LENGTH_ERROR => 'Password must be at least "%minLength%" characters in length',
        self::UPPER_ERROR => 'Password must contain at least "%min%" uppercase letters',
        self::LOWER_ERROR => 'Password must contain at least "%min%" lowercase letters',
        self::DIGIT_ERROR => 'Password must contain at least "%min%" digit characters',
        self::SPECIAL_CHARS_ERROR => 'Password must contain at least "%min%" ' .
            'special characters (.#(){}[]/&%*$!,;:+=|_-?)',
        self::SPECIAL_CHARS_OR_DIGITS_ERROR => 'Password must contain at least "%min%" ' .
            'special characters (.#(){}[]/&%*$!,;:+=|_-?) or digit characters',
        self::SPECIAL_CHARS_OR_DIGITS_SIMPLE_ERROR => 'Password must contain one special character or one digit'
    ];

    /**
     * Minimum length of the password
     * @var int
     */
    protected $minLength = 8;

    /**
     * Minimum number of uppercase characters
     * @var int
     */
    protected $minNbUppers = 1;

    /**
     * Minimum number of lowercase characters
     * @var int
     */
    protected $minNbLowers = 1;

    /**
     * Minimum number of digits
     * @var int
     */
    protected $minNbDigits = 1;

    /**
     * Minimum number of special characters
     * @var int
     */
    protected $minNbSpecialChars = 1;

    /**
     * Minimum number of special characters or digits
     * @var int
     */
    protected $minNbSpecialCharsOrDigits = 0;

    /**
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * @return int
     */
    public function getMinNbUppers(): int
    {
        return $this->minNbUppers;
    }

    /**
     * @return int
     */
    public function getMinNbLowers(): int
    {
        return $this->minNbLowers;
    }

    /**
     * @return int
     */
    public function getMinNbDigits(): int
    {
        return $this->minNbDigits;
    }

    /**
     * @return int
     */
    public function getMinNbSpecialChars(): int
    {
        return $this->minNbSpecialChars;
    }

    /**
     * @return int
     */
    public function getMinNbSpecialCharsOrDigits(): int
    {
        return $this->minNbSpecialCharsOrDigits;
    }
}
