<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\Generalisation\Interfaces\ApiProblemExceptionInterface;

/**
 * Exception Class ApplicationException
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Infrastructure
 * @subpackage Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * <code>
 *
 *      $data = json_decode($request->getContent(), true);
 *      if ($data === null) {
 *          $apiProblem = new ApiProblem(Response::HTTP_BAD_REQUEST, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);
 *          throw new ApiProblemException($apiProblem);
 *      }
 *
 * </code>
 */
class ApiProblemException extends HttpException implements ApiProblemExceptionInterface
{
    /** @var ApiProblem */
    private $apiProblem;

    /**
     * ApiProblemException constructor.
     * @param ApiProblem $apiProblem
     * @param \Exception|null $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct(ApiProblem $apiProblem, \Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->apiProblem = $apiProblem;

        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * @return ApiProblem
     */
    public function getApiProblem() : ApiProblem
    {
        return $this->apiProblem;
    }
}
