<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Validation\SpecHandler\Generalisation;

use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Handler\Generalisation\Interfaces\QueryHandlerInterface;

use Sfynx\CoreBundle\Layers\Application\Query\DecoratorHandler\Generalisation\AbstractQueryDecoratorHandler;
use Sfynx\CoreBundle\Layers\Infrastructure\Logger\Generalisation\TraitLogger;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\SpecificationException;
use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Logical\AndSpec;
use Sfynx\SpecificationBundle\Specification\Logical\TrueSpec;

/**
 * Interface ValidatorInterface
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Generalisation\SpecHandler
 */
abstract class AbstractQuerySpecHandler extends AbstractQueryDecoratorHandler
{
    use TraitLogger;

    /**
     * @var null
     */
    protected $object = null;

    /**
     * AbstractQuerySpecHandler constructor.
     * @param QueryHandlerInterface $queryHandler
     * @param mixed|null $object
     */
    public function __construct(QueryHandlerInterface $queryHandler, $object = null)
    {
        parent::__construct($queryHandler);

        $this->setObject($object);
    }

    /**
     * init the object that must satisfy specs
     * it is generally called in service definition
     * @param mixed|null $object
     * @return self
     */
    public function setObject($object = null)
    {
        $this->object = new \stdClass();
        $this->object->value = $object;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryInterface $query): QueryHandlerInterface
    {
        $this->object->query = $query;

        $specification = $this->initSpecifications();

        $specs = new AndSpec(new TrueSpec(), $specification);
        if (!$specs->isSatisfiedBy($this->object)) {
            $this->logger->error('error in spec handler');
            $this->logger->error($specs->getLogicalExpression());//log the profiler
            $profiler = $specs->getProfiler();
            $this->logger->error(\json_encode($profiler));//log the profiler

            throw SpecificationException::unsatisfiedSpecification($profiler);
        }
        // execute next queryHandler
        return $this->queryHandler->process($query);
    }

    /**
     * @return InterfaceSpecification
     */
    abstract protected function initSpecifications(): InterfaceSpecification;
}
