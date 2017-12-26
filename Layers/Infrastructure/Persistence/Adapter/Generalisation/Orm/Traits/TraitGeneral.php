<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Persistence\Adapter\Generalisation\Orm\Traits;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait Repository
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Infrastructure
 * @subpackage Persistence\Adapter\Generalisation\Orm\Traits
 * @abstract
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @author Riad HELLAL <hellal.riad@gmail.com>
 */
trait TraitGeneral
{
    /** @var RoleFactoryInterface */
    protected $roleFactory;

    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * {@inheritdoc}
     */
    public function total($enabled = null)
    {
        if (!is_null($enabled)) {
            return $this->_em
            ->createQuery("SELECT COUNT(c) FROM {$this->_entityName} c WHERE c.enabled = '{$enabled}'")
            ->getSingleScalarResult();
        }
        return $this->_em->createQuery("SELECT COUNT(c) FROM {$this->_entityName} c")->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function cacheQuery(Query $query, $time = 3600, $MODE = \Doctrine\ORM\Cache::MODE_NORMAL, $setCacheable = true, $namespace = '', $input_hash = '')
    {
        if (!$query) {
            throw new \Gedmo\Exception\InvalidArgumentException('Invalide query instance');
        }
        // create single file from all input
        if (empty($input_hash)) {
            $input_hash = $namespace . sha1(serialize($query->getParameters()) . $query->getSQL());
        }
        $query->useResultCache(true, $time, (string)$input_hash);
        $query->useQueryCache(true);

        if (method_exists($query, 'setCacheMode')) {
            $query->setCacheMode($MODE);
        }
        if ($setCacheable && method_exists($query, 'setCacheable')) {
            $query->setCacheable($setCacheable);
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function checkRoles(QueryBuilder $query, array $user_roles, $route): QueryBuilder
    {
        $entity_name = $this->_entityName;
        if (isset($GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'])
            && isset($GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'][$entity_name])
        ) {
            if (is_array($GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'][$entity_name])){
                if (!in_array($route, $GLOBALS['ENTITIES']['RESTRICTION_BY_ROLES'][$entity_name])){
                    return $query;
                }
            }
            $orModule   = $query->expr()->orx();
            foreach ($user_roles as $key => $role) {
                $orModule->add($query
                    ->expr()
                    ->like('a.heritage', $query->expr()->literal('%"'.$role.'"%')));
            }
            $query->andWhere($orModule);
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function findByQuery(Query $query, $result = 'array')
    {
        if (!$query) {
            throw new \Gedmo\Exception\InvalidArgumentException(
                sprintf(
                    'Failed to find query'
                )
            );
        }
        if ($result == 'array') {
            $entities = $query->getArrayResult();
        } elseif ($result == 'object') {
            $entities = $query->getResult();
        } else {
            throw new \InvalidArgumentException("We haven't set the good option value : array or object !");
        }

        return $entities;
    }

    /**
     * {@inheritdoc}
     */
    public function yieldByQuery(Query $query)
    {
        $iterableResult = $query->iterate();
        foreach ($iterableResult as $row) {
            yield $row[0];

            // detach from Doctrine, so that it can be Garbage-Collected immediately
            $this->_em->detach($row[0]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findTranslationsByQuery($locale, Query $query, $result = "array", $INNER_JOIN = false, $FALLBACK = true, $lazy_loading = true)
    {
        if (!$query) {
            throw new \Gedmo\Exception\InvalidArgumentException(sprintf(
                'Failed to find Query'
            ));
        }
        $query = $this->setTranslatableHints($query, $locale, $INNER_JOIN, $FALLBACK, $lazy_loading);
        if ($result == 'array') {
            $entities = $query->getArrayResult();
        } elseif ($result == 'object') {
            $entities = $query->getResult();
        } else {
            throw new \InvalidArgumentException("We haven't set the good option value : array or object !");
        }

        return $entities;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslatableHints(Query $query, $locale, $INNER_JOIN = false, $FALLBACK = true, $lazy_loading = true)
    {
//        // BE CARFULL ::: Strange Issue with Query Hint and APC
//        $query->setHint(
//            Query::HINT_CUSTOM_OUTPUT_WALKER,
//            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
//        ); // if you use memcache or apc. You should set locale and other options like fallbacks to query through hints. Otherwise the query will be cached with a first used locale
        $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale); // take locale from session or request etc.
        if ($INNER_JOIN) {
            // will use INNER joins for translations instead of LEFT joins, so that in case if you do not want untranslated records in your result set for instance.
            $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_INNER_JOIN, $INNER_JOIN);
        }
        if (!$lazy_loading) {
            // to avoid lazy-loading.
            $query->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);
        }
        // fallback to default values in case if record is not translated
        $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_FALLBACK, $FALLBACK);

//        $config = $this->container->get('doctrine')->getManager()->getConfiguration();
//        if ($config->getCustomHydrationMode(TranslationWalker::HYDRATE_OBJECT_TRANSLATION) === null) {
//            $config->addCustomHydrationMode(
//                TranslationWalker::HYDRATE_OBJECT_TRANSLATION,
//                'Gedmo\\Translatable\\Hydrator\\ORM\\ObjectHydrator'
//            );
//        }
//        $query->setHydrationMode(\Gedmo\Translatable\Query\TreeWalker\TranslationWalker::HYDRATE_OBJECT_TRANSLATION);
//        $query->setHint(Query::HINT_REFRESH, true);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function setHints(Query $query, $lazy_loading = true)
    {
        // BE CARFULL ::: Strange Issue with Query Hint and APC
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        ); // if you use memcache or apc. You should set locale and other options like fallbacks to query through hints. Otherwise the query will be cached with a first used locale
        if (!$lazy_loading) {
            // to avoid lazy-loading.
            $query->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);
        }
        $query->setHint(Query::HINT_REFRESH, true);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllByEntity($locale, $result = "array", $INNER_JOIN = false, $MaxResults = null, $FALLBACK = true, $lazy_loading = true, $orderby = '')
    {
        $query = $this->_em->createQueryBuilder()
            ->select('a')
            ->from($this->_entityName, 'a')
            ->where('a.archived = 0');

        if (!empty($orderby)) {
            $query->orderBy("a.$orderby", 'ASC');
        }
        $query->getQuery();

        if (!is_null($MaxResults)) {
            $query->setMaxResults($MaxResults);
        }

        return $this->findTranslationsByQuery($locale, $query, $result, $INNER_JOIN, $FALLBACK, $lazy_loading);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByEntity($locale, $id, $result = "array", $INNER_JOIN = false, $FALLBACK = true, $lazy_loading = true)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('a')
            ->from($this->_entityName, 'a')
            ->where('a.id = :id');

        $query->getQuery();
        $query->setParameter('id', $id);
        $query->setMaxResults(1);

        return current($this->findTranslationsByQuery($locale, $query, $result, $INNER_JOIN, $FALLBACK, $lazy_loading));
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationsByObjectId($id)
    {
        $query    = $this->_em->createQuery("SELECT p FROM {$this->_entityTranslationName} p  WHERE p.object = :objectId ");
        $query->setParameter('objectId', $id);
        $entities = $query->getResult();
        if (!(null === $entities)){
            return $entities;
        }

        return null;
    }
}
