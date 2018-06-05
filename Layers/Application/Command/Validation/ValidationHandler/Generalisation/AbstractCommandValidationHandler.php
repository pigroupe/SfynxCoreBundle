<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Validation\ValidationHandler\Generalisation;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Constraint;
use Sfynx\CoreBundle\Layers\Application\Command\DecoratorHandler\Generalisation\AbstractCommandDecoratorHandler;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Validation\ValidationHandler\ValidationErrorHandler;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Generalisation\Interfaces\ValidatorInterface;

/**
 * Class AbstractCommandValidationHandler
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Generalisation\ValidationHandler
 * @abstract
 */
abstract class AbstractCommandValidationHandler extends AbstractCommandDecoratorHandler
{
    /** @var ValidatorInterface */
    protected $validator;
    /** @var array */
    protected $constraints;
    /** @var ConstraintViolationListInterface */
    protected $errors = [];
    /** @var bool */
    protected $throwExceptionFromErrors;
    /** @var bool */
    protected $skipArrayValidator = [];

    /**
     * AbstractCommandValidationHandler constructor.
     * @param CommandHandlerInterface $commandHandler
     * @param ValidatorInterface $validator
     */
    public function __construct(CommandHandlerInterface $commandHandler, ValidatorInterface $validator, bool $throwExceptionFromErrors = true) {
        parent::__construct($commandHandler);

        $this->constraints = [];
        $this->validator = $validator;
        $this->throwExceptionFromErrors = $throwExceptionFromErrors;
    }

    /**
     * {@inheritdoc}
     */
    public function process(CommandInterface $command): CommandHandlerInterface
    {
        $this->initConstraints($command);
        $this->errors = $this->validator->validate($command->toArray(true, $this->skipArrayValidator), $this->getConstraints());
        if (!(count($this->errors) === 0)
            && $this->throwExceptionFromErrors
        ) {
            throw new Exception(json_encode($this->getErrors())) ;
        }
        // execute next commandHandler
        $command->errors = $this->getErrors();
        return $this->commandHandler->process($command);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors(): array
    {
        return ValidationErrorHandler::arrayAll($this->errors);
    }

    /**
     * Init array of constraints
     *
     * @param CommandInterface $command
     * @return void
     */
    abstract protected function initConstraints(CommandInterface $command): void;

    /**
     * @param string $field
     * @param Constraint $constraints
     * @return $this
     */
    protected function add(string $field, Constraint $constraints): AbstractCommandDecoratorHandler
    {
        $this->constraints[$field] = $constraints;
        return $this;
    }

    /**
     * @return array
     */
    protected function getConstraints(): array
    {
        return $this->constraints;
    }
}
