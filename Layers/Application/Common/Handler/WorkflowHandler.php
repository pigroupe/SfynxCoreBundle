<?php
namespace Sfynx\CoreBundle\Layers\Application\Common\Handler;

use stdClass;
use SplObserver;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\HandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\WorkflowHandlerInterface;

/**
 * Class WorkflowHandler
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Application
 * @subpackage Workflow\Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WorkflowHandler implements WorkflowHandlerInterface
{
    /** @var HandlerInterface */
    public $handler;
    /** @var \SplObserver[] */
    protected $observers = [];
    /** @var bool */
    protected $changed;
    /** @var \stdClass */
    public $data;
    /** @var \stdClass */
    protected $lastData;

    /**
     * WorkflowHandler constructor.
     */
    public function __construct() {
        $this->observers = [];
        $this->changed = false;
        $this->data = new \stdClass();
    }

    /**
     * {@inheritDoc}
     */
    public function process(HandlerInterface $handler): void
    {
        $this->initHandler($handler);
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
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * {@inheritDoc}
     */
    public function setHandler(HandlerInterface $handler): WorkflowHandlerInterface
    {
        $this->handler = $handler;
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
     * @param HandlerInterface $handler
     * @return WorkflowHandler
     */
    protected function initHandler(HandlerInterface $handler): WorkflowHandler
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @return WorkflowHandler
     */
    protected function clearChanged(): WorkflowHandler
    {
        $this->changed = false;
        return $this;
    }

    /**
     * @return WorkflowHandler
     */
    protected function setChanged(): WorkflowHandler
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
