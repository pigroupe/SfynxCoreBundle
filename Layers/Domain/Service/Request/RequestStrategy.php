<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Request;

use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\HandlerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Handler\AbstractRequest;

/**
 * Default Request strategy .
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Service\Request
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2016-04-18
 */
class RequestStrategy extends AbstractRequest implements RequestInterface
{
    /** @var HandlerInterface */
    protected $client = null;

    public function __construct(HandlerInterface $client)
    {
        $this->client = $client;
        if(!(null === $this->client)) {
            $this->setInit();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentRequest()
    {
        return $this->client->getCurrentRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function getCookies()
    {
        return $this->client->getCookies();
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->client->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->client->getRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader()
    {
        return $this->client->getHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->client->getAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles()
    {
        return $this->client->getFiles();
    }

    /**
     * {@inheritdoc}
     */
    public function getServer()
    {
        return $this->client->getServer();
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($asResource = false)
    {
        return $this->client->getContent($asResource);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null, $deep = false)
    {
        return $this->client->get($key, $default, $deep);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return $this->client->getQueryString();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestUri()
    {
        return $this->client->getRequestUri();
    }

    /**
     * {@inheritdoc}
     */
    public function getUriForPath($path)
    {
        return $this->client->getUriForPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpHost()
    {
        return $this->client->getHttpHost();
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePath()
    {
        return $this->client->getBasePath();
    }

    /**
     * {@inheritdoc}
     */
    public function duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)
    {
        return $this->client->duplicate($query, $request, $attributes, $cookies, $files, $server);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        return $this->client->initialize($query, $request, $attributes, $cookies, $files, $server);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->client->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestFormat($default = 'json')
    {
        return $this->client->getRequestFormat($default);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestFormat($format)
    {
        $this->client->setRequestFormat($format);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType($format)
    {
        return $this->client->getMimeType($format);
    }

    /**
     * {@inheritdoc}
     */
    public function isXmlHttpRequest()
    {
        return $this->client->isXmlHttpRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->client->setLocale($locale);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->client->getLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getSession()
    {
        return $this->client->getSession();
    }

    protected function setInit()
    {
        $this->cookies = $this->client->getCookies();
        $this->query = $this->client->getQuery();
        $this->headers = $this->client->getHeader();
        $this->attributes = $this->client->getAttributes();
        $this->files = $this->client->getFiles();
        $this->server = $this->client->getServer();
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp()
    {
        return $this->client->getClientIp();
    }
}
