<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Response;

use Exception;
use stdClass;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractShowBody;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Class OBCreateShowBody
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Response
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBCreateShowBody extends AbstractShowBody
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
                    'NoLayout' => $this->request->getQuery()->get('NoLayout'),
                ]
            );
        } catch (Exception $e) {
            throw WorkflowException::noCreatedViewForm();
        }
        return true;
    }
}
