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
                    'entities'     => $this->wfHandler->entities,
                    'isServerSide' => property_exists($this->wfHandler->query, 'isServerSide') ? $this->wfHandler->query->getIsServerSide() : '',
                    'NoLayout' => property_exists($this->wfHandler->query, 'noLayout') ? $this->wfHandler->query->getNoLayout() : '',
                    'category' => property_exists($this->wfHandler->query, 'category') ? $this->wfHandler->query->getCategory() : '',
                ]
            );
        } catch (Exception $e) {
            throw WorkflowException::noCreatedViewForm();
        }
        return true;
    }
}
