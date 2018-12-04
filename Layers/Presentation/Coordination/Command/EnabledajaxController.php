<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Command;

use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Interfaces\CommandControllerInterface;
use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\AbstractAjaxController;
use Sfynx\CoreBundle\Layers\Presentation\Adapter\Command\EnabledajaxCommandAdapter;
use Sfynx\CoreBundle\Layers\Presentation\Request\Command\EnabledajaxRequest;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\EnabledajaxCommandHandler;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Handler\ResponseHandler;
use Sfynx\CoreBundle\Layers\Domain\Service\Response\Serializer\SerializerStrategy;

/**
 * Enabled ajax Controller
 *
 * @subpackage Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Command
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EnabledajaxController extends AbstractAjaxController implements CommandControllerInterface
{
    /**
     * {@inheritdoc}
     */
    public function coordinate()
    {
        // 0. Execute events before request
        $this->beforeRequestEvents('enabledajax');

        // 1. Transform Request to Command.
        $adapter = new EnabledajaxCommandAdapter();
        $GridCommand = $adapter->createCommandFromRequest(
            new EnabledajaxRequest($this->request)
        );

        // 2. Business work thanks to the Command.
        (new EnabledajaxCommandHandler($this->manager))->process($GridCommand);

        // 3. We disable all flash message, format using the business work and return the Response.
        $this->message->clear();

        return (new ResponseHandler(SerializerStrategy::create(), $this->request))
            ->create(static::body, Response::HTTP_CREATED)
            ->getResponse();
    }
}
