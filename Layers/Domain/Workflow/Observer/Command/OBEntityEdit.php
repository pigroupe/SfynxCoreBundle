<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Command;

use Exception;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\EntityException;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Command\AbstractEntityEditHandler;

/**
 * Class OBEntityEdit
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBEntityEdit extends AbstractEntityEditHandler
{
    use TraitProcess;
}
