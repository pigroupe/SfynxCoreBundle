<?php
namespace Sfynx\CoreBundle\Layers\Application\Query;

use stdClass;
use SplObserver;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\WorkflowQueryInterface;

/**
 * Class WorkflowQuery
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Application
 * @subpackage Query
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WorkflowQuery implements WorkflowQueryInterface
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
     * WorkflowQuery constructor.
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
    public function setQuery(QueryInterface $query): WorkflowQueryInterface
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
     * @return WorkflowQuery
     */
    protected function initQuery(QueryInterface $query): WorkflowQuery
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return WorkflowQuery
     */
    protected function clearChanged(): WorkflowQuery
    {
        $this->changed = false;
        return $this;
    }

    /**
     * @return WorkflowQuery
     */
    protected function setChanged(): WorkflowQuery
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
