<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Command;

use SplSubject;
use Sfynx\CoreBundle\Layers\Application\Command\Workflow\Generalisation\Interfaces\CommandWorkflowInterface;
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
     * @param CommandWorkflowInterface $wfHandler
     * @return AbstractObserver
     */
    protected function init(CommandWorkflowInterface $wfHandler): AbstractObserver
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
    protected function addHistory(CommandWorkflowInterface $wfHandler): AbstractObserver
    {
        $wfHandler->setCommand($this->wfCommand);
        foreach ($this->wfLastData as $propertyName => $propertyValue) {
            $wfHandler->data->{$propertyName}[] = $propertyValue;
        }
        return $this;
    }
}
