<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for locked attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitLocked
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean", nullable = true)
     */
    protected $locked;

    /**
     * @return bool
     */
    public function isLocked()
    {
        return !$this->isAccountNonLocked();
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    /**
     * @param $boolean
     * @return $this
     */
    public function setLocked($boolean)
    {
        $this->locked = (Boolean)$boolean;
        return $this;
    }

    /**
     * @return bool
     */
    public function getLocked()
    {
        return !$this->locked;
    }
}
