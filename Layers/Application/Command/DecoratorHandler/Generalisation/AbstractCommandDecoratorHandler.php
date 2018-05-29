<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\DecoratorHandler\Generalisation;

use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class AbstractCommandDecoratorHandler.
 *
 * @category Sfynx\CoreBundle
 * @package Layers
 * @subpackage Application\Command\Generalisation
 * @abstract
 */
abstract class AbstractCommandDecoratorHandler implements CommandHandlerInterface
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
