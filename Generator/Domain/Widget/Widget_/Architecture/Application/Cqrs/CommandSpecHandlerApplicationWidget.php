<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Application\Cqrs;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Cqrs Command SpecHandler Application Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Application\Cqrs
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CommandSpecHandlerApplicationWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_cqrs_validator_specification';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'CommandSpecHandlerApplicationWidget is an extendable code parser for object-oriented PHP-Projects to generate SpecHandler component';
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
        $templater = new Templater_\Architecture\Application\Cqrs\CommandSpecHandler\Templater($this);
        $templater->apply();

        return [
            WidgetInterface::KEY_TAG => Templater_\Architecture\Application\Cqrs\CommandSpecHandler\Templater::TAG,
            WidgetInterface::KEY_NAMESPACE => $templater->getTargetNamespace(),
            WidgetInterface::KEY_CLASS => $templater->getTargetClass(),
            WidgetInterface::KEY_CLASSNAME => $templater->getTargetClassname()
        ];
    }
}
