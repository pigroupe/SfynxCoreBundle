<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits;

/**
 * Trait Repository
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage Persistence\Repository\Generalisation\Orm\Traits
 * @abstract
 */
trait TraitSave
{
    /**
     * {@inheritdoc}
     */
    public function save($entity, $andFlush = true, $mergeCheck = false)
    {
        // Check for merging, if requested (trying to persist a detached object without merging will result in a new INSERT).
        if ($mergeCheck) {
            $id = $this->_em->getClassMetadata(get_class($entity))->getIdentifierValues($entity);
            if ($id) {
                $entity = $this->merge($entity);
            } // May also merge cascaded associations.
        }

        $this->persist($entity, $andFlush); // May also persist cascaded associations.

        return $entity; // Will return the managed instance, which may be different from the passed instance if the latter was detached.
    }

    /**
     * {@inheritdoc}
     */
    public function persist($entity, $andFlush = false)
    {
        $this->_em->persist($entity);
        $this->flush($andFlush);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($entity, $andFlush = false)
    {
        $this->_em->remove($entity);
        $this->flush($andFlush);
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($entity)
    {
        $this->_em->refresh($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function detach($entity, $andFlush = false)
    {
        $this->_em->detach($entity);
        $this->flush($andFlush);
    }

    /**
     * {@inheritdoc}
     */
    public function merge($entity, $andFlush = false)
    {
        $this->_em->merge($entity);
        $this->flush($andFlush);
    }

    /**
     * {@inheritdoc}
     */
    public function flush($andFlush = true)
    {
        if ($andFlush) {
            $this->_em->flush();
        }
    }
}
