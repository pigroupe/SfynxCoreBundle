<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation\AbstractAdapterFactory;

class MultiAdapterFactory extends AbstractAdapterFactory
{
    /** @var EntityManager */
    protected $_em_command;
    /** @var EntityManager */
    protected $_em_query;

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
        EntityManagerInterface $emQuery,
        array $commandQueryBuilder = [],
        array $queryQueryBuilder = []
    ) {
        $this->_em_command = $emCommand;
        $this->_em_query = $emQuery;
        $this->_command = new $CommandRepository($this->_em_command, $this->_em_command->getClassMetadata($class));
        $this->_query = new $QueryRepository($this->_em_query, $this->_em_query->getClassMetadata($class));
        $this->_qb_command = $commandQueryBuilder;
        $this->_qb_query = $queryQueryBuilder;
    }
}
