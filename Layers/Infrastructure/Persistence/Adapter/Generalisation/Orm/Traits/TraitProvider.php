<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait TraitProvider
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage Persistence\Adapter\Generalisation\Orm\Traits
 * @abstract
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
trait TraitProvider
{
    /** @var provider */
    protected $provider;

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * @return $this
     */
    public function setProvider(string $provider)
    {
        $this->provider = $provider;
        return $this;
    }
}
