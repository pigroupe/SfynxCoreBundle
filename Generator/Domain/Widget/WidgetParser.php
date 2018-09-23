<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget;

use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Domain\Widget\Exception\WidgetException;
use Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Analyze
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget
 */
class WidgetParser
{
    /** @var Config */
    protected $config;
    /** @var ReporterObservable */
    protected $reporter;

    /**
     * Analyze constructor.
     * @param OutputInterface $output
     */
    public function __construct(Config $config, OutputInterface $output)
    {
        $this->config = $config;
        $this->reporter = new ReporterObservable($config, $output);
    }

    /**
     * List of concrete handlers that can be built using this factory.
     */
    public function handlerList()
    {
        $handlers = [
            Architecture\Infrastructure\Service\ServiceInfrastructureWidget::class,
            Architecture\Domain\Entity\EntityDomainWidget::class,
            Architecture\Domain\Service\ManagerServiceDomainWidget::class,
            Architecture\Domain\Service\ServiceDomainWidget::class,
            Architecture\Domain\Workflow\WorkflowObserverDomainWidget::class,
            Architecture\Application\Service\ServiceApplicationWidget::class,
            Architecture\Application\Validation\ValidationTypeApplicationWidget::class,
            Architecture\Application\Cqrs\QueryValidationHandlerApplicationWidget::class,
            Architecture\Application\Cqrs\QuerySpecHandlerApplicationWidget::class,
            Architecture\Application\Cqrs\QueryApplicationWidget::class,
            Architecture\Application\Cqrs\CommandValidationHandlerApplicationWidget::class,
            Architecture\Application\Cqrs\CommandSpecHandlerApplicationWidget::class,
            Architecture\Application\Cqrs\CommandApplicationWidget::class,
            Architecture\Presentation\RequestPresentationWidget::class,
            Architecture\Presentation\CoordinationtPresentationWidget::class,
        ];

        foreach ($handlers as $handler) {
            yield new $handler;
        }
    }

    /**
     * Runs parser
     *
     * @return ReporterObservable
     * @access public
     * @throws WidgetException
     */
    public function run()
    {
        try {
            foreach ($this->handlerList() as $widget) {
                $widget->apply($this);
            }
            $this->reporter->convertDataToObject();
        } catch (WidgetException $e) {
            throw new WidgetException('Cannot parse widgets', 0, $e);
        }

        return $this->reporter;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return ReporterObservable
     */
    public function getReporter(): ReporterObservable
    {
        return $this->reporter;
    }
}
