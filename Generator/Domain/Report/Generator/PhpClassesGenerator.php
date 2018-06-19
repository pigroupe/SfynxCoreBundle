<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Generator;

use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;

/**
 * Class PhpClassesGenerator
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report\Generator
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PhpClassesGenerator extends AbstractGenerator
{
    /**
     * @inheritdoc
     */
    public function generateClassData(TemplaterInterface $templater): void
    {
        $tag = $templater->getTag();
        $cat = $templater->getCategory();

        static::$dataArr[$cat][$tag]['name'] = $templater->getName();
        static::$dataArr[$cat][$tag]['desc'] = $templater->getDescription();

        foreach (array_values($templater->getTargetWidget()) as $data) {
            $values = array_values($data);
            $config = end($values);

            $templater->targetClassname = $config['class'];

            $namespace = '';
            if (!strrpos($templater->targetClassname, '\\')) {
                $scriptList = $templater::scriptList('');
                $namespace = end($scriptList) . '\\' ;
                if ($templater->has('targetCqrs')) {
                    $namespace = $namespace . $templater->getTargetCqrs();
                }
            }
            if (strrpos($templater->targetClassname, '\\')) {
                $namespace = $namespace . '\\' . ClassHandler::getDirenameFromNamespace($templater->targetClassname);
                $templater->targetClassname = ClassHandler::getClassNameFromNamespace($templater->targetClassname);
            }

            $templater->targetNamespace = sprintf('%s\%s', $templater->namespace, $namespace);
            $templater->targetPath = sprintf('%s/%s/%s', $templater->reportDir, $namespace, $templater->targetClassname . '.php');

            $templater->targetNamespace = str_replace('\\\\', '\\', $templater->targetNamespace);
            $templater->targetPath = str_replace('\\', '/', $templater->targetPath);

            $source = '';
            if ($config['create']) {
                $source = $this->renderSource($templater, $config);
            }

            static::$dataArr[$cat][$tag]['files'][] = [
                'target_namespace' => $templater->getTargetNamespace(),
                'target_path' => $templater->getTargetPath(),
                'target_source' => $source,
                'target_extension' => 'php',
            ];
        }
    }
    
    /**
     * @param TemplaterInterface $templater
     * @param array $config
     * @return string
     * @access private
     */
    protected function renderSource(TemplaterInterface $templater, array $config = []): string
    {
        ob_start();
        echo $templater->getClassValue($config);
        return  ob_get_clean();
    }
}
