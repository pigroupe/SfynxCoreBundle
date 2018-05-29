<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces;

use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Interface CommandAdapterInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Generalisation\Interfaces
 */
interface CommandAdapterInterface
{
    /**
     * @param CommandRequestInterface $request
     * @return mixed
     */
    public function createCommandFromRequest(CommandRequestInterface $request): CommandInterface;
}