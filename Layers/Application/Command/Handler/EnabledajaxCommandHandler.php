<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;

/**
 * Class EnabledajaxCommandHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Handler
 */
class EnabledajaxCommandHandler implements CommandHandlerInterface
{
    /** @var ManagerInterface $userManager */
    protected $manager;

    /**
     * EnabledajaxCommandHandler constructor.
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
            $this->updateEntity($entity);
            $this->manager->getCommandRepository()->persist($entity, true);
        }
        return $this;
    }

    /**
     * @param $entity
     */
    protected function updateEntity(&$entity)
    {
        if (method_exists($entity, 'setArchived')) {
            $entity->setArchived(false);
        }
        if (method_exists($entity, 'setEnabled')) {
            $entity->setEnabled(true);
        }
        if (method_exists($entity, 'setArchiveAt')) {
            $entity->setArchiveAt(null);
        }
        if (method_exists($entity, 'setPosition')) {
            $entity->setPosition(1);
        }
    }
}
