<?php
namespace Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Response;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Domain\Workflow\Observer\Generalisation\Response\AbstractCreateResponseHtml;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Serializer\SerializerStrategy;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler\ResponseHandler;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ResponseException;

/**
 * Class OBCreateResponseHtml
 *
 * @category Sfynx\CoreBundle\Layers
 * @package Domain
 * @subpackage Workflow\Observer\Response
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2016 PI-GROUPE
 */
class OBCreateResponseHtml extends AbstractCreateResponseHtml
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
            $url = !property_exists($this->wfHandler, 'url') ? null : $this->wfHandler->url;
            $this->wfLastData->response = (new ResponseHandler(SerializerStrategy::create(), $this->request->setRequestFormat('html')))
                ->create($this->wfLastData->body, Response::HTTP_OK, $this->headers, $url)
                ->getResponse();
        } catch (Exception $e) {
            throw ResponseException::noCreatedResponse();
        }
        return true;
    }
}
