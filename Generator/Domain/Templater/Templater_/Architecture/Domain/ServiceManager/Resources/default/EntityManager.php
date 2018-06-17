<?php

    $fieldsEntityOption = '';
    if ($templater->has('targetOptions') && !empty($templater->getTargetOptions())) {
        $fieldsEntityOption = $templater->getTargetOptions()['entity'];
    }

    // we search ManyToOne relationship
    $fieldsEntityList = [];
    if (empty($fieldsEntityOption)) {
        foreach ($templater->getTargetCommandFields() as $field) {
            if (($field->type == 'id')
                && property_exists($field, 'mapping')
            ) {
                $fieldsEntityList[] = $field;
            }
        }
    } else {
        $fieldsEntityList = [];
        foreach ($templater->getTargetCommandFields() as $field) {
            if (($field->type == 'id')
                && property_exists($field, 'mapping')
                && ($field->entityName == $fieldsEntityOption)
            ) {
                $fieldsEntityList[] = $field;
            }
        }
    }

    // we search ManyToMany  relationship
    $fieldsEntityArrayList = [];
    if (empty($fieldsEntityOption)) {
        foreach ($templater->getTargetCommandFields() as $field) {
            if (($field->type == 'array')
                && property_exists($field, 'mapping')
                && property_exists($field, 'multiple') && ($field->multiple == true)
            ) {
                $fieldsEntityArrayList[] = $field;
            }
        }
    } else {
        $fieldsEntityArrayList = [];
        foreach ($templater->getTargetCommandFields() as $field) {
            if (($field->type == 'array')
                && property_exists($field, 'mapping')
                && property_exists($field, 'multiple') && ($field->multiple == true)
                && ($field->entityName == $fieldsEntityOption)
            ) {
                $fieldsEntityArrayList[] = $field;
            }
        }
    }
?>
namespace <?php echo $templater->getTargetNamespace(); ?>;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\AbstractManager;
use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;

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
    public function getQueryRepository(): QueryRepositoryInterface
    {
        return parent::getQueryRepository();
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandRepository(): CommandRepositoryInterface
    {
        return parent::getCommandRepository();
    }

    /**
     * {@inheritdoc}
     */
    public function newFromCommand(CommandInterface $command): object
    {
        $class = $this->getClass();
        $entity = $class::newFromCommand($command, [
<?php foreach ($fieldsEntityList as $field): ?>
                '<?php echo lcfirst($field->name); ?>',
<?php endforeach; ?>
            ]
        );
        $this->transformEntity($entity, $command);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFromCommand(object $entity, CommandInterface $command, bool $updateCommand = false): object
    {
        $class = $this->getClass();
        $entity = $class::buildFromCommand($entity, $command, [
<?php foreach ($fieldsEntityList as $field): ?>
                '<?php echo lcfirst($field->name); ?>',
<?php endforeach; ?>
            ],
            $updateCommand
        );
        $this->transformEntity($entity, $command);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFromEntity(CommandInterface $command, object $entity): CommandInterface
    {
        $class = $this->getClass();
        $command = $class::buildFromEntity($command, $entity);

        return $command;
    }

    /**
    * @param object $entity
    * @param CommandInterface $command
    * @return <?php echo $templater->getTargetClassname(); ?>
    */
    protected function transformEntity(object &$entity, CommandInterface $command): <?php echo $templater->getTargetClassname(); ?><?php echo PHP_EOL ?>
    {
<?php foreach ($fieldsEntityList as $field): ?>
        if ('' !== $command-><?php echo lcfirst($field->name); ?> && null !== $command-><?php echo lcfirst($field->name); ?>) {
            $entity->set<?php echo ucfirst(str_replace([$field->entityName, 'Id'], ['', ''], $field->name)); ?>(
                $this->getQueryRepository()->getEntityManager()->getReference(
                    '\<?php if(property_exists($field, 'mapping') && property_exists($field->mapping, 'targetEntity')): echo \Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler::createNamespaceEntity($templater, $field->mapping->targetEntity); endif; ?>',
                    $command-><?php echo lcfirst($field->name); ?><?php echo PHP_EOL ?>
                )
            );
        }
<?php endforeach; ?>

<?php foreach ($fieldsEntityArrayList as $field): ?>
        if ('' !== $command-><?php echo lcfirst($field->name); ?> && null !== $command-><?php echo lcfirst($field->name); ?>) {
            foreach ($command-><?php echo lcfirst($field->name); ?> as $key => $<?php echo lcfirst($field->name); ?>Id) {
                $entity->add<?php echo ucfirst($field->name); ?>(
                    $this->getQueryRepository()->getEntityManager()->getReference(
                        '\<?php if(property_exists($field, 'mapping') && property_exists($field->mapping, 'targetEntity')): echo \Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler::createNamespaceEntity($templater, $field->mapping->targetEntity); endif; ?>',
                        $<?php echo lcfirst($field->name); ?>Id
                    )
                );
            }
        }
<?php endforeach; ?>

        return $this;
    }
}
