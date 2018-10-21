<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Adapter\Command;

use Sfynx\CoreBundle\Layers\Presentation\Adapter\Generalisation\Interfaces\CommandAdapterInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\RequestInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * Class CommandAdapter.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Adapter\Command
 */
class CommandAdapter implements CommandAdapterInterface
{
    /** @var  CommandInterface */
    protected $command;
    /** @var  string|null */
    protected $commandClass;
    /** @var  array */
    protected $parameters;

    const METHOD_REFLEXION = 1;
    const METHOD_NATIVE = 0;

    /**
     * List of concrete handlers that can be built using this factory.
     * @var string[]
     */
    protected static $initCommandList = [
        self::METHOD_REFLEXION => 'createCommandFromReflexion',
        self::METHOD_NATIVE => 'createCommandFromNative',
    ];

    public function __construct(CommandInterface $command, string $commandClass = null)
    {
        $this->command = $command;
        $this->commandClass = $commandClass;
    }

    /**
     * @param RequestInterface $request
     * @return CommandInterface
     */
    public function createCommandFromRequest(RequestInterface $request, bool $reflexion = true): CommandInterface
    {
        $this->parameters = $request->getRequestParameters();

        return $this->{self::$initCommandList[$reflexion]}();
    }

    /**
     * @return CommandInterface
     */
    protected function createCommandFromReflexion(): CommandInterface
    {
        foreach ((new \ReflectionObject($this->command))->getProperties() as $oProperty) {
            $oProperty->setAccessible(true);
            $value = isset($this->parameters[$oProperty->getName()]) ?
                $this->parameters[$oProperty->getName()] : $oProperty->getValue($this->command);
            $oProperty->setValue($this->command, $value);
        }

        return $this->command;
    }

    /**
     * @return CommandInterface
     */
    protected function createCommandFromNative(): CommandInterface
    {
        return $this->commandClass::createFromNative($this->parameters);
    }
}
