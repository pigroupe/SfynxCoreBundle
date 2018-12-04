<?php

namespace Sfynx\CoreBundle\Layers\Domain\Model\Interfaces;

/**
 * TraitSimple Interface
 *
 */
interface TraitSimpleInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived();
}
