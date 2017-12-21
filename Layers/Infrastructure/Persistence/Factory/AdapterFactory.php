<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation\AdapterFactoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class AdapterFactory implements AdapterFactoryInterface
{
    /** @var EntityManager */
    protected $_em;
    /** @var ClassMetadata */
    protected $_ClassMetadata;
    /** @var CommandRepositoryInterface */
    protected $_command;
    /** @var QueryRepositoryInterface */
    protected $_query;

    /**
     * AdapterFactory constructor.
     * @param $class
     * @param CommandRepositoryInterface $CommandRepository
     * @param QueryRepositoryInterface $QueryRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        $class,
        CommandRepositoryInterface $CommandRepository,
        QueryRepositoryInterface $QueryRepository,
        EntityManagerInterface $em
    ) {
        $this->_em = $em;
        $this->_ClassMetadata = $this->_em->getClassMetadata($class);
        $this->_command = new $CommandRepository($this->_em, $this->_ClassMetadata);
        $this->_query = new $QueryRepository($this->_em, $this->_ClassMetadata);
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
