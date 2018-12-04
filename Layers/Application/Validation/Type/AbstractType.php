<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Type;

use Symfony\Component\Form\AbstractType as SymfonyAbstractType;
use Sfynx\CoreBundle\Layers\Application\Validation\Type\Generalisation\Interfaces\FormTypeInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;

/**
 * convert a value into a json string to be stored into the persistency layer
 */
abstract class AbstractType extends SymfonyAbstractType implements FormTypeInterface
{
    /** @var ManagerInterface */
    protected $manager;
    /** @var  string */
    protected $dataClass;
    /** @var array */
    protected $dataForm = [];

    /**
     * AbstractType constructor.
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->dataClass = $manager->getClass();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $dataForm): FormTypeInterface
    {
        $this->dataForm = $dataForm;
        return $this;
    }
}
