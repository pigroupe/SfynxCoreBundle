<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Traits;

use stdclass;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ControllerException;

/**
 * trait class for message response.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Generalisation\Traits
 */
trait TraitMessage
{
    /**
     * @param string $message
     * @return array
     */
    public static function jsonError(string $message)
    {
        return json_encode([
            'status' => 'errors',
            'message' => $message
        ]);
    }
}
