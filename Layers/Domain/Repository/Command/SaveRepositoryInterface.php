<?php
namespace Sfynx\CoreBundle\Layers\Domain\Repository\Command;

/**
 * Save Repository Interface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Repository\Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface SaveRepositoryInterface
{
    /**
     * @param $entity
     * @param bool $andFlush
     * @param bool $mergeCheck
     * @return mixed
     */
    public function save($entity, $andFlush = true, $mergeCheck = false);

    /**
     * Persist an entity
     *
     * @param object $entity
     * @param boolean $andFlush
     * @return void
     */
    public function persist($entity, $andFlush = false);

    /**
     * Remove an entity
     *
     * @param object $entity
     * @param boolean $andFlush
     * @return void
     */
    public function remove($entity, $andFlush = false);

    /**
     * Refresh an entity
     *
     * @param object $entity
     * @return void
     */
    public function refresh($entity);

    /**
     * Detach an entity
     *
     * @param object $entity
     * @param boolean $andFlush
     * @return void
     */
    public function detach($entity, $andFlush = false);

    /**
     * Merge an entity
     *
     * @param object $entity
     * @param boolean $andFlush
     * @return void
     */
    public function merge($entity, $andFlush = false);

    /**
     * flush
     *
     * @param boolean $andFlush
     * @return void
     */
    public function flush($andFlush = true);
}
