<?php
namespace Sfynx\CoreBundle\Layers\Application\Command;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\AbstractCommand;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Countable;

/**
 * Class GridCommand.
 *
 * @category   Core
 * @package    Application
 * @subpackage Command
 */
class GridCommand implements CommandInterface, Countable
{
    /**
     * @var CommandInterface[] List of command objects to manage.
     */
    protected $commands = [];

    /**
     * Constructs the GridCommand object based on an array of all objects to manage.
     *
     * @param CommandInterface[] $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    /**
     * Returns the whole list of objects to manage.
     *
     * @return CommandInterface[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Retrieves a single object by its index.
     *
     * @param int $key
     * @return CommandInterface|null
     */
    public function getByIndex(int $key): ?GridCommand
    {
        if(array_key_exists($key,$this->commands)) {
            return $this->commands[$key];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(bool $skipNull = false, array $skipArray = []): array
    {
        $commands = [];
        foreach ($this->commands as $command) {
            $commands[] = $command->toArray($skipNull);
        }
        return $commands;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->commands);
    }
}
