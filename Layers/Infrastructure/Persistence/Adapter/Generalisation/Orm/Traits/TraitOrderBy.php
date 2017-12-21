<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits;

use Doctrine\ORM\QueryBuilder;
use Sfynx\CoreBundle\Presentation\Adapter\OrderBy;

/**
 * Trait TraitOrderBy
 *
 * @category Sfynx\CoreBundle
 * @package Infrastructure
 * @subpackage Persistence\Generalisation\Orm
 */
trait TraitOrderBy
{
    /**
     * @var OrderBy
     */
    protected $orderBy;

    /**
     * @return OrderBy
     */
    public function getOrderBy(): OrderBy
    {
        return $this->orderBy;
    }

    /**
     * @param OrderBy $orderBy
     * @return $this
     */
    public function setOrderBy(OrderBy $orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param QueryBuilder $qb
     * @param null|string $alias
     * @return $this
     */
    protected function addOrderByToQueryBuilder(QueryBuilder $qb, ?string $alias = null)
    {
        $alias = !empty($alias) ? $alias . '.' : '';

        foreach ($this->orderBy->getOrders() as $orderIndex => $order) {
            $dql = $alias . $order['field'] . ' ' . ['DESC', 'ASC'][(int)$order['asc']];
            $qb->add('orderBy', $dql, (bool)$orderIndex);
        }

        return $this;
    }
}
