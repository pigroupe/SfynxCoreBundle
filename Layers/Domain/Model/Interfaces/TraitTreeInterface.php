<?php

namespace Sfynx\CoreBundle\Layers\Domain\Model\Interfaces;

/**
 * TraitTree Interface
 *
 */
interface TraitTreeInterface
{
    /**
     * Get childrens
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getChildrens();

    /**
     * Get parent
     *
     * @return object Object of the class
     */
    public function getParent();

    /**
     * get the tree of the page, build it from the parent if the tree does not exist
     *
     * @return array og object
     */
    public function getTreeParents();

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot();

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel();

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLeft();

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRight();

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft();

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl();

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt();
}
