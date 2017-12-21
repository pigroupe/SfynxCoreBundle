<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint as SfynxAssert;

/**
 * trait class for email attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitEmail
{
    /**
     * @ORM\Column(name="email", type="string", nullable = true)
     * @Assert\NotBlank(groups={"registration"},message="user.field_required")
     * @Assert\Email(groups={"registration"},message="user.field_email_format")
     * @Assert\Length(max=255, groups={"registration", "user_account"}, maxMessage="user.email.max_length")
     * @SfynxAssert\EmailBlackList(groups={"registration"}, message="user.field_email_black_list")
     */
    protected $email;

    /**
     * @ORM\Column(name="email_canonical", type="string", nullable = true)
     */
    protected $emailCanonical;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * @param $emailCanonical
     * @return $this
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
        return $this;
    }
}
