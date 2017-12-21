<?php
namespace Sfynx\CoreBundle\Layers\Application\Command;

use stdClass;
use SplObserver;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\WorkflowCommandInterface;

/**
 * Class WorkflowCommand
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Application
 * @subpackage Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WorkflowCommand implements WorkflowCommandInterface
{
    /** @var CommandInterface */
    public $command;
    /** @var \SplObserver[] */
    protected $observers = [];
    /** @var bool */
    protected $changed;
    /** @var \stdClass */
    public $data;
    /** @var \stdClass */
    protected $lastData;

    /**
     * WorkflowCommand constructor.
     */
    public function __construct() {
        $this->observers = [];
        $this->changed = false;
        $this->data = new \stdClass();
    }

    /**
     * {@inheritDoc}
     */
    public function process(CommandInterface $command): void
    {
        $this->initCommand($command);
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
    public function getCommand(): CommandInterface
    {
        return $this->command;
    }

    /**
     * {@inheritDoc}
     */
    public function setCommand(CommandInterface $command): WorkflowCommandInterface
    {
        $this->command = $command;
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
     * @param CommandInterface $command
     * @return WorkflowCommand
     */
    protected function initCommand(CommandInterface $command): WorkflowCommand
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return WorkflowCommand
     */
    protected function clearChanged(): WorkflowCommand
    {
        $this->changed = false;
        return $this;
    }

    /**
     * @return WorkflowCommand
     */
    protected function setChanged(): WorkflowCommand
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
