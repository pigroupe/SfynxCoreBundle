<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Query;

use stdClass;
use Doctrine\ORM\QueryBuilder;
use Sfynx\ToolBundle\Util\PiStringManager;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Query\AbstractObserver;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsXmlHttpRequest;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithQueryInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsHandlerCreatedWithServerSideQueryInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsServerSide;

/**
 * Abstract Class AbstractIndexCreateJsonQueryHandler
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Generalisation\Query
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
abstract class AbstractIndexCreateJsonQueryHandler extends AbstractObserver
{
    /** @var string */
    protected $entityName = '';
    /** @var ManagerInterface */
    protected $manager;
    /** @var RequestInterface */
    protected $request;
    /** @var stdClass */
    protected $object;

    /**
     * EntityFindHandler constructor.
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     */
    public function __construct(ManagerInterface $manager, RequestInterface $request)
    {
        $this->entityName = $manager->getEntityName();
        $this->manager = $manager;
        $this->request = $request;
        $this->object = new stdClass();
    }

    /**
     * @return array List of accepted request methods ['POST', 'GET', ...]
     */
    protected function getValidMethods(): array
    {
        return ['GET'];
    }

    /**
     * Prepare object attributs values used by class specifications
     */
    protected function prepareObject()
    {
        $this->object->requestMethod = $this->request->getMethod();
        $this->object->validMethod = $this->getValidMethods();
        $this->object->isXmlHttpRequest = $this->request->isXmlHttpRequest();
        $this->object->handler = new stdClass();
        $this->object->handler->query = $this->wfQuery;
    }

    /**
     * Sends a persists action create to specific create manager.
     * @return AbstractObserver
     */
    protected function execute(): AbstractObserver
    {
        // preapre object attribut used by specifications
        $this->prepareObject();
        // we abort if we are not in the edit form process
        $specs = (new SpecIsValidRequest())
            ->AndSpec(new SpecIsXmlHttpRequest())
            ->AndSpec(new SpecIsHandlerCreatedWithQueryInterface())
            ->AndSpec(new SpecIsHandlerCreatedWithServerSideQueryInterface())
            ->AndSpec(new SpecIsServerSide())
        ;
        if (!$specs->isSatisfiedBy($this->object)) {
            return $this;
        }
        // we run process
        $this->process();

        return $this;
    }

    /**
     * This method implements the init process evenif the request and the form state
     * @return void
     * @throws EntityException
     */
    abstract protected function process(): void;

    /**
     * Create Ajax query
     *
     * @param string $type ["select","count"]
     * @param string $table
     * @param string $aColumns
     * @param string $table
     * @param array  $dateSearch
     * @param array  $cacheQuery_hash
     *
     * @return array
     * @access protected
     */
    protected function createAjaxQuery($type, $aColumns, QueryBuilder $qb, $tablecode = 'a', $dateSearch = null, $cacheQuery_hash = null)
    {
        $locale = $this->wfQuery->locale;

        if ($type == "count") {
            $qb->add('select', $tablecode.'.id');
        }

        /**
         * Date
         */
        if (!(null === $dateSearch) && is_array($dateSearch)) {
            foreach ($dateSearch as $k => $columnSearch) {
                $idMin = "date-{$columnSearch['idMin']}";
                $idMax = "date-{$columnSearch['idMax']}";
                if ($this->request->get($idMin) != '') {
                    $date = \DateTime::createFromFormat($columnSearch['format'], $this->request->get($idMin));
                    $dateMin = $date->format('Y-m-d 00:00:00');
                    //$dateMin = $this->container->get('sfynx.tool.date_manager')->format($date->getTimestamp(), 'long','medium', $locale, "yyyy-MM-dd 00:00:00");
                    $qb->andWhere("{$columnSearch['column']} >= '" . $dateMin . "'");
                }
                if ($this->request->get($idMax) != '') {
                    $date = \DateTime::createFromFormat($columnSearch['format'], $this->request->get($idMax));
                    $dateMax = $date->format('Y-m-d 23:59:59');
                    $qb->andWhere("{$columnSearch['column']} <= '" . $dateMax . "'");
                }
            }
        }

        /**
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $array_params = [];
        $and = $qb->expr()->andx();
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if ( $this->request->get('bSearchable_'.$i) == "true" && $this->request->get('sSearch_'.$i) != '' ) {
                $search_tab = explode("|", $this->request->get('sSearch_'.$i));
                $or = $qb->expr()->orx();
                foreach ($search_tab as $s) {
                    $or->add("LOWER(".$aColumns[(intval($i)-1)].") LIKE :var".$i."");

                    $current_encoding = mb_detect_encoding($s, 'auto');
                    $s = iconv($current_encoding, 'UTF-8', $s);
                    $s = PiStringManager::withoutaccent($s);

                    $array_params["var".$i] = '%'.strtolower($s).'%';
                }
                $and->add($or);
            }
        }
        $qb->andWhere($and);

//        SELECT f0_.id AS id_0, f0_.name AS name_1, f0_.salt AS salt_2, f0_.password AS password_3, f0_.last_login AS last_login_4, f0_.confirmation_token AS confirmation_token_5, f0_.password_requested_at AS password_requested_at_6, f0_.username AS username_7, f0_.username_canonical AS username_canonical_8, f0_.nickname AS nickname_9, f0_.email AS email_10, f0_.email_canonical AS email_canonical_11, f0_.birthday AS birthday_12, f0_.address AS address_13, f0_.country AS country_14, f0_.city AS city_15, f0_.zip_code AS zip_code_16, f0_.created_at AS created_at_17, f0_.updated_at AS updated_at_18, f0_.published_at AS published_at_19, f0_.archive_at AS archive_at_20, f0_.archived AS archived_21, f0_.expired AS expired_22, f0_.expires_at AS expires_at_23, f0_.locked AS locked_24, f0_.credentials_expired AS credentials_expired_25, f0_.credentials_expire_at AS credentials_expire_at_26, f0_.global_opt_in AS global_opt_in_27, f0_.site_opt_in AS site_opt_in_28, f0_.enabled AS enabled_29, f0_.roles AS roles_30, f0_.permissions AS permissions_31, f0_.application_tokens AS application_tokens_32, f0_.lang_code AS lang_code_33 FROM fos_user f0_ LEFT JOIN fos_user_group f2_ ON f0_.id = f2_.user_id LEFT JOIN fos_group f1_ ON f1_.id = f2_.group_id WHERE f0_.roles NOT LIKE '%ROLE_SUBSCRIBER%' AND f0_.roles NOT LIKE '%ROLE_MEMBER%' AND f0_.roles NOT LIKE '%ROLE_PROVIDER%' AND f0_.roles NOT LIKE '%ROLE_CUSTOMER%' AND ((LOWER(CASE WHEN f0_.roles LIKE '%ROLE_SUPER_ADMIN%' OR f1_.roles LIKE '%ROLE_SUPER_ADMIN%' THEN 'ROLE_SUPER_ADMIN' WHEN f0_.roles LIKE '%ROLE_ADMIN%' OR f1_.roles LIKE '%ROLE_ADMIN%' THEN 'ROLE_ADMIN' WHEN f0_.roles LIKE '%ROLE_USER%' OR f1_.roles LIKE '%ROLE_USER%' THEN 'ROLE_USER' ELSE 'OTHER' END) LIKE '%role_user%') OR (LOWER(CASE WHEN f0_.roles LIKE '%ROLE_SUPER_ADMIN%' OR f1_.roles LIKE '%ROLE_SUPER_ADMIN%' THEN 'ROLE_SUPER_ADMIN' WHEN f0_.roles LIKE '%ROLE_ADMIN%' OR f1_.roles LIKE '%ROLE_ADMIN%' THEN 'ROLE_ADMIN' WHEN f0_.roles LIKE '%ROLE_USER%' OR f1_.roles LIKE '%ROLE_USER%' THEN 'ROLE_USER' ELSE 'OTHER' END) LIKE '%role_admin%')) GROUP BY f0_.id ORDER BY f0_.id ASC LIMIT 20 OFFSET 0

        $or = $qb->expr()->orx();
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if ( $this->request->get('bSearchable_'.$i) == "true" && $this->request->get('sSearch') != '' ) {
                $search_tab = explode("|", $this->request->get('sSearch'));
                foreach ($search_tab as $s) {
                    if (!empty($s)) {
                        $or->add("LOWER(".$aColumns[$i].") LIKE :var2".$i."");

                        $current_encoding = mb_detect_encoding($s, 'auto');
                        $s = iconv($current_encoding, 'UTF-8', $s);
                        $s = PiStringManager::withoutaccent($s);

                        $array_params["var2".$i] = '%'.strtolower($s).'%';
                    }
                }
            }
        }
        $qb->andWhere($or);

        /**
         * Grouping
         */
        $qb->groupBy($tablecode.'.id');

        /**
         * Ordering
         */
        $iSortingCols = $this->request->get('iSortingCols', '');
        if ( !empty($iSortingCols) ) {
            for ( $i=0 ; $i<intval($this->request->get('iSortingCols') ) ; $i++ ) {
                $iSortCol_ = $this->request->get('iSortCol_'.$i, '');
                $iSortCol_col = (intval($iSortCol_)-1);
                if (!empty($iSortCol_) && ( $this->request->get('bSortable_'.intval($iSortCol_) ) == "true" ) && isset($aColumns[ $iSortCol_col ])) {
                    $column = $aColumns[ $iSortCol_col ];
                    $sort = $this->request->get('sSortDir_'.$i)==='asc' ? 'ASC' : 'DESC';
                    $qb->addOrderBy($column, $sort);
                }
            }
        }

        /**
         * Paging
         */
        if ($type == 'select') {
            $iDisplayStart = $this->request->get('iDisplayStart', 0);
            $iDisplayLength = $this->request->get('iDisplayLength', 25);

            $qb->setFirstResult($iDisplayStart);
            $qb->setMaxResults($iDisplayLength);
        }
        if (count($array_params) > 0) {
            $qb->setParameters($array_params);
        }
//        $query_sql = $qb->getQuery()->getSql();
//        print_r($query_sql);
//        exit;
        if ((null === $cacheQuery_hash)) {
            $qb = $qb->getQuery();
        } elseif (is_array($cacheQuery_hash)) {
            // we define all options
            if (!isset($cacheQuery_hash['time'])) $cacheQuery_hash['time'] = 3600;
            if (!isset($cacheQuery_hash['mode'])) $cacheQuery_hash['mode'] = \Doctrine\ORM\Cache::MODE_NORMAL;
            if (!isset($cacheQuery_hash['setCacheable'])) $cacheQuery_hash['setCacheable'] = true;
            if (!isset($cacheQuery_hash['namespace'])) $cacheQuery_hash['namespace'] = '';
            if (!isset($cacheQuery_hash['input_hash'])) $cacheQuery_hash['input_hash'] = '';
            // we set the query result
            $qb = $this->manager->getQueryRepository()->cacheQuery(
                $qb->getQuery(),
                $cacheQuery_hash['time'],
                $cacheQuery_hash['mode'],
                $cacheQuery_hash['setCacheable'],
                $cacheQuery_hash['namespace'],
                $cacheQuery_hash['input_hash']
            );
        }

        $result = $this->manager->getQueryRepository()->findTranslationsByQuery($locale, $qb, 'object', false, true);
        if ($type == 'count') {
            $result = count($result);
        }

        return $result;
    }
}
