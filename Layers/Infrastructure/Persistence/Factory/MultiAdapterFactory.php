<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation\AbstractAdapterFactory;

class MultiAdapterFactory extends AbstractAdapterFactory
{
    /** @var EntityManagerInterface */
    protected $_em_command;
    /** @var EntityManagerInterface */
    protected $_em_query;

    /**
     * MultiAdapterFactory constructor.
     * @param string $class
     * @param string $commandProvider
     * @param string $queryProvider
     * @param string $CommandRepository
     * @param string $QueryRepository
     * @param EntityManagerInterface $emCommand
     * @param EntityManagerInterface $emQuery
     * @param array $commandQueryBuilder
     * @param array $queryQueryBuilder
     */
    public function __construct(
        string $class,
        string $commandProvider,
        string $queryProvider,
        string $CommandRepository,
        string $QueryRepository,
        EntityManagerInterface $emCommand,
        EntityManagerInterface $emQuery,
        array $commandQueryBuilder = [],
        array $queryQueryBuilder = []
    ) {
        $this->_em_command = $emCommand;
        $this->_em_query = $emQuery;
        $this->_qb_command = $commandQueryBuilder;
        $this->_qb_query = $queryQueryBuilder;

        $this->_command = new $CommandRepository($this->_em_command, $this->_em_command->getClassMetadata($class));
        $this->_query = new $QueryRepository($this->_em_query, $this->_em_query->getClassMetadata($class));

        $this->_command->setProvider($commandProvider);
        $this->_query->setProvider($queryProvider);
    }
}
