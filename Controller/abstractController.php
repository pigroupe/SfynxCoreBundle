<?php
/**
 * This file is part of the <Core> project.
 *
 * @subpackage Core
 * @package    Controller
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;

use Sfynx\ToolBundle\Exception\ControllerException;
use Sfynx\ToolBundle\Util\PiStringManager;

/**
 * abstract controller.
 *
 * @subpackage Core
 * @package    Controller
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class abstractController extends Controller
{
    /**
     * Enabled entities.
     *
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
        // csrf control
        $this->checkCsrf('grid-action');
        //
        $request = $this->container->get('request');
        $em      = $this->getDoctrine()->getManager();        
        if ($request->isXmlHttpRequest()) {
            $data        = $request->get('data', null);
            $new_data    = null;                                   
            foreach ($data as $key => $value) {
                $values     = explode('_', $value);
                $id         = $values[2];
                $position   = $values[0];  
                $new_data[$key] = array('position'=>$position, 'id'=>$id);
                $new_pos[$key]  = $position;
            }
            array_multisort($new_pos, SORT_ASC, $new_data);
            krsort($new_data);
            foreach ($new_data as $key => $value) {
                $entity = $em->getRepository($this->_entityName)->find($value['id']);
                if (method_exists($entity, 'setArchived')) {
                    $entity->setArchived(false);
                }
                if (method_exists($entity, 'setEnabled')) {
                    $entity->setEnabled(true);
                }
                if (method_exists($entity, 'setArchiveAt')) {
                    $entity->setArchiveAt(null);
                }
                if (method_exists($entity, 'setPosition')) {
                    $entity->setPosition(1);
                }
                $em->persist($entity);
                $em->flush();
            }
            $em->clear();
            // we disable all flash message
            $this->container->get('session')->getFlashBag()->clear();            
            $tab= array();
            $tab['id'] = '-1';
            $tab['error'] = '';
            $tab['fieldErrors'] = '';
            $tab['data'] = '';             
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;            
        } else {
            throw ControllerException::callAjaxOnlySupported('enabledajax');
        } 
    }

    /**
     * Disable entities.
     * 
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
        // csrf control
        $this->checkCsrf('grid-action');
        //
        $request = $this->container->get('request');
        $em      = $this->getDoctrine()->getManager();        
        if ($request->isXmlHttpRequest()) {
            $data        = $request->get('data', null);
            $new_data    = null;
            foreach ($data as $key => $value) {
                $values     = explode('_', $value);
                $id         = $values[2];
                $position   = $values[0];    
                $new_data[$key] = array('position'=>$position, 'id'=>$id);
                $new_pos[$key]  = $position;
            }
            array_multisort($new_pos, SORT_ASC, $new_data);
            foreach ($new_data as $key => $value) {
                $entity = $em->getRepository($this->_entityName)->find($value['id']);
                if (method_exists($entity, 'setEnabled')) {
                    $entity->setEnabled(false);
                }
                if (method_exists($entity, 'setPosition')) {
                    $entity->setPosition(null);
                }
                $em->persist($entity);
                $em->flush();
            }
            $em->clear();
            // we disable all flash message
            $this->container->get('session')->getFlashBag()->clear();
            // we encode results            
            $tab= array();
            $tab['id'] = '-1';
            $tab['error'] = '';
            $tab['fieldErrors'] = '';
            $tab['data'] = '';
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;            
        } else {
            throw ControllerException::callAjaxOnlySupported('disableajax');
        } 
    } 
    
    /**
     * Deletes a entity.
     *
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deletajaxAction()
    {
        // csrf control
        $this->checkCsrf('grid-action');
        //
        $request = $this->container->get('request');
        $em      = $this->getDoctrine()->getManager();         
        if ($request->isXmlHttpRequest()) {
            $data        = $request->get('data', null);
            $new_data    = null;
            foreach ($data as $key => $value) {
                $values     = explode('_', $value);
                $id         = $values[2];
                $position   = $values[0];    
                $new_data[$key] = array('position'=>$position, 'id'=>$id);
                $new_pos[$key]  = $position;
            }
            array_multisort($new_pos, SORT_ASC, $new_data);
            foreach ($new_data as $key => $value) {
                $entity = $em->getRepository($this->_entityName)->find($value['id']);
                $em->remove($entity);
                $em->flush();
            }
            $em->clear();
            // we disable all flash message
            $this->container->get('session')->getFlashBag()->clear();
            // we encode results            
            $tab= array();
            $tab['id'] = '-1';
            $tab['error'] = '';
            $tab['fieldErrors'] = '';
            $tab['data'] = '';
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        } else {
            throw ControllerException::callAjaxOnlySupported('deleteajax');
        }
    }    
    
    /**
     * Archive entities.
     *
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function archiveajaxAction()
    {
        // csrf control
        $this->checkCsrf('grid-action');
        //
        $request = $this->container->get('request');
        $em      = $this->getDoctrine()->getManager();         
        if ($request->isXmlHttpRequest()) {
            $data        = $request->get('data', null);
            $new_data    = null;
            foreach ($data as $key => $value) {
                $values     = explode('_', $value);
                $id         = $values[2];
                $position   = $values[0];
                $new_data[$key] = array('position'=>$position, 'id'=>$id);
                $new_pos[$key]  = $position;
            }
            array_multisort($new_pos, SORT_ASC, $new_data);
            foreach ($new_data as $key => $value) {
                $entity = $em->getRepository($this->_entityName)->find($value['id']);
                if (method_exists($entity, 'setArchived')) {
                    $entity->setArchived(true);
                }
                if (method_exists($entity, 'setEnabled')) {
                    $entity->setEnabled(false);
                }
                if (method_exists($entity, 'setArchiveAt')) {
                    $entity->setArchiveAt(new \DateTime());
                }                 
                if (method_exists($entity, 'setPosition')) {
                    $entity->setPosition(null);
                }                                
                $em->persist($entity);
                $em->flush();
            }
            $em->clear();
            // we disable all flash message
            $this->container->get('session')->getFlashBag()->clear();
            // we encode results    
            $tab= array();
            $tab['id'] = '-1';
            $tab['error'] = '';
            $tab['fieldErrors'] = '';
            $tab['data'] = '';
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        } else {
            throw ControllerException::callAjaxOnlySupported('disableajax');
        }
    }    

    /**
     * Change the posistion of a entity .
     *
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function positionajaxAction()
    {
        // csrf control
        $this->checkCsrf('grid-action');
        //
        $request = $this->container->get('request');
        $em      = $this->getDoctrine()->getManager();         
        if ($request->isXmlHttpRequest()) {
            $old_position     = $request->get('fromPosition', null);
            $new_position     = $request->get('toPosition', null);
            $direction        = $request->get('direction', null);
            $data             = $request->get('id', null);
            $values           = explode('_', $data);
            $id               = $values[2];
            if (!is_null($id)){
                if ( ($new_position == "NaN") || is_null($new_position) || empty($new_position) )    $new_position     = 1;
                $entity = $em->getRepository($this->_entityName)->find($id);
                if (method_exists($entity, 'setPosition')) {
                	$entity->setPosition($new_position);
                }
                $em->persist($entity);
                $em->flush();
                $em->clear();    
            }        
            // we disable all flash message
            $this->container->get('session')->getFlashBag()->clear();
            // we encode results    
            $tab= array();
            $tab['id'] = '-1';
            $tab['error'] = '';
            $tab['fieldErrors'] = '';
            $tab['data'] = '';
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        } else {
            throw ControllerException::callAjaxOnlySupported('positionajax');
        }
    } 
    
    /**
     * get entities in ajax request for select form.
     *
     * @return Response
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function selectajaxQuery($pagination, $MaxResults, $keywords = null, $query = null, $locale = '', $only_enabled  = true, $cacheQuery_hash = null)
    {
    	$request = $this->container->get('request');
    	$em      = $this->getDoctrine()->getManager();
    	//
    	if (empty($locale)) {
            $locale = $this->container->get('request')->getLocale();
    	}
    	//
    	if ($request->isXmlHttpRequest()) {
            if ( !($query instanceof \Doctrine\DBAL\Query\QueryBuilder) && !($query instanceof \Doctrine\ORM\QueryBuilder)) {
                $query    = $em->getRepository($this->_entityName)
                        ->getAllByCategory('', null, '', '', false);
            }
            if ($only_enabled) {
                $query    			
                ->andWhere('a.enabled = 1');
            }
            // groupe by
            $query->groupBy('a.id');
            // autocompletion
            $array_params = array();
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
                    if ($is_trans && isset($info['field_trans_name']) && isset($info['field_value']) && !empty($info['field_value']) && isset($info['field_name']) && !empty($info['field_name'])) {
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
                    } elseif (!$is_trans && isset($info['field_value']) && !empty($info['field_value']) && isset($info['field_name']) && !empty($info['field_name'])) {
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
            if (!is_null($pagination)) {
                $query->setFirstResult((intVal($pagination)-1)*intVal($MaxResults));
                $query->setMaxResults(intVal($MaxResults));
                //$query_sql = $query->getQuery()->getSql();
                //var_dump($query_sql);
            }
            //
            if (is_null($cacheQuery_hash)) {
                    $query = $query->getQuery();
            } elseif (is_array($cacheQuery_hash)) {
                // we define all options
                if (!isset($cacheQuery_hash['time'])) $cacheQuery_hash['time'] = 3600;
                if (!isset($cacheQuery_hash['mode'])) $cacheQuery_hash['mode'] = 3; // \Doctrine\ORM\Cache::MODE_NORMAL;
                if (!isset($cacheQuery_hash['setCacheable'])) $cacheQuery_hash['setCacheable'] = true;
                if (!isset($cacheQuery_hash['input_hash'])) $cacheQuery_hash['input_hash'] = '';
                if (!isset($cacheQuery_hash['namespace'])) $cacheQuery_hash['namespace'] = '';
                // we set the query result
                $query = $em->getRepository($this->_entityName)->cacheQuery(
                    $query->getQuery(), 
                    $cacheQuery_hash['time'], 
                    $cacheQuery_hash['mode'], 
                    $cacheQuery_hash['setCacheable'], 
                    $cacheQuery_hash['namespace'], 
                    $cacheQuery_hash['input_hash']
                );
            }    		
            // result
            $entities = $em->getRepository($this->_entityName)
                    ->findTranslationsByQuery($locale, $query, 'object', false);
            $tab      = $this->renderselectajaxQuery($entities, $locale);
            // response
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');

            return $response;    		 	
    	} else {
            throw ControllerException::callAjaxOnlySupported(' selectajax');
    	}    	
    }   
    
    /**
     * Select all entities.
     *
     * @return Response
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function renderselectajaxQuery($entities, $locale)
    {
    	$tab = array();
    	foreach ($entities as $obj) {
            $content   = $obj->translate($locale)->getTitle();
            if (!empty($content)) {
                $tab[] = array(
                    'id' => $obj->getId(),
                    'text' =>$this->container->get('twig')->render($content, array())
                );
            }
    	}
    	
    	return $tab;
    }    
    
    /**
     * Create Ajax query
     *
     * @param string $type            ["select","count"]
     * @param string $table
     * @param string $aColumns
     * @param string $table
     * @param array  $dateSearch
     * @param array  $cacheQuery_hash
     * 
     * @return array
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAjaxQuery($type, $aColumns, $qb = null, $tablecode = 'u', $table = null, $dateSearch = null, $cacheQuery_hash = null)
    {
        $request = $this->container->get('request');
        $locale = $this->container->get('request')->getLocale();
        $em     = $this->getDoctrine()->getManager();
        
        if (is_null($qb)) {
            $qb     = $em->createQueryBuilder();
            if ($type == 'select') {
                $qb->add('select', $tablecode);
            } elseif($type == "count") {
                $qb->add('select', $tablecode.'.id');
            } else {
                throw ControllerException::NotFoundOption('type');
            }
            if (isset($this->_entityName) && !empty($this->_entityName)) {
                $qb->add('from', $this->_entityName.' '.$tablecode);
            } elseif (!is_null($table)) {
                $qb->add('from', $table.' '.$tablecode);
            } else {
                throw ControllerException::NotFoundOption('table');
            }
        } elseif($type == "count") {
            $qb->add('select', $tablecode.'.id');
        }
        
        /**
         * Date
         */    
        if (!is_null($dateSearch) && is_array($dateSearch)) {
            foreach ($dateSearch as $k => $columnSearch) {
                $idMin = "date-{$columnSearch['idMin']}";
                $idMax = "date-{$columnSearch['idMax']}";
                if ( $request->get($idMin) != '' ) {
                    $date = \DateTime::createFromFormat($columnSearch['format'], $request->get($idMin));
                    $dateMin = $date->format('Y-m-d 00:00:00');
                    //$dateMin = $this->container->get('sfynx.tool.date_manager')->format($date->getTimestamp(), 'long','medium', $locale, "yyyy-MM-dd 00:00:00");
                    $qb->andWhere("{$columnSearch['column']} >= '" . $dateMin . "'");
                }
                if ( $request->get($idMax) != '') {
                    $date = \DateTime::createFromFormat($columnSearch['format'], $request->get($idMax));
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
        $array_params = array();
        $and = $qb->expr()->andx();
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if ( $request->get('bSearchable_'.$i) == "true" && $request->get('sSearch_'.$i) != '' ) {
                $search_tab = explode("|", $request->get('sSearch_'.$i));
                $or = $qb->expr()->orx();
                foreach ($search_tab as $s) {
                    $or->add("LOWER(".$aColumns[(intval($i)-1)].") LIKE :var".$i."");
                    //
                    $current_encoding = mb_detect_encoding($s, 'auto');
                    $s = iconv($current_encoding, 'UTF-8', $s);
                    $s = PiStringManager::withoutaccent($s);
                    //
                    $array_params["var".$i] = '%'.strtolower($s).'%';
                }
                $and->add($or);
            }
        }
        if ($and!= "") {
        	$qb->andWhere($and); 
        }        
        
        $or = $qb->expr()->orx();
        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if ( $request->get('bSearchable_'.$i) == "true" && $request->get('sSearch') != '' ) {
                $search_tab = explode("|", $request->get('sSearch'));
                foreach ($search_tab as $s) {
                    if(!empty($s)){
                        $or->add("LOWER(".$aColumns[$i].") LIKE :var2".$i."");
                        //
                        $current_encoding = mb_detect_encoding($s, 'auto');
                        $s = iconv($current_encoding, 'UTF-8', $s);
                        $s = PiStringManager::withoutaccent($s);
                        //
                        $array_params["var2".$i] = '%'.strtolower($s).'%';
                    }
                }
            }
        }
        if ($or!= "") {
        	$qb->andWhere($or);
        }
        
        /**
         * Grouping
         */        
        $qb->groupBy($tablecode.'.id');
            
        /**
         * Ordering
          */
        $iSortingCols = $request->get('iSortingCols', '');
        if ( !empty($iSortingCols) ) {
            for ( $i=0 ; $i<intval($request->get('iSortingCols') ) ; $i++ ) {
                $iSortCol_ = $request->get('iSortCol_'.$i, '');
                $iSortCol_col = (intval($iSortCol_)-1);
                if (!empty($iSortCol_) && ( $request->get('bSortable_'.intval($iSortCol_) ) == "true" ) && isset($aColumns[ $iSortCol_col ])) {
                    $column = $aColumns[ $iSortCol_col ];
                    $sort = $request->get('sSortDir_'.$i)==='asc' ? 'ASC' : 'DESC';
                    $qb->addOrderBy($column, $sort);
                }
            }
        }
        
        /**
         * Paging 
         */
        if ($type == 'select') {
            $iDisplayStart = $request->get('iDisplayStart', 0);
            $iDisplayLength = $request->get('iDisplayLength', 25);
            $qb->setFirstResult($iDisplayStart);
            $qb->setMaxResults($iDisplayLength);
        }
        $qb->setParameters($array_params);
        //$query_sql = $qb->getQuery()->getSql();
        //var_dump($query_sql);
        //exit;
        if (is_null($cacheQuery_hash)) {
            $qb = $qb->getQuery();
        } elseif (is_array($cacheQuery_hash)) {
            // we define all options
            if (!isset($cacheQuery_hash['time'])) $cacheQuery_hash['time'] = 3600;
            if (!isset($cacheQuery_hash['mode'])) $cacheQuery_hash['mode'] = 3; // \Doctrine\ORM\Cache::MODE_NORMAL;
            if (!isset($cacheQuery_hash['setCacheable'])) $cacheQuery_hash['setCacheable'] = true;
            if (!isset($cacheQuery_hash['input_hash'])) $cacheQuery_hash['input_hash'] = '';
            if (!isset($cacheQuery_hash['namespace'])) $cacheQuery_hash['namespace'] = '';
            // we set the query result
            $qb     = $em->getRepository($this->_entityName)->cacheQuery(
                $qb->getQuery(), 
                $cacheQuery_hash['time'], 
                $cacheQuery_hash['mode'], 
                $cacheQuery_hash['setCacheable'],
                $cacheQuery_hash['namespace'], 
                $cacheQuery_hash['input_hash']
            );
        }
        $result = $em->getRepository($this->_entityName)
                ->setTranslatableHints($qb, $locale, false, true)->getResult();
        if ($type == 'count') {
            $result = count($result);
        } 
        
        return $result;
    }

    /**
     * Delete the query cache of a id hash.
     *
     * @param string $input_hash
     * 
     * @return array
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteCacheQuery($input_hash)
    {
    	$em = $this->getDoctrine()->getManager();
    	$cacheDriver = $em->getConfiguration()->getResultCacheImpl();
    	$cacheDriver->delete($input_hash);
    }    
    
    /**
     * Delete all query cache ids of a namespace.
     *
     * @param string $namespace
     * 
     * @return array
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAllCacheQuery($namespace = '')
    {
    	$em = $this->getDoctrine()->getManager();
    	$cacheDriver = $em->getConfiguration()->getResultCacheImpl();
    	$cacheDriver->setNamespace($namespace);
    	$cacheDriver->deleteAll();
    }    

    /**
     * Check the validity of a token.
     *
     * <code>
     * in twig
     *     <a href="{{ path('admin_word', { 'NoLayout': NoLayout,  '_token': csrf_token('listword')  }) }}" class="button-ui-back-list">{{ 'pi.grid.action.back-to-the-list'|trans }}</a>
     * in Controller action with admin_word routename    
     *     $this->checkCsrf('listword'); // name of the generated token, must be equal to the one from Twig
     * </code>
     * 
     * @param string $name
     * @param string $query
     * 
     * @return array The list of all the errors
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function checkCsrf($name, $query = '_token')
    {
    	$request      = $this->getRequest();
    	$csrfProvider = $this->get('form.csrf_provider');
    	if (!$csrfProvider->isCsrfTokenValid($name, $request->query->get($query))) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('CSRF token is invalid.');
    	}
    
    	return true;
    }    

    /**
     * Get all error messages after binding form.
     *
     * @param Form   $form
     * @param string $type
     * @param string $delimiter
     * 	
     * @return array The list of all the errors
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function getErrorMessages(Form $form, $type = 'array', $delimiter = "<br />")
    {
        return $this->container('sfynx.tool.twig.extension.form')->getFormErrors($form, $type, $delimiter);
    }
    
    /**
     * Set all error messages in flash.
     *
     * @param Form $form
     * 
     * @return array The list of all the errors
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setFlashErrorMessages(Form $form)
    {
    	return $this->container->get('request')
                ->getSession()
                ->getFlashBag()
                ->add('errorform', $this->getErrorMessages($form, 'string' ));
    }    
    
    /**
     * Set all messages in flash.
     *
     * @param string $messages
     * @param string $param
     * 
     * @return array	The list of all the errors
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setFlashMessages($messages, $param = 'notice')
    {
    	return $this->container->get('request')
                ->getSession()
                ->getFlashBag()
                ->add($param, $messages);
    }    
    
    /**
     * Put result content in cache with ttl.
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getCacheFunction($key, $ttl)
    {
        $dossier = $this->container->getParameter("kernel.root_dir")."/cache/widget/";
    	\Sfynx\ToolBundle\Util\PiFileManager::mkdirr($dossier, 0777);
    	$this->container->get("sfynx.cache.filecache")->getClient()->setPath($dossier);
    	
        return $this->container->get("sfynx.cache.filecache")->get($key); 
    } 
    
    /**
     * Put result content in cache with ttl.
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setCacheFunction($key, $ttl, $newvalue)
    {
        $dossier = $this->container->getParameter("kernel.root_dir")."/cache/widget/";
    	\Sfynx\ToolBundle\Util\PiFileManager::mkdirr($dossier, 0777);
        $this->container->get("sfynx.cache.filecache")->getClient()->setPath($dossier);
        // important : if ttl is equal to zero then the cache is infini
        $this->container->get("sfynx.cache.filecache")->set($key, $newvalue, $ttl);
    }    
}
