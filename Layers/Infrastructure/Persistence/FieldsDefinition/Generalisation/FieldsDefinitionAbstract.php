<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation;

use Sfynx\CoreBundle\Layers\Infrastructure\Exception\InvalidArgumentException;

/**
 * Abstract Class FieldsDefinitionAbstract
 *
 * @category Core
 * @package Infrastructure
 * @subpackage Persistence\FieldsDefinition
 */
abstract class FieldsDefinitionAbstract
{
    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $fields;

    /**
     * @var string[] Associative array where keys are parameters names from the request and values are db fields names.
     */
    protected $flipFields = null;

    /**
     * Returns the name of the database field according to the name of the request parameter.
     *
     * @param string $field
     * @return string
     * @throws InvalidArgumentException
     */
    public function getField(string $field): string
    {
        if (isset($this->fields[$field])) {
            return $this->fields[$field];
        }

        throw InvalidArgumentException::invalidField($field, array_keys($this->fields));
    }

    /**
     * Returns the name of the database field according to the name of the request parameter.
     *
     * @param string $field
     * @return string
     * @throws InvalidArgumentException
     */
    public function getFlipField(string $field): string
    {
        if(is_null($this->flipFields)) {
            $this->flip();
        }
        if (isset($this->flipFields[$field])) {
            return $this->flipFields[$field];
        }

        throw InvalidArgumentException::invalidField($field, array_keys($this->flipFields));
    }

    /**
     * @return void
     */
    public function flip(): void
    {
        $this->flipFields = array_flip($this->fields);
    }
}
