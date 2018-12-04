<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

/**
 * Exception Class ServiceException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ServiceException extends \Exception
{
    /**
     * Returns the <Service Not Supported> Exception.
     *
     * @param string $serviceName
     * @return \Exception
     * @access public
     * @static
     */    
    public static function serviceNotSupported($serviceName = '')
    {
        if (!empty($serviceName)) {
            return new self(sprintf('The %s service selected is not yet supported.', $serviceName));
        }

        return new self("The service selected is not yet supported.");
    }  
        
    /**
     * Returns the <Service Globale Undefined> Exception.
     *
     * @param string $serviceName
     * @param string $matrixName
     * @return \Exception
     * @access public
     * @static
     */    
    public static function serviceGlobaleUndefined($serviceName, $matrixName)
    {
    	return new self(
    	    sprintf('The %s service selected is not yet defined in the grid $GLOBALS["%s"].', $serviceName, $matrixName)
        );
    }
    
    /**
     * Returns the <Service Render Undefined> Exception.   // renderWidgetMethodUndefined
     *
     * @param string $matrixName
     * @return \Exception
     * @access public
     * @static
     */
    public static function serviceRenderUndefined($matrixName)
    {
    	return new self(sprintf('Bad %s render service method !', $matrixName));
    }

    /**
     * Returns the <Service Handler Undefined> Exception.   // renderWidgetMethodUndefined
     *
     * @param string $matrixName
     * @return \Exception
     * @access public
     * @static
     */
    public static function serviceHandlerUndefined($matrixName)
    {
        return new self(sprintf('Bad %s Handler service method !', $matrixName));
    }

    /**
     * Returns the <Service Method UnDefined> Exception.
     *
     * @param string $method
     * @return \Exception
     * @access public
     * @static
     */    
    public static function serviceMethodUnDefined($method)
    {
    	return new self(sprintf('Method %s is not defined in the service !', $method));
    }   

    /**
     * Returns the <service Not Configured Correctly> Exception.
     *
     * @return \Exception
     * @access public
     * @static
     */
    public static function serviceNotConfiguredCorrectly()
    {
        return new self('Service not configured correctly.');
    }     

    /**
     * Returns the <Service Parameter Undefined> Exception.
     *
     * @param string $Param
     * @return \Exception
     * @access public
     * @static
     */
    public static function serviceParameterUndefined($Param)
    {
    	return new self(
    	    sprintf('Service parameter (%s) not defined correctly ! ', $Param)
        );
    }    

    /**
     * Returns the <option Value Not Specified> Exception.
     *
     * @param string $optionName
     * @param string $className
     * @return \Exception
     * @access public
     * @static
     */
    public static function optionValueNotSpecified($optionName, $className = '') 
    {
        if (!empty($className)) {
    	    return new self(
    	        sprintf('Option %s not specified in parameters in the class %s ', $optionName, $className)
            );
        }

        return new self(sprintf('Option %s not specified ! ', $optionName));
    }
}
