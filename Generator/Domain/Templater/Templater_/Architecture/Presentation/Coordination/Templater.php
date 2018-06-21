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
    const TAG = 'templater_archi_pres_coord';

    /** @var array */
    const TARGET_ATTRIBUTS = [
        'conf-index' => 'indexClasses',
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
        $index = $this->getTargetIndexClasses();

        $data = AbstractGenerator::transform($this->getTargetWidget(), false);

        $namespace = ClassHandler::getNamespace($this->getTargetNamespace());
        ClassHandler::addUses($namespace, $data, $index);

        $class = ClassHandler::getClass($this->getTargetClassname());
        ClassHandler::setClassCommentor($class, $this, $data);
        ClassHandler::setExtends($namespace, $class, $data, $index);
        ClassHandler::addImplements($namespace, $class, $data, $index);
        ClassHandler::addTraits($namespace, $class, $data, $index);

        $body = '';
        foreach ($data->body as $options) {
            foreach ($options as $arg => $pattern) {
                $code_design = $pattern->design;

                if (($code_design == 'code')
                    && (property_exists($pattern, 'content'))
                ) {
                    $body .= "$pattern->content" . PHP_EOL;
                } elseif (property_exists($pattern, 'class')) {
                    $code_class = ClassHandler::getClassNameFromNamespace($pattern->class);
                    $code_arg = $arg;

                    $finalClassArgs = '';
                    if (property_exists($pattern, 'arguments')
                        && $pattern->arguments
                    ) {
                        $finalClassArgs = ClassHandler::setArgs($namespace, $pattern->arguments, $index);
                    }

                    $body .= "\$$code_arg = new " . $code_class . "($finalClassArgs);" . PHP_EOL;

                    if (property_exists($pattern, 'calls')
                        && !empty($pattern->calls)
                    ) {
                        foreach ($pattern->calls as $call) {
                            list($methodName, $methodArgs) = $call;
                            $finalArgs = ClassHandler::setArgs($namespace, $methodArgs, $index);

                            if (in_array($code_design, ['adapter', 'handler'])) {
                                $body .= "\$$arg = \$$code_arg->$methodName($finalArgs);" . PHP_EOL;
                            } elseif (in_array($code_design, ['decorator'])
                                && property_exists($pattern, 'handlers') && $pattern->handlers
                            ) {
                                foreach ($pattern->handlers as $handler) {
                                    $functionResult = ClassHandler::setArgNewResult($namespace, $handler, $index, $handler);
                                    $body .= "\$$code_arg = new " . $functionResult . ";" . PHP_EOL;
                                }
                                $body .= "\$$arg = \$$code_arg->$methodName($finalArgs);" . PHP_EOL;
                            } else {
                                $body .= "\$$code_arg->$methodName($finalArgs);" . PHP_EOL;
                            }
                        }
                    }
                    $body .= PHP_EOL;

                    ClassHandler::addUse($namespace, $pattern->class, $index);
                }
            }
        }
        ClassHandler::addConstructorMethod($namespace, $class);
        ClassHandler::addCoordinationMethod($namespace, $class)->addBody($body);

        return ClassHandler::tabsToSpaces($namespace, $this->getIndentation()) .
            ClassHandler::tabsToSpaces($class, $this->getIndentation());
    }
}

