<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture\Application\Cqrs;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\AbstractWidget;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Templater\Templater_;

/**
 * Cqrs Command ValidationHandler Application Widget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Widget_\Architecture\Application\Cqrs
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CommandValidationHandlerApplicationWidget extends AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = 'widget_cqrs_validator_validation';

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'CommandValidationHandlerApplicationWidget is an extendable code parser for object-oriented PHP-Projects to generate ValidationHandler component';
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
     * @param ReporterObservable $reporter
     * @return array
     */
    protected function getData(ReporterObservable $reporter): array
    {
        $templater = new Templater_\Architecture\Application\Cqrs\CommandValidationHandler\Templater($this, $reporter);
        $templater->apply();
        
        return [
            Templater_\Architecture\Application\Cqrs\CommandValidationHandler\Templater::TAG,
        ];
    }
}
