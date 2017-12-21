<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Command;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\CommandAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class CommandAdapter.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Command
 */
class CommandAdapter implements CommandAdapterInterface
{
    /** @var  CommandInterface */
    protected $command;

    public function __construct(CommandInterface $command)
    {
        $this->commmand = $command;
    }

    /**
     * @param CommandRequestInterface $request
     * @return CommandInterface
     */
    public function createCommandFromRequest(CommandRequestInterface $request): CommandInterface
    {
        $this->parameters = $request->getRequestParameters();

        $tab = get_object_vars($this->commmand);
        $tab = is_array($tab) ? $tab : [];
        foreach ($tab as $property => $value) {
            $this->commmand->{$property} = isset($this->parameters[$property]) ? $this->parameters[$property] : $this->commmand->{$property};
        }

        return $this->commmand;
    }
}
