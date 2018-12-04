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
trait TraitTree
{
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", nullable=true)
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", nullable=true)
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    private $lvl;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @var array parents_tree
     */
    protected $parents_tree = null;

    /**
     * @inheritdoc}
     */
    public function getChildrens()
    {
        return $this->childrens;
    }

    /**
     * @inheritdoc}
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritdoc}
     */
    public function setTreeParents(array $parents)
    {
        $this->parents_tree = $parents;
    }

    /**
     * @inheritdoc}
     */
    public function getTreeParents()
    {
        if (!$this->parents_tree) {
            $page = $this;
            $parents = [];
            while ($page->getParent()) {
                $page = $page->getParent();
                $parents[] = $page;
            }
            $this->setTreeParents(array_reverse($parents));
        }

        return $this->parents_tree;
    }

    /**
     * @inheritdoc}
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @inheritdoc}
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @inheritdoc}
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @inheritdoc}
     */
    public function getLeft()
    {
        return $this->lft;
    }

    /**
     * @inheritdoc}
     */
    public function getRight()
    {
        return $this->rgt;
    }

    /**
     * @inheritdoc}
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * @inheritdoc}
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @inheritdoc}
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    /**
     * @inheritdoc}
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @inheritdoc}
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * @inheritdoc}
     */
    public function getRgt()
    {
        return $this->rgt;
    }
}
