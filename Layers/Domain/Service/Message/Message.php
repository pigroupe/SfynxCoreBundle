<?php
/**
 * This file is part of the <Core> project.
 *
 * @subpackage Core
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Layers\Domain\Service\Message;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Sfynx\ToolBundle\Twig\Extension\PiFormExtension;

/**
 * Message class
 *
 * @subpackage Core
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Message
{
    /** @var FlashBagInterface */
    protected $flashBag;

    /** @var PiFormExtension $formExtension */
    protected $formExtension;

    /**
     * Message constructor.
     * @param FlashBagInterface $flashBag
     * @param PiFormExtension $formExtension
     */
    public function __construct(
        FlashBagInterface $flashBag,
        PiFormExtension $formExtension
    ) {
        $this->flashBag = $flashBag;
        $this->formExtension = $formExtension;
    }

    /**
     * Get all error messages after binding form.
     *
     * @param Form   $form
     * @param string $type
     * @param string $delimiter
     *
     * @return array The list of all the errors
     * @access protected
     */
    public function getErrorMessages(Form $form, $type = 'array', $delimiter = "<br />")
    {
        return $this->formExtension->getFormErrors($form, $type, $delimiter);
    }

    /**
     * Set all error messages in flash.
     *
     * @param Form $form
     *
     * @return array The list of all the errors
     * @access protected
     */
    public function setFlashErrorMessages(Form $form)
    {
        return $this->flashBag->add('errorform', $this->getErrorMessages($form, 'string'));
    }

    /**
     * Set all messages in flash.
     *
     * @param string $messages
     * @param string $param
     *
     * @return array	The list of all the errors
     * @access protected
     */
    public function setFlashMessages($messages, $param = 'notice')
    {
        return $this->flashBag->add($param, $messages);
    }

    /**
     * Disable all flash message
     */
    public function clear()
    {
        $this->flashBag->clear();
    }
}
