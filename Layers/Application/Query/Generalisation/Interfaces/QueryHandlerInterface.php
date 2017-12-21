<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces;

use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Application\Common\Generalisation\Interfaces\HandlerInterface;

/**
 * Interface QueryHandlerInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Query\Generalisation\Interfaces
 */
interface QueryHandlerInterface extends HandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function process(QueryInterface $query): QueryHandlerInterface;
}
