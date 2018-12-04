<?php
namespace Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Traits;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TraitCreateInstanceFromNative.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Validation\Generalisation\Traits
 */
trait TraitCreateInstanceFromNative
{
    /**
     * UpdateCommand new instance.
     * @param array $arguments
     * @return self
     */
    public function __construct(array $arguments = [])
    {
        foreach ($arguments as $attr => $value) {
            $this->$attr = $value;
        }
    }


    /**
     * UpdateCommand new instance.
     * @param array $arguments
     * @return self
     */
    public static function createFromNative(array $arguments): self
    {
        return new self($arguments);
    }
}
