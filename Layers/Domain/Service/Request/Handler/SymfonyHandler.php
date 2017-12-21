<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Request\Handler;

use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\HandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Default Request strategy .
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Service\Request\Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2016-04-18
 */
class SymfonyHandler extends AbstractRequest implements HandlerInterface
{
    /** @var RequestStack */
    protected $request = null;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($asResource = false)
    {
        try {
            return $this->getCurrentRequest()->getContent($asResource);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null, $deep = false)
    {
        try {
            return $this->getCurrentRequest()->get($key, $default, $deep);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        try {
            return $this->getCurrentRequest()->getMethod();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestFormat($default = 'json')
    {
        try {
            return $this->getCurrentRequest()->getRequestFormat($default);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestFormat($format)
    {
        return $this->getCurrentRequest()->setRequestFormat($format);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType($format)
    {
        try {
            return $this->getCurrentRequest()->getMimeType($format);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isXmlHttpRequest()
    {
        try {
            return $this->getCurrentRequest()->isXmlHttpRequest();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->getCurrentRequest()->setLocale($locale);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        try {
            return $this->getCurrentRequest()->getLocale();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSession()
    {
        try {
            return $this->getCurrentRequest()->getSession();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCookies()
    {
        try {
            return $this->getCurrentRequest()->cookies;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        try {
            return $this->getCurrentRequest()->query;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        try {
            return $this->getCurrentRequest()->request;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader()
    {
        try {
            return $this->getCurrentRequest()->headers;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        try {
            return $this->getCurrentRequest()->attributes;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles()
    {
        try {
            return $this->getCurrentRequest()->files;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getServer()
    {
        try {
            return $this->getCurrentRequest()->server;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp()
    {
        try {
            return $this->getCurrentRequest()->getClientIp();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        try {
            return $this->getCurrentRequest()->getQueryString();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestUri()
    {
        try {
            return $this->getCurrentRequest()->getRequestUri();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUriForPath($path)
    {
        try {
            return $this->getCurrentRequest()->getUriForPath($path);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpHost()
    {
        try {
            return $this->getCurrentRequest()->getHttpHost();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePath()
    {
        try {
            return $this->getCurrentRequest()->getBasePath();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)
    {
        try {
            return $this->getCurrentRequest()->duplicate($query, $request, $attributes, $cookies, $files, $server);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        try {
            return $this->getCurrentRequest()->initialize($query, $request, $attributes, $cookies, $files, $server);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentRequest()
    {
        if (null !== $this->request->getCurrentRequest()) {
            return $this->request->getCurrentRequest();
        } else {
            throw new \InvalidArgumentException("The currentRequest object is null");
        }
    }
}
