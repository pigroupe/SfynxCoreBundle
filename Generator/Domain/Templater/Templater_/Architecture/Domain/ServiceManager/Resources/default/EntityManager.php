<?php
    use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;

    $fieldsEntityOption = '';
    if ($templater->has('targetOptions') && !empty($templater->getTargetOptions())) {
        $fieldsEntityOption = $templater->getTargetOptions()['entity'];
    }

    // we search ManyToOne relationship
    $fieldsEntityList = [];
    if (empty($fieldsEntityOption)) {
        foreach ($templater->getTargetCommandFields() as $field) {
            if (($field->type == ClassHandler::TYPE_ENTITY)
                && property_exists($field, 'mapping')
                && (!property_exists($field, 'primaryKey') || !$field->primaryKey)
            ) {
                $fieldsEntityList[] = $field;
            }
        }
    } else {
        $fieldsEntityList = [];
        foreach ($templater->getTargetCommandFields() as $field) {
            if (($field->type == ClassHandler::TYPE_ENTITY)
                && property_exists($field, 'mapping')
                && (!property_exists($field, 'primaryKey') || !$field->primaryKey)
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
            if (($field->type == ClassHandler::TYPE_ARRAY)
                && property_exists($field, 'mapping')
                && property_exists($field, 'multiple') && ($field->multiple == true)
                && (!property_exists($field, 'primaryKey') || !$field->primaryKey)
            ) {
                $fieldsEntityArrayList[] = $field;
            }
        }
    } else {
        $fieldsEntityArrayList = [];
        foreach ($templater->getTargetCommandFields() as $field) {
            if (($field->type == ClassHandler::TYPE_ARRAY)
                && property_exists($field, 'mapping')
                && property_exists($field, 'multiple') && ($field->multiple == true)
                && (!property_exists($field, 'primaryKey') || !$field->primaryKey)
                && ($field->entityName == $fieldsEntityOption)
            ) {
                $fieldsEntityArrayList[] = $field;
            }
        }
    }

    $count = 0;
    $content = '[';
    if(!empty($fieldsEntityList)) {
        $count++;
        foreach ($fieldsEntityList as $field) {
            $content .= PHP_EOL . "            '" . lcfirst($field->name) . "',";
        }
    }
    if ($count == 0) {
        $endContent1 = $content . ']);' . PHP_EOL;
        $endContent2 = $content . '], $updateCommand);' . PHP_EOL;
    }
    if ($count == 1) {
        $endContent1 = $content . PHP_EOL . '        ]);' . PHP_EOL;
        $endContent2 = $content . PHP_EOL . '        ], $updateCommand);' . PHP_EOL;
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
        $entity = $class::newFromCommand($command, <?php echo $endContent1; ?>
        $this->transformEntity($entity, $command);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFromCommand(object $entity, CommandInterface $command, bool $updateCommand = false): object
    {
        $class = $this->getClass();
        $entity = $class::buildFromCommand($entity, $command, <?php echo $endContent2; ?>
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
        // we search ManyToOne relationship
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

        // we search ManyToMany  relationship
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
