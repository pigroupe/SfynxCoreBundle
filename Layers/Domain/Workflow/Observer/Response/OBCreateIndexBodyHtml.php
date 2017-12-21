<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Response;

use Exception;
use stdClass;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractCreateIndexBodyHtml;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\WorkflowException;

/**
 * Class OBCreateIndexBodyHtml
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Response
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBCreateIndexBodyHtml extends AbstractCreateIndexBodyHtml
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
                    'isServerSide' => $this->wfHandler->query->isServerSide,
                    'entities'     => $this->wfHandler->entities,
                    'NoLayout' => $this->wfHandler->query->NoLayout,
                    'category' => $this->wfHandler->query->category
                ]
            );
        } catch (Exception $e) {
            throw WorkflowException::noCreatedViewForm();
        }
        return true;
    }
}
