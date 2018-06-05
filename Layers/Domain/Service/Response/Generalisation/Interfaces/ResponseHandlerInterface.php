<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Response\Generalisation\Interfaces;

use Symfony\Component\HttpFoundation\Response;

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
    public function getResponse(): Response;
}