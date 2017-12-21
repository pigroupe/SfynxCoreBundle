<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * trait class for position attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model\Traits
 */
trait TraitPosition
{
    /**
     * @inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
}
