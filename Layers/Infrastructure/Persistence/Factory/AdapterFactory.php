<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation\AbstractAdapterFactory;

class AdapterFactory extends AbstractAdapterFactory
{
    /** @var EntityManager */
    protected $_em;
    /** @var ClassMetadata */
    protected $_ClassMetadata;

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
        EntityManagerInterface $em,
        array $commandQueryBuilder = [],
        array $queryQueryBuilder = []
    ) {
        $this->_em = $em;
        $this->_ClassMetadata = $this->_em->getClassMetadata($class);
        $this->_command = new $CommandRepository($this->_em, $this->_ClassMetadata);
        $this->_query = new $QueryRepository($this->_em, $this->_ClassMetadata);
        $this->_qb_command = $commandQueryBuilder;
        $this->_qb_query = $queryQueryBuilder;
    }
}
