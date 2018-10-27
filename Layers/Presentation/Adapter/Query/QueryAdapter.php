<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Query;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\QueryAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\RequestInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

/**
 * Class QueryAdapter.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Query
 */
class QueryAdapter implements QueryAdapterInterface
{
    /** @var  QueryInterface */
    protected $query;
    /** @var  string|null */
    protected $queryClass;
    /** @var  array */
    protected $parameters;

    const METHOD_REFLEXION = 1;
    const METHOD_NATIVE = 0;

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected static $initQueryList = [
        self::METHOD_REFLEXION => 'createQueryFromReflexion',
        self::METHOD_NATIVE => 'createQueryFromNative',
    ];

    /**
     * QueryAdapter constructor.
     * @param QueryInterface $query
     */
    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
        $this->queryClass = \get_class($query);
    }

    /**
     * @param RequestInterface $request
     * @param bool $reflexion
     * @return QueryInterface
     */
    public function createQueryFromRequest(RequestInterface $request, bool $reflexion = true): QueryInterface
    {
        $this->parameters = $request->getRequestParameters();

        return $this->{self::$initQueryList[$reflexion]}();
    }

    /**
     * @return QueryInterface
     */
    protected function createQueryFromReflexion(): QueryInterface
    {
        foreach ((new \ReflectionObject($this->query))->getProperties() as $oProperty) {
            $oProperty->setAccessible(true);
            $value = isset($this->parameters[$oProperty->getName()]) ?
                $this->parameters[$oProperty->getName()] : $oProperty->getValue($this->query);
            $oProperty->setValue($this->query, $value);
        }

        return $this->query;
    }

    /**
     * @return QueryInterface
     */
    protected function createQueryFromNative(): QueryInterface
    {
        return $this->queryClass::createFromNative($this->parameters);
    }
}
