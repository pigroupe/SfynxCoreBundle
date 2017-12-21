<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for address attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitAddress
{
    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="text", nullable = true)
     * @Assert\Length(min=4,max=100, groups={"personal_data"},maxMessage="user.address.max_length", minMessage="user.address.min_length")
     * @Assert\Regex(pattern="/^[\w ,\'#*-]+$/u",groups={"personal_data"},message="user.address.regex")
     */
    protected $address;

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     *
     * @return self The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }
        if ($this->address !== $v) {
            $this->address = $v;
        }
        return $this;
    }
}
