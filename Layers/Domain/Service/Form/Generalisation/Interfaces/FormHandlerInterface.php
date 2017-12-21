<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Form\Generalisation\Interfaces;

use Symfony\Component\Form\FormInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;

/**
 * A FormHandler is a object that is reponsable of form binding and post treatment
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Service\Form\Generalisation\Interfaces
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface FormHandlerInterface
{
    /**
     * The process function should bind the form, check if it is valid
     * and do any post treatment (persisting the entity etc.)
     * 
     * @param mixed $object
     * @param bool $forceHandleRequest
     * 
     * @return bool False to notify that postprocessing could not be executed.
     *                 This can be the case when the form is not valid, the request method
     *                 not supported etc.
     */
    public function process($object = null, bool $forceHandleRequest = false): bool;

    /**
     * Set Process Manager
     *
     * @param ManagerInterface $manager
     * @return FormHandlerInterface
     */
    public function setManager(ManagerInterface $manager);

    /**
     * Set model Data
     *
     * @param mixed $modelData The data formatted as expected for the underlying object
     * @return FormHandlerInterface
     */
    public function setObject($modelData);

    /**
     * Get model Data
     * @return mixed
     */
    public function getObject();

    /**
     * Get form errors
     * @return array
     */
    public function getErrors();

    /**
     * Returns the current form
     *
     * @return FormInterface
     */
    public function getForm();
}
