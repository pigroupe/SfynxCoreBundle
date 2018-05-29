<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for locked attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitLocked
{
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
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
     * @return bool
     */
    public function getLocked()
    {
        return !$this->locked;
    }
}
