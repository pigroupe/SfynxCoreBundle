<?php
namespace Sfynx\CoreBundle\Layers\Domain\Repository\Query;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;

/**
 * GeneralRepositoryInterface
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
interface GeneralRepositoryInterface
{
    /**
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     * @param string $indexBy The index for the from.
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias, $indexBy = null);

    /**
     * Count all fields existed from the given entity
     *
     * @param boolean $enabled [0, 1]
     *
     * @return string the count of all fields.
     * @access public
     */
    public function count($enabled = null);

    /**
     * return query in cache
     *
     * @param Query   $query
     * @param integer $time
     * @param string  $MODE [MODE_GET, MODE_PUT , MODE_NORMAL , MODE_REFRESH]
     * @param boolean $setCacheable
     * @param string  $namespace
     * @param string  $input_hash
     *
     * @return Query
     * @access public
     */
    public function cacheQuery(Query $query, $time = 3600, $MODE = \Doctrine\ORM\Cache::MODE_NORMAL, $setCacheable = true, $namespace = '', $input_hash = '');

    /**
     * add where for user roles
     *
     * @param QueryBuilder $query QueryBuilder instance
     * @param array $user_roles
     * @param string $route
     *
     * @return QueryBuilder
     * @access public
     */
    public function checkRoles(QueryBuilder $query, array $user_roles, $route): QueryBuilder;

    /**
     * Loads all translations with all translatable fields from the given entity
     *
     * @param Query   $query
     * @param string  $result = {'array', 'object'}
     * @return array|object of result query
     * @access public
     */
    public function findByQuery(Query $query, $result = 'array');

    /**
     * way to iterate over a large result set with "yield" php function
     *
     * <code>
     *  $q = $this->_em->createQuery('SELECT e FROM AppBundle:EntityTwo e');
     *  $entityOne->setEntityTwo($this->yieldByQuery($q));
     * </code>
     *
     * @param Query   $query
     * @return iterator
     * @access public
     */
    public function yieldByQuery(Query $query);

    /**
     * Loads all translations with all translatable fields from the given entity
     *
     * @param string  $locale
     * @param Query   $query
     * @param string  $result = {'array', 'object'}
     * @param boolean $INNER_JOIN
     * @param boolean $FALLBACK
     * @param boolean $lazy_loading
     *
     * @return array|object of result query
     * @access public
     */
    public function findTranslationsByQuery($locale, Query $query, $result = "array", $INNER_JOIN = false, $FALLBACK = true, $lazy_loading = true);

    /**
     * Loads all translations with all translatable
     * fields from the given entity
     *
     * @link https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md#entity-domain-object
     *
     * @param Query   $query
     * @param string  $locale
     * @param string  $result = {'array', 'object'}
     * @param boolean $INNER_JOIN
     * @param boolean $FALLBACK
     * @param boolean $lazy_loading
     *
     * @return Query
     * @access public
     */
    public function setTranslatableHints(Query $query, $locale, $INNER_JOIN = false, $FALLBACK = true, $lazy_loading = true);

    /**
     * Loads all translations with all translatable
     * fields from the given entity
     *
     * @link https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md#entity-domain-object
     *
     * @param Query   $query
     * @param boolean $lazy_loading
     *
     * @return Query
     * @access public
     */
    public function setHints(Query $query, $lazy_loading = true);

    /**
     * Find all translations by an entity.
     *
     * @param string  $locale
     * @param string  $result = {'array', 'object'}
     * @param boolean $INNER_JOIN
     * @param int     $MaxResults
     * @param boolean $FALLBACK
     * @param boolean $lazy_loading
     * @param string $orderby
     *
     * @return array|object
     * @access public
     */
    public function findAllByEntity($locale, $result = "array", $INNER_JOIN = false, $MaxResults = null, $FALLBACK = true, $lazy_loading = true, $orderby = '');

    /**
     * Find a translation of an entity by its id
     *
     * @param interger $id
     * @param string   $locale
     * @param string   $result = {'array', 'object'}
     * @param boolean  $INNER_JOIN
     * @param boolean  $FALLBACK
     * @param boolean  $lazy_loading
     *
     * @return array|object
     * @access public
     */
    public function findOneByEntity($locale, $id, $result = "array", $INNER_JOIN = false, $FALLBACK = true, $lazy_loading = true);

    /**
     * Gets all field values of an translation entity.
     *
     * @param $id value of the id
     *
     * @return mixed
     * @access public
     */
    public function getTranslationsByObjectId($id);
}
