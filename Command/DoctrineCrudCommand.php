<?php
/**
 * This file is part of the <Core> project.
 *
 * @subpackage Core
 * @package    Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-03-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Command;

use Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;
use Sensio\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand as BaseGenerator;

/**
 * Command CRUD.
 *
 * <code>
 *    php app/console sfynx:generate:crud
 * </code>
 * 
 * @subpackage Core
 * @package    Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DoctrineCrudCommand extends BaseGenerator
{
    /**
     * @var \Sfynx\ToolBundle\Util\PiLogManager
     */
    private $_logger;
    
    /**
     * Constructor.
     *
     * @param HttpKernelInterface $kernel A HttpKernelInterface instance
     * 
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct($kernel = null)
    {
        parent::__construct();    
        //-----we initialize the container-----
        if (is_object($kernel) && method_exists($kernel, 'getContainer')) {
            $this->setContainer($kernel->getContainer());
        }
    }

    /**
     * configure the command.
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function configure()
    {
        parent::configure();
        $this->setName('sfynx:generate:crud');
    }

    /**
     * configure the command.
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */     
    protected function getGenerator($bundle = null)
    {
        //-----we initialize the logger-----
        $this->_logger    = $this->getContainer()->get('sfynx.tool.log_manager');
        $this->_logger->setPath($this->getContainer()->getParameter("kernel.logs_dir"));
        $this->_logger->setInit('log_corebundle_crud', date("YmdH"));
        $this->_logger->setInfo(date("Y-m-d H:i:s")." [LOG CRUD] Begin launch  :");
        
        $generator_crud = new DoctrineCrudGenerator($this->getContainer()->get('filesystem'), __DIR__.'/../Resources/views/skeleton/crud');
        $this->setGenerator($generator_crud);
        
        $generator_form = new DoctrineFormGenerator($this->getContainer()->get('filesystem'), __DIR__.'/../Resources/views/skeleton/form');
        $this->setFormGenerator($generator_form);

        //-----we close the logger-----
        $this->_logger->setInfo(date("Y-m-d H:i:s")." [END] End launch");
        $this->_logger->save();         
        
        return parent::getGenerator();
    }
}
