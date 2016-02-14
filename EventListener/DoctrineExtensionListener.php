<?php
/**
 * This file is part of the <Core> project.
 *
 * @subpackage   Core
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Translation listener manager.
 *
 * @subpackage   Core
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DoctrineExtensionListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function onLateKernelRequest(GetResponseEvent $event)
    {
        $locale = $event->getRequest()->getLocale();
        //$locale = $event->getRequest()->get('lang', $locale);
                
        $translatableListener = $this->container->get('gedmo.listener.translatable');
        $translatableListener->setTranslatableLocale($locale);
        
        $is_defaultlocale_setting    = $this->container->getParameter('sfynx.core.translation.defaultlocale');
        $defaultlocale               = $this->container->getParameter('locale');
        if (!$is_defaultlocale_setting) {
            // !!! This force the system to create all translation fields in the default locale language.
            $translatableListener->setDefaultLocale("important");
        } else {
            $translatableListener->setDefaultLocale($defaultlocale);
        }
        
        //$translatableListener->setTranslationFallback(true);
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $loggable = $this->container->get('gedmo.listener.loggable');
            $loggable->setUsername($securityContext->getToken()->getUsername());
        }
    }
    
    public function changeDefaultLocale($lang)
    {
        $translatableListener = $this->container->get('gedmo.listener.translatable');
        $translatableListener->setDefaultLocale($lang);
    }    
}