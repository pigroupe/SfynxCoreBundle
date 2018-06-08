<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Application\Service;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Service Application Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Application\Service
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ServiceApplicationWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_service_app_class';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'ServiceApplicationWidget is an extendable code parser for object-oriented PHP-Projects to generate service component';
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        return WidgetInterface::CAT_ARCHI_APP;
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
        $templater = new Templater_\Architecture\Application\Service\Templater($this);
        $templater->apply();

        return [
            WidgetInterface::KEY_TAG => Templater_\Architecture\Application\Service\Templater::TAG,
            WidgetInterface::KEY_NAMESPACE => $templater->getTargetNamespace(),
            WidgetInterface::KEY_CLASS => $templater->getTargetClassname(),
            WidgetInterface::KEY_CLASSNAME => $templater->getTargetClassname()
        ];
    }
}
