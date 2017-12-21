<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation\AdapterFactoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class MultiAdapterFactory implements AdapterFactoryInterface
{
    /** @var EntityManager */
    protected $_em_command;
    /** @var EntityManager */
    protected $_em_query;
    /** @var CommandRepositoryInterface */
    protected $_command;
    /** @var QueryRepositoryInterface */
    protected $_query;

    /**
     * MultiAdapterFactory constructor.
     * @param $class
     * @param CommandRepositoryInterface $CommandRepository
     * @param QueryRepositoryInterface $QueryRepository
     * @param EntityManagerInterface $emCommand
     * @param EntityManagerInterface $emQuery
     */
    public function __construct(
        $class,
        $CommandRepository,
        $QueryRepository,
        EntityManagerInterface $emCommand,
        EntityManagerInterface $emQuery
    ) {
        $this->_em_command = $emCommand;
        $this->_em_query = $emQuery;
        $this->_command = new $CommandRepository($this->_em_command, $this->_em_command->getClassMetadata($class));
        $this->_query = new $QueryRepository($this->_em_query, $this->_em_query->getClassMetadata($class));
    }

    public function getQueryRepository()
    {
        return $this->_query;
    }

    public function getCommandRepository()
    {
        return $this->_command;
    }
}
