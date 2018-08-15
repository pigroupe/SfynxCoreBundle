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
     * Create __toArray method.
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
        ?array $fields = []
    ): void {

        $arguments = [];
        $fieldContent = '';
        foreach ($fields as $field) {
            \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
            if (!$isFieldEntity) {
                $propertyFieldName = \lcfirst($field->name);
                $fieldContent .= sprintf("\$this->%s = \$%s;", $propertyFieldName, $propertyFieldName) . PHP_EOL;

                $typeFieldName = ClassHandler::getType($field->type, $field, true);
                $ClassTypeFieldName = ClassHandler::getClassNameFromNamespace($typeFieldName);
                ClassHandler::addUse($namespace, $field->type, $index);

                array_push($arguments, sprintf('%s $%s', $ClassTypeFieldName, $propertyFieldName));
            } else {
                $fieldContent .= sprintf("\$this->id = BaseUuid::uuid4();") . PHP_EOL;
            }
        }

        ClassHandler::addUse($namespace, 'Ramsey\Uuid\Uuid as BaseUuid', $index);

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
