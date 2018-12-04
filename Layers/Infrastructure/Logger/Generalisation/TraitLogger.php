<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Logger\Generalisation;

use Psr\Log\LoggerInterface;

/**
 * Trait TraitLogger
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Infrastructure
 * @subpackage Logger\Generalisation
 */
trait TraitLogger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
