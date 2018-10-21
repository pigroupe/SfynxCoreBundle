<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;

use stdClass;
use SplSubject;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
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
        ?array $fields = [],
        ?array $options = null
    ): void {
        $args = [];
        foreach ($fields as $field) {
            $propertyFieldName = \lcfirst($field->name);
            \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
            if ($isFieldEntity) {
                $propertyFieldName = 'id';
            }
            $args[$propertyFieldName] = sprintf("\$this->%s", $propertyFieldName);
        }

        $fieldContent = 'return ';
        $args = AbstractGenerator::transform($args, true);
        $fieldContent .= ClassHandler::recursiveArrayToString($args, '', '    ');
        $fieldContent .= ';' . PHP_EOL;

        ClassHandler::createMethods(
            $namespace,
            $class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => '__toArray',
                        'comments' => ['Return all properties of the class in array.'],
                        'visibility' => 'public',
                        'arguments' => [],
                        'returnType' => 'array',
                        'body' => [$fieldContent]
                    ]]
                ]
            ], false),
            $index
        );
    }
}
