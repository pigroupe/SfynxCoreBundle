<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Query;

use stdClass;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Query\AbstractObserver;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithQueryInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidQueryBuilderQuery;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithLocaleQueryInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithServerSideQueryInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsServerSide;

/**
 * Abstract Class AbstractIndexFindEntitiesHandler
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Query
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractIndexFindEntitiesHandler extends AbstractObserver
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
     * EntityFindHandler constructor.
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
        return ['GET'];
    }

    /**
     * Prepare object attributs values used by class specifications
     */
    protected function prepareObject()
    {
        $this->object->requestMethod = $this->request->getMethod();
        $this->object->validMethod = $this->getValidMethods();
        $this->object->data = $this->wfLastData;
        $this->object->handler = new stdClass();
        $this->object->handler->query = $this->wfQuery;
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
        $specs = (new SpecIsValidRequest())
            ->AndSpec(new SpecIsValidQueryBuilderQuery())
            ->AndSpec(new SpecIsHandlerCreatedWithQueryInterface())
            ->AndSpec(new SpecIsHandlerCreatedWithLocaleQueryInterface())
            ->AndSpec(new SpecIsHandlerCreatedWithServerSideQueryInterface())
            ->NotSpec(new SpecIsServerSide())
        ;
        if (!$specs->isSatisfiedBy($this->object)) {
            return $this;
        }
        // we run process
        $this->process();

        return $this;
    }

    /**
     * This method implements the init process evenif the request and the form state
     * @return void
     * @throws EntityException
     */
    abstract protected function process(): void;
}
