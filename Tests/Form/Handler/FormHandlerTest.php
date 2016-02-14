<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Core
 * @package    Test
 * @subpackage Form
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Tests\Form\Handler;

use Sfynx\CoreBundle\Tests\Form\Handler\AbstractFormHandlerTestCase;
use Phake;

/**
 * @category   Core
 * @package    Test
 * @subpackage Form
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class FormHandlerTest extends AbstractFormHandlerTestCase
{
    public function testProcessValidatesTheRequestAndForm()
    {
        $handler = Phake::partialMock(
            'Sfynx\CoreBundle\Form\Handler\AbstractFormHandler',
            $this->form,
            $this->request
        );

        $this->setMocksToValidPost();

        Phake::when($handler)->getValidMethods()
            ->thenReturn(array('POST'));

        $handler->process();

        $this->verifyHandleRequest();
        Phake::verify($handler, Phake::times(1))->onSuccess();
    }

    public function testReturnsTheOnSuccessReturnValue()
    {
        $handler = Phake::partialMock(
            'Sfynx\CoreBundle\Form\Handler\AbstractFormHandler',
            $this->form,
            $this->request
        );

        $this->setMocksToValidPost();

        Phake::when($handler)->getValidMethods()
            ->thenReturn(array('POST'));
        Phake::when($handler)->onSuccess()
            ->thenReturn(false);

        $this->assertFalse($handler->process());

        $this->verifyHandleRequest();
        Phake::verify($handler, Phake::times(1))->onSuccess();
    }

    public function testHasFormAccessorMethod()
    {
        $handler = Phake::partialMock(
            'Sfynx\CoreBundle\Form\Handler\AbstractFormHandler',
            $this->form,
            $this->request
        );

        $this->assertEquals($this->form, $handler->getForm());
    }
}
