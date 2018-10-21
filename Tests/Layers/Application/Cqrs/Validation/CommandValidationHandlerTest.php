<?php
namespace Tests\Application\Cqrs\Validation;

use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface as LegacyValidatorInterface;
//use Symfony\Component\Validator\Constraints as Assert;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Generalisation\Interfaces\ValidatorInterface;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\SymfonyValidatorStrategy;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Validation\ValidationHandler\Generalisation\AbstractCommandValidationHandler;

/**
 * Class CommandValidationHandler
 * This class permits to test the AbstractCommandValidationHandler class.
 *
 * @category   Tests
 * @package    Application
 * @subpackage Cqrs\Validatio
 *
 * @group unit
 * @requires PHP 7.0
 *
 */
class CommandValidationHandler extends TestCase
{
   /** @var ValidatorInterface */
    protected $validator;
    /** @var CommandInterface */
    protected $command;
    /** @var AbstractCommandValidationHandler $handler */
    protected $commandValidationHandler;
    /** @var CommandHandlerInterface $commandHandler */
    protected $commandHandler;

    /**
     * @return array
     */
    protected static function getConstaints()
    {
        return [
            'entityId' => new Assert\Optional([
                new Assert\NotBlank(),

            ]),
            'username' => new Assert\Optional([
                new Assert\NotBlank(),

            ]),
            'email' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Email(),

            ]),
            'roles' => new Assert\Optional([
                new Assert\NotBlank(),
            ])
        ];
    }

    /**
     * Configures the test.
     */
    public function setUp(): void
    {
        $this->validator = Phake::mock(ValidatorInterface::class);
        $this->LegacyValidatorInterface = Phake::mock(LegacyValidatorInterface::class);
        $this->command = Phake::mock(CommandInterface::class);
        $this->commandHandler = Phake::mock(CommandHandlerInterface::class);

        $this->validatorStrategy = Phake::partialMock(SymfonyValidatorStrategy::class, $this->LegacyValidatorInterface);
        $this->commandValidationHandler = Phake::partialMock(AbstractCommandValidationHandler::class, $this->commandHandler, $this->validatorStrategy);

        /** initialize initConstraints method of commandValidationHandler class */
        Phake::when($this->commandValidationHandler)->initConstraints()
            ->thenReturn(self::getConstaints());
    }

    /**
     * Test getConstraints method
     */
    public function testInitConstraint(): void
    {
        Phake::makeVisible($this->commandValidationHandler)->getConstraints();
        static::assertArrayHasKey('entityId', $this->commandValidationHandler->getConstraints());
    }

    /**
     * Test initErrors method
     */
    public function testValidate(): void
    {
        Phake::makeVisible($this->commandValidationHandler)->getConstraints();
        static::assertArrayHasKey('entityId', $this->commandValidationHandler->getConstraints());
    }

    /**
     * Tests createCommandFromRequest method.
     *
     * @covers ::createCommandFromRequest()
     */
    public function testProcess(): void
    {
        $returned_commandHandler_instance = $this->commandValidationHandler->process($this->command);
        Phake::verify($this->command, Phake::times(1))->process(Phake::anyParameters());
        static::assertInstanceOf(CommandHandlerInterface::class, $returned_commandHandler_instance);
//        Phake::verifyCallMethodWith('process', $this->command)->isCalledOn($this->commandHandler);
    }
}
