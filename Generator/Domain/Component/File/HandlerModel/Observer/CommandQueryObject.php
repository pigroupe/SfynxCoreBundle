<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Observer;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Generalisation\AbstractHandlerModel;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Component\File\MethodModel;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class CommandQueryObject
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\HandlerModel\Observer
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CommandQueryObject extends AbstractHandlerModel
{
    /** @var array */
    protected static $performMethods = [
        MethodModel\Construct::class,
        MethodModel\CreateFromNative::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        $this->createBody($subject);

        return true;
    }

    /**
     * @param SplSubject $subject
     * @return string
     */
    protected function createBody(SplSubject $subject)
    {
        $fieldAll = [];
        $templater = $subject->event->template;

        foreach ($templater->getTargetCommandFields() as $field) {
            $fieldAll[] = $field;
        }

        $performMethod = self::$performMethods;
        if (!empty($this->parameters['performMethods'])) {
            $performMethods = $this->parameters['performMethods'];
        }
        $performMethods = array_reverse($performMethods);
        foreach ($performMethods as $method) {
            $method::handle(
                $subject->event->namespace,
                $subject->event->class,
                $subject->event->index,
                $fieldAll,
                \array_merge(['toEntity' => false], $this->parameters)
            );
        }
    }
}
