<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Generalisation;

use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

/**
 * Class AbstractQuery
 *
 * @category Sfynx\CoreBundle
 * @package Layers
 * @subpackage Application\Query\Generalisation
 * @abstract
 */
abstract class AbstractQuery implements QueryInterface
{
    /** @var  string */
    public $_token;

    /**
     * @var int
     * attribut used add errors from validation system
     */
    public $errors = null;

    /**
     * {@inheritdoc}
     */
    public function toArray(bool $skipNull = false, array $skipArray = []): array
    {
        $tab = get_object_vars($this);
        $tab = is_array($tab) ? $tab : [];

        if ($skipNull) {
            $tab = array_filter($tab, function ($value, $key) use($skipArray) {
                if (is_array($value)) {
                    return '' !== $value && null !== $value && !in_array($key, $skipArray);
                }
                return '' !== $value && null !== $value;
            }, ARRAY_FILTER_USE_BOTH);
        }
        return $tab;
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}
