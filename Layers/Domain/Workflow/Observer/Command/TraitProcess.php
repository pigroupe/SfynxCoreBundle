<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Command;

use Exception;
use Symfony\Component\Form\Extension\Core\DataTransformer\ValueToDuplicatesTransformer;


/**
 * Class TraitProcess
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
trait TraitProcess
{
    /**
     * {@inheritdoc}
     */
    protected function onSuccess(): void
    {
        $entity = $this->wfLastData->entity;
        try {
            if (is_object($entity)) {
                $this->manager->getCommandRepository()->save($entity);

                if (property_exists($this, 'updateCommand') && true === $this->updateCommand) {
                    $this->wfCommand = $this->manager->buildFromEntity($this->wfCommand, $entity);
                }
            }
        } catch (Exception $e) {
            $this->wfCommand->errors['success'] = 'errors.entity.save';
        }
        // we add the last entity version
        $this->wfLastData->entity = $entity;
    }
}
