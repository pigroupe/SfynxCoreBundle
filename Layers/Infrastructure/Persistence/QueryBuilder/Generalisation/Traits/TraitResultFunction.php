<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\QueryBuilder\Generalisation\Traits;

use Doctrine\ORM\EntityRepository;

/**
 * Trait TraitResultFunction
 *
 * @category Sfynx\CoreBundle
 * @package Infrastructure
 * @subpackage Persistence\Generalisation\Traits
 *
 * <code>
 *      $this->wfLastData->query = $this->manager->getQueryRepository(
 *      'FindAllByCategoryQueryBuilder',
 *      [
 *      $this->wfQuery->getCategory(),
 *      null,
 *      '',
 *      '',
 *      false
 *      ]
 *      )->getResultBuilder();
 *
 * <code>
 */
trait TraitResultFunction
{
    /** @var string */
    protected $currentNameBuilder;
    /** @var array */
    protected $resultBuilder = [];

    /**
     * @param string $nameBuilder
     * @return self
     */
    public function setCurrentNameBuilder(string $nameBuilder): EntityRepository
    {
        $this->currentNameBuilder = $nameBuilder;
        return $this;
    }

    /**
     * @param string $nameBuilder
     * @param string $className
     * @param array $args
     * @return $this
     */
    public function setResultBuilder(string $nameBuilder, string $className, array $args): EntityRepository
    {
        $this->setCurrentNameBuilder($nameBuilder);
        $this->resultBuilder[$nameBuilder] = \call_user_func_array([new $className($this), '__invoke'], $args);
        return $this;
    }

    /**
     * @param string|null $nameBuilder
     * @return mixed
     */
    public function getResultBuilder(string $nameBuilder = null)
    {
        if (null === $nameBuilder) {
            $nameBuilder = $this->currentNameBuilder;
        }
        return $this->resultBuilder[$nameBuilder];
    }
}
