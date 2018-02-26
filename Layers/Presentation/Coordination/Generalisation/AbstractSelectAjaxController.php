<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Sfynx\ToolBundle\Util\PiStringManager;
use Sfynx\ToolBundle\Twig\Extension\PiFormExtension;

use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\AbstractAjaxController;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler\ResponseHandler;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Serializer\SerializerStrategy;

/**
 * abstract Select Ajax Controller.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Generalisation
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 */
abstract class AbstractSelectAjaxController extends AbstractAjaxController
{
    /** @var string */
    protected $autorization_role = 'ROLE_USER';
    /** @var TranslatorInterface */
    protected $translator;
    /** @var \Twig_Environment */
    protected $twig;

    /**
     * AbstractSelectAjaxController constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     * @param PiFormExtension $formExtension
     * @param TranslatorInterface $translator
     * @param $twig
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        ManagerInterface $manager,
        RequestInterface $request,
        CsrfTokenManagerInterface $securityManager,
        PiFormExtension $formExtension,
        TranslatorInterface $translator,
        \Twig_Environment $twig
    ) {
        parent::__construct($authorizationChecker, $manager, $request, $securityManager, $formExtension);

        $this->translator = $translator;
        $this->twig = $twig;
    }

    /**
     * get entities in ajax request for select form.
     *
     * @return Response
     * @access public
     */
    final public function coordinate()
    {
        // we test >authorization checker
        if (false === $this->authorizationChecker->isGranted($this->autorization_role)) {
            throw new AccessDeniedException();
        }
        // execute events before request
        $entityName = $this->beforeRequestEvents('selectajax');
        // we initialize param
        $this->init();
        // we return response
        $tab = $this->execute();

        return (new ResponseHandler(SerializerStrategy::create(), $this->request->setRequestFormat('json')))
            ->create(json_encode($tab), Response::HTTP_CREATED, [
                'Content-Type' => 'application/json'
            ])
            ->getResponse();
    }

    /**
     * we initialize param
     * @return void
     */
    abstract protected function init(): void;

