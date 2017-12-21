<?php
namespace Sfynx\CoreBundle\Test\Presentation\Adapter\Command;

use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\CommandAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Adapter\Command\CommandAdapter;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class CommandAdapterTest
 * This class permits to test the CommandAdapter class.
 *
 * @category   Sfynx\CoreBundle\Test
 * @package    Presentation
 * @subpackage Adapter\Command
 *
 * @group unit
 * @requires PHP 7.0
 *
 */
class CommandAdapterTest extends TestCase
{
    const valuesGame = [
        'field1' => 'value1',
        'field2' => '1977-05-09',
        'field3' => 119.0
    ];

    /**
     * @var Phake_IMock $request Mock instance of CommandInterface.
     * @see CommandInterface
     */
    protected $initial_command_instance;

    /**
     * @var Phake_IMock $request Mock instance of CommandInterface.
     * @see CommandInterface
     */
    protected $returned_command_instance;

    /**
     * @var Phake_IMock $request Mock instance of CommandRequestInterface.
     * @see CommandRequestInterface
     */
    protected $request;

    /** @var CommandAdapterInterface $adapter */
    protected $adapter;

    /**
     * Configures the test.
     */
    public function setUp(): void
    {
        $this->request = Phake::mock(CommandRequestInterface::class);
        Phake::when($this->request)->getRequestParameters()
            ->thenReturn(self::valuesGame);

        $this->adapter = new CommandAdapter($this->getInitialeCommandInstance());
    }

    /**
     * Tests createCommandFromRequest method.
     *
     * @covers ::createCommandFromRequest()
     */
    public function testNominal(): void
    {
        $returned_command_instance = $this->adapter->createCommandFromRequest($this->request);
        Phake::verify($this->request, Phake::times(1))->getRequestParameters(Phake::anyParameters());
        static::assertInstanceOf(CommandInterface::class, $returned_command_instance);
        $this->assertEquals($this->getEndCommandInstance(), $returned_command_instance);
    }

    /**
     * Set initiale command instance with public properties
     * @return CommandInterface
     */
    protected function getInitialeCommandInstance(): CommandInterface
    {
        if (!($this->initial_command_instance instanceof CommandInterface)) {
            $this->initial_command_instance = Phake::mock(CommandInterface::class);
            foreach (self::valuesGame as $k => $v) {
                $this->initial_command_instance->$k = null;
            }
        }
        return $this->initial_command_instance;
    }

    /**
     * Set initiale command instance with public properties
     * @return CommandInterface
     */
    protected function getEndCommandInstance(): CommandInterface
    {
        if ($this->initial_command_instance instanceof CommandInterface) {
            foreach ($this->initial_command_instance as $k => $v) {
                $this->initial_command_instance->$k = $v;
            }
        }
        return $this->initial_command_instance;
    }
}
