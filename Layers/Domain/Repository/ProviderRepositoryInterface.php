<?php
namespace Sfynx\CoreBundle\Layers\Domain\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Provider Repository Interface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Repository
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface ProviderRepositoryInterface
{
    /**
     * @return string
     */
    public function getProvider(): string;

    /**
     * @param string $provider
     * @return $this
     */
    public function setProvider(string $provider);
}
