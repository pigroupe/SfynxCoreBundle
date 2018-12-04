<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;

/**
 * Class PositionajaxCommandHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Handler
 */
class PositionajaxCommandHandler implements CommandHandlerInterface
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
        $command = $command->toArray();

        $id = $command['id'];
        $new_position = $command['toPosition'];
        if (!(null === $command['id'])) {
            if (($new_position == "NaN")
                || (null === $new_position)
                || empty($new_position)
            ) {
                $new_position     = 1;
            }

            $entity = $this->manager->getQueryRepository()->find($id);
            $this->updateEntity($entity, $new_position);
            $this->manager->getCommandRepository()->persist($entity, true);
        }
        return $this;
    }

    /**
     * @param $entity
     */
    protected function updateEntity(&$entity, int $new_position)
    {
        if (method_exists($entity, 'setPosition')) {
            $entity->setPosition($new_position);
        }
    }
}
