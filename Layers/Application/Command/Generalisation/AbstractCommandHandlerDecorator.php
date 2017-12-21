<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Generalisation;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class AbstractCommandHandlerDecorator.
 *
 * @category Sfynx\CoreBundle
 * @package Layers
 * @subpackage Application\Command\Generalisation
 * @abstract
 */
abstract class AbstractCommandHandlerDecorator implements CommandHandlerInterface
{
    /**
     * @var CommandHandlerInterface
     */
    protected $commandHandler;

    /**
     * @param CommandHandlerInterface $commandHandler
     */
    public function __construct(CommandHandlerInterface $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }

    /**
     * @param CommandInterface $command
     * @return mixed
     */
    abstract public function process(CommandInterface $command): CommandHandlerInterface;
}
