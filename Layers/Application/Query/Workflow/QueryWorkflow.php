<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Workflow;

use stdClass;
use SplObserver;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Workflow\Generalisation\Interfaces\QueryWorkflowInterface;

/**
 * Class QueryWorkflow
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Application
 * @subpackage Query
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class QueryWorkflow implements QueryWorkflowInterface
{
    /** @var QueryInterface */
    public $query;
    /** @var \SplObserver[] */
    protected $observers = [];
    /** @var bool */
    protected $changed;
    /** @var \stdClass */
    public $data;
    /** @var \stdClass */
    protected $lastData;

    /**
     * QueryWorkflow constructor.
     */
    public function __construct() {
        $this->observers = [];
        $this->changed = false;
        $this->data = new \stdClass();
    }

    /**
     * {@inheritDoc}
     */
    public function process(QueryInterface $query): void
    {
        $this->initQuery($query);
        // notify all observers
        $this->setChanged();
        $this->notify();
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): stdClass
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function setQuery(QueryInterface $query): QueryWorkflowInterface
    {
        $this->query = $query;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(SplObserver $observer)
    {
        $key = array_search($observer,$this->observers, true);
        if ($key){
            unset($this->observers[$key]);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function notify()
    {
        if ($this->hasChanged()) {
            foreach ($this->observers as $observer) {
                $observer->update($this);
            }
            $this->clearChanged();
        }
    }

    /**
     * @param QueryInterface $query
     * @return QueryWorkflow
     */
    protected function initQuery(QueryInterface $query): QueryWorkflow
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return QueryWorkflow
     */
    protected function clearChanged(): QueryWorkflow
    {
        $this->changed = false;
        return $this;
    }

    /**
     * @return QueryWorkflow
     */
    protected function setChanged(): QueryWorkflow
    {
        $this->changed = true;
        return $this;
    }

    /**
     * @return bool
     */
    protected function hasChanged(): bool
    {
        return $this->changed;
    }
}
