<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Generalisation\Interfaces\ResponseHandlerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Generalisation\Interfaces\SerializerInterface;

/**
 * ResponseHandler.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Response\Handler
 */
class ResponseHandler implements ResponseHandlerInterface
{
    /** @var RequestInterface  */
    protected $request;
    /** @var Response */
    protected $response;
    /** @var SerializerInterface */
    protected $serializer;
    /** @var string */
    protected $format;
    /** @var int */
    protected $status;
    /** @var string */
    protected $url = '';
    /** @var array */
    protected $headers;
    /** @var string */
    protected $body = '';

    const options = [
        'version' => 1,
        'groups' => ['default']
    ];

    /**
     * ResponseHandler constructor.
     *
     * @param SerializerInterface $serializer
     * @param RequestInterface $request
     */
    public function __construct(SerializerInterface $serializer, RequestInterface $request, $options = self::options)
    {
        $this->setSerializer($serializer)
            ->setRequest($request)
            ->setFormat($request->getRequestFormat())
            ->setVersion($options['version'])
            ->setGroup($options['groups']);
    }

    /**
     * @param null $body
     * @param null $status
     * @param array $headers
     * @param null|string $url
     * @return ResponseHandlerInterface
     */
    public function create($body = null, $status = Response::HTTP_OK, array $headers = [], string $url = null): ResponseHandlerInterface
    {
        $this
            ->setStatus($status)
            ->setUrl($url)
            ->setHeaders($headers)
            ->setContent($body)
        ;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): Response
    {
        if (!is_null($this->url)) {
            $this->response = new RedirectResponse($this->url);
        } elseif ('json' == $this->getFormat()) {
            $this->response = new JsonResponse();
        } else {
            $this->response = new Response();
        }

        $this->response->setStatusCode($this->status);

        if (!empty($this->headers)) {
            $this->response->headers->add($this->headers);
        }

        if (!empty($this->body)) {
            $this->response->setContent($this->body);
        }

        return $this->response;
    }

    /**
     * Sets the HTTP status code.
     *
     * @param int $status
     * @return ResponseHandler
     */
    protected function setStatus(int $status): ResponseHandler
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string|null $url
     * @return ResponseHandler
     */
    protected function setUrl(string $url = null): ResponseHandler
    {
        $this->url = $url;
        if (null !== $url) {
            $this->setStatus(Response::HTTP_FOUND);
        }
        return $this;
    }

    /**
     * Sets the headers.
     *
     * @param array $headers
     * @return ResponseHandler
     */
    protected function setHeaders(array $headers): ResponseHandler
    {
        // The $headers array must contain the "Content-Type" element.
        // Use the format of the request if not defined.
        if (!array_key_exists('Content-Type', $headers)) {
            $headers['Content-Type'] = $this->getRequest()->getMimeType($this->getFormat());
        }
        $this->headers = $headers;
        return $this;
    }

    /**
     * Sets the data.
     *
     * @param mixed $data
     * @return ResponseHandler
     */
    protected function setContent($body): ResponseHandler
    {
        $this->body = $body;
        if (null !== $body && !is_string($body)) {
            $this->body = $this->getSerializer()->serialize($body, $this->getFormat());
        }
        return $this;
    }

    /**
     * Sets the RequestInterface object.
     *
     * @param RequestInterface $request
     * @return ResponseHandler
     */
    protected function setRequest(RequestInterface $request): ResponseHandler
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Gets the RequestInterface object.
     *
     * @return RequestInterface
     */
    protected function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Gets the format.
     *
     * @return string|null
     */
    protected function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets the format.
     *
     * @param string $format
     * @return ResponseHandler
     */
    protected function setFormat($format): ResponseHandler
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @param int $version
     */
    protected function setVersion(int $version): ResponseHandler
    {
        if (!empty($version)) {
            $this->getSerializer()->getSerializationContext()->setVersion($version);
        }
        return $this;
    }

    /**
     * @param array $group
     */
    protected function setGroup(array $group): ResponseHandler
    {
        if (null !== $group) {
            $this->getSerializer()->getSerializationContext()->setGroups($group);
        }
        return $this;
    }

    /**
     * Gets the serialization
     *
     * @return SerializerInterface
     */
    protected function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param SerializerInterface $serializer
     * @return $this
     */
    protected function setSerializer(SerializerInterface $serializer): ResponseHandler
    {
        $this->serializer = $serializer;
        return $this;
    }
}
