<?php
namespace Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation;

use Assert\Tests\ChildStdClass;

use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Widget\WidgetParser;
use Sfynx\CoreBundle\Generator\Domain\Widget\Metrics;
use Sfynx\CoreBundle\Generator\Domain\Widget\Exception\WidgetException;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;

/**
 * Class AbstractWidget
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Widget\Generalisation
 * @abstract
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractWidget implements WidgetInterface
{
    /** @var string */
    const TAG = '';

    /** @var Config */
    protected $config;

    /**
     * @inheritdoc
     */
    public function apply(WidgetParser $parser): void
    {
        $this->setParams($parser);

        if ($this->isSatisfiedBy()) {
            $this->parseMetrics($parser);
        }
    }

    /**
     * @param WidgetParser $parser
     * @return AbstractWidget
     */
    protected function setParams(WidgetParser $parser): AbstractWidget
    {
        $this->config = new Config();
        $this->config->set('pathDir', $parser->config->get('report-dir'));
        $this->config->set('template', $parser->config->get('report-template'));

        if (isset($parser->config->get('conf-widget')[static::TAG])
            && !empty($parser->config->get('conf-widget')[static::TAG])
        ) {
            $this->config->set('conf-widget', $parser->config->get('conf-widget')[static::TAG]);
            $this->config->set('conf-mapping', $parser->config->get('conf-mapping'));
            $this->config->set('conf-cqrs', $parser->config->get('conf-cqrs'));
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function isSatisfiedBy(): bool
    {
        return $this->config->has('conf-widget');
    }

    /**
     * @param WidgetParser $parser
     * @return void
     */
    protected function parseMetrics(WidgetParser $parser): void
    {
        $metrics = [
            WidgetInterface::KEY_CATEGORY    => $this->getCategory(),
            WidgetInterface::KEY_NAME        => $this->getName(),
            WidgetInterface::KEY_DESCRIPTION => $this->getDescription(),
            WidgetInterface::KEY_DATA        => $this->getData($parser->reporter)
        ];
        $parser->reporter->metrics->attach(static::TAG, $metrics);
    }

    /**
     * @param ReporterObservable $reporter
     * @return array
     */
    abstract protected function getData(ReporterObservable $reporter): array;

    /**
     * @inheritdoc
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        throw new WidgetException('getName method is not define !');
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        throw new WidgetException('getCategory method is not define !');
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        throw new WidgetException('getDescription method is not define !');
    }
}
