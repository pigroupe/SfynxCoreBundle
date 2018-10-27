<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;

use stdClass;
use SplSubject;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
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

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected static $createMethodList = [
        self::METHOD_ARRAY => 'createMethodFromArray',
        self::METHOD_NATIVE => 'createMethodFromNative',
    ];

    /**
     * Create CreateFromNative method.
     *
     * @param PhpNamespace $namespace
     * @param ClassType $class
     * @param array|null $index
     * @param array|null $fields
     * @static
     * @return void
     */
    public static function handle(
        PhpNamespace $namespace,
        ClassType $class,
        ?array $index = [],
        ?array $fields = [],
        ?array $options = null
    ): void {
        $method = !isset($options['createFromNativeType']) ? 'general_arg' : $options['createFromNativeType'];
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
        $fieldContent = 'return new self(';
        foreach ($fields as $field) {
            if(!($field->type == ClassHandler::TYPE_UUID
                && isset($options['toEntity'])
                && $options['toEntity'])
            ) {
                $propertyFieldName = \lcfirst($field->name);
                $prefix = (0 == $count) ? '' : ',';
                $fieldContent .= $prefix . PHP_EOL . sprintf("  \$%s", $propertyFieldName);
                $typeFieldName = ClassHandler::getType($field->type, $field, true);
                $ClassTypeFieldName = ClassHandler::getClassNameFromNamespace($typeFieldName);

                if (isset($field->primaryKey) && $field->primaryKey) {
                    $ClassTypeFieldName = 'int';
                }
                if (isset($options['toEntity']) && $options['toEntity']
                    && property_exists($field, 'mapping') && property_exists($field->mapping, 'relationship')
                    && $field->mapping->relationship == 'ManyToMany'
                ) {
                    $ClassTypeFieldName = 'iterable';
                    ClassHandler::addUse($namespace, 'iterable', $index);
                }

                ClassHandler::addUse($namespace, $field->type, $index);
                array_push($arguments, sprintf('%s $%s = null', $ClassTypeFieldName, $propertyFieldName));

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
        $fieldContent = 'return new self(';
        foreach ($fields as $field) {
            if(!($field->type == ClassHandler::TYPE_UUID
                && isset($options['toEntity'])
                && $options['toEntity'])
            ) {
                $propertyFieldName = \lcfirst($field->name);
                $prefix = (0 == $count) ? '' : ',';
                $fieldContent .= $prefix . PHP_EOL . sprintf("  \$arguments['%s'] ?? null", $propertyFieldName);
                $typeFieldName = ClassHandler::getType($field->type, $field, true);

                ClassHandler::addUse($namespace, $field->type, $index);

                $count++;
            }
        }
        $fieldContent .= PHP_EOL . ');';
        array_push($arguments, 'array $arguments');

        return [
            'arguments' => $arguments,
            'fieldContent' => $fieldContent,
        ];
    }
}
