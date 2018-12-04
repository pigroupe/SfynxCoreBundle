<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces;

/**
 * Class TemplaterInterface
 * 
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Templater\Generalisation\Interfaces
 * @interface
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface TemplaterInterface
{
    /**
     * @param Metric $metric
     * @return void
     */
    public function apply(): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getCategory(): string;

    /**
     * @return string
     */
    public function getTag(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param array $data
     * @return string
     */
    public function getClassValue(array $data = []): string;

    /**
     * @return int
     */
    public function getIndentation(): int;
}
