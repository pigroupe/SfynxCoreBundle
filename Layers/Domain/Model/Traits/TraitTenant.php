<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

//use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;

/**
 * Trait TraitTenant
 * Trait for tenant.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
 */
trait TraitTenant
{
    /**
     * @JMS\Serializer\Annotation\Since("1")
     * @ORM\Column(type="string")
     * ODM\Field(type="string")
     * @CouchDB\Field(type="string")
     */
    protected $tenantId;

    /**
     * @return string
     */
    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    /**
     * @param string $tenantId
     * @return $this
     */
    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }
}
