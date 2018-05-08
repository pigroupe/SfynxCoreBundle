<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Generator;

use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;

/**
 * Class SimpleClassGenerator
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report\Generator
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SimpleClassGenerator extends AbstractGenerator
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

        foreach ($templater::scriptList(strtolower($templater->template)) as $tagTemplater => $data) {
            list($namespace, $source) = $data;
            list($direname, $basename, $extension, $templater->targetClassname) = array_values(pathinfo($source));

            if ($templater->has('targetCqrs')) {
                $namespace = $namespace . '\\' . $templater->getTargetCqrs();
            }

            if ($templater->has('targetClass')) {
                $templater->targetClassname = $templater->getTargetClass()[$tagTemplater];

                if (strrpos($templater->targetClassname, '\\')) {
                    $namespace = $namespace . '\\' . substr($templater->targetClassname, 0, - strlen($templater->targetClassname) + strrpos($templater->targetClassname, '\\'));
                    $templater->targetClassname = substr($templater->targetClassname, strrpos($templater->targetClassname, '\\') + 1);
                }
            }

            $templater->targetNamespace = sprintf('%s\%s', $templater->namespace, $namespace);
            $templater->targetPath = sprintf('%s/%s/%s', $templater->reportDir, str_replace('\\', '/', $namespace), $templater->targetClassname . '.' . $extension);

            static::$dataArr[$cat][$tag]['files'][] = [
                'target_namespace' => $templater->getTargetNamespace(),
                'target_path' => $templater->getTargetPath(),
                'target_source' => $this->renderSource($templater, $source),
            ];
        }
    }

    /**
     * @param TemplaterInterface $templater
     * @param string $source
     * @return string
     * @access private
     */
    protected function renderSource(TemplaterInterface $templater, string $source): string
    {
        ob_start();
        require $source;
        return ob_get_clean();
    }
}
