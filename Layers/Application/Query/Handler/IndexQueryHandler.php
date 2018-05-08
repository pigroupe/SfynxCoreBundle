<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Handler;

use Exception;
use stdClass;
use Sfynx\CoreBundle\Layers\Application\Query\Handler\Generalisation\Interfaces\QueryHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Workflow\Generalisation\Interfaces\QueryWorkflowInterface;

use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithEntities;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Class IndexQueryHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Query\Handler
 */
class IndexQueryHandler implements QueryHandlerInterface
{
    /** @var QueryInterface */
    public $query;
    /** @var array */
    public $entities;
    /** @var  array */
    public $result;
    /** @var  array */
    public $total;
    /** @var array */
    /** @var QueryWorkflowInterface */
    protected $workflowQuery;

    /**
     * @param QueryWorkflowInterface $workflowQuery
     */
    public function __construct(QueryWorkflowInterface $workflowQuery)
    {
        $this->workflowQuery = $workflowQuery;
    }

    /**
     * @param QueryInterface $query
     *
     * @return QueryHandlerInterface
     * @throws WorkflowException
     */
    public function process(QueryInterface $query): QueryHandlerInterface
    {
        // execute all observers in the wrokflow
        $this->workflowQuery->process($query);
        // get last version of entity and errors objects
        $this->query = $query;
        // we create object to specification
        if (property_exists($this->workflowQuery->getData(), 'entities')) {
            $this->entities = end($this->workflowQuery->getData()->entities);
        }
        if (property_exists($this->workflowQuery->getData(), 'result')) {
            $this->result = end($this->workflowQuery->getData()->result);
        }
        if (property_exists($this->workflowQuery->getData(), 'total')) {
            $this->total = end($this->workflowQuery->getData()->total);
        }
        // we abort if we are not in the list of entities
        $object = new stdClass();
        $object->handler = $this;
        $specs = (new SpecIsHandlerCreatedWithEntities());
        if (!$specs->isSatisfiedBy($object)) {
            throw WorkflowException::noEntityInstances();
        }
        return $this;
    }
}
