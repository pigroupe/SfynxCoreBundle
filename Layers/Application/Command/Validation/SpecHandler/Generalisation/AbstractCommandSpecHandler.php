<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Validation\SpecHandler\Generalisation;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;

use Sfynx\CoreBundle\Layers\Application\Command\DecoratorHandler\Generalisation\AbstractCommandDecoratorHandler;
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
abstract class AbstractCommandSpecHandler extends AbstractCommandDecoratorHandler
{
    use TraitLogger;

    /**
     * @var null
     */
    protected $object = null;

    /**
     * AbstractCommandSpecHandler constructor.
     * @param CommandHandlerInterface $commandHandler
     * @param mixed|null $object
     */
    public function __construct(CommandHandlerInterface $commandHandler, $object = null)
    {
        parent::__construct($commandHandler);

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
    public function process(CommandInterface $command): CommandHandlerInterface
    {
        $this->object->command = $command;

        $specification = $this->initSpecifications();

        $specs = new AndSpec(new TrueSpec(), $specification);
        if (!$specs->isSatisfiedBy($this->object)) {
            $this->logger->error('error in spec handler');
            $this->logger->error($specs->getLogicalExpression());//log the profiler
            $profiler = $specs->getProfiler();
            $this->logger->error(json_encode($profiler));//log the profiler

            throw SpecificationException::unsatisfiedSpecification($profiler);
        }
        // execute next commandHandler
        return $this->commandHandler->process($command);
    }

    /**
     * @return InterfaceSpecification
     */
    abstract protected function initSpecifications(): InterfaceSpecification;
}
