<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for username attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitUsername
{
    /**
     * @ORM\Column(name="username", type="string", length=50, nullable = true)
     * @Assert\Length(min = 2, max = 50, minMessage = "core.field.min_length", maxMessage="core.field.max_length", groups={"registration"})
     * @Assert\NotBlank(message="core.field.required", groups={"registration"})
     * @Assert\Regex(pattern="/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u", message="core.field.regex", groups={"registration"})
     */
    protected $username;

    /**
     * @ORM\Column(name="username_canonical", type="string", nullable = true)
     */
    protected $usernameCanonical;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }
}
