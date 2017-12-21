<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Handler;

/**
 * Class DisabledajaxCommandHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command\Handler
 */
class DisabledajaxCommandHandler extends EnabledajaxCommandHandler
{
    /**
     * @param $entity
     */
    protected function updateEntity(&$entity)
    {
        if (method_exists($entity, 'setEnabled')) {
            $entity->setEnabled(false);
        }
        if (method_exists($entity, 'setPosition')) {
            $entity->setPosition(null);
        }
    }
}
