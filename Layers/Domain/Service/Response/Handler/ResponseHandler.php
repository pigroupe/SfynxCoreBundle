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
    /** @var string */
    protected $url;

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
     * @param null $data
     * @param null $statusCode
     * @param array $headers
     * @param null|string $url
     * @return ResponseHandlerInterface
     */
    public function create($data = null, $statusCode = null, array $headers = [], string $url = null): ResponseHandlerInterface
    {
        $this
            ->setUrl($url)
            ->setStatusCode($statusCode ?: Response::HTTP_OK)
            ->setHeaders($headers)
            ->setData($data)
        ;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl(string $url = null, $status = Response::HTTP_FOUND): ResponseHandler
    {
        $this->url = $url;
        if (null !== $url) {
            $this->setStatusCode($status);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        if (null === $this->response) {
            if (!is_null($this->url)) {
                $this->response = new RedirectResponse($this->url);
            } elseif ('json' == $this->getFormat()) {
                $this->response = new JsonResponse();
            } else {
                $this->response = new Response();
            }
        }
        return $this->response;
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
     * Sets the HTTP status code.
     *
     * @param int $code
     * @return ResponseHandler
     */
    protected function setStatusCode($code): ResponseHandler
    {
        $this->getResponse()->setStatusCode($code);
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
        if (!empty($headers)) {
            $this->getResponse()->headers->replace($headers);
        }
        return $this;
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
     * @param int $group
     */
    protected function setGroup(array $group): ResponseHandler
    {
        if (null !== $group) {
            $this->getSerializer()->getSerializationContext()->setGroups($group);
        }
        return $this;
    }

    /**
     * Sets the data.
     *
     * @param mixed $data
     * @return ResponseHandler
     */
    protected function setData($data): ResponseHandler
    {
        if (null !== $data && !is_string($data)) {
            $this->getResponse()->setContent(
                $this->getSerializer()->serialize($data, $this->getFormat())
            );
        } else {
            $this->getResponse()->setContent($data);
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
