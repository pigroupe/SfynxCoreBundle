<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Domain\WorkflowObserver;

use Sfynx\CoreBundle\Generator\Domain\Widget\Generalisation\Interfaces\WidgetInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\AbstractTemplater;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;

/**
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Domain\WorkflowObserver
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Templater extends AbstractTemplater implements TemplaterInterface
{
    /** @var string */
    const TAG = 'templater_archi_dom_work_obs';

    /** @var array */
    const TARGET_ATTRIBUTS = [
        'conf-mapping' => 'commandFields',
        'conf-widget',
        'conf-cqrs'
    ];

    /** @var string */
    const TEMPLATE_GENERATOR = ReporterObservable::GENERATOR_PHP_MULTIPLE;

    /**
     * @inheritdoc
     */
    public static function scriptList(string $template): array
    {
        return ['Domain\Worflow\Observer'];
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Service class';
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): string
    {
        return WidgetInterface::CAT_ARCHI_DOM;
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
This class expose workflow observer component
EOT;
    }

    /**
     * @inheritdoc
     */
    public function getClassValue(array $data = []): string
    {
        $data = AbstractGenerator::transform($data, false);

        $namespace = ClassHandler::getNamespace($this->getTargetNamespace());
        ClassHandler::addUses($namespace, $data);

        $class = $namespace->addClass($this->getTargetClassname());
        ClassHandler::setClassCommentor($class, $this);
        ClassHandler::addImplements($namespace, $class, $data);
        ClassHandler::addTraits($namespace, $class, $data);
        ClassHandler::setExtends($namespace, $class, $data);

        return (string)$namespace;
    }
}