    /**
     * get selected entities for autocompletion.
     *
     * @return array
     * @access public
     */
    protected function execute()
    {
        // we get paramas
        $pagination = $this->getParam('pagination', null);
        $MaxResults = $this->getParam('max', null);
        $keywords = $this->getParam('keywords', null);
        $cacheQuery_hash = $this->getParam('cacheQuery_hash', null);
        $locale = $this->getLocale();
        $query = $this->getQuery();
        if ($this->getParam('only_enabled', true)) {
            $query->andWhere('a.enabled = 1');
        }
        $query->groupBy('a.id');
        // autocompletion
        $array_params = [];
        if (is_array($keywords) && (count($keywords) >= 1)) {
            $i = 0;
            foreach ($keywords as $info) {
                $is_trans = false;
                if (isset($info['field_trans']) && !empty($info['field_trans'])) {
                    $is_trans = $info['field_trans'];
                    if (!isset($info['field_trans_name']) || empty($info['field_trans_name'])) {
                        $is_trans = false;
                    }
                }
                if ($is_trans && isset($info['field_trans_name'])
                    && isset($info['field_value'])
                    && !empty($info['field_value'])
                    && isset($info['field_name'])
                    && !empty($info['field_name'])
                ) {
                    $current_encoding = mb_detect_encoding($info['field_value'], 'auto');
                    $info['field_value'] = iconv($current_encoding, 'UTF-8', $info['field_value']);
                    $info['field_value'] = PiStringManager::withoutaccent($info['field_value']);

                    $trans_name = $info['field_trans_name'];
                    $andModule_title = $query->expr()->andx();
                    $andModule_title->add($query->expr()->eq("LOWER({$trans_name}.locale)", "'{$locale}'"));
                    $andModule_title->add($query->expr()->eq("LOWER({$trans_name}.field)", "'".$info['field_name']."'"));
                    //$andModule_title->add($query->expr()->like("LOWER({$trans_name}.content)", $query->expr()->literal('%'.strtolower(addslashes($info['field_value'])).'%')));
                    $andModule_title->add("LOWER({$trans_name}.content) LIKE :var1".$i."");
                    $array_params["var1".$i] = '%'.strtolower($info['field_value']).'%';

                    $andModule_id = $query->expr()->andx();
                    //$andModule_id->add($query->expr()->like('LOWER(a.id)', $query->expr()->literal('%'.strtolower(addslashes($info['field_value'])).'%')));
                    $andModule_id->add("LOWER(a.id) LIKE :var2".$i."");
                    $array_params["var2".$i] = '%'.strtolower($info['field_value']).'%';

                    $orModule  = $query->expr()->orx();
                    $orModule->add($andModule_title);
                    $orModule->add($andModule_id);

                    $query->andWhere($orModule);
                } elseif (!$is_trans && isset($info['field_value'])
                    && !empty($info['field_value'])
                    && isset($info['field_name'])
                    && !empty($info['field_name'])
                ) {
                    $current_encoding = mb_detect_encoding($info['field_value'], 'auto');
                    $info['field_value'] = iconv($current_encoding, 'UTF-8', $info['field_value']);
                    $info['field_value'] = PiStringManager::withoutaccent($info['field_value']);

                    //$query->add($query->expr()->like('LOWER('.$info['field_name'].')', $query->expr()->literal('%'.strtolower(addslashes($info['field_value'])).'%')));
                    $query->add("LOWER(".$info['field_name'].") LIKE :var3".$i."");
                    $array_params["var3".$i] = '%'.strtolower($info['field_value']).'%';
                }
                $i++;
            }
            $query->setParameters($array_params);
        }
        // pagination
        if (!(null === $pagination)) {
            $query->setFirstResult((intVal($pagination)-1)*intVal($MaxResults));
            $query->setMaxResults(intVal($MaxResults));
//            $query_sql = $query->getQuery()->getSql();
//            var_dump($query_sql);
        }

        if ((null === $cacheQuery_hash)) {
            $query = $query->getQuery();
        } elseif (is_array($cacheQuery_hash)) {
            // we define all options
            if (!isset($cacheQuery_hash['time'])) $cacheQuery_hash['time'] = 3600;
            if (!isset($cacheQuery_hash['mode'])) $cacheQuery_hash['mode'] = 3; // \Doctrine\ORM\Cache::MODE_NORMAL;
            if (!isset($cacheQuery_hash['setCacheable'])) $cacheQuery_hash['setCacheable'] = true;
            if (!isset($cacheQuery_hash['input_hash'])) $cacheQuery_hash['input_hash'] = '';
            if (!isset($cacheQuery_hash['namespace'])) $cacheQuery_hash['namespace'] = '';
            // we set the query result
            $query = $this->manager->getQueryRepository()->cacheQuery(
                $query->getQuery(),
                $cacheQuery_hash['time'],
                $cacheQuery_hash['mode'],
                $cacheQuery_hash['setCacheable'],
                $cacheQuery_hash['namespace'],
                $cacheQuery_hash['input_hash']
            );
        }
        // result
        $entities = $this->manager->getQueryRepository()->findTranslationsByQuery($locale, $query, 'object', false);
        return $this->renderQuery($entities, $locale);
    }

    /**
     * @return array
     */
    protected function renderQuery($entities, $locale)
    {
        $tab = [];
        foreach ($entities as $obj) {
            $content   = $obj->translate($locale)->getTitle();
            if (!empty($content)) {
                $tab[] = [
                    'id' => $obj->getId(),
                    'text' => $this->twig->render($content, [])
                ];
            }
        }
        return $tab;
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        if (property_exists($this->param, 'locale')) {
            return $this->param->locale;
        }
        return $this->request->getLocale();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder|\Doctrine\DBAL\Query\QueryBuilder
     */
    protected function getQuery()
    {
        if (property_exists($this->param, 'query')
            && !($this->param->query instanceof \Doctrine\DBAL\Query\QueryBuilder)
            && !($this->param->query instanceof \Doctrine\ORM\QueryBuilder)
        ) {
            return $this->manager->getQueryRepository()->getAllByCategory('', null, '', '', false);
        }
        return $this->getParam('query');
    }
}
