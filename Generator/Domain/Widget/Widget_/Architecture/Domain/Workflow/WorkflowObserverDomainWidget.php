<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Domain\Workflow;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Workflow Observer Domain Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Domain\Workflow
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WorkflowObserverDomainWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_workflow_observer_class';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'WorkflowObserverDomainWidget is an extendable code parser for object-oriented PHP-Projects to generate workflow observer component';
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        return WidgetInterface::CAT_ARCHI_DOM;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return <<<EOT
This class get delta metrics from da-uml.svg files.

EOT;

    }

    /**
     * @param ReporterObservable $reporter
     * @return array
     */
    protected function getData(ReporterObservable $reporter): array
    {
        $templater = new Templater_\Architecture\Domain\WorkflowObserver\Templater($this, $reporter);
        $templater->apply();
        
        return [
            Templater_\Architecture\Domain\WorkflowObserver\Templater::TAG,
        ];
    }
}
