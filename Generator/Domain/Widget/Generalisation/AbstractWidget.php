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
    /** @var WidgetParser */
    public $parser;

    /**
     * @inheritdoc
     */
    public function apply(WidgetParser $parser): void
    {
        $this->setParams($parser);

        if ($this->isSatisfiedBy()) {
            $this->parseMetrics();
        }
    }

    /**
     * @param WidgetParser $parser
     * @return AbstractWidget
     */
    protected function setParams(WidgetParser $parser): AbstractWidget
    {
        $this->parser = $parser;
        $this->config = new Config();
        $this->config->set('pathDir', $parser->getConfig()->get('report-dir'));
        $this->config->set('template', $parser->getConfig()->get('report-template'));
        $this->config->set('reportDir', $parser->getConfig()->get('report-dir') . '/' . $parser->getConfig()->get('namespace'));
        $this->config->set('namespace', $parser->getConfig()->get('namespace'));
        $this->config->set('template', $parser->getConfig()->get('report-template'));
        $this->config->set('indentation', $parser->getConfig()->get('conf-indentation'));

        if (!empty($parser->getConfig()->get('conf-widget')[static::TAG])) {
            $this->config->set('conf-widget', $parser->getConfig()->get('conf-widget')[static::TAG]);
            $this->config->set('conf-index', $parser->getConfig()->get('conf-index'));
            $this->config->set('conf-mapping', $parser->getConfig()->get('conf-mapping'));
            $this->config->set('conf-cqrs', $parser->getConfig()->get('conf-cqrs'));
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function isSatisfiedBy(): bool
    {
        return $this->getConfig()->has('conf-widget')
            && $this->getConfig()->has('conf-index')
            && $this->getConfig()->has('conf-mapping')
            && $this->getConfig()->has('conf-cqrs');
    }

    /**
     * @return void
     */
    protected function parseMetrics(): void
    {
        $data = $this->getData();

        $metrics = array_merge($data, [
            WidgetInterface::KEY_CATEGORY    => $this->getCategory(),
            WidgetInterface::KEY_NAME        => $this->getName(),
            WidgetInterface::KEY_DESCRIPTION => $this->getDescription(),
        ]);
        $this->getParser()->getReporter()->metrics->attach(static::TAG, $metrics);

        $this->updateIndex($metrics);
    }

    /**
     * Update the index key with the complet namespace of generated class
     *
     * @param array $metrics
     * @return void
     */
    protected function updateIndex(array $metrics): void
    {
        $index = $this->getParser()->getConfig()->get('conf-index');
        $oldkey = $metrics[WidgetInterface::KEY_CLASS];
        $classname = $metrics[WidgetInterface::KEY_CLASSNAME];
        $namespace = $metrics[WidgetInterface::KEY_NAMESPACE];

        if (array_key_exists($oldkey, $index)) {
            $newkey = $namespace . '\\' . $classname;
            $index[$newkey] = $index[$oldkey];
            unset($index[$oldkey]);
        }

        $this->getParser()->getConfig()->set('conf-index', $index);
    }

    /**
     * @return array
     */
    abstract protected function getData(): array;

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
    public function getParser(): WidgetParser
    {
        return $this->parser;
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
