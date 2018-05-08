<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\HandlerInterface;

/**
 * Interface CommandHandlerInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Generalisation\Interfaces
 */
interface CommandHandlerInterface extends HandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function process(CommandInterface $command): CommandHandlerInterface;
}
