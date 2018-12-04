<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Command;

use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Interfaces\CommandControllerInterface;
use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\AbstractAjaxController;
use Sfynx\CoreBundle\Layers\Presentation\Adapter\Command\DeleteajaxCommandAdapter;
use Sfynx\CoreBundle\Layers\Presentation\Request\Command\DeleteajaxRequest;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\DeleteajaxCommandHandler;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler\ResponseHandler;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Serializer\SerializerStrategy;

/**
 * Delete ajax Controller
 *
 * @subpackage Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 */
class DeletajaxController extends AbstractAjaxController implements CommandControllerInterface
{
    /**
     * {@inheritdoc}
     */
    public function coordinate()
    {
        // 0. Execute events before request
        $this->beforeRequestEvents('deleteajax');

        // 1. Transform Request to Command.
        $adapter = new DeleteajaxCommandAdapter();
        $GridCommand = $adapter->createCommandFromRequest(
            new DeleteajaxRequest($this->request)
        );

        // 2. Business work thanks to the Command.
        (new DeleteajaxCommandHandler($this->manager))->process($GridCommand);

        // 3. We disable all flash message, format using the business work and return the Response.
        $this->message->clear();

        return (new ResponseHandler(SerializerStrategy::create(), $this->request))
            ->create(static::body, Response::HTTP_CREATED)
            ->getResponse();
    }
}
