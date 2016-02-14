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
namespace Sfynx\CoreBundle\Tests\Form\Type;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormBuilder;

/**
 * This class adds extension validator
 * 
 * @category   Core
 * @package    Test
 * @subpackage Form
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractTypeWithValidatorExtensionTestCase extends TypeTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = Forms::createFormFactoryBuilder()->addTypeExtension(
            new FormTypeValidatorExtension(
                $this->getMock('Symfony\Component\Validator\ValidatorInterface')
            )
        )->addTypeGuesser(
            $this->getMockBuilder('Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser')
                ->disableOriginalConstructor()->getMock()
        )->getFormFactory();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }
}
