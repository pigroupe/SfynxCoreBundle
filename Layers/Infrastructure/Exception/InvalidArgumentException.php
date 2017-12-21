<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exception Class InvalidArgumentException
 *
 * @category Core
 * @package Infrastructure
 * @subpackage Exception
 */
class InvalidArgumentException extends Exception
{
    /**
     * InvalidArgumentException constructor.
     * @param string $message
     * @param Exception|null $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct($message, Response::HTTP_BAD_REQUEST, $previous);
    }

    /**
     * @param mixed $value
     * @param string $expectedType
     * @return ValidationException
     */
    public static function invalidType($value, string $expectedType): ValidationException
    {
        $valueType = is_object($value) ? get_class($value) : gettype($value);
        return new static(sprintf('Expected argument of type "%s", "%s" given', $expectedType, $valueType));
    }

    /**
     * @return InvalidArgumentException
     */
    public static function invalidArgument(): InvalidArgumentException
    {
        return new static('Invalid argument given');
    }

    /**
     * @param string $value
     * @param array $fieldList
     * @return InvalidArgumentException
     */
    public static function invalidField(string $value, array $fieldList): InvalidArgumentException
    {
        $message = 'Field "%s" does not match any authorized database fields.' . PHP_EOL
            . 'List of allowed fields are: %s.';

        return new static(sprintf($message, $value, '"' . implode('", "', $fieldList)) . '"');
    }
}
