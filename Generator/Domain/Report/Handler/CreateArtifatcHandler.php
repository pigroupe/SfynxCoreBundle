<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Handler;

use stdClass;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\Interfaces\HandlerInterface;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\AbstractGenerator;
use Sfynx\CoreBundle\Generator\Domain\Report\ReporterObservable;
use Sfynx\CoreBundle\Generator\Domain\Component\File\RecurseCopy;
use Sfynx\CoreBundle\Generator\Domain\Component\Output\Output;
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;

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
    /** @var Output */
    public $output;

    /**
     * @inheritdoc
     */
    public function handle(ReporterObservable $reporter): void
    {
        $this->output = $reporter->output;

        $this->objectHandler($reporter->getDataCl());
    }

    /**
     * @param stdClass $object
     * @return void
     */
    protected function objectHandler(stdClass $object): void
    {
        if (property_exists($object, 'files')) {
            $this->directoriesHandler($object);
            $this->filesHandler($object);
        } else {
            foreach (get_object_vars($object) as $children) {
                if ($children instanceof stdClass) {
                    $this->objectHandler($children);
                }
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
     * @param string $eol
     * @return void
     */
    protected function filesHandler(stdClass $object, $eol = PHP_EOL): void
    {
        foreach ($object->files as $file) {
            if (!empty($file->target_source)) {
                $this->versioningFileHandler($file->target_path);

                $content = ClassHandler::getFileCommentor() . $eol . $file->target_source;
                $content = ('php' == $file->target_extension) ? '<?php' . $eol . $content : $content;

                file_put_contents($file->target_path, $content);
                $this->output->writeln(sprintf('<info>++</info> create file: "%s"', $file->target_path));
            }
        }

        AbstractGenerator::intitializeDataCl();
        AbstractGenerator::intitializeDataArr();
    }

    /**
     * @param string $path
     * @retunr void
     */
    protected function versioningFileHandler(string $path): void
    {
        if (file_exists($path)) {
            file_put_contents($path . '.back' . '.' . time(), file_get_contents($path));
            $this->output->writeln(sprintf('<info>++</info> verioning file: "%s"', $path));
        }
    }
}