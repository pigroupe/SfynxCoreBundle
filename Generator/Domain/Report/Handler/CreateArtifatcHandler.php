<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Handler;

use \stdClass;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\Interfaces\HandlerInterface;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Component\File\RecurseCopy;

/**
 * create Directories Handler
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report\Handler
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CreateArtifatcHandler implements HandlerInterface
{
    /**
     * @inheritdoc
     */
    public function handle(ReporterObservable $reporter): void
    {
        $this->objectHandler($reporter->getDataCl());
    }

    /**
     * @param stdClass $object
     * @return void
     */
    protected function objectHandler(stdClass $object): void
    {
        foreach ($object as $parent) {
            if (($parent instanceof stdClass)
                && !in_array('files', get_object_vars($parent))
            ) {
                foreach (get_object_vars($parent) as $children) {
                    $this->objectHandler($children);
                }
            } else {
                $this->directoriesHandler($object);
                $this->filesHandler($object);
            }
        }
    }

    /**
     * @param stdClass $object
     * @return void
     */
    protected function directoriesHandler(stdClass $object): void
    {
        foreach ($object->files as $file) {
            if (!empty($file->target_source)) {
                RecurseCopy::createIfNotExist(dirname($file->target_path));
            }
        }
    }

    /**
     * @param stdClass $object
     * @return void
     */
    protected function filesHandler(stdClass $object, $eol = PHP_EOL): void
    {
        foreach ($object->files as $file) {
            if (!empty($file->target_source)) {
                file_put_contents($file->target_path, '<?php' . $eol . $file->target_source);
            }
        }
    }
}