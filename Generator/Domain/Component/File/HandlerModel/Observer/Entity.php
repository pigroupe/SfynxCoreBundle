<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Observer;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Generalisation\AbstractHandlerModel;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class Entity
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\HandlerModel\Observer
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Entity extends AbstractHandlerModel
{
    /** @var array */
    protected static $defaultMethod = [MethodModel\Getter::class];
    /** @var array */
    protected static $relationshipMethods = [
        'OneToOne' => [MethodModel\Getter::class],
        'ManyToOne' => [MethodModel\Getter::class],
        'OneToMany' => [MethodModel\Getter::class],
        'ManyToMany' => [MethodModel\Getter::class],
    ];
    /** @var array */
    protected static $performMethods = [
        MethodModel\Construct::class,
        MethodModel\CreateFromNative::class,
        MethodModel\ToString::class,
        MethodModel\ToArray::class,
        MethodModel\Serialize::class,
        MethodModel\Unserialize::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        $this->createBody($subject);

        return true;
    }

    /**
     * @param SplSubject $subject
     * @return string
     */
    protected function createBody(SplSubject $subject)
    {
        $fieldAll = [];
        $fieldsEntityOption = '';
        $templater = $subject->event->template;

        if ($templater->has('targetOptions') && !empty($templater->getTargetOptions())) {
            if (!empty($templater->getTargetOptions()['mapping'])) {
                $fieldsEntityOption = $templater->getTargetOptions()['mapping'];
            }
        }

        foreach ($templater->getTargetCommandFields() as $field) {
            $propertyFieldName = \lcfirst($field->name);
            $typeFieldName =  ClassHandler::getType($field->type, $field, true);

            if (($field->type == ClassHandler::TYPE_ENTITY
                || $field->type == ClassHandler::TYPE_ARRAY)
                && \property_exists($field, 'mapping')
                && \property_exists($field->mapping, 'relationship')
                && (empty($fieldsEntityOption) || $field->entityName == $fieldsEntityOption)
            ) {
                $fieldAll[] = $field;
                $this->createRelationshipMethods($subject, $field, $typeFieldName, $propertyFieldName);
            } elseif(empty($fieldsEntityOption) || $field->entityName == $fieldsEntityOption) {
                $fieldAll[] = $field;
                $this->createDefaultMethods($subject, $field, $typeFieldName, $propertyFieldName);
            }
        }

        $performMethod = self::$performMethods;
        if (!empty($this->parameters['performMethods'])) {
            $performMethods = $this->parameters['performMethods'];
        }
        $performMethods = array_reverse($performMethods);
        foreach ($performMethods as $method) {
            $method::handle(
                $subject->event->namespace,
                $subject->event->class,
                $subject->event->index,
                $fieldAll
            );
        }
    }

    /**
     * Create getter or/and setter method of a field
     *
     * @param SplSubject $subject
     * @param stdClass $field
     * @param string $typeFieldName
     * @param string $propertyFieldName
     * @return void
     */
    protected function createDefaultMethods(SplSubject $subject, stdClass $field, string $typeFieldName, string $propertyFieldName): void
    {
        foreach (self::$defaultMethod as $method) {
            $method::handle(
                $subject->event->namespace,
                $subject->event->class,
                $subject->event->index,
                $field,
                $typeFieldName,
                $propertyFieldName
            );
        }
    }

    /**
     * Create getter or/and setter method of a field
     *
     * @param SplSubject $subject
     * @param stdClass $field
     * @param string $typeFieldName
     * @param string $propertyFieldName
     * @return void
     */
    protected function createRelationshipMethods(SplSubject $subject, stdClass $field, string $typeFieldName, string $propertyFieldName): void
    {
        $relationship = $field->mapping->relationship;
        $methods = self::$relationshipMethods[$relationship];

        if (\property_exists($field, 'mapping')
            && \property_exists($field->mapping, 'setter')
            && true === $field->mapping->setter
        ) {
            \array_push($methods, MethodModel\Setter::class);
        }
        if (\property_exists($field, 'mapping')
            && \property_exists($field->mapping, 'getter')
            && false === $field->mapping->getter
            && isset($methods[MethodModel\Getter::class])
        ) {
            unset($methods[MethodModel\Getter::class]);
        }

        foreach ($methods as $method) {
            $method::handle(
                $subject->event->namespace,
                $subject->event->class,
                $subject->event->index,
                $field,
                $typeFieldName,
                $propertyFieldName
            );
        }
    }
}
