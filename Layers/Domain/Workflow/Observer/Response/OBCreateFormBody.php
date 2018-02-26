<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Response;

use Exception;
use stdClass;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractCreateFormBody;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Class OBCreateFormBody
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Response
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBCreateFormBody extends AbstractCreateFormBody
{
    /**
     * {@inheritdoc}
     */
    protected function process(): bool
    {
        $this->wfLastData->body = '';
        try {
            $this->wfLastData->body = $this->templating->render(
                $this->param->templating,
                [
                    'entity' => $this->wfHandler->entity,
                    'edit_form' => $this->wfLastData->form->createView(),
                    'NoLayout' => property_exists($this->wfHandler->command, 'noLayout') ? $this->wfHandler->command->getNoLayout() : '',
                    'category' => property_exists($this->wfHandler->command, 'category') ? $this->wfHandler->command->getCategory() : '',
                    'status' => property_exists($this->wfHandler->command, 'status') ? $this->wfHandler->command->getStatus() : '',
                    'errors_form' => $this->wfHandler->errors
                ]
            );
        } catch (Exception $e) {
            throw WorkflowException::noCreatedViewForm();
        }
        return true;
    }
}
