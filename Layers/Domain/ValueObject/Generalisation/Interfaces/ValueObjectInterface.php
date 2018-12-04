<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Interfaces;

use stdClass;

/**
 * Interface ValueObjectInterface
 *
 * @category Sfynx\DddBundle\Layer
 * @package Domain
 * @subpackage ValueObject\Generalisation\Interfaces
 */
interface ValueObjectInterface
{
    /**
     * Build and return a new instance of child VO.
     *
     * @param  stdClass $stdClass
     * @return ValueObjectInterface
     */
    public static function fromNative(): ValueObjectInterface;

    /**
     * Check the equality of the current Vo with another Vo passed by argument.
     * @param ValueObjectInterface $vo
     * @return bool
     */
    public function isEqual(ValueObjectInterface $vo): bool;

    /**
     * Check if the current Vo is empty, meaning all of its properties are NULL.
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Return the serialized properties of the class.
     * @return string
     */
    public function serialize(): string;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * Return all properties of the class in array.
     * @return array
     */
    public function __toArray(): array;
}
