<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Cqrs\Query;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\AbstractTemplater;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Application\Cqrs\Query
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Templater extends AbstractTemplater implements TemplaterInterface
{
    /** @var string */
    const TAG = 'templater_archi_app_cqrs_query';

    /** @var array */
    const TARGET_ATTRIBUTS = [
        'conf-mapping' => 'commandFields',
        'conf-widget',
        'conf-cqrs'
    ];

    /** @var string */
    const TEMPLATE_GENERATOR = ReporterObservable::GENERATOR_PHP_MULTIPLE;

    /**
     * @inheritdoc
     */
    public static function scriptList(string $template): array
    {
        return ['Application\Cqrs'];
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Query class';
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
    public function getTag(): string
    {
        return self::TAG;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return <<<EOT
This class expose query component
EOT;
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
        if (\property_exists($data, 'type') && ($data->type == 'interface')) {
            $class->setType(ClassType::TYPE_INTERFACE);
        }
        if (\property_exists($data, 'type') && ($data->type == 'trait')) {
            $class->setType(ClassType::TYPE_TRAIT);
        }
        if (\property_exists($data, 'type') && ($data->type == 'abstract')) {
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

        foreach ($this->getTargetCommandFields() as $field) {
            $class->addProperty(lcfirst($field->name))->setComment(sprintf('@var %s', ClassHandler::getType($field->type, $field)))->setVisibility('protected');
        }

        return ClassHandler::tabsToSpaces($namespace, $this->getIndentation()) .
            ClassHandler::tabsToSpaces($class, $this->getIndentation());
    }
}

