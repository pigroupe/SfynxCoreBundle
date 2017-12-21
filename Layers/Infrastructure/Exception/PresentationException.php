<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exception Class PresentationException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PresentationException extends Exception
{
    /**
     * @param string $message
     * @param Exception|null $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct($message, Response::HTTP_BAD_REQUEST, $previous);
    }

    /**
     * Returns the <Invalid Json Request> Exception.
     *
     * @return PresentationException
     */
    public static function invalidJsonRequest(): PresentationException
    {
        return new static('Invalid Json in request');
    }

    /**
     * Returns the <Invalid Json Request> Exception.
     *
     * @return PresentationException
     */
    public static function invalidCommandHandlerResponse(): PresentationException
    {
        return new static('Invalid return instance of the command handler');
    }
}
