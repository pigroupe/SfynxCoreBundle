<?php

namespace Sfynx\CoreBundle\Layers\Domain\Service\Request\Handler;

/**
 * Default Abstract of the Request strategy .
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Service\Request\Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2016-04-18
 */
abstract class AbstractRequest {
    /** @var cookies */
    public $cookies = null;
    /** @var query */
    public $query = null;
    /** @var header */
    public $headers = null;
    /** @var attributes */
    public $attributes = null;
    /** @var files */
    public $files = null;
    /** @var server */
    public $server = null;
}
