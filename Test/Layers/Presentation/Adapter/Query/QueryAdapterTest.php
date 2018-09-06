<?php
namespace Sfynx\CoreBundle\Test\Presentation\Adapter\Command;

use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\QueryAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Adapter\Query\QueryAdapter;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\QueryRequestInterface;
use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

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
class QueryAdapterTest extends TestCase
{
    const valuesGame = [
        'field1' => 'value1',
        'field2' => '1977-05-09',
        'field3' => 119.0
    ];

    /**
     * @var Phake_IMock $request Mock instance of QueryInterface.
     * @see QueryInterface
     */
    protected $initial_query_instance;

    /**
     * @var Phake_IMock $request Mock instance of QueryInterface.
     * @see QueryInterface
     */
    protected $returned_query_instance;

    /**
     * @var Phake_IMock $request Mock instance of QueryRequestInterface.
     * @see QueryRequestInterface
     */
    protected $request;

    /** @var QueryAdapterInterface $adapter */
    protected $adapter;

    /**
     * Configures the test.
     */
    public function setUp(): void
    {
        $this->request = Phake::mock(QueryRequestInterface::class);
        Phake::when($this->request)->getRequestParameters()
            ->thenReturn(self::valuesGame);

        $this->adapter = new QueryAdapter($this->getInitialeQueryInstance());
    }

    /**
     * Tests buildCommandFromRequest method.
     *
     * @covers ::createQueryFromRequest()
     */
    public function testNominal(): void
    {
        $returned_query_instance = $this->adapter->createQueryFromRequest($this->request);
        Phake::verify($this->request, Phake::times(1))->getRequestParameters(Phake::anyParameters());
        static::assertInstanceOf(QueryInterface::class, $returned_query_instance);
        $this->assertEquals($this->getEndQueryInstance(), $returned_query_instance);
    }

    /**
     * Set initiale query instance with public properties
     * @return QueryInterface
     */
    protected function getInitialeQueryInstance(): QueryInterface
    {
        if (!($this->initial_query_instance instanceof QueryInterface)) {
            $this->initial_query_instance = Phake::mock(QueryInterface::class);
            foreach (self::valuesGame as $k => $v) {
                $this->initial_query_instance->$k = null;
            }
        }
        return $this->initial_query_instance;
    }

    /**
     * Set initiale query instance with public properties
     * @return QueryInterface
     */
    protected function getEndQueryInstance(): QueryInterface
    {
        if ($this->initial_query_instance instanceof QueryInterface) {
            foreach ($this->initial_query_instance as $k => $v) {
                $this->initial_query_instance->$k = $v;
            }
        }
        return $this->initial_query_instance;
    }
}
