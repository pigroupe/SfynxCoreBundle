<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for zip code attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitZipcode
{
    /**
     * @var string $zip_code
     *
     * @Assert\Regex(pattern="/^[0-9]{5}$/", groups={"registration"},message="user.zip_code.regex")
     * @ORM\Column(name="zip_code", type="string", nullable = true)
     */
    protected $zip_code;

    /**
     * Get the [zip_code] column value.
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Set the value of [zip_code] column.
     *
     * @param string $v new value
     *
     * @return string
     */
    public function setZipCode($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }
        return $v;
    }
}
