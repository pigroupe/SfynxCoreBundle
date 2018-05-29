<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * trait class for time attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
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
      * @ORM\PrePersist()
      */
    public function setCreatedValue()
    {
         // we create the Created_at value
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }
        // we modify the Updated_at value
        if (!$this->getUpdatedAt()) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

     /**
      * @ORM\PreUpdate()
      */
    public function setUpdatedValue()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

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
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
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
    public function setPublishedAt($publishedAt)
    {
        $this->published_at = $publishedAt;
        return $this;
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
    public function setArchiveAt($archiveAt)
    {
        $this->archive_at = $archiveAt;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getArchiveAt()
    {
        return $this->archive_at;
    }

    /**
     * Set archived
     *
     * @param boolean $enabled
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
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
