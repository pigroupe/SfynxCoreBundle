<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Interfaces;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ResultInterface
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Presentation
 * @subpackage Coordination\Generalisation\Interfaces
 */
interface CommandControllerInterface
{
    /**
     * Execute controller
     *
     * @return Response
     * @access public
     */
    public function coordinate();
}
