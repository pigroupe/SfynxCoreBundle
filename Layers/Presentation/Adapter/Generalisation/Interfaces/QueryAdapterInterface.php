<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces;

use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\RequestInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

/**
 * Interface QueryAdapterInterface
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Generalisation\Interfaces
 */
interface QueryAdapterInterface
{
    /**
     * @param RequestInterface $request
     * @return QueryInterface
     */
    public function createQueryFromRequest(RequestInterface $request, bool $reflexion = true): QueryInterface;
}