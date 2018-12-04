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

        return self::createFromNative($stdClass);
    }

    /**
     * Build and return a new instance of child VO.
     * @param array $arguments
     * @param array $excluded
     * @return ValueObjectInterface
     */
    public static function createFromNative(array $arguments, array $excluded = []): self
    {
        $oVO = new static();

        foreach ((new \ReflectionObject($oVO))->getProperties() as $oProperty) {
            if (!\in_array($oProperty->getName(), $excluded)) {
                $oProperty->setAccessible(true);
                $value = isset($arguments[$oProperty->getName()]) ? $arguments[$oProperty->getName()] : $oProperty->getValue($oVO);
                $oProperty->setValue($oVO, $value);
            }
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
    public function isEqual(ValueObjectInterface $vo): bool
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
    public function isEmpty(): bool
    {
        foreach ($this as $value) {
            if (null !== $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return \serialize($this->__toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return \implode(' ', $this->__toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function __toArray(): array;
}
