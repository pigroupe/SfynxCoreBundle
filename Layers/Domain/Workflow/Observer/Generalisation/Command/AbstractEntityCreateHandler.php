<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Command;

use stdClass;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Command\AbstractObserver;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidCommand;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsEntityCreated;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\EntityException;

/**
 * Abstract Class AbstractEntityCreateHandler
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Form
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractEntityCreateHandler extends AbstractObserver
{
    /** @var string */
    protected $entityName = '';
    /** @var ManagerInterface */
    protected $manager;
    /** @var RequestInterface */
    protected $request;
    /** @var stdClass */
    protected $object;

    /**
     * AbstractEntityCreateHandler constructor.
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     */
    public function __construct(ManagerInterface $manager, RequestInterface $request)
    {
        $this->entityName = $manager->getEntityName();
        $this->manager = $manager;
        $this->request = $request;
        $this->object = new stdClass();
    }

    /**
     * @return array List of accepted request methods ['POST', 'GET', ...]
     */
    protected function getValidMethods(): array
    {
        return array('POST');
    }

    /**
     * This method implements the init process evenif the request and the form state
     * @return void
     * @throws EntityException
     */
    protected function onInit(): void
    {
        try {
            $this->wfLastData->entity = $this->manager->newFromCommand($this->wfCommand);
        } catch (Exception $e) {
            throw EntityException::NotBuildEntityFromCommand($this->entityName);
        }
    }

    /**
     * This method implements the process if the request is not validated
     * @return void
     */
    protected function onStart(): void
    {
        $this->wfCommand->errors = [];
    }

    /**
     * This method implements the process in the end of the process if no started
     * @return void
     */
    protected function onEnd(): void
    {}

    /**
     * This method implements the post-processing if the form is bound and valid.
     * The return value will be available as process() return (should be falsy on failure)
     * @return void
     */
    abstract protected function onSuccess(): void;

    /**
     * Prepare object attributs values used by class specifications
     */
    protected function prepareObject()
    {
        $this->object->requestMethod = $this->request->getMethod();
        $this->object->validMethod = $this->getValidMethods();
        $this->object->entityId = $this->wfCommand->entityId;
        $this->object->errors = $this->wfCommand->errors;
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
        $specs = new SpecIsEntityCreated();
        if (!$specs->isSatisfiedBy($this->object)) {
            return $this;
        }
        // we run edit form process
        $this->process();

        return $this;
    }

    /**
     * The process function check if request is valid
     * and do any post treatment (persisting the entity etc.)
     *
     * @return bool False to notify that postprocessing could not be executed.
     *                 This can be the case when the validators are not valid, the request method
     *                 not supported etc.
     */
    public function process(): bool
    {
        // we run init form process
        $this->onInit();
        // start process when get form
        $specs = new SpecIsValidRequest();
        if (!$specs->isSatisfiedBy($this->object)) {
            $this->onStart();
            return false;
        }
        // validate process when post form
        $specs = (new SpecIsValidRequest())->AndSpec(new SpecIsValidCommand());
        if ($specs->isSatisfiedBy($this->object)) {
            $this->onSuccess();
        }
        $this->onEnd();

        return true;
    }
}
