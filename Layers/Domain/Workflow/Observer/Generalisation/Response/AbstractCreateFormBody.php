<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response;

use Exception;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithEntityInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithErrors;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsFormCreated;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsParamCreatedWithTemplating;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsXmlHttpRequest;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractObserver;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Abstract Class AbstractCreateFormBody
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Response
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractCreateFormBody extends AbstractObserver
{
    /** @var RequestInterface */
    protected $request;
    /** @var EngineInterface */
    protected $templating;
    /** @var stdClass */
    protected $object;
    /** @var stdclass */
    protected $param;

    /**
     * OBUserCreateBody constructor.
     *
     * @param RequestInterface $request
     * @param EngineInterface $templating
     * @param array $param
     */
    public function __construct(RequestInterface $request, EngineInterface $templating, ?stdclass $param)
    {
        $this->request = $request;
        $this->templating = $templating;
        $this->object = new stdClass();
        $this->param = $param;
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
        $this->object->handler = $this->wfHandler;
        $this->object->data = $this->wfLastData;
        $this->object->param = $this->param;
    }

    /**
     * Sends a persists action create to specific create manager.
     * @return AbstractObserver
     * @throws WorkflowException
     */
    protected function execute(): AbstractObserver
    {
        // preapre object attribut used by specifications
        $this->prepareObject();
        // we abort if we are not in the create form process
        $specs = (new SpecIsValidRequest())
            ->NotSpec(new SpecIsXmlHttpRequest())
            ->AndSpec(new SpecIsHandlerCreatedWithEntityInterface())
            ->AndSpec(new SpecIsParamCreatedWithTemplating())
            ->AndSpec(new SpecIsHandlerCreatedWithErrors())
            ->AndSpec(new SpecIsFormCreated())
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
     * @throws WorkflowException
     */
    abstract protected function process(): bool;
}
