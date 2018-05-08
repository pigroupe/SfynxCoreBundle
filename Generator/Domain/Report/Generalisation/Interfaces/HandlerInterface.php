<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\Interfaces;

use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;

/**
 * Interface HandlerInterface
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report\Generalisation\Interfaces
 */
interface HandlerInterface
{
    /**
     * handle class
     *
     * @return void
     */
    public function handle(ReporterObservable $reporter): void;
}
