<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Generator;

use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;
use Sfynx\CoreBundle\Generator\Domain\Report\Generator\SimpleClassGenerator;

/**
 * Class PhpClassGenerator
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report\Generator
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PhpClassGenerator extends SimpleClassGenerator
{
    /**
     * @param TemplaterInterface $templater
     * @param array $data
     * @return string
     * @access private
     */
    protected function renderSource(TemplaterInterface $templater, string $source): string
    {
        ob_start();
        echo $templater->getClassValue([]);
        return ob_get_clean();
    }
}
