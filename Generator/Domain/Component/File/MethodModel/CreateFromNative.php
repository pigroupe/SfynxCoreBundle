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
 * Class CreateFromNative
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\MethodModel
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CreateFromNative
{
    const METHOD_ARRAY = 'general_arg';
    const METHOD_NATIVE = 'specific_arg';
    const METHOD_VO = 'valueObjectAggregator';

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected static $createMethodList = [
        self::METHOD_ARRAY => 'createMethodFromArray',
        self::METHOD_NATIVE => 'createMethodFromNative',
        self::METHOD_VO => 'createMethodFromValueObject',
    ];

    /**
     * Create CreateFromNative method.
     *
     * @param PhpNamespace $namespace
     * @param ClassType $class
     * @param array|null $index
     * @param array|null $fields
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
        string $method = Entity::ENTITY_TYPE_DEFAULT
    ): void {
        if ($method == Entity::ENTITY_TYPE_DEFAULT) {
            $method = !isset($options['createFromNativeType']) ? self::METHOD_ARRAY : $options['createFromNativeType'];
        }
        $result = self::{self::$createMethodList[$method]}($namespace, $index, $fields, $options);

        ClassHandler::createMethods(
            $namespace,
            $class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => 'createFromNative',
                        'comments' => [sprintf('%s new instance.', $class->getName())],
                        'visibility' => 'public',
                        'arguments' => $result['arguments'],
                        'returnType' => 'self',
                        'static' => true,
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
    protected static function createMethodFromNative(
        PhpNamespace $namespace,
        ?array $index = [],
        ?array $fields = [],
        ?array $options = null
    ): array {
        $arguments = [];
        $count = 0;
        $prefixField = '';
        $fieldContent = 'return new self(';

        if (!empty($options['prefixField'])) {
            $prefixField = $options['prefixField'];
        }

        foreach ($fields as $field) {
            if(!($field->type == ClassHandler::TYPE_UUID
                && isset($options['toEntity'])
                && $options['toEntity'])
            ) {
                $propertyFieldName = \lcfirst($prefixField . \ucfirst($field->name));

                $prefix = (0 == $count) ? '' : ',';
                $fieldContent .= $prefix . PHP_EOL . sprintf("  \$%s", $propertyFieldName);

                $typeFieldName = ClassHandler::getType($field->type, $field, true);
                $ClassTypeFieldName = ClassHandler::getClassNameFromNamespace($typeFieldName);

                if (isset($field->primaryKey) && $field->primaryKey) {
                    $ClassTypeFieldName = 'int';
                }
                if (isset($options['toEntity']) && $options['toEntity']
                    && \property_exists($field, 'mapping') && property_exists($field->mapping, 'relationship')
                    && $field->mapping->relationship == 'ManyToMany'
                ) {
                    $ClassTypeFieldName = 'iterable';
                    ClassHandler::addUse($namespace, 'iterable', $index);
                }

                ClassHandler::addUse($namespace, $field->type, $index);
                \array_push($arguments, sprintf('%s $%s = null', $ClassTypeFieldName, $propertyFieldName));

                $count++;
            }
        }
        $fieldContent .= PHP_EOL . ');';

        return [
            'arguments' => $arguments,
            'fieldContent' => $fieldContent,
        ];
    }

    /**
     * @param PhpNamespace $namespace
     * @param array|null $fields
     * @param array|null $options
     * @return array
     */
    protected static function createMethodFromArray(
        PhpNamespace $namespace,
        ?array $index = [],
        ?array $fields = [],
        ?array $options = null
    ): array {
        $arguments = [];
        $count = 0;
        $prefixField = '';
        $fieldContent = 'return new self(';

        if (!empty($options['prefixField'])) {
            $prefixField = $options['prefixField'];
        }

        foreach ($fields as $field) {
            if(!($field->type == ClassHandler::TYPE_UUID
                && isset($options['toEntity'])
                && $options['toEntity'])
            ) {
                $propertyFieldName = \lcfirst($prefixField . \ucfirst($field->name));
                $prefix = (0 == $count) ? '' : ',';
                $fieldContent .= $prefix . PHP_EOL . sprintf("  \$arguments['%s'] ?? null", $propertyFieldName);

                ClassHandler::addUse($namespace, $field->type, $index);

                $count++;
            }
        }
        $fieldContent .= PHP_EOL . ');';

        \array_push($arguments, 'array $arguments');
        \array_push($arguments, 'array $excluded = []');

        return [
            'arguments' => $arguments,
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
        if (!isset($options['templater'])) {
            throw new \LogicException('templater option is not define !');
        }

        $templater = $options['templater'];
        $arguments = [];
        $count = 0;
        $fieldContent = 'return new self(';

        foreach ($fields as $field) {
            if($field->type == ClassHandler::TYPE_VO) {
                $voName = $field->voName;

                $vo = $templater->getTargetValueObjects()[$voName];
                $vo = AbstractGenerator::transform($vo, false);
                $voClassName = ClassHandler::getClassNameFromNamespace($vo->type);

                $prefix = (0 == $count) ? '' : ',';
                $count++;

                if ($voName == 'IdVO') {
                    \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
                    if ($isFieldEntity) {
                        $fieldContent .= $prefix . PHP_EOL . sprintf("  %s::createFromNative(\array_intersect(\$arguments, ['%s']))", $voClassName, $field->name);
                    } else {
                        $fieldContent .= $prefix . PHP_EOL . sprintf("  \$arguments['%s'])) ?? null", $field->name);
                    }
                } else {
                    $fieldContent .= $prefix . PHP_EOL . sprintf("  %s::createFromNative(\$arguments)", $voClassName);
                }
                ClassHandler::addUse($namespace, $vo->type, $index);
            } elseif(!($field->type == ClassHandler::TYPE_UUID
                && isset($options['toEntity'])
                && $options['toEntity'])
            ) {
                $prefix = (0 == $count) ? '' : ',';
                $count++;

                $propertyFieldName = \lcfirst($field->name);
                $fieldContent .= $prefix . PHP_EOL . sprintf("  \$arguments['%s'] ?? null", $propertyFieldName);

                ClassHandler::addUse($namespace, $field->type, $index);
            }
        }
        $fieldContent .= PHP_EOL . ');';

        \array_push($arguments, 'array $arguments');
        \array_push($arguments, 'array $excluded = []');


        return [
            'arguments' => $arguments,
            'fieldContent' => $fieldContent,
        ];
    }
}
