<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Manager
 * @package    Cookie
 * @subpackage Factory
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Layers\Domain\Service\Cookie;

use Sfynx\CoreBundle\Layers\Domain\Service\Cookie\Generalisation\CookieInterface;

/**
 *
 *
 * @category   Manager
 * @package    Cookie
 * @subpackage Factory
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class CookieFactory implements CookieInterface
{
    protected $param;

    /**
     * {@inheritdoc}
     */
    public function getDateExpire()
    {
        $dateExpire = 0;
        if ($this->param->date_expire && !empty($this->param->date_interval)) {
            $dateExpire = new \DateTime("NOW");
            if (is_numeric($this->param->date_interval)) {
                $dateExpire = time() + intVal($this->param->date_interval);
            } else {
                $dateExpire->add(new \DateInterval($this->param->date_interval));
            }
        }

        return $dateExpire;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $option)
    {
        $this->param = (object) $option;
    }
}
