<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Presentation;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Request Presentation Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Presentation
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RequestPresentationWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_request';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'RequestPresentationWidget is an extendable code parser for object-oriented PHP-Projects to generate Request component';
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        return WidgetInterface::CAT_ARCHI_PRES;
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
        $templater = new Templater_\Architecture\Presentation\Request\Templater($this);
        $templater->apply();

        return [
            WidgetInterface::KEY_TAG => Templater_\Architecture\Presentation\Request\Templater::TAG,
            WidgetInterface::KEY_NAMESPACE => $templater->getTargetNamespace(),
            WidgetInterface::KEY_CLASS => $templater->getTargetClass(),
            WidgetInterface::KEY_CLASSNAME => $templater->getTargetClassname()
        ];
    }
}
