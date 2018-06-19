<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget;

use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Domain\Widget\Exception\WidgetException;
use Sfynx\CoreBundle\Generator\Domain\Widget\Widget_\Architecture;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Component\Issue\Issuer;
use Sfynx\CoreBundle\Generator\Domain\Component\Output\Output;

/**
 * Class Analyze
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget
 */
class WidgetParser
{
    /** @var Output */
    protected $output;
    /** @var Issuer */
    protected $issuer;
    /** @var Config */
    protected $config;
    /** @var ReporterObservable */
    protected $reporter;

    /**
     * Analyze constructor.
     * @param OutputInterface $output
     */
    public function __construct(Config $config, Output $output, Issuer $issuer)
    {
        $this->output = $output;
        $this->issuer = $issuer;
        $this->config = $config;
        $this->reporter = new ReporterObservable($config, $output);
    }

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    public static function handlerList()
    {
        return [
            Architecture\Infrastructure\Service\ServiceInfrastructureWidget::TAG => new Architecture\Infrastructure\Service\ServiceInfrastructureWidget(),
            Architecture\Domain\Service\ManagerServiceDomainWidget::TAG => new Architecture\Domain\Service\ManagerServiceDomainWidget(),
            Architecture\Domain\Service\ServiceDomainWidget::TAG => new Architecture\Domain\Service\ServiceDomainWidget(),
            Architecture\Domain\Workflow\WorkflowObserverDomainWidget::TAG => new Architecture\Domain\Workflow\WorkflowObserverDomainWidget(),
            Architecture\Application\Service\ServiceApplicationWidget::TAG => new Architecture\Application\Service\ServiceApplicationWidget(),
            Architecture\Application\Validation\ValidationTypeApplicationWidget::TAG => new Architecture\Application\Validation\ValidationTypeApplicationWidget(),
            Architecture\Application\Cqrs\CommandValidationHandlerApplicationWidget::TAG => new Architecture\Application\Cqrs\CommandValidationHandlerApplicationWidget(),
            Architecture\Application\Cqrs\CommandSpecHandlerApplicationWidget::TAG => new Architecture\Application\Cqrs\CommandSpecHandlerApplicationWidget(),
            Architecture\Application\Cqrs\CommandApplicationWidget::TAG => new Architecture\Application\Cqrs\CommandApplicationWidget(),
            Architecture\Presentation\RequestPresentationWidget::TAG => new Architecture\Presentation\RequestPresentationWidget(),
            Architecture\Presentation\CoordinationtPresentationWidget::TAG => new Architecture\Presentation\CoordinationtPresentationWidget(),
        ];
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
            foreach (static::handlerList() as $widget) {
                $widget->apply($this);
            }
            $this->reporter->convertDataToObject();
        } catch (WidgetException $e) {
            $this->output->writeln(sprintf('<error>Cannot parse widgets</error>'));
        }
        $this->output->write('<info>++</info> Executing system analyzes...');
        $this->output->clearln();

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
