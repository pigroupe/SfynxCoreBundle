<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces;

/**
 * Interface CommandRequestInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Request\Generalisation\Interfaces
 */
interface CommandRequestInterface
{
    /**
     * @return mixed
     */
    public function getRequestParameters();
}
