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
 * Class QueryHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Query\Handler
 */
class QueryHandler implements QueryHandlerInterface
{
    /** @var QueryInterface */
    public $query;
    /** @var QueryWorkflowInterface */
    protected $queryWorkflow;
    /** @var QueryHandlerInterface */
    protected $queryHandler;

    /**
     * @param QueryWorkflowInterface $queryWorkflow
     * @param null|QueryHandlerInterface $queryHandlers
     */
    public function __construct(QueryWorkflowInterface $queryWorkflow, QueryHandlerInterface $queryHandler = null)
    {
        $this->queryWorkflow = $queryWorkflow;
        $this->queryHandler = $queryHandler;
    }

    /**
     * @param QueryInterface $query
     *
     * @return QueryHandlerInterface
     * @throws WorkflowException
     */
    public function process(QueryInterface $query): QueryHandlerInterface
    {
        /* execute all observers in the wrokflow */
        $this->workflowQuery->process($query);
        /* get last version of entity and errors objects */
        $this->query = $query;
        /* get last version of public attributes of queryWorkflow and Query objects */
        foreach (\get_object_vars($this->queryWorkflow->getData()) as $property => $value) {
            $this->$property = end($this->queryWorkflow->getData()->$property);
            $this->setQueryHandlerProperty($property);
        }
        foreach (\get_object_vars($this->queryWorkflow->getQuery()) as $property => $value) {
            $this->$property = $this->queryWorkflow->getQuery()->$property;
            $this->setQueryHandlerProperty($property);
        }
        /* execute next commandHandler */
        if (null !== $this->queryHandler) {
            return $this->queryHandler->process($command);
        }

        return $this;
    }

    /**
     * We propage a public property to the next queryHandler
     * @param string $property
     * @return void
     */
    protected function setQueryHandlerProperty(string $property): void
    {
        if (null !== $this->queryHandler) {
            $this->queryHandler->$property = $this->$property;
        }
    }
}
