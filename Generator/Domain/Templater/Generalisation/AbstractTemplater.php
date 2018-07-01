<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation;

use Nette\PhpGenerator\ClassType;
use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;
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
        'conf-options' => 'options',
        'conf-cqrs'
    ];

    /** @var string */
    const TEMPLATE_GENERATOR = ReporterObservable::GENERATOR_TEMPLATE;

    /** @var bool */
    const NAMESPACE_WITH_CQRS = true;

    /** @var integer */
    protected $indentation;
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
     */
    public function __construct(WidgetInterface $widget)
    {
        $this->widget = $widget;
        $this->reporter = $widget->getParser()->getReporter();
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
        $this->reportDir = $this->widget->getConfig()->get('reportDir');
        $this->namespace = $this->widget->getConfig()->get('namespace');
        $this->template = $this->widget->getConfig()->get('template');
        $this->indentation = $this->widget->getConfig()->get('indentation');

        foreach (static::TARGET_ATTRIBUTS as $source => $attribut) {
            $attributName = "target" . ucfirst(str_replace('conf-', '', $attribut));

            if ($this->widget->getConfig()->has($attribut)) {
                $this->$attributName = $this->widget->getConfig()->get($attribut);
            } elseif ($this->widget->getConfig()->has($source)) {
                $this->$attributName = $this->widget->getConfig()->get($source);
                if (array_key_exists($attribut, $this->widget->getConfig()->get($source))) {
                    $this->$attributName = $this->widget->getConfig()->get($source)[$attribut];
                }
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
        $index = $this->updateIndex();

        $data = AbstractGenerator::transform($data, false);

        $namespace = ClassHandler::getNamespace($this->getTargetNamespace());
        ClassHandler::addUses($namespace, $data);

        $class = ClassHandler::getClass($this->getTargetClassname());
        if (property_exists($data, 'type') && ($data->type == 'interface')) {
            $class->setType(ClassType::TYPE_INTERFACE);
        }
        if (property_exists($data, 'type') && ($data->type == 'trait')) {
            $class->setType(ClassType::TYPE_TRAIT);
        }
        if (property_exists($data, 'type') && ($data->type == 'abstract')) {
            $class->setAbstract(true);
        }

        ClassHandler::setClassCommentor($class, $this, $data);
        ClassHandler::setExtends($namespace, $class, $data, $index);
        ClassHandler::addUses($namespace, $data, $index);
        ClassHandler::addImplements($namespace, $class, $data, $index, $this->getNamespace());
        ClassHandler::addTraits($namespace, $class, $data, $index);
        ClassHandler::addArguments($namespace, $class, $data, $index);
        ClassHandler::addModels($this, $namespace, $class, $data, $index);
        ClassHandler::createMethods($namespace, $class, $data, $index);
        ClassHandler::addConstructorMethod($namespace, $class, $data);
        ClassHandler::addMethods($class);

        return ClassHandler::tabsToSpaces($namespace, $this->getIndentation()) .
                ClassHandler::tabsToSpaces($class, $this->getIndentation());
    }

    /**
     * @inheritdoc
     */
    public function getIndentation(): int
    {
        return $this->indentation;
    }

    /**
     * Update the index key with the complet namespace of generated class
     *
     * @return array|null
     */
    protected function updateIndex(): ?array
    {
        $index = $this->widget->getParser()->getConfig()->get('conf-index');
        $classname = $this->getTargetClassname();
        $namespace = $this->getTargetNamespace();

        // we replace class name from complet name from index
        foreach ($index as $class => $arguments) {
            $oldkey = $class;
            $newkey = $class;
            str_replace($classname, $classname, $class, $count1);

            if ($count1) {
                if (array_key_exists($oldkey, $index)) {
                    $newkey = $namespace . '\\' . $classname;
                    $index[$newkey] = $index[$oldkey];
                    unset($index[$oldkey]);
                }
            }
        }

        $this->widget->getParser()->getConfig()->set('conf-index', $index);

        return $index;
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
        exit(sprintf( "Opps! The method %s call is not allowed!", $methodName));
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
        exit(sprintf( "Opps! The property %s call is not allowed!", $property));
    }
}
