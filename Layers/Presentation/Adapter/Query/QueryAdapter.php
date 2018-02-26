<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Query;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\QueryAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\QueryRequestInterface;
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

    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param QueryRequestInterface $request
     * @return QueryInterface
     */
    public function createQueryFromRequest(QueryRequestInterface $request): QueryInterface
    {
        $this->parameters = $request->getRequestParameters();

//        $tab = get_object_vars($this->query);
//        $tab = is_array($tab) ? $tab : [];
//        foreach ($tab as $property => $value) {
//            $this->query->{$property} = isset($this->parameters[$property]) ? $this->parameters[$property] : $this->query->{$property};
//        }

        foreach ((new \ReflectionObject($this->query))->getProperties() as $oProperty) {
            $oProperty->setAccessible(true);
            $value = isset($this->parameters[$oProperty->getName()]) ? $this->parameters[$oProperty->getName()] : $oProperty->getValue($this->query);
            $oProperty->setValue($this->query, $value);
        }

        return $this->query;
    }
}