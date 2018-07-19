<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Generalisation;

use Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Generalisation\Interfaces\HandlerModelInterface;

/**
 * Class AbstractHandlerModel.
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractHandlerModel implements HandlerModelInterface
{
    /** @var array */
    protected $parameters;

    /**
     * AbstractHandlerModel constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }
}
