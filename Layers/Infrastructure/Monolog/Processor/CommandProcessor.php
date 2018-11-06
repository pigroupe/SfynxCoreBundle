<?php
namespace Sfynx\CoreBundle\Infrastructure\Monolog\Processor;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;

/**
 *
 * @category   Sfynx\CoreBundle
 * @package    Infrastructure
 * @subpackage Monolog\Processor
 */
class CommandProcessor
{
    public function processRecord(array $record)
    {
        if (isset($record['context']['command'])) {
            $command = $record['context']['command'];
            if ($command instanceof CommandInterface ) {
                $record['extra']['command'] = $command->toArray();
            }
        }

        return $record;
    }
}
