<?php
namespace Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces;

/**
 * Interface CommandQueryInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Common\Generalisation\Interfaces
 */
interface CommandQueryInterface
{
    /**
     * Returns a conversion into an array of all objects to manage.
     *
     * @param bool $skipNull Boolean that asks if you need to skip objects that are null. False by default.
     * @param bool $skipArray Boolean that asks if you need to skip array objects. False by default.
     * @return array
     */
    public function toArray(bool $skipNull = false, array $skipArray = []): array;
}
