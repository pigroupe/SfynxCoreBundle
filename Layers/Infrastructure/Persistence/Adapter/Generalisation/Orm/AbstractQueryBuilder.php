<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm;

use Doctrine\ORM\EntityRepository;

/**
 * Abstract Query Builder
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage Persistence\Adapter\Generalisation\Orm
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-17
 */
abstract class AbstractQueryBuilder
{
    /** @var EntityRepository */
    protected $entityRepository;

    /**
     * AbstractQueryBuilder constructor.
     * @param EntityRepository $entityRepository
     */
    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }
}