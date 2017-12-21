<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Handler;

use Exception;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\WorkflowQueryInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Class ShowQueryHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Query\Handler
 */
class ShowQueryHandler implements QueryHandlerInterface
{
    /** @var QueryInterface */
    public $query;
    /** @var EntityInterface */
    public $entity;
    /** @var array */
    /** @var WorkflowQueryInterface */
    protected $workflowQuery;

    /**
     * @param WorkflowQueryInterface $workflowQuery
     */
    public function __construct(WorkflowQueryInterface $workflowQuery)
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
        $this->entity = end($this->workflowQuery->getData()->entity);
        if (!($this->entity instanceof EntityInterface)) {
            throw WorkflowException::noCreatedEntity();
        }
        return $this;
    }
}
