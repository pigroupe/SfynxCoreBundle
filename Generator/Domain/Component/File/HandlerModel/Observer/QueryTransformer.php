<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Observer;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Generalisation\AbstractHandlerModel;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class QueryTransformer
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\HandlerModel\Observer
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class QueryTransformer extends AbstractHandlerModel
{
    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        $args = $this->getArgs($subject);
        $queryBuilderMethod = $this->parameters[0];

        $methodArgs = ClassHandler::setArgs($subject->event->namespace, $args, $subject->event->index, false, 'argument');

        $body = '' . PHP_EOL
            . "\$result = [];" . PHP_EOL
            . "\$query = (new $queryBuilderMethod(\$this->entityRepository))->__invoke($methodArgs);" . PHP_EOL
            . '' . PHP_EOL
            . "return \$result;"  . PHP_EOL
        ;

        ClassHandler::createMethods(
            $subject->event->namespace,
            $subject->event->class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => '__invoke',
                        'arguments' => $args,
                        'visibility' => 'public',
                        'returnType' => 'array',
                        'body' => [$body]
                    ]]
                ]
            ], false),
            $subject->event->index
        );

        return false;
    }

    /**
     * @param SplSubject $subject
     * @param string $content
     * @return string
     */
    protected function getArgs(SplSubject $subject)
    {
        return  AbstractGenerator::transform($this->parameters[1], true);
    }
}
