<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response;

use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler\ResponseHandler;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsCreatedWithBody;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsXmlHttpRequest;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractObserver;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ResponseException;

/**
 * Abstract Class AbstractCreateResponseHtml
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Response
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractCreateResponseHtml extends AbstractObserver
{
    /** @var RequestInterface */
    protected $request;
    /** @var stdClass */
    protected $object;
    /** @var array */
    protected $headers;

    /**
     * OBUserCreateResponse constructor.
     * @param RequestInterface $request
     * @param array $headers
     */
    public function __construct(RequestInterface $request, array $headers = ['Cache-Control' => 'no-cache'])
    {
        $this->request = $request;
        $this->headers = $headers;
        $this->object = new stdClass();
    }

    /**
     * @return array List of accepted request methods ['POST', 'GET', ...]
     */
    protected function getValidMethods(): array
    {
        return ['GET', 'POST'];
    }

    /**
     * Prepare object attributs values used by class specifications
     */
    protected function prepareObject()
    {
        $this->object->requestMethod = $this->request->getMethod();
        $this->object->validMethod = $this->getValidMethods();
        $this->object->isXmlHttpRequest = $this->request->isXmlHttpRequest();
        $this->object->data = $this->wfLastData;
    }

    /**
     * Sends a persists action create to specific create manager.
     * @return AbstractObserver
     * @throws ResponseException
     */
    protected function execute(): AbstractObserver
    {
        // preapre object attribut used by specifications
        $this->prepareObject();
        // we abort if we are not in the create form process
        $specs = (new SpecIsValidRequest())
            ->NotSpec(new SpecIsXmlHttpRequest())
            ->AndSpec(new SpecIsCreatedWithBody())
            ;
        if (!$specs->isSatisfiedBy($this->object)) {
            return $this;
        }
        // we run edit form process
        $this->process();

        return $this;
    }

    /**
     * The process function ...
     *
     * @return bool False to notify that postprocessing could not be executed.
     * @throws ResponseException
     */
    abstract protected function process(): bool;
}
