<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Type\Generalisation\Interfaces;

use Symfony\Component\Form\FormTypeInterface as SymfonyFormTypeInterface;

/**
 * A FormHandler is a object that is reponsable of form binding and post treatment
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Application\Validation\Type\Generalisation\Interfaces
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface FormTypeInterface extends SymfonyFormTypeInterface
{
    /**
     * @param array $dataForm
     * @return FormTypeInterface
     */
    public function setData(array $dataForm): FormTypeInterface;
}
