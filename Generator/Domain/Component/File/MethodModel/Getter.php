<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;

use stdClass;
use SplSubject;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class Getter
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\MethodModel
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Getter
{
    /**
     * Create getter method of a field
     *
     * @param PhpNamespace $namespace
     * @param ClassType $class
     * @param array|null $index
     * @param stdClass $field
     * @param string $typeFieldName
     * @param string $propertyFieldName
     * @static
     * @return void
     */
    public static function handle(
        PhpNamespace $namespace,
        ClassType $class,
        ?array $index = [],
        stdClass $field,
        string $typeFieldName = '',
        string $propertyFieldName = ''
    ): void {
        $getterFieldName = 'get' . \ucfirst($field->name);
        $ClassTypeFieldName = ClassHandler::getClassNameFromNamespace($typeFieldName);

        \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
        if ($isFieldEntity) {
            $propertyFieldName = 'id';
            $ClassTypeFieldName = 'int';
            $typeFieldName = 'int';
            $getterFieldName = 'getId';
        }
        $getterFieldBody = sprintf('return $this->%s;', $propertyFieldName);

        $comment = sprintf('@var %s $%s', $ClassTypeFieldName, $propertyFieldName);
        if (empty($typeFieldName)) {
            $comment = sprintf('@var %s', $propertyFieldName);
        }
        ClassHandler::addUse($namespace, $field->type, $index);

        $class->addProperty($propertyFieldName)
            ->setVisibility('protected')
            ->addComment(sprintf('@var %s $%s', $ClassTypeFieldName, $propertyFieldName));

        ClassHandler::createMethods(
            $namespace,
            $class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => $getterFieldName,
                        'comments' => ['Return the ' . $propertyFieldName],
                        'visibility' => 'public',
                        'returnType' => $typeFieldName,
                        'body' => [$getterFieldBody]
                    ]]
                ]
            ], false),
            $index
        );
    }
}
