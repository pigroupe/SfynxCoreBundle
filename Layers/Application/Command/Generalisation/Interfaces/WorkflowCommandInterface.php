<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class WorkflowCommandInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Generalisation\Interfaces
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface WorkflowCommandInterface extends SplSubject
{
    /**
     * Processes with the given Command object.
     *
     * @param CommandInterface $command
     * @return mixed
     */
    public function process(CommandInterface $command): void;

    /**
     * Returns the whole data that contains all elements to each steps of the workflow.
     *
     * @return stdClass
     */
    public function getData(): stdClass;

    /**
     * Returns the Command object given to the workflow.
     *
     * @return CommandInterface
     */
    public function getCommand(): CommandInterface;

    /**
     * @param CommandInterface $command
     * @return WorkflowCommandInterface
     */
    public function setCommand(CommandInterface $command): WorkflowCommandInterface;
}
