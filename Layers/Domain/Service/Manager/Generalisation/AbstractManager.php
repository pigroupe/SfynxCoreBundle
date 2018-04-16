<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation\AdapterFactoryInterface;

/**
 * Class AbstractManager
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Service\Manager\Generalisation
 * @abstract
 */
abstract class AbstractManager implements ManagerInterface
{
    /** @var AdapterFactoryInterface  */
    protected $factory;
    /** @var \stdclass */
    protected $param;

    /**
     * Constructor.
     *
     * @param AdapterFactoryInterface $factory
     */
    public function __construct(
        AdapterFactoryInterface $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * {@inheritDoc}
     */
    public function setParams(array $option)
    {
        $this->param = (object) $option;
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->param->class;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityName()
    {
        return $this->getCommandRepository()->getEntityName();
    }

    /**
     * {@inheritDoc}
     */
    public function getTableName() {
        return $this->getCommandRepository()->getTableName();
    }

    /**
     * @return void
     */
    public function setIdGenerator() {
        $this->getCommandRepository()->setIdGenerator();
    }

    /**
     * {@inheritDoc}
     */
    public function getCommandRepository(): CommandRepositoryInterface
    {
        return $this->factory->getCommandRepository();
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryRepository(): QueryRepositoryInterface
    {
        return $this->factory->getQueryRepository();
    }

    /**
     * {@inheritDoc}
     */
    public function newFromCommand(CommandInterface $command): object
    {
        $class = $this->getClass();
        $entity = $class::newFromCommand($command);

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function buildFromCommand(object $entity, CommandInterface $command, bool $updateCommand = false): object
    {
        $class = $this->getClass();
        $entity = $class::buildFromCommand($entity, $command, [], $updateCommand);

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function buildFromEntity(CommandInterface $command, object $entity): CommandInterface
    {
        $class = $this->getClass();
        $command =  $class::buildFromEntity($command, $entity);

        return $command;
    }

    /**
     * {@inheritDoc}
     */
    public function reload(object $entity)
    {
        $this->getCommandRepository()->refresh($entity);
    }

    /**
     * {@inheritDoc}
     */
    public function create(): object
    {
        $class = $this->getClass();
        $entity = new $class;

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function update(object $entity, $andFlush = true): void
    {
        $this->getCommandRepository()->persist($entity, $andFlush);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(object $entity, $andFlush = true) : void
    {
        $this->getCommandRepository()->remove($entity, $andFlush);
    }

    /**
     * {@inheritDoc}
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->getQueryRepository()->find($id, $lockMode, $lockVersion);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        return $this->getQueryRepository()->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getQueryRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }
}