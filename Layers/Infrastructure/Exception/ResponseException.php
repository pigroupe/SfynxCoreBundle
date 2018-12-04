<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exception Class ResponseException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ResponseException extends Exception
{
    /**
     * Returns the <No Created Entity> Exception.
     *
     * @return WorkflowException
     */
    public static function noCreatedResponse(): ResponseException
    {
        return new static('Response has not been created');
    }
}
