<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response;

use Exception;
use stdClass;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithErrors;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsFormCreated;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsXmlHttpRequest;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractObserver;

/**
 * Abstract Class AbstractInjectFormErrors
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Response
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractInjectFormErrors extends AbstractObserver
{
    /** @var RequestInterface */
    protected $request;
    /** @var TranslatorInterface */
    protected $translator;
    /** @var stdClass */
    protected $object;

    /**
     * OBUserCreateFormView constructor.
     *
     * @param RequestInterface $request
     * @param TranslatorInterface $translator
     */
    public function __construct(RequestInterface $request, TranslatorInterface $translator)
    {
        $this->request = $request;
        $this->translator = $translator;
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
        $this->object->data = $this->wfLastData;
    }

    /**
     * Sends a persists action create to specific create manager.
     * @return AbstractObserver
     */
    protected function execute(): AbstractObserver
    {
        // preapre object attribut used by specifications
        $this->prepareObject();
        // we abort if we are not in the create form process
        $specs = (new SpecIsValidRequest())
            ->NotSpec(new SpecIsXmlHttpRequest())
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
     * The process function add errors given from  CommandHandlerInterface in the last form view
     *
     * @return bool False to notify that postprocessing could not be executed.
     */
    abstract protected function process(): bool;
}
