<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Core
 * @package    Form
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-03-09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Form\Handler;

/**
 * A FormHandler is a object that is reponsable of form binding and post treatment
 *
 * @category   Core
 * @package    Form
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface FormHandlerInterface
{
    /**
     * The process function should bind the form, check if it is valid
     * and do any post treatment (persisting the entity etc.)
     * 
     * @param object $object
     * 
     * @return Boolean False to notify that postprocessing could not be executed.
     *                 This can be the case when the form is not valid, the request method
     *                 not supported etc.
     */
    public function process($object = null);
}
