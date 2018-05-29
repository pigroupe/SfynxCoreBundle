<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * trait class for enabled attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitEnabled
{
    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     * ODM\Field(name="enabled", type="boolean")
     * CouchDB\Field(type="boolean")
     */
    protected $enabled;

    /**
     * @inheritdoc}
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}
