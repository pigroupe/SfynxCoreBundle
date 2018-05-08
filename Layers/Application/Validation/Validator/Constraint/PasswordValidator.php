<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\ConstraintValidator;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Length;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\OccurrenceRegex;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Password;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\Traits\TraitValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class PasswordValidator
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class PasswordValidator extends ConstraintValidator
{
    use TraitValidator;

    /**
     * @var LengthValidator Validator object that will be used to check the length of the password.
     */
    protected $lengthValidator;

    /**
     * @var OccurrenceRegexValidator Validator object that will be used to check some properties of the password.
     */
    protected $occurrenceRegexValidator;

    /**
     * Initialize the validators that will be required to validate the password.
     *
     * @return $this
     */
    public function initValidators()
    {
        //Validator for the length
        $this->lengthValidator = new LengthValidator();
        $this->lengthValidator->initialize($this->context);

        //Validator for the occurrences
        $this->occurrenceRegexValidator = new OccurrenceRegexValidator();
        $this->occurrenceRegexValidator->initialize($this->context);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Sfynx\DddBundle\Layer\Infrastructure\Exception\ValidationException
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Password $constraint */
        self::checkConstraintType($constraint, Password::class);

        $value = (string)$value;

        $this->initValidators()
            ->validateLength($value, $constraint)
            ->validateUpper($value, $constraint)
            ->validateLower($value, $constraint)
            ->validateDigit($value, $constraint)
            ->validateSpecial($value, $constraint)
            ->validateSpecialOrDigit($value, $constraint);
    }

    /**
     * Check the password contains at least a minimum number of characters.
     *
     * @param string $value
     * @param Password $constraint
     * @return $this
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Sfynx\DddBundle\Layer\Infrastructure\Exception\ValidationException
     */
    protected function validateLength(string $value, Password $constraint)
    {
        $lengthOptions = [
            'min' => $constraint->getMinLength(),
            'messages' => [Length::MIN_ERROR => $constraint->messages[Password::LENGTH_ERROR]]
        ];
        $this->lengthValidator->validate($value, new Length($lengthOptions));

        return $this;
    }

    /**
     * Check the password contains at least a minimum number of uppercase characters.
     *
     * @param string $value
     * @param Password $constraint
     * @return $this
     * @throws \Sfynx\DddBundle\Layer\Infrastructure\Exception\ValidationException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     */
    protected function validateUpper(string $value, Password $constraint)
    {
        $nbUpperValidatorOptions = [
            'regex' => '/[A-Z]/',
            'min' => $constraint->getMinNbUppers(),
            'messages' => [OccurrenceRegex::MIN_ERROR => $constraint->messages[Password::UPPER_ERROR]]
        ];
        $this->occurrenceRegexValidator->validate($value, new OccurrenceRegex($nbUpperValidatorOptions));

        return $this;
    }

    /**
     * Check the password contains at least a minimum number of lowercase characters.
     *
     * @param string $value
     * @param Password $constraint
     * @return $this
     * @throws \Sfynx\DddBundle\Layer\Infrastructure\Exception\ValidationException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     */
    protected function validateLower(string $value, Password $constraint)
    {
        $nbLowerValidatorOptions = [
            'regex' => '/[a-z]/',
            'min' => $constraint->getMinNbLowers(),
            'messages' => [OccurrenceRegex::MIN_ERROR => $constraint->messages[Password::LOWER_ERROR]]
        ];
        $this->occurrenceRegexValidator->validate($value, new OccurrenceRegex($nbLowerValidatorOptions));

        return $this;
    }

    /**
     * Check the password contains at least a minimum number of digits.
     *
     * @param string $value
     * @param Password $constraint
     * @return $this
     * @throws \Sfynx\DddBundle\Layer\Infrastructure\Exception\ValidationException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     */
    protected function validateDigit(string $value, Password $constraint)
    {
        $nbDigitValidatorOptions = [
            'regex' => '/\d/',
            'min' => $constraint->getMinNbDigits(),
            'messages' => [OccurrenceRegex::MIN_ERROR => $constraint->messages[Password::DIGIT_ERROR]]
        ];
        $this->occurrenceRegexValidator->validate($value, new OccurrenceRegex($nbDigitValidatorOptions));

        return $this;
    }

    /**
     * Check the password contains at least a minimum number of special characters.
     *
     * @param string $value
     * @param Password $constraint
     * @return $this
     * @throws \Sfynx\DddBundle\Layer\Infrastructure\Exception\ValidationException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     */
    protected function validateSpecial(string $value, Password $constraint)
    {
        $nbSpecialValidatorOptions = [
            'regex' => '/[!#\$%&()\*\-\/:?@\[\\\\\]_{|}]/',
            'min' => $constraint->getMinNbSpecialChars(),
            'messages' => [OccurrenceRegex::MIN_ERROR => $constraint->messages[Password::SPECIAL_CHARS_ERROR]]
        ];
        $this->occurrenceRegexValidator->validate($value, new OccurrenceRegex($nbSpecialValidatorOptions));

        return $this;
    }

    /**
     * Check the password contains at least a minimum number of special or digit characters.
     *
     * @param string $value
     * @param Password $constraint
     * @return $this
     * @throws \Sfynx\DddBundle\Layer\Infrastructure\Exception\ValidationException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     */
    protected function validateSpecialOrDigit(string $value, Password $constraint)
    {
        $nbSpecialOrDigitValidatorOptions = [
            'regex' => '/[\.#(){}\[\]\/&%\*\$!,;:\+=|_\-\s"\'<>0-9]/',
            'min' => $constraint->getMinNbSpecialCharsOrDigits(),
            'messages' => [OccurrenceRegex::MIN_ERROR => $constraint->messages[Password::SPECIAL_CHARS_OR_DIGITS_ERROR]]
        ];
        $this->occurrenceRegexValidator->validate($value, new OccurrenceRegex($nbSpecialOrDigitValidatorOptions));

        return $this;
    }
}
