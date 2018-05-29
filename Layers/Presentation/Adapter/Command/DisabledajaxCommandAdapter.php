<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Command;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\CommandAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\DisabledajaxCommand;
use Sfynx\CoreBundle\Layers\Application\Command\GridCommand;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class DisabledajaxCommandAdapter.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Command
 */
class DisabledajaxCommandAdapter implements CommandAdapterInterface
{
    /**
     * @param CommandRequestInterface $request
     * @return NewCommand
     */
    public function createCommandFromRequest(CommandRequestInterface $request): CommandInterface
    {
        $parameters = $request->getRequestParameters();

        foreach ($parameters as $parameters) {
            $commands[] = new DisabledajaxCommand($parameters);
        }
        return new GridCommand($commands);
    }
}