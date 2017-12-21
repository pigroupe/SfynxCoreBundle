<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Query;

use stdClass;
use Exception;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Query\AbstractEntityShowHandler;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\EntityException;

/**
 * Abstract Class OBEntityShowHandler
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Query
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBEntityShowHandler extends AbstractEntityShowHandler
{
    /**
     * This method implements the init process evenif the request and the form state
     * @return void
     * @throws EntityException
     */
    protected function process(): void
    {
        try {
            $this->wfLastData->entity = $this->manager->getQueryRepository()->find($this->wfQuery->entityId);
        } catch (Exception $e) {
            throw EntityException::NotFoundEntity($this->entityName);
        }
    }
}
