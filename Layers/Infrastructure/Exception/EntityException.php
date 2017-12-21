<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

/**
 * Exception Class EntityException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EntityException extends \Exception
{
    /**
     * Returns the <Id Entity UnDefined> Exception.
     *
     * @param integer $id
     * @param string $className
     * @return \Exception
     * @access public
     * @static
     */    
    public static function IdEntityUnDefined($id, $className)
    {
        return new self(sprintf('Id %s is not defined like a %s entity !', $id, $className));
    }

    /**
     * Returns the <Not Found Entity> Exception.
     *
     * @param string $entityName
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotFoundEntities($entityName)
    {
        return new self(sprintf('Unable to find %s entities.', $entityName));
    }

    /**
     * Returns the <Not Found Entity> Exception.
     *
     * @param string $entityName
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotFoundEntity($entityName)
    {
        return new self(sprintf('Unable to find %s entity.', $entityName));
    }

    /**
     * Returns the <Not Found Entity> Exception.
     *
     * @param string $entityName
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotBuildEntityFromCommand($entityName)
    {
    	return new self(sprintf('Unable to build %s entity from command.', $entityName));
    }

    /**
     * Returns the <Not Save Entity> Exception.
     *
     * @param string $entityName
     * @return \Exception
     * @access public
     * @static
     */
    public static function NotSaveEntity($entityName)
    {
        return new self(sprintf('Unable to save %s entity.', $entityName));
    }
}