<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Command;

use SplSubject;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\WorkflowCommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Abstract Class AbstractObserver
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Command
 * @abstract
 */
abstract class AbstractObserver implements ObserverInterface
{
    /** @var CommandInterface */
    protected $wfCommand;
    /** @var \stdClass */
    protected $wfData;
    /** @var \stdClass */
    protected $wfLastData;

    /**
     * {@inheritDoc}
     */
    public function update(SplSubject $subject)
    {
        return $this->init($subject)->execute()->addHistory($subject);
    }

    /**
     * @param WorkflowCommandInterface $wfHandler
     * @return AbstractObserver
     */
    protected function init(WorkflowCommandInterface $wfHandler): AbstractObserver
    {
        $this->wfCommand = $wfHandler->getCommand();
        $this->wfData = $wfHandler->getData();
        $this->wfLastData = new \stdClass();

        foreach ($this->wfData as $propertyName => $aAllSteps) {
            $this->wfLastData->{$propertyName} = end($aAllSteps);
        }
        return $this;
    }

    /**
     * @return AbstractObserver
     */
    abstract protected function execute(): AbstractObserver;

    /**
     * @return AbstractObserver
     */
    protected function addHistory(WorkflowCommandInterface $wfHandler): AbstractObserver
    {
        $wfHandler->setCommand($this->wfCommand);
        foreach ($this->wfLastData as $propertyName => $propertyValue) {
            $wfHandler->data->{$propertyName}[] = $propertyValue;
        }
        return $this;
    }
}
