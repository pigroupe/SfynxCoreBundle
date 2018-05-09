<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File;

use stdClass;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;

/**
 * File finder
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ClassHandler
{
    /**
     * @inheritdoc
     */
    public static function getDirenameFromNamespace(string $namespace): string
    {
        return substr($namespace, 0, - strlen($namespace) + strrpos($namespace, '\\'));
    }

    /**
     * @inheritdoc
     */
    public static function getClassNameFromNamespace(string $namespace): string
    {
        return substr($namespace, strrpos($namespace, '\\') + 1);
    }

    /**
     * @param string $namespace
     * @return PhpNamespace
     * @static
     */
    public static function getNamespace(string $namespace): PhpNamespace
    {
        return new PhpNamespace($namespace);
    }

    /**
     * @param ClassType $class
     * @param stdClass $data
     * @return void
     * @static
     */
    public static function addUses(PhpNamespace $namespace, stdClass $data): void
    {
        if (property_exists($data, 'options')
            && property_exists($data->options, 'uses')
            && $data->options->uses
        ) {
            foreach ($data->options->uses as $use) {
                $namespace->addUse($use);
            }
        }
    }

    /**
     * @param ClassType $class
     * @param stdClass $data
     * @return void
     * @static
     */
    public static function setClassCommentor(ClassType $class, TemplaterInterface $templater): void
    {
        $package = ucfirst(strtolower($templater->getCategory()));

        $class->addComment('Class ' . $templater->getTargetClassname());
        $class->addComment('');
        $class->addComment('@category ' . $templater->getNamespace());
        $class->addComment('@package ' . $package);
        $class->addComment('@subpackage ' . str_replace($templater->getNamespace() . '\\' . $package . '\\', '', $templater->getTargetNamespace()));
        $class->addComment('@author SFYNX <contact@pi-groupe.net>');
        $class->addComment('@licence LGPL');
    }

    /**
     * @param ClassType $class
     * @param stdClass $data
     * @return void
     * @static
     */
    public static function setExtends(PhpNamespace $namespace, ClassType $class, stdClass $data): void
    {
        if (property_exists($data, 'extends')
            && $data->extends
        ) {
            $class->setExtends(self::getClassNameFromNamespace($data->extends));
            $namespace->addUse($data->extends);
        }
    }

    /**
     * @param ClassType $class
     * @param stdClass $data
     * @return void
     * @static
     */
    public static function addImplements(PhpNamespace $namespace, ClassType $class, stdClass $data): void
    {
        if (property_exists($data, 'options')
            && property_exists($data->options, 'implements')
            && $data->options->implements
        ) {
            foreach ($data->options->implements as $implement) {
                $class->addImplement(self::getClassNameFromNamespace($implement));
                $namespace->addUse($implement);
            }
        }
    }

    /**
     * @param ClassType $class
     * @param stdClass $data
     * @return void
     * @static
     */
    public static function addTraits(PhpNamespace $namespace, ClassType $class, stdClass $data): void
    {
        if (property_exists($data, 'options')
            && property_exists($data->options, 'traits')
            && $data->options->traits
        ) {
            foreach ($data->options->traits as $trait) {
                $class->addTrait(self::getClassNameFromNamespace($trait));
                $namespace->addUse($trait);
            }
        }
    }

    /**
     * @param ClassType $class
     * @param stdClass $data
     * @return void
     * @static
     */
    public static function setCoordinationMethode(PhpNamespace $namespace, ClassType $class): Method
    {
        $namespace->addUse('Symfony\Component\HttpFoundation\Response');

        return $class->addMethod('coordinate')->addComment('@return Response');
    }

    /**
     * @param array $args
     * @return string
     */
    public static function setArgs(PhpNamespace $namespace, array $args): string
    {
        $result = [];
        foreach ($args as $arg) {
            $methodArgsClass = str_replace('new', '', $arg, $countNew);
            str_replace('$', '', $arg, $countArg);

            if ($countNew == 1) {
                $result[] = "$arg()";

                $namespace->addUse(trim($methodArgsClass));
            } elseif ($countArg == 1) {
                $result[] = "$arg";
            }
        }

        return implode(', ', $result);
    }
}