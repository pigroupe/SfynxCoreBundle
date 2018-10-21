<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;

use stdClass;
use SplSubject;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class Construct
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\MethodModel
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Construct
{
    /**
     * Create Construct method.
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
        $arguments = [];
        $fieldContent = '';
        foreach ($fields as $field) {
            if($field->type == ClassHandler::TYPE_UUID
                && isset($options['toEntity']) && $options['toEntity']
            ) {
                $fieldContent .= sprintf("\$this->id = BaseUuid::uuid4();") . PHP_EOL;
                ClassHandler::addUse($namespace, 'Ramsey\Uuid\Uuid as BaseUuid', $index);
            } else {
                $propertyFieldName = \lcfirst($field->name);
                $fieldContent .= sprintf("\$this->%s = \$%s;", $propertyFieldName, $propertyFieldName) . PHP_EOL;
                $typeFieldName = ClassHandler::getType($field->type, $field, true);
                $ClassTypeFieldName = ClassHandler::getClassNameFromNamespace($typeFieldName);

                if ($field->type == ClassHandler::TYPE_UUID) {
                    $ClassTypeFieldName = 'string';
                } elseif (isset($field->primaryKey) && $field->primaryKey) {
                    $ClassTypeFieldName = 'int';
                }
                if ($field->type == 'id' && isset($options['toEntity']) && !$options['toEntity']) {
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
            }
        }

        ClassHandler::createMethods(
            $namespace,
            $class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => '__construct',
                        'comments' => [sprintf('%s constructor.', $class->getName())],
                        'visibility' => 'public',
                        'arguments' => $arguments,
                        'returnType' => '',
                        'body' => [$fieldContent]
                    ]]
                ]
            ], false),
            $index
        );
    }
}
