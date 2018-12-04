<?php
namespace Sfynx\CoreBundle\Layers\Domain\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Result Function Repository Interface
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
interface ResultFunctionRepositoryInterface
{
    /**
     * @param string $nameBuilder
     * @return self
     */
    public function setCurrentNameBuilder(string $nameBuilder): EntityRepository;

    /**
     * @param string $nameBuilder
     * @param string $className
     * @param array $args
     * @return $this
     */
    public function setResultBuilder(string $nameBuilder, string $className, array $args): EntityRepository;

    /**
     * @param string|null $nameBuilder
     * @return mixed
     */
    public function getResultBuilder(string $nameBuilder = null);
}
