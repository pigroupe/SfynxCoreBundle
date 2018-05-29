<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * trait class for time attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitDatetime
{
    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    protected $created_at;

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated_at;

    /**
     * @var date $published_at
     *
     * @ORM\Column(name="published_at", type="datetime", nullable=true)
     */
    protected $published_at;

    /**
     * @var datetime $archive_at
     *
     * @ORM\Column(name="archive_at", type="datetime", nullable=true)
     */
    protected $archive_at;

    /**
     * @var boolean $archived
     *
     * @ORM\Column(name="archived", type="boolean", nullable=true)
     */
    protected $archived = false;

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * {@inheritdoc}
     */
    public function getArchiveAt()
    {
        return $this->archive_at;
    }

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }
}
