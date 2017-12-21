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
     * @return string
     */
    public function __toString(): string;

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
}
