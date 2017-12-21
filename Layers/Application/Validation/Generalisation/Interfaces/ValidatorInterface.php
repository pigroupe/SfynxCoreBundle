<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\Interfaces;

/**
 * Interface ValidatorInterface
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Generalisation\Interfaces
 */
interface ValidatorInterface
{
    public function validate($data, array $constraints);
}
