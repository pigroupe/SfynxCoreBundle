<?php
namespace Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Traits;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TraitEventsForm.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Validation\Generalisation\Traits
 */
trait TraitEventsForm
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @return FormBuilderInterface
     */
    public function builderEvents(FormBuilderInterface $builder)
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                array($this, '_onPreSetData')
            )
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                array($this, '_onPostSetData')
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                array($this, '_onPreSubmit')
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                array($this, '_onSubmit')
            )
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                array($this, '_onPostSubmit')
            )
        ;

        return $builder
    }


    final protected function _onPreSetData(FormEvent $event)
    {
        if (method_exists('onPreSetData')) {
            $this->onPreSetData($event);
        } else {
            $event_object = new ObjectEvent(
                $event,
                $this->container
            );
            $this->container->get('event_dispatcher')->dispatch('sfynx.core.form.events.presetdata.' . $this->getName(), $event_object);
            $event = $event_object->getEventForm();
        }
    }

    final protected function _onPostSetData(FormEvent $event)
    {
        if (method_exists('onPreSetData')) {
            $this->onPreSetData($event);
        }
    }

    final protected function _onPreSubmit(FormEvent $event)
    {
        if (method_exists('onPreSubmit')) {
            $this->onPreSubmit($event);
        }
    }

    final protected function _onSubmit(FormEvent $event)
    {
        if (method_exists('onSubmit')) {
            $this->onSubmit($event);
        }
    }

    final protected function _onPostSubmit(FormEvent $event)
    {
        if (method_exists('onPostSubmit')) {
            $this->onPostSubmit($event);
        }
    }
}
