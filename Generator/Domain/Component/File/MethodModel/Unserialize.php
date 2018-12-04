<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;

use stdClass;
use SplSubject;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
use Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Observer\Entity;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class Unserialize
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\MethodModel
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Unserialize
{
    const METHOD_DEFAULT = '';
    const METHOD_VO = 'valueObjectAggregator';

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected static $createMethodList = [
        self::METHOD_DEFAULT => 'createDefaultMethod',
        self::METHOD_VO => 'createMethodFromValueObject',
    ];

    /**
     * Create unserialize method.
     *
     * @param PhpNamespace $namespace
     * @param ClassType $class
     * @param array|null $index
     * @param array|null $options
     * @param string|null $method
     * @static
     * @return void
     */
    public static function handle(
        PhpNamespace $namespace,
        ClassType $class,
        ?array $index = [],
        ?array $fields = [],
        ?array $options = null,
        string $method = ''
    ): void {
        $method = ($method == Entity::ENTITY_TYPE_DEFAULT) ? self::METHOD_DEFAULT : $method;
        $result = self::{self::$createMethodList[$method]}($namespace, $index, $fields, $options);

        ClassHandler::createMethods(
            $namespace,
            $class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => 'unserialize',
                        'comments' => ['Set properties from serialised data.'],
                        'visibility' => 'public',
                        'arguments' => $result['arguments'],
                        'returnType' => 'void',
                        'body' => [$result['fieldContent']]
                    ]]
                ]
            ], false),
            $index
        );
    }

    /**
     * @param PhpNamespace $namespace
     * @param array|null $fields
     * @param array|null $options
     * @return array
     */
    protected static function createDefaultMethod(
        PhpNamespace $namespace,
        ?array $index = [],
        ?array $fields = [],
        ?array $options = null
    ): array {
        $fieldContent = '$unserializedData = \unserialize($data);' . PHP_EOL;
        foreach ($fields as $field) {
            $propertyFieldName = \lcfirst($field->name);
            $propertyFieldNameNew = $propertyFieldName;

            \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
            if ($isFieldEntity) {
                $propertyFieldNameNew = 'id';
            }
            $fieldContent .= sprintf("\$this->%s = \$unserializedData['%s'];", $propertyFieldNameNew, $propertyFieldName) . PHP_EOL;
        }

        return [
            'arguments' => ['string $data'],
            'fieldContent' => $fieldContent,
        ];
    }

    /**
     * @param PhpNamespace $namespace
     * @param array|null $fields
     * @param array|null $options
     * @return array
     */
    protected static function createMethodFromValueObject(
        PhpNamespace $namespace,
        ?array $index = [],
        ?array $fields = [],
        ?array $options = null
    ): array {
        $fieldContent = '$unserializedData = \unserialize($data);' . PHP_EOL;
        $templater = $options['templater'];

        foreach ($fields as $field) {
            if($field->type == ClassHandler::TYPE_VO) {
                $voName = $field->voName;

                $vo = $templater->getTargetValueObjects()[$voName];
                $vo = AbstractGenerator::transform($vo, false);

                $voClassName = ClassHandler::getClassNameFromNamespace($vo->type);

                \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
                if ($voName == 'IdVO' && $isFieldEntity) {
                    $fieldContent .= PHP_EOL . sprintf("\$this->%s = %s::createFromNative(\array_intersect(\$arguments, ['%s']));", 'id', $voClassName, $field->name);
                } elseif ($voName == 'IdVO' && !$isFieldEntity) {
                    $fieldContent .= PHP_EOL . sprintf("\$this->%s = \$unserializedData['%s'];", $field->name, $field->name);
                } else {
                    $fieldContent .= PHP_EOL . sprintf("\$this->%s = %s::createFromNative(\$unserializedData);", $field->name, $voClassName);
                }
                ClassHandler::addUse($namespace, $vo->type, $index);
            } elseif(!($field->type == ClassHandler::TYPE_UUID
                && isset($options['toEntity'])
                && $options['toEntity'])
            ) {
                $propertyFieldName = \lcfirst($field->name);

                $fieldContent .= PHP_EOL . sprintf("\$this->%s = \$unserializedData['%s'];", $propertyFieldName, $propertyFieldName);

                ClassHandler::addUse($namespace, $field->type, $index);
            }
        }

        return [
            'arguments' => ['string $data'],
            'fieldContent' => $fieldContent,
        ];
    }
}
