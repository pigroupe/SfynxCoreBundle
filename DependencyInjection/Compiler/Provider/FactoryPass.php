<?php
namespace Sfynx\CoreBundle\DependencyInjection\Compiler\Provider;

use Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Handler\HandlerFactoryPass;

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
class FactoryPass
{
    /**
     * Create the class that will change the factory class regarding the provider type.
     *
     * @param string $entity Entity name
     * @param string $alias Alias name
     * @param bool $multiple
     * @return FactoryPassInterface
     */
    public static function create($entity, $alias, array $parameters, bool $multiple = true)
    {
        return new HandlerFactoryPass($entity, $alias, $parameters, $multiple);
    }
}
