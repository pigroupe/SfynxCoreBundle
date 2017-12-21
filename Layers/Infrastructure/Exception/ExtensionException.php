<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

use Exception;

/**
 * Exception Class ExtensionException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ExtensionException extends Exception
{
    /**
     * Returns the <File UnDefined> Exception.
     *
     * @param string $file
     * @return \Exception
     * @access public
     * @static
     */    
    public static function FileUnDefined($file)
    {
        return new static(
            sprintf('File %s doesn\'t exist in the web/bundle !', $file)
        );
    }
    
     /**
     * Returns the <Option Value Not Specified> Exception.
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
    		return new static(
    		    sprintf('Option %s not specified in parameters in the class %s ', $optionName, $className)
            );
    	}

   		return new static(
   		    sprintf('Option %s not specified ! ', $optionName)
        );
    }
}