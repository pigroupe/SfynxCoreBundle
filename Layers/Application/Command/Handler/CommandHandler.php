<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

use Exception;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\DecoratorHandler\Generalisation\AbstractCommandDecoratorHandler;
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
class CommandHandler extends AbstractCommandDecoratorHandler
{
    /** @var CommandInterface */
    public $command;
    /** @var CommandWorkflowInterface */
    protected $commandWorkflow;
    /** @var null|ManagerInterface[] */
    protected $manager;

    /**
     * @param CommandWorkflowInterface $workflowCommand
     * @param string|ManagerInterface[] $manager
     * @param null|CommandHandlerInterface $commandHandler
     */
    public function __construct(CommandWorkflowInterface $workflowCommand, $manager = null, CommandHandlerInterface $commandHandler = null)
    {
        $this->commandHandler = $commandHandler;
        $this->commandWorkflow = $workflowCommand;
        $this->manager = $manager;

        if(\is_string($manager)) {
            $this->manager = [$manager];
        }
    }

    /**
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     * @throws WorkflowException
     */
    public function process(CommandInterface $command): CommandHandlerInterface
    {
        /* we dispatch the command workflow */
        $this->dispatch($command);

        // get last version of entity and errors objects
        foreach (\get_object_vars($this->commandWorkflow->getData()) as $property => $value) {
            $this->$property = end($this->commandWorkflow->getData()->$property);
            $this->setCommandHandlerProperty($property);
        }
        foreach (\get_object_vars($this->commandWorkflow->getCommand()) as $property => $value) {
            $this->$property = $this->commandWorkflow->getCommand()->$property;
            $this->setCommandHandlerProperty($property);
        }

        // execute next commandHandler
        if (null !== $this->commandHandler) {
            return $this->commandHandler->process($command);
        }

        return $this;
    }

    /**
     * execute all observers in the wrokflow with doctrine commit transactions executed in the command workflow
     * @return void
     * @throws ViolationEntityException
     */
    protected function dispatch(CommandInterface $command): void
    {
        $this->command = $command;

        if (null !== $this->manager) {
            foreach ($this->manager as $manager) {
                $manager->getCommandRepository()->getEm()->getConnection()->beginTransaction();
            }
            try {
                /* execute all observers in the wrokflow */
                $this->commandWorkflow->process($command);

                // Try and commit the transaction
                foreach ($this->manager as $em) {
                    $manager->getCommandRepository()->getEm()->flush();
                    $manager->getCommandRepository()->getEm()->getConnection()->commit();
                }
            } catch (ViolationEntityException $e) {
                // Rollback the failed transaction attempt
                foreach ($this->manager as $em) {
                    $manager->getCommandRepository()->getEm()->getConnection()->rollback();
                }
                throw EntityException::ViolationEntity();
            }
        } else {
            /* execute all observers in the wrokflow */
            $this->commandWorkflow->process($command);
        }
    }

    /**
     * We propage a public property to the next commandHandler
     * @param string $property
     * @return void
     */
    protected function setCommandHandlerProperty(string $property): void
    {
        if (null !== $this->commandHandler) {
            $this->commandHandler->$property = $this->$property;
        }
    }
}
