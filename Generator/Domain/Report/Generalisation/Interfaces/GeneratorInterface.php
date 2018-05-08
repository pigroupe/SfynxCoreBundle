<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\Interfaces;

use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\TemplaterInterface;

/**
 * Interface GeneratorInterface
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report\Generalisation\Interfaces
 */
interface GeneratorInterface
{
    /**
     * Converting array data to stdClass data
     *
     * @return void
     * @static
     */
    public static function convertDataToObject(): void;

    /**
     * Converting an stdClass -> array  => $option = true
     * Converting an array -> stdClass  => $option = false
     *
     * @param mixed $data
     * @param boolean $option
     * @return mixed
     * @static
     */
    public static function transform($data, $option = true);

    /**
     * Sets js and css script of graphs.
     *
     * @param TemplaterInterface $templater
     * @return void
     * @throws ReportException
     */
    public function generateClassData(TemplaterInterface $templater): void;
}
