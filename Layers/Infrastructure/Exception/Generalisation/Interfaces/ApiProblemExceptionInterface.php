<?php
namespace Sfynx\CoreBundle\Layers\Infrastructure\Exception\Generalisation\Interfaces;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

interface ApiProblemExceptionInterface extends HttpExceptionInterface
{
    public function getApiProblem() : ApiProblem;
}
