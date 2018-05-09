<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Presentation\Coordination;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\AbstractTemplater;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;

/**
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Presentation\Coordination
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Templater extends AbstractTemplater implements TemplaterInterface
{
    /** @var string */
    const TAG = 'templater_archi_pres_request';

    /** @var array */
    const TARGET_ATTRIBUTS = [
        'conf-mapping' => 'commandFields',
        'conf-widget',
        'conf-cqrs'
    ];

    /** @var string */
    const TEMPLATE_GENERATOR = ReporterObservable::GENERATOR_PHP;

    /**
     * @inheritdoc
     */
    public static function scriptList(string $template): array
    {
        return [
            '0' => [
                'Presentation\Coordination',
                __DIR__ . "/Coordination.php",
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Coordination class';
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        return WidgetInterface::CAT_ARCHI_PRES;
    }

    /**
     * @inheritdoc
     */
    public function getTag(): string
    {
        return self::TAG;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return <<<EOT
This class expose coordination component
EOT;
    }

    /**
     * @inheritdoc
     */
    public function getClassValue(array $data = []): string
    {
        $data = AbstractGenerator::transform($this->getTargetWidget(), false);

        $namespace = ClassHandler::getNamespace($this->getTargetNamespace());
        ClassHandler::addUses($namespace, $data);

        $class = $namespace->addClass($this->getTargetClassname());
        ClassHandler::setClassCommentor($class, $this);
        ClassHandler::addImplements($namespace, $class, $data);
        ClassHandler::addTraits($namespace, $class, $data);
        ClassHandler::setExtends($namespace, $class, $data);
        $method = ClassHandler::setCoordinationMethode($namespace, $class);

        $body = '';
        foreach ($data->body as $options) {
            foreach ($options as $arg => $pattern) {
                $code_design = $pattern->design;
                $code_class = ClassHandler::getClassNameFromNamespace($pattern->class);
                $code_arg = ucfirst(strtolower($code_class));

                $finalClassArgs = '';
                if (property_exists($pattern, 'arguments') && $pattern->arguments) {
                    $finalClassArgs = ClassHandler::setArgs($namespace, $pattern->arguments);
                }

                $body .= "\$$code_arg = new " . $code_class . "($finalClassArgs);" . PHP_EOL;

                if (property_exists($pattern, 'calls') && $pattern->calls) {
                    foreach ($pattern->calls as $call) {
                        list($methodName, $methodArgs) = $call;
                        $finalArgs = ClassHandler::setArgs($namespace, $methodArgs);

                        if (in_array($code_design, ['adapter', 'handler'])) {
                            $body .= "\$$arg = \$$code_arg->$methodName($finalArgs);" . PHP_EOL;
                        } elseif (in_array($code_design, ['decorator'])
                            && property_exists($pattern, 'handlers') && $pattern->handlers
                        ) {
                            foreach ($pattern->handlers as $handler) {
                                $body .= "\$$code_arg = new " . $handler . "(\$$code_arg);" . PHP_EOL;

                                $namespace->addUse($handler);
                            }
                        } else {
                            $body .= "\$$code_arg->$methodName($finalArgs);" . PHP_EOL;
                        }
                    }
                }
                $body .= PHP_EOL;

                $namespace->addUse($pattern->class);
            }
        }
        $method->addBody($body);

        return (string)$namespace;
    }
}

