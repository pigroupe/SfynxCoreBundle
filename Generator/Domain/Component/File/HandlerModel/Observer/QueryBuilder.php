<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Observer;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Generalisation\AbstractHandlerModel;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class QueryBuilder
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File\HandlerModel\Observer
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class QueryBuilder extends AbstractHandlerModel
{
    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        $args = $this->getArgs($subject);

        $body = '' . PHP_EOL
            . "\$query = \$this->entityRepository->createQueryBuilder('a')" . PHP_EOL
            . "    ->select('a');" . PHP_EOL
            . '' . PHP_EOL
            . "return \$query;"  . PHP_EOL
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
                        'returnType' => 'QueryBuilder',
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
        return  AbstractGenerator::transform($this->parameters, true);
    }
}
