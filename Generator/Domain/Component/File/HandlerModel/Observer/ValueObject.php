<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Observer;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Generator\Application\Config\Exception\ConfigException;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class ValueObject
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\HandlerModel\Observer
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ValueObject extends Entity
{
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
        $this->fieldAll = [];
        $fieldsEntityOption = '';
        $templater = $subject->event->template;

        if ($templater->has('targetOptions') && !empty($templater->getTargetOptions())) {
            if (!empty($templater->getTargetOptions()['mapping'])) {
                $fieldsEntityOption = $templater->getTargetOptions()['mapping'];
            }
        }

        if (empty($this->parameters['mapping'])
            || empty($templater->getTargetValueObjects()[$this->parameters['mapping']])
        ) {
            throw new ConfigException('ValueObject name do not exist in the mapping configuration');
        }

        $vo = $templater->getTargetValueObjects()[$this->parameters['mapping']]['x-fields'];
        $vo = AbstractGenerator::transform($vo, false);

        foreach ($vo as $fieldName => $field) {
            $field->name = !isset($field->name) ? $fieldName : $field->name;
            $propertyFieldName = \lcfirst($field->name);
            $typeFieldName =  ClassHandler::getType($field->type, $field, true);

            if (($field->type == ClassHandler::TYPE_ENTITY
                    || $field->type == ClassHandler::TYPE_ARRAY)
                && \property_exists($field, 'mapping')
                && \property_exists($field->mapping, 'relationship')
                && (empty($fieldsEntityOption) || $field->entityName == $fieldsEntityOption)
            ) {
                $this->createRelationshipMethods($subject, $field, $typeFieldName, $propertyFieldName);
            } elseif(empty($fieldsEntityOption) || $field->entityName == $fieldsEntityOption) {
                $this->createDefaultMethods($subject, $field, $typeFieldName, $propertyFieldName);
            }
        }

        $performMethod = Entity::$performMethods;
        if (!empty($this->parameters['performMethods'])) {
            $performMethods = $this->parameters['performMethods'];
        }

        $performMethods = \array_reverse($performMethods);
        foreach ($performMethods as $method) {
            $method::handle(
                $subject->event->namespace,
                $subject->event->class,
                $subject->event->index,
                $this->fieldAll,
                \array_merge(['toEntity' => true, 'templater' => $templater], $this->parameters),
                self::ENTITY_TYPE_DEFAULT
            );
        }
    }
}
