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
        $fieldContent = 'return new self(';
        $count = 0;
        foreach ($fields as $field) {
            \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
            if (!$isFieldEntity) {
                $propertyFieldName = \lcfirst($field->name);

                $prefix = (0 == $count) ? '' : ',';
                $fieldContent .= $prefix . PHP_EOL . sprintf("  \$%s", $propertyFieldName, $propertyFieldName);

                $typeFieldName = ClassHandler::getType($field->type, $field, true);
                $ClassTypeFieldName = ClassHandler::getClassNameFromNamespace($typeFieldName);
                ClassHandler::addUse($namespace, $field->type, $index);

                array_push($arguments, sprintf('%s $%s', $ClassTypeFieldName, $propertyFieldName));

                $count++;
            }
        }
        $fieldContent .= PHP_EOL . ');';

        ClassHandler::addUse($namespace, 'Ramsey\Uuid\Uuid as BaseUuid', $index);

        ClassHandler::createMethods(
            $namespace,
            $class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => 'createFromNative',
                        'comments' => [sprintf('%s new instance.', $class->getName())],
                        'visibility' => 'public',
                        'arguments' => $arguments,
                        'returnType' => 'self',
                        'static' => true,
                        'body' => [$fieldContent]
                    ]]
                ]
            ], false),
            $index
        );
    }
}
