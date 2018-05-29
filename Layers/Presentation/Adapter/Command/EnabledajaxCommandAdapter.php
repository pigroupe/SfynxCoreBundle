<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Command;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\CommandAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\EnabledajaxCommand;
use Sfynx\CoreBundle\Layers\Application\Command\GridCommand;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class EnabledajaxCommandAdapter.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Command
 */
class EnabledajaxCommandAdapter implements CommandAdapterInterface
{
    /**
     * @param CommandRequestInterface $request
     * @return NewCommand
     */
    public function createCommandFromRequest(CommandRequestInterface $request): CommandInterface
    {
        $parameters = $request->getRequestParameters();

        foreach ($parameters as $parameters) {
            $commands[] = new EnabledajaxCommand($parameters);
        }
        return new GridCommand($commands);
    }
}
