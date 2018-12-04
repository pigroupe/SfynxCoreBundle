<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm;

use Doctrine\ORM\QueryBuilder;

/**
 * Class Query
 *
 * @category Sfynx\DddBundle\Layer
 * @package Infrastructure
 * @subpackage Persistence\Generalisation\Orm
 */
class Query
{
    /**
     * @var QueryBuilder $queryBuilder
     */
    protected $queryBuilder;

    /**
     * Query constructor.
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        $result = $this->queryBuilder->getQuery()->getResult();
        if (empty($result)) {
            return null;
        }

        return end($result);
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->queryBuilder->getQuery()->getResult();
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalCount(): int
    {
        $this->queryBuilder->select($this->queryBuilder->expr()->count(1))
            ->setFirstResult(null)
            ->setMaxResults(null);

        return (int)$this->queryBuilder->getQuery()->getSingleScalarResult();
    }
}
