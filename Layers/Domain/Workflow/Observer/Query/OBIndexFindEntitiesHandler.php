<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Query;

use stdClass;
use Exception;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Query\AbstractIndexFindEntitiesHandler;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\EntityException;

/**
 * Abstract Class OBIndexFindEntitiesHandler
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Query
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBIndexFindEntitiesHandler extends AbstractIndexFindEntitiesHandler
{
    /**
     * This method implements the init process evenif the request and the form state
     * @return void
     * @throws EntityException
     */
    protected function process(): void
    {
        try {
            $this->wfLastData->entities = $this->manager->getQueryRepository()->findTranslationsByQuery(
                $this->wfQuery->locale,
                $this->wfLastData->query->getQuery(),
                'object',
                false
            );
        } catch (Exception $e) {
            throw EntityException::NotFoundEntities($this->entityName);
        }
    }
}
