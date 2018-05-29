<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Command;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\CommandAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\PositionajaxCommand;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class PositionajaxCommandAdapter.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Command
 */
class PositionajaxCommandAdapter implements CommandAdapterInterface
{
    /**
     * @param CommandRequestInterface $request
     * @return NewCommand
     */
    public function createCommandFromRequest(CommandRequestInterface $request): CommandInterface
    {
        $parameters = $request->getRequestParameters();
        return new PositionajaxCommand($parameters);
    }
}