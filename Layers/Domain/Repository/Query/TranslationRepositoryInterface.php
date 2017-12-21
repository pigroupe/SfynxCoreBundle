<?php
namespace Sfynx\CoreBundle\Layers\Domain\Repository\Query;

use Doctrine\ORM\QueryBuilder;

/**
 * TranslationRepositoryInterface
 *
 * @category   Core
 * @package    Repository
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface TranslationRepositoryInterface
{
    /**
     * Find a translation of an entity by its id and return the query
     *
     * @param integer $id
     *
     * @return QueryBuilder
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function findOneQueryByEntity($id);

    /**
     * Loads all translations with all translatable
     * fields from the given entity
     *
     * @param object $entity Must implement Translatable
     *
     * @return array list of translations in locale groups
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function findTranslations($entity);

    /**
     * Loads all translations with all translatable
     * fields by a given entity primary key
     *
     * @param mixed $id - primary key value of an entity
     *
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function findTranslationsByObjectId($id);

    /**
     * Makes additional translation of $entity $field into $locale using $value
     *
     * @param object $entity
     * @param string $field
     * @param string $locale
     * @param mixed  $value
     *
     * @return mixed
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function translate($entity, $field, $locale, $value);

    /**
     * Find the entity $class by the translated field.
     * Result is the first occurence of translated field.
     * Query can be slow, since there are no indexes on such
     * columns
     *
     * @param string $field
     * @param string $value
     * @param string $class
     *
     * @return object - instance of $class or null if not found
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function findObjectByTranslatedField($field, $value, $class);

    /**
     * Gets all field values of an entity.
     *
     * @param string $field value of the field table
     *
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getArrayAllByField($field);

    /**
     * Gets all entities by one category.
     *
     * @param string  $category
     * @param integer $MaxResults
     * @param string  $ORDER_PublishDate ['ASC', 'DESC']
     * @param string  $ORDER_Position    ['ASC', 'DESC']
     * @param boolean $enabled
     * @param boolean $is_checkRoles
     * @param boolean $with_archive
     *
     * @return QueryBuilder
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllByCategory($category = '', $MaxResults = null, $ORDER_PublishDate = '', $ORDER_Position = '', $enabled = true, $is_checkRoles = true, $with_archive = false);

    /**
     * Gets all entities by multiple fields.
     *
     * @param string  $fields
     * @param integer $MaxResults
     * @param string  $ORDER_PublishDate ['ASC', 'DESC']
     * @param string  $ORDER_Position    ['ASC', 'DESC']
     * @param boolean $is_checkRoles
     *
     * @return QueryBuilder
     * @access public
     * @author Riad HELLAL <hellal.riad@gmail.com>
     */
    public function getAllByFields($fields = array(), $MaxResults = null, $ORDER_PublishDate = '', $ORDER_Position = '', $is_checkRoles = true);

    /**
     * Gets all order by param.
     *
     * @param string  $field
     * @param string  $ORDER ['ASC', 'DESC']
     * @param boolean $enabled
     * @param boolean $is_checkRoles
     * @param boolean $with_archive
     *
     * @return QueryBuilder
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllOrderByField($field = 'createat', $ORDER = "DESC", $enabled = null, $is_checkRoles = true, $with_archive = false);

    /**
     * Gets all between first and last position.
     *
     * @param string  $FirstPosition
     * @param string  $LastPosition
     * @param boolean $enabled
     * @param boolean $is_checkRoles
     * @param boolean $with_archive
     *
     * @return QueryBuilder
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllBetweenPosition($FirstPosition = null, $LastPosition = null, $enabled = null, $is_checkRoles = true, $with_archive = false);

    /**
     * Gets max/min value of a column.
     *
     * @param string  $field
     * @param string  $type
     * @param boolean $enabled
     * @param boolean $is_checkRoles
     * @param boolean $with_archive
     *
     * @return QueryBuilder
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getMaxOrMinValueOfColumn($field, $type = 'MAX', $enabled = null, $is_checkRoles = true, $with_archive = false);

    /**
     * Find all entities of the entity by category
     *
     * @param string  $locale
     * @param string  $result = {'array', 'object'}
     * @param bool    $INNER_JOIN
     * @param integer $MaxResults
     * @param boolean $is_checkRoles
     * @param boolean $FALLBACK
     * @param boolean $lazy_loading
     *
     * @return object
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllEnabled($locale, $result = "object", $INNER_JOIN = false, $MaxResults = null, $is_checkRoles = true, $FALLBACK = true, $lazy_loading = true);

    /**
     * Find all entities of the entity by category
     *
     * @param string  $locale
     * @param string  $category
     * @param string  $result = {'array', 'object'}
     * @param bool    $INNER_JOIN
     * @param boolean $is_checkRoles
     * @param boolean $FALLBACK
     * @param boolean $lazy_loading
     *
     * @return object
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllEnableByCat($locale, $category, $result = "object", $INNER_JOIN = false, $is_checkRoles = true, $FALLBACK = true, $lazy_loading = true);

    /**
     * Find all entities of the entity by category
     *
     * @param string  $locale
     * @param string  $category
     * @param string  $result = {'array', 'object'}
     * @param boolean $INNER_JOIN
     * @param boolean $is_checkRoles
     * @param boolean $FALLBACK
     * @param boolean $lazy_loading
     *
     * @return object
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllEnableByCatAndByPosition($locale, $category, $result = "object", $INNER_JOIN = false, $is_checkRoles = true, $FALLBACK = true, $lazy_loading = true);

    /**
     * Find a translation field of an entity by its id
     *
     * @param string  $locale
     * @param array   $fields
     * @param bool    $INNER_JOIN
     *
     * @return object
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getContentByField($locale, array $fields, $INNER_JOIN = false);

    /**
     * Find a translation of an entity by its id
     *
     * @param string  $locale
     * @param array   $fields
     * @param string  $result     ['array', 'object']
     * @param boolean $INNER_JOIN
     *
     * @return null|object
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getEntityByField($locale, array $fields, $result = "object", $INNER_JOIN = false);
}
