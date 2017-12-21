<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * trait class for position attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitPosition
{
    /**
     * @inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
        //return $this;
    }

    /**
     * @inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
}
