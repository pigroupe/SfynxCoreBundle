<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

use Exception;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\WorkflowCommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
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
    /** @var EntityInterface */
    public $entity;
    /** @var array */
    public $errors = [];
    /** @var string */
    public $url = null;
    /** @var WorkflowCommandInterface */
    protected $workflowCommand;

    /**
     * @param WorkflowCommandInterface $workflowCommand
     */
    public function __construct(WorkflowCommandInterface $workflowCommand)
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
        }
        if (property_exists($this->workflowCommand->getData(), 'url')) {
            $this->url = end($this->workflowCommand->getData()->url);
        }
        if (property_exists($this->workflowCommand->getCommand(), 'errors')) {
            $this->errors = $this->workflowCommand->getCommand()->errors;
        }

        if (!($this->entity instanceof EntityInterface)) {
            throw WorkflowException::noCreatedEntity();
        }
        return $this;
    }
}
