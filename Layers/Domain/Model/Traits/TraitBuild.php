<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use ReflectionObject;
use Sfynx\CoreBundle\Layers\Domain\Model\Interfaces\EntityInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 * trait class for build entity or command.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitBuild
{
    /**
     * Build and return a new instance of entity from array
     * @param array $arr
     * @param array $excluded
     * @return EntityInterface
     */
    final public static function newFromArray(array $arr, array $excluded = []): EntityInterface
    {
        $oVO = new static();
        foreach ($arr as $propertyName => $value) {
            if (!in_array($propertyName, $excluded)) {
                $oVO->{$propertyName} = $value;
            }
        }
        return $oVO;
    }

    /**
     * Build and return a new instance of entity from command
     * @param CommandInterface $command
     * @param array $excluded
     * @return EntityInterface
     */
    final public static function newFromCommand(CommandInterface $command, array $excluded = []): EntityInterface
    {
        $oVO = new static();
        foreach ($command as $propertyName => $value) {
            if (!in_array($propertyName, $excluded)) {
                $oVO->{$propertyName} = $value;
            }
        }
        return $oVO;
    }

    /**
     * Build and return an existed instance of entity from command
     * @param EntityInterface $entity
     * @param CommandInterface $command
     * @param array $excluded
     * @return EntityInterface
     */
    final public static function buildFromCommand( EntityInterface $entity, CommandInterface $command, array $excluded = []): EntityInterface
    {
        foreach ((new ReflectionObject($command))->getProperties() as $oProperty) {
            $oProperty->setAccessible(true);
            $valueCommand = $oProperty->getValue($command);
            if ('' !== $valueCommand && null !== $valueCommand) {
                if (!in_array($oProperty->getName(), $excluded)) {
                    $entity->{$oProperty->getName()} = $oProperty->getValue($command);
                }
            }
        }
        return $entity;
    }

    /**
     * Build and return an existed instance of entity from array
     * @param EntityInterface $entity
     * @param array $arr
     * @param array $excluded
     * @return EntityInterface
     */
    final public static function buildFromArray( EntityInterface $entity, array $arr, array $excluded = []): EntityInterface
    {
        foreach ($arr as $key => $value) {
            if (!in_array($key, $excluded)) {
                $entity->{$key} = $value;
            }
        }
        return $entity;
    }

    /**
     * Returns a CommandInterface object representation of the given object, using all its properties.
     *
     * @param CommandInterface $command
     * @param EntityInterface $entity
     * @param array $excluded
     * @return CommandInterface
     */
    public static function buildFromEntity(CommandInterface $command, EntityInterface $entity, array $excluded = []): CommandInterface
    {
        foreach ((new ReflectionObject($entity))->getProperties() as $oProperty) {
            $oProperty->setAccessible(true);
            if (!in_array($oProperty->getName(), $excluded)) {
                $command->{$oProperty->getName()} = $oProperty->getValue($entity);
            }
        }
        return $command;
    }
}
