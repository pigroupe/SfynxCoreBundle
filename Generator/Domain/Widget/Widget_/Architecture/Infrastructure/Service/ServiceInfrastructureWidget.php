<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Infrastructure\Service;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Service Infrastructure Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Infrastructure\Service
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ServiceInfrastructureWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_infra_class';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'ServiceInfrastructureWidget is an extendable code parser for object-oriented PHP-Projects to generate service component';
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        return WidgetInterface::CAT_ARCHI_INFRA;
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
     * @inheritdoc
     */
    protected function getData(): array
    {
        $templater = new Templater_\Architecture\Infrastructure\Service\Templater($this);
        $templater->apply();

        return [
            WidgetInterface::KEY_TAG => Templater_\Architecture\Infrastructure\Service\Templater::TAG,
            WidgetInterface::KEY_NAMESPACE => $templater->getTargetNamespace(),
            WidgetInterface::KEY_CLASS => $templater->getTargetClassname(),
            WidgetInterface::KEY_CLASSNAME => $templater->getTargetClassname()
        ];
    }
}
