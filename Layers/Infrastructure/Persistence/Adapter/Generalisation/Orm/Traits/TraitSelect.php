<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits;

use Doctrine\ORM\QueryBuilder;
use Sfynx\CoreBundle\Presentation\Adapter\Limit;

/**
 * Trait TraitSelect
 *
 * @category Sfynx\CoreBundle
 * @package Infrastructure
 * @subpackage Persistence\Generalisation\Orm
 */
trait TraitSelect
{
    /** @var array $select */
    protected $selects;

    /**
     * Returns the alias defined in the repository.
     *
     * @return array
     */
    public function getSelects(): array
    {
        return $this->selects;
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setSelects(?array $value)
    {
        $this->selects = $value;
        return $this;
    }
}
