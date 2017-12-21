<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Cache;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Cache Query class
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage Cache;
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 */
class CacheQuery
{
    /** @var EntityManager $EntityManager */
    protected $entityManager;

    /**
     * CacheQuery constructor.
     *
     * @param EntityManager $EntityManager
     */
    public function __construct(
        EntityManager $EntityManager
    ) {
        $this->entityManager = $EntityManager;
    }

    /**
     * Delete the query cache of a id hash.
     *
     * @param string $input_hash
     *
     * @return array
     * @access protected
     * @return boolean
     */
    public function deleteCacheQuery($input_hash)
    {
        $cacheDriver = $this->entityManager->getConfiguration()->getResultCacheImpl();
        return $cacheDriver->delete($input_hash);
    }

    /**
     * Delete all query cache ids of a namespace.
     *
     * @param string $namespace
     *
     * @return array
     * @access protected
     * @return boolean
     */
    public function deleteAllCacheQuery($namespace)
    {
        $cacheDriver = $this->entityManager->getConfiguration()->getResultCacheImpl();
        return $cacheDriver->delete($namespace);
    }
}
