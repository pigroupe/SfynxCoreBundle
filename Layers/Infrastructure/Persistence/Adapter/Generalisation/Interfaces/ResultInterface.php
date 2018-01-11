<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Interfaces;

use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\FieldsDefinition\Generalisation\FieldsDefinitionAbstract;

/**
 * Interface ResultInterface
 *
 * @category COre
 * @package Infrastructure
 * @subpackage Persistence\Generalisation
 */
interface ResultInterface
{
    /**
     * @return array
     */
    public function getScalarResult();

    /**
     * @param null $hydrationMode
     * @return mixed
     */
    public function getSingleResult($hydrationMode = null);

    /**
     * @return mixed
     */
    public function getSingleScalarResult();

    /**
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalCount(): int;

    /**
     * @param null $hydrationMode
     * @return mixed
     */
    public function getOneOrNullResult($hydrationMode = null);

    /**
     * @return mixed
     */
    public function getResults();

    /**
     * @return mixed
     */
    public function getArrayResults();

    /**
     * @return FieldsDefinitionAbstract
     */
    public function getFieldsDefinition(): FieldsDefinitionAbstract;

    /**
     * @return Result
     */
    public function setFieldsDefinition(FieldsDefinitionAbstract $fieldsDefinition): ResultInterface;

    /**
     * @return array
     */
    public function getData();
}
