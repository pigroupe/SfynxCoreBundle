<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation;

use stdclass;
use Symfony\Component\Validator\Validator\ValidatorInterface as LegacyValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Traits\TraitParam;
use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\AbstractFormController;
use Sfynx\CoreBundle\Layers\Application\Response\Handler\Generalisation\Interfaces\ResponseHandlerInterface;
use Sfynx\CoreBundle\Layers\Application\Command\Handler\Generalisation\Interfaces\CommandHandlerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;

/**
 * abstract Command controller.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Generalisation
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractCommandController
{
    /** @var ManagerInterface */
    protected $manager;
    /** @var RequestInterface */
    protected $request;
    /** @var LegacyValidatorInterface */
    protected $validator;
    /** @var TranslatorInterface */
    protected $translator;
    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;
    /** @var  CommandHandlerInterface */
    protected $commandHandler;
    /** @var  ResponseHandlerInterface */
    protected $responseHandler;

    use TraitParam;

    /**
     * UsersController constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     * @param LegacyValidatorInterface $validator
     * @param TranslatorInterface $translator
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        ManagerInterface $manager,
        RequestInterface $request,
        EngineInterface $templating,
        TranslatorInterface $translator
    ) {
        $this->manager = $manager;
        $this->request = $request;
        $this->validator = $validator;
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
    }
}
