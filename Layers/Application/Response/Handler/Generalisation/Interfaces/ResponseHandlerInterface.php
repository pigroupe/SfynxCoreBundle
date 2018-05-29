<?php
namespace Sfynx\CoreBundle\Layers\Application\Response\Handler\Generalisation\Interfaces;

use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\HandlerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ResponseHandlerInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Response\Generalisation\Interfaces
 */
interface ResponseHandlerInterface
{
    /**
     * @param HandlerInterface $handler
     * @return ResponseHandlerInterface
     */
    public function process(HandlerInterface $handler): ResponseHandlerInterface;

    /**
     * @return Response
     */
    public function getResponse();
}
