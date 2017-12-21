<?php
namespace Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\HandlerInterface;

/**
 * Class WorkflowHandlerInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Common\Generalisation\Interfaces
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface WorkflowHandlerInterface extends SplSubject
{
    /**
     * Processes with the given Command object.
     *
     * @param HandlerInterface $command
     * @return mixed
     */
    public function process(HandlerInterface $handler): void;

    /**
     * Returns the whole data that contains all elements to each steps of the workflow.
     *
     * @return stdClass
     */
    public function getData(): stdClass;

    /**
     * Returns the Command object given to the workflow.
     *
     * @return HandlerInterface
     */
    public function getHandler(): HandlerInterface;

    /**
     * @param CommandHandlerInterface $command
     * @return WorkflowHandlerInterface
     */
    public function setHandler(HandlerInterface $handler): WorkflowHandlerInterface;
}
