<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

use Exception;

/**
 * Exception Class ControllerException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ControllerException extends Exception
{
    /**
     * Returns the <Not Found Object> Exception.
     *
     * @param object $object
     *
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotFoundObject($object) {
    	return new static(sprintf('Unable to find %s.', get_class($object)));
    }

    /**
     * Returns the <Not Found Entity> Exception.
     *
     * @param string $entityName
     *
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotFoundEntity($entityName)
    {
        return new static(sprintf('Unable to find %s entity.', $entitName));
    }

    /**
     * Returns the <Not Found Parameter> Exception.
     *
     * @param string $paramName
     *
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotFoundParam($paramName)
    {
        return new self(sprintf('Unable to find %s parameter.', $paramName));
    }

    /**
     * Returns the <Not Found Argument> Exception.
     *
     * @param string $paramName
     *
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotFoundArg($argName)
    {
        return new static(sprintf('Unable to find %s argument.', $argName));
    }

    /**
     * Returns the <Not Found Option> Exception.
     *
     * @param string $option
     *
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotFoundOption($option)
    {
        return new static(sprintf('Unable to find %s option.', $option));
    }

    /**
     * Returns the <Call Ajax Only Supported> Exception.
     *
     * @param string $method
     *
     * @return \Exception
     * @access public
     * @static
     */
    public static function callAjaxOnlySupported($method)
    {
        return new static(sprintf('The method %s can be called only in ajax..', $method));
    }
}
