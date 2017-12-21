<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Response;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractCreateResponseJson;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Serializer\SerializerStrategy;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler\ResponseHandler;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ResponseException;

/**
 * Class OBCreateResponseJson
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Response
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBCreateResponseJson extends AbstractCreateResponseJson
{
    /**
     * The process function ...
     *
     * @return bool False to notify that postprocessing could not be executed.
     * @throws ResponseException
     */
    public function process(): bool
    {
        $return = true;
        try {
            $this->wfLastData->response = (new ResponseHandler(SerializerStrategy::create(), $this->request->setRequestFormat('json')))
                ->create($this->wfLastData->rows, Response::HTTP_OK, $this->headers)
                ->getResponse();
        } catch (Exception $e) {
            throw ResponseException::noCreatedResponse();
        }
        return $return;
    }
}
