<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for city attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitCity
{
    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", nullable = true)
     * @Assert\Length(min=2, max=50, groups={"registration"}, maxMessage="user.city.max_length", minMessage="user.city.min_length")
     * @Assert\Regex(pattern="/^[a-zA-ZÀ-ÿ ,\'-]+$/",groups={"registration", "personal_data"},message="user.city.regex")
     */
    protected $city;

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of [city] column.
     *
     * @param string $v new value
     *
     * @return string
     */
    public function setCity($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }
        return $v;
    }
}
