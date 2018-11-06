<?php
namespace Sfynx\CoreBundle\Infrastructure\Monolog\Processor;

use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\Interfaces\QueryInterface;

/**
 *
 * @category   Sfynx\CoreBundle
 * @package    Infrastructure
 * @subpackage Monolog\Processor
 */
class QueryProcessor
{
    public function processRecord(array $record)
    {
        if (isset($record['context']['query'])) {
            $query = $record['context']['query'];
            if ($query instanceof QueryInterface ) {
                $record['extra']['query'] = $query->toArray();
            }
        }

        return $record;
    }
}
