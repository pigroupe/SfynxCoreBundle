<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Cookie\Generalisation;

/**
 * Interface CookieInterface
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface CookieInterface
{
    /**
     * Set from configuration the Date Expire value for cookies
     *
     * @access public
     * @return \DateTime
     */
    public function getDateExpire();

    /**
     * Sets parameter values.
     *
     * @access protected
     * @return void
     */
    public function setParams(array $option);
}
