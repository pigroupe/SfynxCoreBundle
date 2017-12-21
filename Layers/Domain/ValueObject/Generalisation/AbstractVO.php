<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation;

use Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Interfaces\ValueObjectInterface;

/**
 * Abstract Class AbstractVO
 *
 * @category Sfynx\DddBundle\Layer
 * @package Domain
 * @subpackage ValueObject\Generalisation
 * @abstract
 */
abstract class AbstractVO implements ValueObjectInterface
{
    /**
     * {@inheritdoc}
     */
    public static function fromNative(): ValueObjectInterface
    {
        $stdClass = \func_get_arg(0);

        return self::build($stdClass);
    }

    /**
     * Build and return a new instance of child VO.
     * @param \stdClass $arguments
     * @return ValueObjectInterface
     */
    final public static function build(\stdClass $arguments): ValueObjectInterface
    {
        $oVO = new static();
        foreach ($arguments as $propertyName => $value) {
            $oVO->{$propertyName} = $value;
        }
        $oVO->transform();

        return $oVO;
    }

    /**
     * @return ValueObjectInterface
     */
    public function transform(): ValueObjectInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function isEqual(ValueObjectInterface $vo): bool
    {
        //Specific use of simple equality operator to compare object properties.
        //With the use of strict equality operator, objects references will be different: it will always return false.
        /** @noinspection PhpNonStrictObjectEqualityInspection */
        /** @noinspection TypeUnsafeComparisonInspection */
        return $this == $vo;
    }

    /**
     * {@inheritdoc}
     */
    final public function isEmpty(): bool
    {
        foreach ($this as $value) {
            if (null !== $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * Return a serialized string value of the Vo .
     * @return string
     */
    public function __toString(): string
    {
        return serialize($this);
    }
}
