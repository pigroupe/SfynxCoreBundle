<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits;

use Doctrine\ORM\QueryBuilder;
use Sfynx\CoreBundle\Presentation\Adapter\Limit;

/**
 * Trait TraitLimit
 *
 * @category Sfynx\CoreBundle
 * @package Infrastructure
 * @subpackage Persistence\Generalisation\Orm
 */
trait TraitLimit
{
    /**
     * @var Limit
     */
    protected $limit;

    /**
     * @return Limit
     */
    public function getLimit(): Limit
    {
        return $this->limit;
    }

    /**
     * @param Limit $limit
     * @return $this
     */
    public function setLimit(Limit $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param QueryBuilder $qb
     * @return $this
     */
    protected function setLimitToQueryBuilder(QueryBuilder $qb)
    {
        if ($this->limit->getCount() != 0) {
            $qb->setFirstResult($this->limit->getStart())
                ->setMaxResults($this->limit->getCount());
        }
        return $this;
    }
}
