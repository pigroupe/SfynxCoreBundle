<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for expired attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitExpire
{
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $expired;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="expires_at", type="datetime", nullable = true)
     */
    protected $expiresAt;

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpired()
    {
        return $this->expired;
    }

    public function isExpired()
    {
        return !$this->isAccountNonExpired();
    }

    public function isAccountNonExpired()
    {
        if ((true === $this->expired)
            || (null !== $this->expiresAt && $this->expiresAt->getTimestamp() < time())
        ) {
            return false;
        }
        return true;
    }
}
