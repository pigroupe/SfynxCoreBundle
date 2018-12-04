<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response;

use SplSubject;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\WorkflowHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\HandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;

/**
 * Abstract Class AbstractObserver
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Response
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractObserver implements ObserverInterface
{
    /** @var HandlerInterface */
    protected $wfHandler;
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
     * @param WorkflowHandlerInterface $wfHandler
     * @return AbstractObserver
     */
    protected function init(WorkflowHandlerInterface $wfHandler): AbstractObserver
    {
        $this->wfHandler = $wfHandler->getHandler();
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
     * @param WorkflowHandlerInterface $wfHandler
     * @return AbstractObserver
     */
    protected function addHistory(WorkflowHandlerInterface $wfHandler): AbstractObserver
    {
        $wfHandler->setHandler($this->wfHandler);
        foreach ($this->wfLastData as $propertyName => $propertyValue) {
            $wfHandler->data->{$propertyName}[] = $propertyValue;
        }
        return $this;
    }
}
