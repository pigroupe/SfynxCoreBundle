<?php
namespace Sfynx\CoreBundle\DependencyInjection\Compiler\Provider;

use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Generalisation\FactoryPassInterface;
use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Handler\HandlerOrmFactoryPass;
use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Handler\HandlerGeneralFactoryPass;

/**
 * Class FactoryPass
 *
 * @category   Bundle
 * @package    Sfynx\CoreBundle
 * @subpackage DependencyInjection\Compiler\Provider
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class FactoryPass implements FactoryPassInterface
{
    protected static $mapping = [
        self::ORM_DATABASE_TYPE => HandlerOrmFactoryPass::class,
        self::ODM_DATABASE_TYPE => HandlerOdmFactoryPass::class,
        self::COUCHDB_DATABASE_TYPE => HandlerCouchdbFactoryPass::class
    ];

    /**
     * Create the class that will change the factory class regarding the provider type.
     *
     * @param string $type Database used
     * @param string $entity Entity name
     * @param string $alias Alias name
     * @return FactoryPassInterface
     */
    public static function create($type, $entity, $alias, bool $multiple = true)
    {
        if (array_key_exists($type, self::$mapping)) {
            return new self::$mapping[$type]($entity, $alias, $multiple);
        }
        return new HandlerGeneralFactoryPass($type, $entity, $alias, $multiple);
    }
}
