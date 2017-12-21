<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Traits;

use stdclass;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ControllerException;

/**
 * trait class for enabled attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Generalisation\Traits
 */
trait TraitParam
{
    /** @var stdclass */
    protected $param;

    /**
     * Sets parameter template values.
     *
     * @access protected
     * @return void
     */
    public function setParams(array $option)
    {
        $this->param = (object) $option;
    }

    /**
     * Return property value even if the default value given in argument
     * @param $property
     * @param $defaultValue
     * @return bool
     */
    protected function getParam($property, $defaultValue = null)
    {
        if (property_exists($this->param, $property)) {
            return $this->param->$property;
        }
        return $defaultValue;
    }

    /**
     * Return property value  even if return exception
     * @param $property
     * @param $defaultValue
     * @return bool
     */
    protected function getParamOrThrow($property)
    {
        if (property_exists($this->param, $property)) {
            return $this->param->$property;
        }
        throw ControllerException::NotFoundParam($property);
    }
}
