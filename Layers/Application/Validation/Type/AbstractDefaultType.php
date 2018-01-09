<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Type;

use Symfony\Component\Form\AbstractType as SymfonyAbstractType;
use Sfynx\CoreBundle\Layers\Domain\Service\Form\Generalisation\Interfaces\FormTypeInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;

/**
 * convert a value into a json string to be stored into the persistency layer
 */
abstract class AbstractDefaultType extends SymfonyAbstractType implements FormTypeInterface
{
    /** @var string */
    protected $data_form = [];

    /**
     * {@inheritdoc}
     */
    public function initData(array $data_form): FormTypeInterface
    {
        $this->data_form = $data_form;
        return $this;
    }
}
