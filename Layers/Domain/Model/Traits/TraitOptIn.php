<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trait class for opt in attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitOptIn
{
    /**
     * @var boolean $global_opt_in
     *
     * @ORM\Column(name="global_opt_in", type="boolean", nullable = true)
     */
    protected $global_opt_in;

    /**
     * @var boolean $site_opt_in
     *
     * @ORM\Column(name="site_opt_in", type="boolean", nullable = true)
     */
    protected $site_opt_in;

    /**
     * Get the [global_opt_in] column value.
     *
     * @return boolean
     */
    public function getGlobalOptIn()
    {
        return $this->global_opt_in;
    }

    /**
     * Sets the value of the [global_opt_in] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     *
     * @return User The current object (for fluent API support)
     */
    public function setGlobalOptIn($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }
        if ($this->global_opt_in !== $v) {
            $this->global_opt_in = $v;
        }
        return $this;
    }

    /**
     * Get the [site_opt_in] column value.
     *
     * @return boolean
     */
    public function getSiteOptIn()
    {
        return $this->site_opt_in;
    }

    /**
     * Sets the value of the [site_opt_in] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     *
     * @return User The current object (for fluent API support)
     */
    public function setSiteOptIn($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }
        if ($this->site_opt_in !== $v) {
            $this->site_opt_in = $v;
        }
        return $this;
    }
}
