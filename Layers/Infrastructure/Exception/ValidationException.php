<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;

/**
 * Exception Class ValidationException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ValidationException extends Exception
{
    /**
     * @var array Array of data to describe errors
     */
    protected $data;

    /**
     * @param string $message
     * @param array $data
     * @param Exception|null $previous
     */
    public function __construct($message = '', array $data = [], Exception $previous = null)
    {
        parent::__construct($message, Response::HTTP_BAD_REQUEST, $previous);
        $this->data = $data;
    }

    /**
     * @param array $data
     *
     * @return ValidationException
     */
    public static function validationFailed($data): ValidationException
    {
        return new static('The validation failed', $data);
    }

    /**
     * @param Constraint $constraint
     * @param string $expectedClass
     * @return ValidationException
     */
    public static function unexpectedConstraint(Constraint $constraint, string $expectedClass): ValidationException
    {
        $message = sprintf('Expected constraint of type "%s", "%s" given', $expectedClass, get_class($constraint));
        return new static($message);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
