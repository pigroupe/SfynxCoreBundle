<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report;

use stdClass;
use Sfynx\CoreBundle\Generator\Application\Config\Config;
use Sfynx\CoreBundle\Generator\Domain\Component\Output\Output;
use Sfynx\CoreBundle\Generator\Domain\Report\Exception\ReportException;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;
use Sfynx\CoreBundle\Generator\Domain\Report\Metrics;
use Sfynx\CoreBundle\Generator\Domain\Report\Generator;
use Sfynx\CoreBundle\Generator\Domain\Report\Data;
use Sfynx\CoreBundle\Generator\Domain\Report\Handler;

/**
 * Reporter Observable class
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ReporterObservable
{
    /**  @var Config */
    public $config;
    /** @var Output */
    public $output;
    /** @var Metrics */
    public $metrics;

    /** @var string */
    const GENERATOR_TEMPLATE = 'templateClassGenerator';
    /** @var string */
    const GENERATOR_TEMPLATE_MULTIPLE = 'templateClassesGenerator';
    /** @var string */
    const GENERATOR_PHP = 'phpClassGenerator';
    /** @var string */
    const GENERATOR_PHP_MULTIPLE = 'phpClassesGenerator';

    /**
     * List of concrete generator Class that can be built using this report.
     * @var string[]
     */
    protected static $generatorList = [
        Generator\TemplateClassGenerator::class => self::GENERATOR_TEMPLATE,
        Generator\TemplateClassesGenerator::class => self::GENERATOR_TEMPLATE_MULTIPLE,
        Generator\PhpClassGenerator::class => self::GENERATOR_PHP,
        Generator\PhpClassesGenerator::class => self::GENERATOR_PHP_MULTIPLE,
    ];

    /**
     * List of handlers
     * @var string[]
     */
    public static function handlerList()
    {
        return [
            new Handler\CreateArtifatcHandler(),
        ];
    }

    /**
     * Reporter constructor.
     * @param Config $config
     * @param Output $output
     */
    public function __construct(Config $config, Output $output)
    {
        $this->config = $config;
        $this->output = $output;

        $this->metrics = new Metrics();

        foreach (self::$generatorList as $class => $attr) {
            $this->$attr = new $class();
        }
    }

    /**
     * @param Metrics $metrics
     *
     * @return void
     * @access public
     * @throws ReportException
     */
    public function generate()
    {
        if (!$this->config->has('report-dir')) {
            throw new ReportException('Bad report-dir configuration !');
        }
        foreach (static::handlerList() as $handler) {
            $handler->handle($this);
        }
        $this->output->writeln(sprintf('<info>++</info> Artifact report generated in "%s" directory', $this->config->get('report-dir')));
    }

    /**
     * Converting array data from all generators to stdClass data
     *
     * @return void
     * @static
     */
    public function convertDataToObject()
    {
        AbstractGenerator::convertDataToObject();
    }

    /**
     * @return stdClass
     */
    public function getDataCl(): stdClass
    {
        return AbstractGenerator::$dataCl;
    }

    /**
     * @return array
     */
    public function getDataArr(): array
    {
        return AbstractGenerator::$dataArr;
    }
}
