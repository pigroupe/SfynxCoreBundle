<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Cache;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Sfynx\ToolBundle\Util\PiFileManager;
use Sfynx\CacheBundle\Manager\Generalisation\ClientInterface;

/**
 * Cache File class
 *
 * @subpackage Core
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 */
class CacheFile
{
    /** @var RegistryInterface $registry */
    protected $registry;

    /** @var ClientInterface $fileCache */
    protected $fileCache;

    /**
     * FrontendController constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(
        RegistryInterface $registry,
        ClientInterface $fileCache

    ) {
        $this->registry = $registry;
        $this->fileCache = $fileCache;
    }

    /**
     * Put result content in cache with ttl.
     *
     * @param string $key
     * @param integer $ttl
     * @param string $dossier
     * @return mixed
     */
    public function getCache($key, $ttl, $dossier)
    {
    	$this->setPath($dossier);
        return $this->fileCache->get($key);
    }

    /**
     * Put result content in cache with ttl.
     * if ttl is equal to zero then the cache is infini
     *
     * @param string $key
     * @param integer $ttl
     * @param string $dossier
     * @param $dossier
     */
    public function setCache($key, $ttl, $newvalue, $dossier)
    {
        $this->setPath($dossier)
        $this->fileCache->set($key, $newvalue, $ttl);
    }

    private function setPath($dossier)
    {
        PiFileManager::mkdirr($dossier);
        $this->fileCache->getClient()->setPath($dossier);
    }
}
