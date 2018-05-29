<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use ReflectionClass;
use ReflectionObject;

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
     * @return object
     */
    final public static function newFromArray(array $arr, array $excluded = []): object
    {
        $oEntity = new static();
        foreach ($arr as $propertyName => $value) {
            if (!in_array($propertyName, $excluded)) {
                $oEntity->{$propertyName} = $value;
            }
        }
        return $oEntity;
    }

    /**
     * Build and return a new instance of entity from command
     * @param CommandInterface $command
     * @param array $excluded
     * @param bool $updateCommand
     * @return object
     */
    final public static function newFromCommand(CommandInterface $command, array $excluded = [], bool $updateCommand = false): object
    {
        return static::buildFromCommand(new static(), $command, $excluded, $updateCommand);
    }

    /**
     * Build and return an existed instance of entity from command
     * @param object $entity
     * @param CommandInterface $command
     * @param array $excluded
     * @param bool $updateCommand
     * @return object
     */
    final public static function buildFromCommand(
        object $entity,
        CommandInterface $command,
        array $excluded = [],
        bool $updateCommand = false
    ): object {
        foreach ((new ReflectionObject($command))->getProperties() as $oProperty) {
            $oProperty->setAccessible(true);
            $valueCommand = $oProperty->getValue($command);
            if ($updateCommand ||
                (!$updateCommand && '' !== $valueCommand && null !== $valueCommand)
            ) {
                if (!in_array($oProperty->getName(), $excluded)) {
                    $entity->{$oProperty->getName()} = $oProperty->getValue($command);
                }
            }
        }
        return $entity;
    }

    /**
     * Build and return an existed instance of entity from array
     * @param object $entity
     * @param array $arr
     * @param array $excluded
     * @return object
     */
    final public static function buildFromArray(object $entity, array $arr, array $excluded = []): object
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
     * @param object $entity
     * @param array $excluded
     * @param array $match
     * @return CommandInterface
     */
    public static function buildFromEntity(
        CommandInterface $command,
        object $entity,
        array $excluded = [],
        array $match = []
    ): CommandInterface {
        foreach ((new ReflectionObject($entity))->getProperties() as $oProperty) {
            $oProperty->setAccessible(true);
            if (!in_array($oProperty->getName(), $excluded)) {
                $value = $oProperty->getValue($entity);
                $field = $oProperty->getName();

                if (array_key_exists($field, $match)) {
                    $field = $match[$field];
                }

                if (property_exists($command, $field)) {
                    $reflectionProperty = (new ReflectionClass(get_class($command)))->getProperty($field);
                    $reflectionProperty->setAccessible(true);
                    $reflectionProperty->setValue($command, $value);
                }
            }
        }
        return $command;
    }
}
