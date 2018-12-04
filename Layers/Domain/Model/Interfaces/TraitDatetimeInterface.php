<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Interfaces;

/**
 * TraitDatetime Interface
 *
 */
interface TraitDatetimeInterface
{
    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt();

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt();

    /**
     * Get published_at
     *
     * @return date
     */
    public function getPublishedAt();

    /**
     * Get archive_at
     *
     * @return datetime
     */
    public function getArchiveAt();
}
