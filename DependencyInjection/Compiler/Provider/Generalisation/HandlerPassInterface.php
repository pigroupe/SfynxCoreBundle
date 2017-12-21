<?php

namespace Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Generalisation;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface ChangeRepositoryFactoryPassInterface
 *
 * @category   Core
 * @package    Provider
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface HandlerPassInterface extends CompilerPassInterface
{
    public function process(ContainerBuilder $container);
}
