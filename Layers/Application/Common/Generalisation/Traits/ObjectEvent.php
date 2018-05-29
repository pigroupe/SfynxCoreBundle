<?php
namespace Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Traits;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ObjectEvent.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Validation\Generalisation\Traits
 */
class ObjectEvent extends Event
{
    /**
     * @var FormEvent $eventForm
     */
    private $eventForm;

     /**
     * @var ContainerInterface
     */
    private $container;


    public function __construct(FormEvent $event, ContainerInterface $container)
    {
        $this->eventForm  = $event;
        $this->container  = $container;
    }

    /**
     * @return container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return eventArgs
     */
    public function getEventForm()
    {
        return $this->eventForm;
    }


   /**
     * Return the token object.
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     * @access protected
     */
    public function getToken()
    {
        return  $this->container->get('security.context')->getToken();
    }

    /**
     * Return the connected user name.
     *
     * @return string User name
     * @access protected
     */
    public function getUserName()
    {
        return $this->getToken()->getUser()->getUsername();
    }

    /**
     * Return the user permissions.
     *
     * @return array User permissions
     * @access protected
     */
    public function getUserPermissions()
    {
        return $this->getToken()->getUser()->getPermissions();
    }

    /**
     * Return the user roles.
     *
     * @return array User roles
     * @access protected
     */
    public function getUserRoles()
    {
        return $this->getToken()->getUser()->getRoles();
    }

    /**
     * Return if yes or no the user is anonymous token.
     *
     * @return boolean
     * @access protected
     */
    public function isAnonymousToken()
    {
        if (($this->getToken() instanceof AnonymousToken) || ($this->getToken() === null)) {
            return true;
        }

        return false;
    }

    /**
     * Return if yes or no the user is UsernamePassword token.
     *
     * @return boolean
     * @access protected
     */
    public function isUsernamePasswordToken()
    {
        if ($this->getToken() instanceof UsernamePasswordToken) {
            return true;
        }

        return false;
    }
}
