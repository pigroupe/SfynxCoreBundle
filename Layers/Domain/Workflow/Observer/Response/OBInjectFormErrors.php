<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Response;

use Exception;
use Symfony\Component\Form\FormError;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractInjectFormErrors;

/**
 * Class OBInjectFormErrors
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Response
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBInjectFormErrors extends AbstractInjectFormErrors
{
    /**
     * The process function add errors given from  CommandHandlerInterface in the last form view
     *
     * @return bool False to notify that postprocessing could not be executed.
     */
    protected function process(): bool
    {
        $return = true;
        foreach ($this->wfHandler->errors as $key => $error) {
            try {
                if ($key == 'plainPassword') {
                    $this->wfLastData->form->get($key)->get('first')->addError(new FormError($this->translator->trans($error['first'])));
                    $this->wfLastData->form->get($key)->get('second')->addError(new FormError($this->translator->trans($error['second'])));
                } else {
                    $this->wfLastData->form->get($key)->addError(new FormError($this->translator->trans($error)));
                }
            } catch (Exception $e) {
                $return = false;
            }
        }
        return $return;
    }
}
