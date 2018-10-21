<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;

use stdClass;
use SplSubject;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
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
    /**
     * Create unserialize method.
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
        $fieldContent = '$unserializedData = \unserialize($data);' . PHP_EOL;
        foreach ($fields as $field) {
            $propertyFieldName = \lcfirst($field->name);
            \str_replace('entityid', 'entityid', \strtolower($field->name), $isFieldEntity);
            if ($isFieldEntity) {
                $propertyFieldName = 'id';
            }
            $fieldContent .= sprintf("\$this->%s = \$unserializedData['%s'];", $propertyFieldName, $propertyFieldName) . PHP_EOL;
        }

        ClassHandler::createMethods(
            $namespace,
            $class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => 'unserialize',
                        'comments' => ['Set properties from serialised data.'],
                        'visibility' => 'public',
                        'arguments' => ['string $data'],
                        'returnType' => 'void',
                        'body' => [$fieldContent]
                    ]]
                ]
            ], false),
            $index
        );
    }
}
