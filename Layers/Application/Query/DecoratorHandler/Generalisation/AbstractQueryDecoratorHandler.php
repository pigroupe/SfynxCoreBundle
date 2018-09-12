<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\DecoratorHandler\Generalisation;

use Sfynx\CoreBundle\Layers\Application\Query\Handler\Generalisation\Interfaces\QueryHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

/**
 * Class AbstractQueryDecoratorHandler.
 *
 * @category Sfynx\CoreBundle
 * @package Layers
 * @subpackage Application\Query\Generalisation
 * @abstract
 */
abstract class AbstractQueryDecoratorHandler implements QueryHandlerInterface
{
    /**
     * @var QueryHandlerInterface
     */
    protected $queryHandler;

    /**
     * @param QueryHandlerInterface $queryHandler
     */
    public function __construct(QueryHandlerInterface $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    /**
     * @param QueryInterface $query
     * @return mixed
     */
    abstract public function process(QueryInterface $query): QueryHandlerInterface;
}
