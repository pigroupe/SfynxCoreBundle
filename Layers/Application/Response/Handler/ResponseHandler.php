<?php
namespace Sfynx\CoreBundle\Layers\Application\Response\Handler;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Application\Response\Handler\Generalisation\Interfaces\ResponseHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\HandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\WorkflowHandlerInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ResponseException;

/**
 * Class ResponseHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Response\Handler
 */
class ResponseHandler implements ResponseHandlerInterface
{
    /** @var HandlerInterface */
    public $handler;
    /** @var Response */
    public $response;
    /** @var array */
    public $errors = [];
    /** @var WorkflowHandlerInterface */
    protected $WorkflowHandler;

    /**
     * @param WorkflowHandlerInterface $workflowHandler
     */
    public function __construct(WorkflowHandlerInterface $WorkflowHandler)
    {
        $this->WorkflowHandler = $WorkflowHandler;
    }

    /**
     * @param HandlerInterface $handler
     *
     * @return ResponseHandlerInterface
     * @throws ResponseException|WorkflowException
     */
    public function process(HandlerInterface $handler): ResponseHandlerInterface
    {
        // set handler
        $this->handler = $handler;
        // execute all observers in the wrokflow
        $this->WorkflowHandler->process($handler);
        // get last version of response and body objects
        if (property_exists($this->WorkflowHandler->getData(), 'response')) {
            $this->response = end($this->WorkflowHandler->getData()->response);

            if (!($this->response instanceof Response)) {
                throw ResponseException::noCreatedResponse();
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }
}
