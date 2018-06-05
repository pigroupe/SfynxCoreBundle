<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

use Exception;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Workflow\Generalisation\Interfaces\CommandWorkflowInterface;

use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Class CommandHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Handler
 */
class FormCommandHandler implements CommandHandlerInterface
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

    /**
     * @param CommandWorkflowInterface $workflowCommand
     */
    public function __construct(CommandWorkflowInterface $workflowCommand)
    {
        $this->workflowCommand = $workflowCommand;
    }

    /**
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     * @throws WorkflowException
     */
    public function process(CommandInterface $command): CommandHandlerInterface
    {
        // execute all observers in the wrokflow
        $this->workflowCommand->process($command);
        // get last version of entity and errors objects
        $this->command = $command;

        if (property_exists($this->workflowCommand->getData(), 'entity')) {
            $this->entity = end($this->workflowCommand->getData()->entity);

            if (!is_object($this->entity)) {
                throw WorkflowException::noCreatedEntity();
            }
        }
        if (property_exists($this->workflowCommand->getData(), 'url')) {
            $this->url = end($this->workflowCommand->getData()->url);
        }
        if (property_exists($this->workflowCommand->getCommand(), 'errors')) {
            $this->errors = $this->workflowCommand->getCommand()->errors;
        }

        return $this;
    }
}
