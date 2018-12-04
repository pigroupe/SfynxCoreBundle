<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Domain\Service;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Manager Service Domain Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Domain\Service
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ManagerServiceDomainWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_service_manager_class';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'ManagerServiceDomainWidget is an extendable code parser for object-oriented PHP-Projects to generate manager component';
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
     * @inheritdoc
     */
    public function isSatisfiedBy(): bool
    {
        return $this->config->has('conf-widget');
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        $templater = new Templater_\Architecture\Domain\ServiceManager\Templater($this);
        $templater->apply();

        return [
            WidgetInterface::KEY_TAG => Templater_\Architecture\Domain\ServiceManager\Templater::TAG,
            WidgetInterface::KEY_NAMESPACE => $templater->getTargetNamespace(),
            WidgetInterface::KEY_CLASS => $templater->getTargetClass(),
            WidgetInterface::KEY_CLASSNAME => $templater->getTargetClassname()
        ];
    }
}
