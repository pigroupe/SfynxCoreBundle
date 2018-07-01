<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Observer;

use stdClass;
use SplSubject;
use Sfynx\CoreBundle\Generator\Domain\Component\File\HandlerModel\Generalisation\HandlerModelInterface;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class FormData
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class FormData implements HandlerModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        $content = $this->createBody($subject);

        $body = '' . PHP_EOL
            . 'try {' . PHP_EOL
            . "    \$this->wfLastData->formViewData['command'] = \$this->wfHandler->command;" . PHP_EOL
            . "    \$this->wfLastData->formViewData['entity'] = \$this->wfHandler->entity;" . PHP_EOL
            . "    \$this->wfLastData->formViewData['data_class'] = get_class(\$this->wfHandler->command);" . PHP_EOL
            . $content
            . '' . PHP_EOL
            . '} catch (Exception $e) {' . PHP_EOL
            . '    throw WorkflowException::noCreatedFormData();' . PHP_EOL
            . '}' . PHP_EOL
            . 'return true;'  . PHP_EOL
        ;

        ClassHandler::createMethods(
            $subject->event->namespace,
            $subject->event->class,
            AbstractGenerator::transform([
                'options' => [
                    'methods' => [[
                        'name' => 'process',
                        'comments' => ['{@inheritdoc}'],
                        'visibility' => 'protected',
                        'returnType' => 'bool',
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
    protected function createBody(SplSubject $subject, string $content = '')
    {
        $templater = $subject->event->template;

        foreach ($templater->getTargetCommandFields() as $field) {
            if ($field->type == ClassHandler::TYPE_ENTITY
                || ($field->type == ClassHandler::TYPE_ARRAY
                    && property_exists($field, 'mapping')
                )
            ) {
                $managerName = 'manager';
                if (property_exists($field, 'mapping')
                    && property_exists($field->mapping, 'manager')
                ) {
                    $managerName = lcfirst(ClassHandler::getClassNameFromNamespace($field->mapping->manager));

                    $info = ClassHandler::getArgResult($subject->event->namespace, $field->mapping->manager, [], false);
                    $managerName = lcfirst($info['value']);
                    ClassHandler::setArgClassResult($subject->event->namespace, $field->mapping->manager, $subject->event->index, $info['value'], $info['basename'], true);
                }

                $content .= PHP_EOL . '    $this->wfLastData->formViewData[\'' . lcfirst($field->name) . "List'] = \$this->{$managerName}". PHP_EOL;
                $content .= '       ->getQueryRepository()' . PHP_EOL;
                $content .= '       ->findAll();';
            }
        }

        return $content;
    }
}
