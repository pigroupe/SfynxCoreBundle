<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response;

use stdClass;
use Symfony\Component\Form\FormFactoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Form\Generalisation\Interfaces\FormTypeInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithEntityInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsXmlHttpRequest;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractObserver;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Abstract Class AbstractCreateFormView
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Response
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractCreateFormView extends AbstractObserver
{
    /** @var RequestInterface */
    protected $request;
    /** @var FormFactoryInterface */
    protected $formFactory;
    /** @var FormTypeInterface */
    protected $formType = '';
    /** @var stdClass */
    protected $object;

    /**
     * OBUserCreateFormView constructor.
     *
     * @param RequestInterface $request
     * @param FormFactoryInterface $formFactory
     * @param FormTypeInterface $formType
     */
    public function __construct(RequestInterface $request, FormFactoryInterface $formFactory, FormTypeInterface $formType)
    {
        $this->request = $request;
        $this->formFactory = $formFactory;
        $this->formType = $formType;
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
        $this->object->handler = $this->wfHandler;
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
        ;
        if (!$specs->isSatisfiedBy($this->object)) {
            return $this;
        }
        // we run edit form process
        $this->process();

        return $this;
    }

    /**
     * The process function create form view
     *
     * @return bool False to notify that postprocessing could not be executed.
     * @throws WorkflowException
     */
    abstract protected function process(): bool;
}
