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
namespace Sfynx\CoreBundle\Tests\Form\Validation;

use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * This is the base class for testing object validation.
 * 
 * @category   Core
 * @package    Test
 * @subpackage Form
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractValidationTestCase extends WebTestCase
{
   /**
     * @var Validation
     */    
    protected $validator;

    protected function getValidator()
    {
        if (!$this->validator) {
            $client = static::createClient();
            $this->validator = $client->getContainer()->get('validator');
        }

        return $this->validator;
    }
}
