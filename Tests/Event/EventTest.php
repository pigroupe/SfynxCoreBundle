<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Core
 * @package    Test
 * @subpackage Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Tests\Event;

use Phake;

/**
 * @category   Core
 * @package    Test
 * @subpackage Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class EventTest extends \PHPUnit_Framework_TestCase
{
    protected function createEvent($subject)
    {
        $event = Phake::mock('Symfony\Component\EventDispatcher\GenericEvent');
        Phake::when($event)->getSubject()
            ->thenReturn($subject);

        return $event;
    }
}
