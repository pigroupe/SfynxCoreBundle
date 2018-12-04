<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for nickname attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitNickname
{
    /**
     * @ORM\Column(name="nickname", type="string", length=50, nullable = true)
     * @Assert\Length(min = 8, max = 50, minMessage = "core.field.min_length", maxMessage="core.field.max_length", groups={"registration"})
     * @Assert\NotBlank(message="core.field.required", groups={"registration"})
     * @Assert\Regex(pattern="/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u", message="core.field.regex", groups={"registration"})
     */
    protected $nickname;

    /**
     * Set nickname
     *
     * @param text $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }
}
