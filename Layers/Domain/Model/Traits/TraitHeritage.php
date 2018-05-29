<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * trait class for heritage attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 *
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2014-02-16
 */
trait TraitHeritage
{
    /**
     * @var array
     * @ORM\Column(name="secure_roles", type="array", nullable=true)
     */
    protected $heritage;

    /**
     * Set Role heritage
     *
     * @param array $heritage
     * @return self
     */
    public function setHeritage( array $heritage)
    {
        $this->heritage = [];
        foreach ($heritage as $role) {
            $this->addRoleInHeritage($role);
        }
        return $this;
    }

    /**
     * Get heritage
     *
     * @return array
     */
    public function getHeritage()
    {
        return $this->heritage;
    }

    /**
     * Adds a role heritage.
     *
     * @param string $role
     * @param self
     */
    public function addRoleInHeritage($role)
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->heritage, true)) {
            $this->heritage[] = $role;
        }
        return $this;
    }
}
