namespace <?php echo $templater->getTargetNamespace(); ?>;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\AbstractManager;

/**
 * Class <?php echo $templater->getTargetClassname(); ?><?php echo PHP_EOL ?>
 * Layout manager working with entities (Orm, Odm, Couchdb)
 *
 * @category <?php echo $templater->getNamespace(); ?><?php echo PHP_EOL ?>
 * @package Application
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Application\\', '', $templater->getTargetNamespace()); ?><?php echo PHP_EOL ?>
 *
 * @author SFYNX <sfynx@pi-groupe.net>
 * @link http://www.sfynx.fr
 * @license LGPL (https://opensource.org/licenses/LGPL-3.0)
 */
class <?php echo $templater->getTargetClassname(); ?> extends AbstractManager
{
    /**
     * {@inheritdoc}
     */
    public function newFromCommand(CommandInterface $command): object
    {
        $class = $this->getClass();
        $entity = $class::newFromCommand($command, ['image', 'image2']);
        $this->transformEntity($entity, $command);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFromCommand(object $entity, CommandInterface $command, bool $updateCommand = false): object
    {
        $class = $this->getClass();
        $entity = $class::buildFromCommand($entity, $command, ['image', 'image2'], $updateCommand);
        $this->transformEntity($entity, $command);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFromEntity(CommandInterface $command, object $entity): \CommandInterface
    {
        $class = $this->getClass();
        $command = $class::buildFromEntity($command, $entity);

        return $command;
    }

    /**
    * @param object $entity
    * @param CommandInterface $command
    * @return EntityManager
    */
    protected function transformEntity(object &$entity, CommandInterface $command): EntityManager
    {
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->type == 'id'  && property_exists($field, 'mapping')): ?>
        if ('' !== $command-><?php echo lcfirst($field->name); ?> && null !== $command-><?php echo lcfirst($field->name); ?>) {
            $entity->set<?php echo ucfirst($field->name); ?>(
                $this->getQueryRepository()->getEntityManager()->getReference(
                    '\<?php if(property_exists($field, 'mapping') && property_exists($field->mapping, 'targetEntity')): echo $field->mapping->targetEntity; endif; ?>',
                    $command-><?php echo lcfirst($field->name); ?><?php echo PHP_EOL ?>
                )
            );
        }
<?php endif; ?>
<?php endforeach; ?>

        return $this;
    }
}
