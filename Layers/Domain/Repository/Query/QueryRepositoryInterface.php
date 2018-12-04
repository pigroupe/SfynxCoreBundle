<?php
namespace Sfynx\CoreBundle\Layers\Domain\Repository\Query;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

use Sfynx\CoreBundle\Layers\Domain\Repository\ResultFunctionRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\ProviderRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\GeneralRepositoryInterface;
use Sfynx\CoreBundle\Layers\Domain\Repository\Query\TranslationRepositoryInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Interfaces\ResultInterface;
use Sfynx\CoreBundle\Layers\Infrastructure\Cache\CacheQuery;

/**
 * Query Repository Interface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Repository\Query
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface QueryRepositoryInterface extends ResultFunctionRepositoryInterface, ProviderRepositoryInterface, GeneralRepositoryInterface, TranslationRepositoryInterface, ObjectRepository
{
    /**
     * @return ResultInterface
     */
    public function Result(?Query $query): ResultInterface;

    /**
     * @return CacheQuery
     */
    public function getCacheFactory(): CacheQuery;

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface;

    /**
     * @return string
     */
    public function getEntityName(): string;

    /**
     * @return string
     */
    public function getTableName(): string;

    /**
     * @param string    entityName    nom de l'entité
     * @param integer   id    id de la référence demandée
     */
    public function getReference($id);

    /**
     * @param $query
     * @param $dbRef
     * @param $refValue
     * @return QueryBuilder
     */
    public function clauseAndWhere(QueryBuilder $query, $dbRef, $refValue): QueryBuilder;

    /**
     * @param QueryBuilder $query
     * @return mixed
     */
    public function execute(QueryBuilder $query);

    /**
     * @param string $entropy
     * @return string
     */
    public static function struuid(string $entropy);
}
