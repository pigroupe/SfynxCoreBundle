<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Command;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\CommandAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\DeleteajaxCommand;
use Sfynx\CoreBundle\Layers\Application\Command\GridCommand;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class DeleteajaxCommandAdapter.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Command
 */
class DeleteajaxCommandAdapter implements CommandAdapterInterface
{
    /**
     * @param CommandRequestInterface $request
     * @return NewCommand
     */
    public function createCommandFromRequest(CommandRequestInterface $request): CommandInterface
    {
        $parameters = $request->getRequestParameters();

        foreach ($parameters as $parameters) {
            $commands[] = new DeleteajaxCommand($parameters);
        }
        return new GridCommand($commands);
    }
}