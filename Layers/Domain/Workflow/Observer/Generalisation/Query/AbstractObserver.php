<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Query;

use SplSubject;
use Sfynx\CoreBundle\Layers\Application\Query\Workflow\Generalisation\Interfaces\QueryWorkflowInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Interfaces\ObserverInterface;


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
    /** @var QueryInterface */
    protected $wfQuery;
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
     * @param QueryWorkflowInterface $wfHandler
     * @return AbstractObserver
     */
    protected function init(QueryWorkflowInterface $wfHandler): AbstractObserver
    {
        $this->wfQuery = $wfHandler->getQuery();
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
    protected function addHistory(QueryWorkflowInterface $wfHandler): AbstractObserver
    {
        $wfHandler->setQuery($this->wfQuery);
        foreach ($this->wfLastData as $propertyName => $propertyValue) {
            $wfHandler->data->{$propertyName}[] = $propertyValue;
        }
        return $this;
    }
}
