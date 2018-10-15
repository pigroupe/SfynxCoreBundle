<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Response\Api;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ResponseException;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\Api\AbstractCreateResponse;

/**
 * Class OBCreatedStatusResponseJson
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Response\Api
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBCreatedStatusResponseJson extends AbstractCreateResponse
{
    /**
     * The process function ...
     *
     * @return bool False to notify that postprocessing could not be executed.
     * @throws ResponseException
     */
    public function process(): bool
    {
        try {
            // https://restfulapi.net/http-methods/
            switch ($this->object->requestMethod) {
                case 'POST':
                    $status = Response::HTTP_CREATED;
                    break;
                case 'PUT':
                    $status = Response::HTTP_OK;
                    break;
                case 'PATCH':
                    $status = Response::HTTP_OK;
                    break;
                case 'DELETE':
                    $status = Response::HTTP_OK;
                    break;
                case 'GET':
                    $status = Response::HTTP_OK;
                    break;
            }

            $this->wfLastData->response->setStatusCode($status);
        } catch (Exception $e) {
            throw ResponseException::noCreatedResponse();
        }

        return true;
    }
}
