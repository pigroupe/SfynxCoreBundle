<?php
namespace Sfynx\CoreBundle\Layers\Application\Query\Validation\ValidationHandler\Generalisation;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Constraint;
use Sfynx\CoreBundle\Layers\Application\Query\DecoratorHandler\Generalisation\AbstractQueryDecoratorHandler;
use Sfynx\CoreBundle\Layers\Application\Query\Handler\Generalisation\Interfaces\QueryHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;
use Sfynx\CoreBundle\Layers\Application\Common\Handler\ValidationErrorHandler;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Generalisation\Interfaces\ValidatorInterface;

/**
 * Class AbstractQueryValidationHandler
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Generalisation\ValidationHandler
 * @abstract
 */
abstract class AbstractQueryValidationHandler extends AbstractQueryDecoratorHandler
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
     * AbstractQueryValidationHandler constructor.
     * @param QueryHandlerInterface $queryHandler
     * @param ValidatorInterface $validator
     */
    public function __construct(QueryHandlerInterface $queryHandler, ValidatorInterface $validator, bool $throwExceptionFromErrors = true) {
        parent::__construct($queryHandler);

        $this->constraints = [];
        $this->validator = $validator;
        $this->throwExceptionFromErrors = $throwExceptionFromErrors;
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryInterface $query): QueryHandlerInterface
    {
        $this->initConstraints($query);
        $this->errors = $this->validator->validate($query->toArray(true, $this->skipArrayValidator), $this->getConstraints());
        if (!(\count($this->errors) === 0)
            && $this->throwExceptionFromErrors
        ) {
            throw new Exception(\json_encode($this->getErrors())) ;
        }
        // execute next queryHandler
        $query->errors = $this->getErrors();
        return $this->queryHandler->process($query);
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
     * @param QueryInterface $query
     * @return void
     */
    abstract protected function initConstraints(QueryInterface $query): void;

    /**
     * @param string $field
     * @param Constraint $constraints
     * @return $this
     */
    protected function add(string $field, Constraint $constraints): AbstractQueryDecoratorHandler
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
