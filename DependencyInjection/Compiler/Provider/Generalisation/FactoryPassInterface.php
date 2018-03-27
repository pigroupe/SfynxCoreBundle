<?php

namespace Sfynx\CoreBundle\DependencyInjection\Compiler\Provider\Generalisation;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface FactoryPassInterface
 *
 * @category   Bundle
 * @package    Sfynx\CoreBundle
 * @subpackage DependencyInjection\Compiler\Provider\Generalisation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface FactoryPassInterface
{
    /** @var string */
    const ORM_DATABASE_TYPE = 'orm';

    /** @var string */
    const ODM_DATABASE_TYPE = 'odm';

    /** @var string */
    const COUCHDB_DATABASE_TYPE = 'couchdb';

    /** @var string */
    const LDAP_DATABASE_TYPE = 'ldap';

    /** @var string */
    const JWT_DATABASE_TYPE = 'jwt';

    /** @var string */
    const WSSE_DATABASE_TYPE = 'wsse';
}
