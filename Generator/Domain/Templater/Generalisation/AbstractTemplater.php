<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation;

use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Exception\TemplaterException;

/**
 * Class AbstractTemplater
 * 
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Templater\Generalisation
 * @abstract
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractTemplater implements TemplaterInterface
{
    /** @var string */
    const TAG = '';
    /** @var array */
    const TARGET_ATTRIBUTS = [
        'conf-mapping' => 'commandFields',
        'conf-widget' => 'class',
        'conf-cqrs'
    ];
    /** @var string */
    const TEMPLATE_GENERATOR = ReporterObservable::GENERATOR_SIMPLE;

    /** @var WidgetInterface  */
    protected $widget;
    /** @var ReporterObservable  */
    protected $reporter;
    /** @var string */
    public $reportDir;
    /** @var string */
    public $template;
    /** @var string */
    public $namespace;

    /**
     * GraphAbstract constructor.
     * @param WidgetInterface $widget
     * @param ReporterObservable $reporter
     */
    public function __construct(WidgetInterface $widget, ReporterObservable $reporter)
    {
        $this->widget = $widget;
        $this->reporter = $reporter;
    }

    /**
     * @inheritdoc
     */
    public static function scriptList(string $template): array
    {
        throw new TemplaterException('scriptList method is not define !');
    }

    /**
     * @inheritdoc
     */
    final public function apply(): void
    {
        $this->reportDir = $this->reporter->config->get('report-dir') . '/' . $this->reporter->config->get('namespace');
        $this->namespace = $this->reporter->config->get('namespace');
        $this->template = $this->reporter->config->get('report-template');

        foreach (static::TARGET_ATTRIBUTS as $source => $attribut) {
            $attributName = "target" . ucfirst(str_replace('conf-', '', $attribut));

            if ($this->widget->getConfig()->has($attribut)) {
                $this->$attributName = $this->widget->getConfig()->get($attribut);
            } else {
                $this->$attributName = $this->widget->getConfig()->get($source)[$attribut];
            }
        }

        $this->reporter->{static::TEMPLATE_GENERATOR}->generateClassData($this);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        throw new TemplaterException('getName method is not define !');
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        throw new TemplaterException('getCategory method is not define !');
    }

    /**
     * @inheritdoc
     */
    public function getTag(): string
    {
        throw new TemplaterException('getTag method is not define !');
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        throw new TemplaterException('getDescription method is not define !');
    }

    /**
     * @inheritdoc
     */
    public function getClassValue(array $data = []): string
    {
        echo '';
    }

    /**
     * @param $methodName
     * @param null $params
     * @return mixed
     */
    public function __call($methodName, $params = null)
    {
        $methodPrefix = substr($methodName, 0, 3);
        $property = lcfirst(substr($methodName, 3));
        if($methodPrefix == 'get' && property_exists($this, $property)) {
            return $this->$property;
        }
        if($methodName == 'has') {
            return property_exists($this, $params[0]);
        }
        exit("Opps! The method call is not allowed!");
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if(property_exists($this, $property)) {
            return $this->$property;
        }
        exit("Opps! The method is not allowed!");
    }
}
