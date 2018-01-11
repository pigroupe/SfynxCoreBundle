<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm;

use Doctrine\ORM\Query;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Interfaces\ResultInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation\FieldsDefinitionAbstract;

/**
 * Class Result
 *
 * @category Sfynx\CoreBundle
 * @package Infrastructure
 * @subpackage Persistence\Generalisation\Orm
 */
class Result implements ResultInterface
{
    /** @var Query $query */
    protected $query;
    /** @var FieldsDefinitionAbstract */
    protected $fieldsDefinition = null;

    /**
     * Query constructor.
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getScalarResult()
    {
        return $this->query->getScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getSingleResult($hydrationMode = null)
    {
        return $this->query->getSingleResult($hydrationMode);
    }

    /**
     * {@inheritdoc}
     */
    public function getSingleScalarResult()
    {
        return $this->getSingleResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalCount(): int
    {
        $this->query
            ->setFirstResult(null)
            ->setMaxResults(null);

        return (int)$this->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getOneOrNullResult($hydrationMode = null)
    {
        return $this->query->getOneOrNullResult($hydrationMode);
    }

    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        $result = $this->query->getResult();
        if ($result == []) {
            $result = null;
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getArrayResults()
    {
        $results = $this->query->getArrayResult();

        if ($this->fieldsDefinition) {
            foreach ($results as &$row) {
                foreach ($row as $key => $v) {
                    $new_key = $this->fieldsDefinition->getFlipField($key);
                    $row[$new_key] = $v;
                }
            }
        }
        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldsDefinition(): FieldsDefinitionAbstract
    {
        return $this->fieldsDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function setFieldsDefinition(FieldsDefinitionAbstract $fieldsDefinition): ResultInterface
    {
        $this->fieldsDefinition = $fieldsDefinition;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return [
            'results' => $this->getArrayResults(),
            'total_rows' => $this->getTotalCount()
        ];
    }
}
