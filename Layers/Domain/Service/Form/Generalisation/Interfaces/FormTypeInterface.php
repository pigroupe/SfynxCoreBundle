<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Form\Generalisation\Interfaces;

use Symfony\Component\Form\FormTypeInterface as SymfonyFormTypeInterface;

/**
 * A FormHandler is a object that is reponsable of form binding and post treatment
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Service\Form\Type\Generalisation\Interfaces
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface FormTypeInterface extends SymfonyFormTypeInterface
{
    /**
     * @param array $data_form
     * @return FormTypeInterface
     */
    public function initData(array $data_form): FormTypeInterface;
}
