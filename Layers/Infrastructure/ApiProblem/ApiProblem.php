<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\ApiProblem;

use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 * @link https://tools.ietf.org/html/rfc7807
 */
class ApiProblem
{
    public const TYPE_VALIDATION_ERROR = 'validation-error';
    public const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid-request-body-format';

    private static $titles = [
        self::TYPE_VALIDATION_ERROR => "Your request parameters didn't validate.",
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Your request was sent with invalid format.',
    ];

    /** @var int */
    protected $statusCode;
    /** @var null|string */
    protected $type;
    /** @var string */
    protected $title;
    /** @var array */
    protected $extensions;
    /** @var array */
    protected $headers;

    /**
     * ApiProblem constructor.
     * @param int $statusCode
     * @param null|string $type
     */
    public function __construct(int $statusCode, ?string $type = null)
    {
        if ($type === null) {
            // no type? The default is about:blank and the title should
            // be the standard status code message
            $type = 'about:blank';

            $title = isset(Response::$statusTexts[$statusCode])
                ? Response::$statusTexts[$statusCode]
                : 'Unknown status code';

        } else {
            if (!isset(self::$titles[$type])) {
                throw new \InvalidArgumentException('No title for type ' . $type);
            }
            $title = self::$titles[$type];
        }

        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->title = $title;
        $this->extensions = [];
        $this->headers = [];
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setExtraData(string $name, $value): self
    {
        $this->extensions[$name] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $problemDetails = \array_merge(
            $this->extensions,
            [
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            ]
        );

        return $problemDetails;
    }

    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param array $headers
     * @return ApiProblem
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
