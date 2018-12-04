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
 * Class ToArray
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\MethodModel
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ToArray
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
     * Create __toArray method.
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
                        'name' => '__toArray',
                        'comments' => ['Return all properties of the class in array.'],
                        'visibility' => 'public',
                        'arguments' => $result['arguments'],
                        'returnType' => 'array',
                        'body' => [$result['fieldContent']],
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
        $arguments = [];
        foreach ($fields as $field) {
            $propertyFieldName = \lcfirst($field->name);
            $propertyFieldNameNew = $propertyFieldName;

            \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
            if ($isFieldEntity) {
                $propertyFieldNameNew = 'id';
            }
            $arguments[$propertyFieldNameNew] = sprintf("\$this->%s", $propertyFieldName);
        }

        $fieldContent = 'return ';
        $arguments = AbstractGenerator::transform($arguments, true);
        $fieldContent .= ClassHandler::recursiveArrayToString($arguments, '', '    ');
        $fieldContent .= ';' . PHP_EOL;

        return [
            'arguments' => [],
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
        $templater = $options['templater'];
        $arguments = [];

        foreach ($fields as $field) {
            if($field->type == ClassHandler::TYPE_VO) {
                $voName = $field->voName;
                $propertyFieldNameNew = $field->name;

                $vo = $templater->getTargetValueObjects()[$voName];
                $vo = AbstractGenerator::transform($vo, false);

                $voClassName = ClassHandler::getClassNameFromNamespace($vo->type);

                \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
                if ($isFieldEntity) {
                    $propertyFieldNameNew = 'id';
                }
                $arguments[$propertyFieldNameNew] = sprintf("\$this->%s->__toArray()", $propertyFieldNameNew);

                $typeFieldName = ClassHandler::getType($vo->type, $field, true);
                ClassHandler::addUse($namespace, $typeFieldName, $index);
            }
        }

        $fieldContent = 'return ';
        $arguments = AbstractGenerator::transform($arguments, true);
        $fieldContent .= ClassHandler::recursiveArrayToString($arguments, '', '    ');
        $fieldContent .= ';' . PHP_EOL;

        return [
            'arguments' => [],
            'fieldContent' => $fieldContent,
        ];
    }
}
