<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Workflow\Generalisation\Interfaces;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

/**
 * Class QueryWorkflowInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Query\Generalisation\Interfaces
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface QueryWorkflowInterface extends SplSubject
{
    /**
     * Processes with the given Query object.
     *
     * @param QueryInterface $query
     * @return mixed
     */
    public function process(QueryInterface $query): void;

    /**
     * Returns the whole data that contains all elements to each steps of the workflow.
     *
     * @return stdClass
     */
    public function getData(): stdClass;

    /**
     * Returns the Query object given to the workflow.
     *
     * @return QueryInterface
     */
    public function getQuery(): QueryInterface;

    /**
     * @param QueryInterface $query
     * @return QueryWorkflowInterface
     */
    public function setQuery(QueryInterface $query): QueryWorkflowInterface;
}
