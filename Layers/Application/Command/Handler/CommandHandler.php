<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

use Exception;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Workflow\Generalisation\Interfaces\CommandWorkflowInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\EntityException;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\Entity\ViolationEntityException;

/**
 * Class CommandHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Handler
 */
class CommandHandler implements CommandHandlerInterface
{
    /** @var CommandInterface */
    public $command;
    /** @var object */
    public $entity;
    /** @var array */
    public $errors = [];
    /** @var string */
    public $url = null;
    /** @var CommandWorkflowInterface */
    protected $workflowCommand;
    /** @var ManagerInterface */
    protected $manager;

    /**
     * @param CommandWorkflowInterface $workflowCommand
     * @param ManagerInterface $manager
     */
    public function __construct(CommandWorkflowInterface $workflowCommand, ManagerInterface $manager)
    {
        $this->workflowCommand = $workflowCommand;
        $this->manager = $manager;
    }

    /**
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     * @throws WorkflowException
     */
    public function process(CommandInterface $command): CommandHandlerInterface
    {

        $this->manager->getCommandRepository()->getEm()->getConnection()->beginTransaction();
        try {
            /* execute all observers in the wrokflow */
            $this->workflowCommand->process($command);

            /* execute transaction*/
            $this->manager->getCommandRepository()->getEm()->flush();
            $this->manager->getCommandRepository()->getEm()->getConnection()->commit();
        } catch (ViolationEntityException $e) {
            $this->manager->getCommandRepository()->getEm()->getConnection()->rollBack();

            throw EntityException::ViolationEntity();
        }
        // get last version of entity and errors objects
        $this->command = $command;

        if (\property_exists($this->workflowCommand->getData(), 'entity')) {
            $this->entity = end($this->workflowCommand->getData()->entity);

            if (!\is_object($this->entity)) {
                throw WorkflowException::noCreatedEntity();
            }
        }
        if (\property_exists($this->workflowCommand->getData(), 'url')) {
            $this->url = end($this->workflowCommand->getData()->url);
        }
        if (\property_exists($this->workflowCommand->getCommand(), 'errors')) {
            $this->errors = $this->workflowCommand->getCommand()->errors;
        }

        return $this;
    }
}
