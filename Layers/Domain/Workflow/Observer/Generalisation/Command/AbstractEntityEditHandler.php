<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Command;

use stdClass;
use Exception;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Command\AbstractObserver;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidCommand;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsEntityEdited;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\EntityException;

/**
 * Abstract Class AbstractEntityEditHandler
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Form
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractEntityEditHandler extends AbstractObserver
{
    /** @var string */
    protected $entityName = '';
    /** @var ManagerInterface */
    protected $manager;
    /** @var RequestInterface */
    protected $request;
    /** @var stdClass */
    protected $object;
    /** @var bool */
    protected $updateCommand;

    /**
     * AbstractEntityEditHandler constructor.
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     * @param bool $updateCommand
     */
    public function __construct(ManagerInterface $manager, RequestInterface $request, bool $updateCommand = false)
    {
        $this->entityName = $manager->getEntityName();
        $this->manager = $manager;
        $this->request = $request;
        $this->updateCommand = $updateCommand;

        $this->object = new stdClass();
    }

    /**
     * @return array List of accepted request methods ['POST', 'GET', ...]
     */
    protected function getValidMethods(): array
    {
        return ['POST', 'PATCH', 'PUT'];
    }

    /**
     * This method implements the init process evenif the request and the form state
     * @return void
     * @throws EntityException
     */
    protected function onInit(): void
    {
        try {
            $query = $this->manager->getQueryRepository()->findOneQueryByEntity($this->wfCommand->getEntityId())->getQuery();
            $this->wfLastData->entity = $this->manager->getQueryRepository()->Result($query)->getOneOrNullResult(\Doctrine\ORM\AbstractQuery::HYDRATE_OBJECT);

            $specs = new SpecIsValidRequest();
            if ($this->updateCommand && !$specs->isSatisfiedBy($this->object)) {
                $this->wfCommand = $this->manager->buildFromEntity($this->wfCommand, $this->wfLastData->entity);
            }
        } catch (Exception $e) {
            throw EntityException::NotFoundEntity($this->entityName);
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
     * This method implements the process if the request is validate
     */
    protected function onContinue(): void
    {
        $entity = $this->wfLastData->entity;
        $entity = $this->manager->buildFromCommand($entity, $this->wfCommand, $this->updateCommand);
        $this->wfLastData->entity = $entity;
    }

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
        $this->object->entityId = $this->wfCommand->getEntityId();
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
        // we abort if we are not in the edit form process
        $specs = new SpecIsEntityEdited();
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
        $this->onContinue();
        // validate process when post form
        $specs = (new SpecIsValidRequest())->AndSpec(new SpecIsValidCommand());
        if ($specs->isSatisfiedBy($this->object)) {
            $this->onSuccess();
        }
        $this->onEnd();

        return true;
    }
}
