<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;

/**
 * Class DeleteajaxCommandHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Handler
 */
class DeleteajaxCommandHandler implements CommandHandlerInterface
{
    /** @var ManagerInterface */
    protected $manager;

    /**
     * DeleteajaxCommandHandler constructor.
     * @param ManagerInterface|null $manager
     */
    public function __construct(ManagerInterface $manager = null)
    {
        $this->manager = $manager;
    }

    /**
     * @param CommandInterface $command
     * @return bool
     * @throws \Exception
     */
    public function process(CommandInterface $command): CommandHandlerInterface
    {
        $commands = $command->toArray();
        foreach ($commands as $key => $com) {
            $entity = $this->manager->getQueryRepository()->find($com['id']);
            $this->manager->getCommandRepository()->remove($entity, true);
        }
        return $this;
    }
}
