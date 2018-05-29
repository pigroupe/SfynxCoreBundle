<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for expired attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitExpire
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="expired", type="boolean", nullable = true)
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

    /**
     * Sets this user to expired.
     *
     * @param Boolean $boolean
     *
     * @return self
     */
    public function setExpired($boolean)
    {
        $this->expired = (Boolean) $boolean;
        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return self
     */
    public function setExpiresAt(DateTime $date)
    {
        $this->expiresAt = $date;
        return $this;
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
