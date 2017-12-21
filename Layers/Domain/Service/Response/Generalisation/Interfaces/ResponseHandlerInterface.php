<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Response\Generalisation\Interfaces;

use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler\ResponseHandler;

interface ResponseHandlerInterface
{
    /**
     * @param null $data
     * @param null $statusCode
     * @param array $headers
     * @param null|string $url
     * @return ResponseHandlerInterface
     */
    public function create($data = null, $statusCode = null, array $headers = [], string $url = null): ResponseHandlerInterface;


    /**
     * Get the response
     *
     * @return Response
     */
    public function getResponse();


    /**
     * @param null|string $url
     * @param int $status
     * @return ResponseHandler
     */
    public function setUrl(string $url = null, $status = Response::HTTP_FOUND): ResponseHandler;
}