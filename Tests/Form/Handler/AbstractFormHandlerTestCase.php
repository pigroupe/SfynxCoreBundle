<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Core
 * @package    Test
 * @subpackage Form
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Tests\Form\Handler;

use \Phake;

/**
 * This is a base test case for all FormHandlers.
 * 
 * @category   Core
 * @package    Test
 * @subpackage Form
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractFormHandlerTestCase extends \PHPUnit_Framework_TestCase
{
    protected $form;
    protected $request;
    protected $userManager;
    protected $eventDispatcher;

    protected function setUp()
    {
        $this->form = Phake::mock('Symfony\Component\Form\Form');
        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        $this->userManager = Phake::mock('FOS\UserBundle\Model\UserManagerInterface');
        $this->eventDispatcher = Phake::mock(
            'Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher'
        );
    }

    protected function setMocksToValidPost()
    {
        Phake::when($this->request)->getMethod()->thenReturn('POST');
        Phake::when($this->form)->isValid()->thenReturn(true);
    }

    protected function setMocksToInvalidPost()
    {
        Phake::when($this->request)->getMethod()->thenReturn('POST');
        Phake::when($this->form)->isValid()->thenReturn(false);
    }

    protected function verifyBind()
    {
        Phake::verify($this->request, Phake::times(1))->getMethod();
        Phake::verify($this->form, Phake::times(1))->bind(Phake::anyParameters());
        Phake::verify($this->form, Phake::times(1))->isValid();
    }

    protected function verifyNoBindOnGet()
    {
        Phake::verify($this->request)->getMethod();
        Phake::verify($this->form, Phake::times(0))->bind();
    }
    
    protected function verifyHandleRequest()
    {
        Phake::verify($this->request, Phake::times(1))->getMethod();
        Phake::verify($this->form, Phake::times(1))->handleRequest(Phake::anyParameters());
        Phake::verify($this->form, Phake::times(1))->isValid();
    }        
}
