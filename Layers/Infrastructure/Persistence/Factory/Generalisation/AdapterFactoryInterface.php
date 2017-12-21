<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation;

use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;

/**
 * User Factory Interface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage Persistence\Factory\Generalisation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface AdapterFactoryInterface
{
    /**
     * @return QueryRepositoryInterface
     */
    public function getQueryRepository();

    /**
     * @return CommandRepositoryInterface
     */
    public function getCommandRepository();
}
