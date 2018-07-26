<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Domain\Entity;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Entity Domain Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Domain\Entity
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EntityDomainWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_entity_class';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'EntityDomainWidget is an extendable code parser for object-oriented PHP-Projects to generate entity component';
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
This class generate entity files.

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
        $templater = new Templater_\Architecture\Domain\Entity\Templater($this);
        $templater->apply();

        return [
            WidgetInterface::KEY_TAG => Templater_\Architecture\Domain\Entity\Templater::TAG,
            WidgetInterface::KEY_TAG => Templater_\Architecture\Domain\Service\Templater::TAG,
            WidgetInterface::KEY_NAMESPACE => $templater->getTargetNamespace(),
            WidgetInterface::KEY_CLASS => $templater->getTargetClassname(),
            WidgetInterface::KEY_CLASSNAME => $templater->getTargetClassname()
        ];
    }
}
