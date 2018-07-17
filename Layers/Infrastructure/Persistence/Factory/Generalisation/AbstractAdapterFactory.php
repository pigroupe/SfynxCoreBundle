<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation;

use Sfynx\CoreBundle\Layers\Domain\Repository\Command\CommandRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\QueryRepositoryInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Factory\Generalisation\Interfaces\AdapterFactoryInterface;

/**
 * Abstract Adapter Factory
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage Persistence\Factory\Generalisation
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-17
 */
abstract class AbstractAdapterFactory implements AdapterFactoryInterface
{
    /** @var CommandRepositoryInterface */
    protected $_command;
    /** @var QueryRepositoryInterface */
    protected $_query;
    /** @var array */
    protected $_qb_command = [];
    /** @var array */
    protected $_qb_query = [];

    /**
     * {@inheritDoc}
     */
    public function getQueryRepository(string $className = null, array $args = []): QueryRepositoryInterface
    {
        if (null !== $className) {
            $this->_query
                ->setResultBuilder($className, $this->_qb_query[$className], $args);
        }
        return $this->_query;
    }

    /**
     * {@inheritDoc}
     */
    public function getCommandRepository(string $className = null, array $args = []): CommandRepositoryInterface
    {
        if (null !== $className) {
            $this->_command
                ->setResultBuilder($className, $this->_qb_command[$className], $args);
        }
        return $this->_command;
    }
}