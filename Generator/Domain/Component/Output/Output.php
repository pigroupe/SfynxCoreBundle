<?php

/*
 * (c) Jean-François Lépine <https://twitter.com/Halleck45>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CoreBundle\Generator\Domain\Component\Output;

/**
 * Class Output
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\Output
 */
interface Output
{
    /**
     * @param $message
     * @return $this
     */
    public function writeln($message);

    /**
     * @param $message
     * @return $this
     */
    public function write($message);

    /**
     * @param $message
     * @return $this
     */
    public function err($message);

    /**
     * @return $this
     */
    public function clearln();
}

