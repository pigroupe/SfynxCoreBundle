<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Sfynx\AuthBundle\Domain\Entity\Role;
use Sfynx\TriggerBundle\EventListener\abstractTriggerListener;

/**
 * abstract listener manager.
 * This event is called after an entity is constructed by the EntityManager.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage EventListener
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2011-01-30
 */
abstract class abstractListener  extends abstractTriggerListener
{
    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        // Sets parameter template values.
        $this->setParams();
    }

    /**
     * Sets parameter values.
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setParams()
    {
    	if ($this->container->hasParameter('sfynx.core.permission.restriction_by_roles')) {
            $this->is_permission_restriction_by_roles = $this->container->getParameter('sfynx.core.permission.restriction_by_roles');
    	} else {
            $this->is_permission_restriction_by_roles = false;
    	}
    	if ($this->container->hasParameter('sfynx.core.permission.authorization.prepersist')) {
            $this->is_permission_authorization_prepersist_authorized  = $this->container->getParameter('sfynx.core.permission.authorization.prepersist');
    	} else {
            $this->is_permission_authorization_prepersist_authorized  = false;
    	}
    	if ($this->container->hasParameter('sfynx.core.permission.authorization.preupdate')) {
            $this->is_permission_authorization_preupdate_authorized   = $this->container->getParameter('sfynx.core.permission.authorization.preupdate');
    	} else {
            $this->is_permission_authorization_preupdate_authorized   = false;
    	}
    	if ($this->container->hasParameter('sfynx.core.permission.authorization.preremove')) {
            $this->is_permission_authorization_preremove_authorized   = $this->container->getParameter('sfynx.core.permission.authorization.preremove');
    	} else {
            $this->is_permission_authorization_preremove_authorized   = false;
    	}
    	if ($this->container->hasParameter('sfynx.core.permission.prohibition.preupdate')) {
            $this->is_permission_prohibition_preupdate_authorized     = $this->container->getParameter('sfynx.core.permission.prohibition.preupdate');
    	} else {
            $this->is_permission_prohibition_preupdate_authorized     = false;
    	}
    	if ($this->container->hasParameter('sfynx.core.permission.prohibition.preremove')) {
            $this->is_permission_prohibition_preremove_authorized     = $this->container->getParameter('sfynx.core.permission.prohibition.preremove');
    	} else {
            $this->is_permission_prohibition_preremove_authorized     = false;
    	}
    }

    /**
     * prePersist default.
     * BE CAREFUL !!! this method has to be used in the last of your LifecycleEvent management.
     *
     * @param LifecycleEventArgs $eventArgs
     * @param boolean            $isAnonymousToken        true to activate the anonymous token control.
     * @param boolean            $isUsernamePasswordToken true to activate the user token control.
     * @param boolean            $isAllPermissions        true to enable full permission regardless of the user.
     *
     * @return boolean
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _prePersist(LifecycleEventArgs $eventArgs, $isAnonymousToken = true, $isUsernamePasswordToken = true, $isAllPermissions = false)
    {
        $entity         = $eventArgs->getEntity();
        $entityManager  = $eventArgs->getEntityManager();
        $entity_name    = get_class($entity);
        //update created_at field when method setCreatedAt exists in entity object
        if (method_exists($entity, 'setCreatedAt')) {
            // we modify the Updated_at value
            if(!$entity->getCreatedAt()) {
                $entity->setCreatedAt(new \DateTime());
            }
        }
        //update updated_at field when method setUpdatedAt exists in entity object
        if (method_exists($entity, 'setUpdatedAt')) {
            // we modify the Created_at value
            if(!$entity->getUpdatedAt()) {
                $entity->setUpdatedAt(new \DateTime());
            }
        }
        //update heritage field when method setHeritage exists in entity object
        if (method_exists($entity, 'setHeritage')
                && !($entity instanceof Role)
        ) {
            // we modify the heritage value
            if ($isUsernamePasswordToken && $this->tokenStorage->isUsernamePasswordToken()) {
                $entity->setHeritage($this->container
                    ->get('sfynx.auth.role.factory')
                    ->getBestRoles($this->tokenStorage->getUserRoles()));
            } else {
                // we set all right of all user to see the row
                $entity->setHeritage(array('ROLE_USER'));
            }
        }
        // we give the right of persist if the entity is in the AUTHORIZATION_PREPERSIST container
        if ($this->is_permission_authorization_prepersist_authorized
            && $this->container->get('request_stack')->getCurrentRequest()
            && isset($_SERVER['REQUEST_URI'])
            && !empty($_SERVER['REQUEST_URI'])
        ) {
            if (isset($GLOBALS['ENTITIES']['AUTHORIZATION_PREPERSIST']) && isset($GLOBALS['ENTITIES']['AUTHORIZATION_PREPERSIST'][$entity_name])) {
                if (is_array($GLOBALS['ENTITIES']['AUTHORIZATION_PREPERSIST'][$entity_name])) {
                    $route = $this->container->get('request_stack')->getCurrentRequest()->get('_route');
                    if ((empty($route) || ($route == "_internal"))) {
                        $route = $this->container->get('sfynx.tool.route.factory')->getMatchParamOfRoute('_route', $this->container->get('request_stack')->getCurrentRequest()->getLocale());
                    }
                    if (in_array($route, $GLOBALS['ENTITIES']['AUTHORIZATION_PREPERSIST'][$entity_name])) {
                        $entityManager->initializeObject($entity);

                        return true;
                    }
                } elseif ($GLOBALS['ENTITIES']['AUTHORIZATION_PREPERSIST'][$entity_name] == true) {
                    $entityManager->initializeObject($entity);

                    return true;
                }
            }
        } else {
            $entityManager->initializeObject($entity);

            return true;
        }
        // If AnonymousToken user,
        if ($isAnonymousToken && $this->tokenStorage->isAnonymousToken()) {
            // we schedules the orphaned entity for removal
            $entityManager->getUnitOfWork()->scheduleOrphanRemoval($entity);
            // we throw the message.
            $this->setFlash('pi.session.flash.right.anonymous');

            return false;
        }
        // If  autentication user
        if ($isUsernamePasswordToken && $this->tokenStorage->isUsernamePasswordToken()) {
            // if user have the create right
            if ( in_array('CREATE', $this->tokenStorage->getUserPermissions())
                || in_array('ROLE_SUPER_ADMIN', $this->tokenStorage->getUserRoles())
                || $isAllPermissions
            ) {
                $entityManager->initializeObject($entity);
                // we throw the message.
                if ($entity instanceof \Sfynx\CmfBundle\Layers\Domain\Entity\HistoricalStatus) {
                } else {
                    $this->setFlash('pi.session.flash.right.create');
                }

                return true;
            } else {
                // we schedules the orphaned entity for removal
                $entityManager->getUnitOfWork()->scheduleOrphanRemoval($entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.uncreate');

                return false;
            }
        }
    }

    /**
     * preUpdate default.
     * BE CAREFUL !!! this method has to be used in the last of your LifecycleEvent management.
     *
     * @param LifecycleEventArgs $eventArgs
     * @param boolean            $isAnonymousToken        true to activate the anonymous token control.
     * @param boolean            $isUsernamePasswordToken true to activate the user token control.
     * @param boolean            $isAllPermissions        true to enable full permission regardless of the user.
     *
     * @return boolean
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _preUpdate(LifecycleEventArgs $eventArgs, $isAnonymousToken = true, $isUsernamePasswordToken = true, $isAllPermissions = false)
    {
        $entity         = $eventArgs->getEntity();
        $entityManager  = $eventArgs->getEntityManager();
        $entity_name    = get_class($entity);
        // we given't the right of remove if the entity is in the PROHIBITION_PREUPDATE container
        if ($this->is_permission_prohibition_preupdate_authorized
            && isset($GLOBALS['ENTITIES']['PROHIBITION_PREUPDATE'])
            && isset($GLOBALS['ENTITIES']['PROHIBITION_PREUPDATE'][$entity_name])
        ) {
            if (is_array($GLOBALS['ENTITIES']['PROHIBITION_PREUPDATE'][$entity_name])) {
                $id_entity = $entity->getId();
                if (in_array($id_entity, $GLOBALS['ENTITIES']['PROHIBITION_PREUPDATE'][$entity_name])) {
                    // just for register in data the change do in this class listener :
                    $class = $entityManager->getClassMetadata(get_class($entity));
                    $entityManager->getUnitOfWork()->computeChangeSet($class, $entity);
                    // we throw the message.
                    $this->setFlash('pi.session.flash.right.anonymous');

                    return false;
                }
            } elseif ($GLOBALS['ENTITIES']['PROHIBITION_PREUPDATE'][$entity_name] == true) {
                // just for register in data the change do in this class listener :
                $class = $entityManager->getClassMetadata(get_class($entity));
                $entityManager->getUnitOfWork()->computeChangeSet($class, $entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.anonymous');

                return false;
            }
        }
        if (method_exists($entity, 'setUpdatedAt')) {
            // we modify the Update_at value
            $entity->setUpdatedAt(new \DateTime());
        }
        if ($this->is_permission_authorization_preupdate_authorized
            && $this->container->get('request_stack')->getCurrentRequest()
            && isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])
        ) {
            // we give the right of update if the entity is in the AUTHORIZATION_PREPERSIST container
            if (isset($GLOBALS['ENTITIES']['AUTHORIZATION_PREUPDATE']) && isset($GLOBALS['ENTITIES']['AUTHORIZATION_PREUPDATE'][$entity_name])) {
                if (is_array($GLOBALS['ENTITIES']['AUTHORIZATION_PREUPDATE'][$entity_name])) {
                    $route = $this->container->get('request_stack')->getCurrentRequest()->get('_route');
                    if ((empty($route) || ($route == "_internal"))) {
                        $route = $this->container->get('sfynx.tool.route.factory')
                            ->getMatchParamOfRoute('_route', $this->container->get('request_stack')->getCurrentRequest()->getLocale());
                    }
                    if (in_array($route, $GLOBALS['ENTITIES']['AUTHORIZATION_PREUPDATE'][$entity_name])) {
                        $class = $entityManager->getClassMetadata(get_class($entity));
                        $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($class, $entity);

                        return true;
                    }
                } elseif ($GLOBALS['ENTITIES']['AUTHORIZATION_PREUPDATE'][$entity_name] == true) {
                    $class = $entityManager->getClassMetadata(get_class($entity));
                    $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($class, $entity);

                    return true;
                }
            }
        } else {
            $class = $entityManager->getClassMetadata(get_class($entity));
            $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($class, $entity);

            return true;
        }
        // If AnonymousToken user,
        if ($isAnonymousToken && $this->tokenStorage->isAnonymousToken()) {
            // just for register in data the change do in this class listener :
            $class = $entityManager->getClassMetadata(get_class($entity));
            $entityManager->getUnitOfWork()->computeChangeSet($class, $entity);
            // we throw the message.
            $this->setFlash('pi.session.flash.right.anonymous');

            return false;
        }
        // If  autentication user
        if ($isUsernamePasswordToken && $this->tokenStorage->isUsernamePasswordToken()) {
            if ($this->isRestrictionByRole($entity)) {
                // just for register in data the change do in this class listener :
                $class = $entityManager->getClassMetadata(get_class($entity));
                $entityManager->getUnitOfWork()->computeChangeSet($class, $entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.unupdate');

                return false;
            }
            // if user have the edit right
            if ( in_array('EDIT', $this->tokenStorage->getUserPermissions())
                || in_array('ROLE_SUPER_ADMIN', $this->tokenStorage->getUserRoles())
                || $isAllPermissions
            ) {
                // we persist the values of the entity
                $class = $entityManager->getClassMetadata(get_class($entity));
                $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($class, $entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.update');

                return true;
            } else {
                // just for register in data the change do in this class listener :
                $class = $entityManager->getClassMetadata(get_class($entity));
                $entityManager->getUnitOfWork()->computeChangeSet($class, $entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.unupdate');

                return false;
            }
        }
    }

    /**
     * PreRemove default.
     * BE CAREFUL !!! this method has to be used in the last of your LifecycleEvent management.
     *
     * @param LifecycleEventArgs $eventArgs
     * @param boolean            $isAnonymousToken        true to activate the anonymous token control.
     * @param boolean            $isUsernamePasswordToken true to activate the user token control.
     * @param boolean            $isAllPermissions        true to enable full permission regardless of the user.
     *
     * @return boolean
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _preRemove(LifecycleEventArgs $eventArgs, $isAnonymousToken = true, $isUsernamePasswordToken = true, $isAllPermissions = false)
    {
        // get the order entity
        $entity         = $eventArgs->getEntity();
        $entityManager  = $eventArgs->getEntityManager();
        $entity_name    = get_class($entity);
        // we given't the right of remove if the entity is in the PROHIBITION_PREREMOVE container
        if ($this->is_permission_prohibition_preremove_authorized
            && isset($GLOBALS['ENTITIES']['PROHIBITION_PREREMOVE'])
            && isset($GLOBALS['ENTITIES']['PROHIBITION_PREREMOVE'][$entity_name])
        ) {
            if (is_array($GLOBALS['ENTITIES']['PROHIBITION_PREREMOVE'][$entity_name])) {
                $id_entity = $entity->getId();
                if (in_array($id_entity, $GLOBALS['ENTITIES']['AUTHORIZATION_PREREMOVE'][$entity_name])) {
                    // we stop the remove method.
                    $entityManager->getUnitOfWork()->detach($entity);
                    // we throw the message.
                    $this->setFlash('pi.session.flash.right.undelete');

                    return false;
                }
            } elseif ($GLOBALS['ENTITIES']['PROHIBITION_PREREMOVE'][$entity_name] == true) {
                // we stop the remove method.
                $entityManager->getUnitOfWork()->detach($entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.undelete');

                return false;
            }
        }
        if ($this->is_permission_authorization_preremove_authorized
            && $this->container->get('request_stack')->getCurrentRequest()
            && isset($_SERVER['REQUEST_URI'])
            && !empty($_SERVER['REQUEST_URI'])
        ) {
            // we give the right of remove if the entity is in the AUTHORIZATION_PREREMOVE container
            if (isset($GLOBALS['ENTITIES']['AUTHORIZATION_PREREMOVE']) && isset($GLOBALS['ENTITIES']['AUTHORIZATION_PREREMOVE'][$entity_name])) {
                if (is_array($GLOBALS['ENTITIES']['AUTHORIZATION_PREREMOVE'][$entity_name])) {
                    $route = $this->container->get('request_stack')->getCurrentRequest()->get('_route');
                    if ((empty($route) || ($route == "_internal"))) {
                        $route = $this->container->get('sfynx.tool.route.factory')->getMatchParamOfRoute('_route', $this->container->get('request_stack')->getCurrentRequest()->getLocale());
                    }
                    if (in_array($route, $GLOBALS['ENTITIES']['AUTHORIZATION_PREREMOVE'][$entity_name])) {
                        return true;
                    }
                } elseif ($GLOBALS['ENTITIES']['AUTHORIZATION_PREREMOVE'][$entity_name] == true) {
                    return true;
                }
            }
        } else {
            return true;
        }
        // If AnonymousToken user
        if ($isAnonymousToken && $this->tokenStorage->isAnonymousToken()) {
            //  we stop the remove method.
            $entityManager->getUnitOfWork()->detach($entity);
            // we throw the message.
            $this->setFlash('pi.session.flash.right.anonymous');

            return false;
        }
        // If  autentication user
        if ($isUsernamePasswordToken && $this->tokenStorage->isUsernamePasswordToken()) {
            if ($this->isRestrictionByRole($entity)) {
                //  we stop the remove method.
                $entityManager->getUnitOfWork()->detach($entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.undelete');

                return false;
            }
            // if user have the delete right
            if ( in_array('DELETE', $this->tokenStorage->getUserPermissions())
                || in_array('ROLE_SUPER_ADMIN', $this->tokenStorage->getUserRoles())
                || $isAllPermissions
            ) {
                // we throw the message.
                $this->setFlash('pi.session.flash.right.delete');

                return true;
            } else {
                //  we stop the remove method.
                $entityManager->getUnitOfWork()->detach($entity);
                // we throw the message.
                $this->setFlash('pi.session.flash.right.undelete');

                return false;
            }
        }
        // we persist the values of the entity
        //$class = $entityManager->getClassMetadata(get_class($entity));
        //$entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($class, $entity);

        /*

        just for register in data the change do in this class listener :
        $class = $entityManager->getClassMetadata(get_class($entity));
        $entityManager->getUnitOfWork()->computeChangeSet($class, $entity);

        To get a field value:
        $eventArgs->getNewValue('Fieldname');

        To know if a field has changed
        $eventArgs->hasChangedField('creditCard')

        */
    }

    /**
     * Return true if the restriction on the entity is activated.
     *
     * @param object $entity
     *
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isRestrictionByRole($entity)
    {
        $right       = true;
        $entity_name = get_class($entity);

        if (
            $this->is_permission_restriction_by_roles
            && !($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            && isset($GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'])
            && isset($GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'][$entity_name])
        ) {
               if (is_array($GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'][$entity_name])){
                   $route = $this->container->get('request_stack')->getCurrentRequest()->get('_route');
                   if ((empty($route) || ($route == "_internal"))) {
                       $route = $this->container->get('sfynx.tool.route.factory')->getMatchParamOfRoute('_route', $this->container->get('request_stack')->getCurrentRequest()->getLocale());
                   }
                   if (!in_array($route, $GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'][$entity_name])) {
                       return false;
                   }
               }
               // Gets all user roles.
               $user_roles            = $this->container->get('sfynx.auth.role.factory')->getAllUserRoles();
               // Gets the best role authorized to access to the entity.
               $authorized_page_roles = $this->container->get('sfynx.auth.role.factory')->getBestRoles($entity->getHeritage());
               $right = false;
               if ((null === $authorized_page_roles) || empty($authorized_page_roles)) {
                   $right = true;
               } else {
                   foreach ($authorized_page_roles as $key=>$role_page) {
                       if (in_array($role_page, $user_roles)) {
                           $right = true;
                       }
                   }
               }
        }

        return !$right;
    }

    /**
     * Sets the repository service.
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    protected function setRepository()
    {
        $this->repository = $this->container->get('pi_app_admin.repository');
    }

    /**
     * Gets the repository service of the entity given in param.
     *
     * @param string $nameEntity
     *
     * @return ObjectRepository
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    protected function getRepository($nameEntity = '')
    {
        if (empty($this->repository)) {
            $this->setRepository();
        }
        if (!empty($nameEntity)) {
            return $this->repository->getRepository($nameEntity);
        }
        throw new \Doctrine\ORM\EntityNotFoundException();
     }
}
