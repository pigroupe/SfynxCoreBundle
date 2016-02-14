<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Core
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @category   Core
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfynx_core');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addCacheConfig($rootNode);
        $this->addCookiesConfig($rootNode);
        $this->addTranslationConfig($rootNode);
        $this->addPermissionConfig($rootNode);

        return $treeBuilder;
    }
    
    /**
     * Admin config
     *
     * @param $rootNode ArrayNodeDefinition Class
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function addCacheConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
        ->children()
            ->arrayNode('cache_dir')
                ->addDefaultsIfNotSet()
                ->children()                
                    ->scalarNode('media')->defaultValue('%kernel.root_dir%/cachesfynx/Media/')->cannotBeEmpty()->end()
                ->end()        
            ->end()
        ->end();
    }        

    /**
     * Cookies config
     *
     * @param ArrayNodeDefinition $rootNode
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addCookiesConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
            ->arrayNode('cookies')
            ->addDefaultsIfNotSet()
               ->children()
                   ->scalarNode('application_id')->cannotBeEmpty()->end()                  
                   ->booleanNode('date_expire')->defaultValue(true)->end()
                   ->scalarNode('date_interval')->defaultValue("PT4H")->end()
                ->end()
            ->end()
    	->end();
    }    
    
    /**
     * Translation config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addTranslationConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
        	->arrayNode('translation')
            ->addDefaultsIfNotSet()
            	->children()
            	    ->scalarNode('defaultlocale_setting')->defaultValue(true)->end()
            	->end()
        	->end()
    	->end();
    }    
    
    /**
     * Permission config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addPermissionConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
	    	->arrayNode('permission')
	    	    ->addDefaultsIfNotSet()
		    	->children()
		    	
		    		->booleanNode('restriction_by_roles')->isRequired()->defaultValue(false)->end()
		    				    	
			    	->arrayNode('authorization')
				    ->isRequired()
					   	->children()
					    	->booleanNode('prepersist')->defaultValue(false)->end()
					    	->booleanNode('preupdate')->defaultValue(false)->end()
					    	->booleanNode('preremove')->defaultValue(false)->end()
				    	->end()
			    	->end()
			    	
			    	->arrayNode('prohibition')
			    	->isRequired()
				    	->children()
					    	->booleanNode('preupdate')->defaultValue(false)->end()
					    	->booleanNode('preremove')->defaultValue(false)->end()
				    	->end()
			        ->end()	
			        			    	
			    ->end()
	        ->end()
	    ->end();
    }  

}