<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as LegacyValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sfynx\ToolBundle\Util\PiStringManager;
use Sfynx\ToolBundle\Twig\Extension\PiFormExtension;

use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Traits\TraitParam;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Message\Message;
use Sfynx\CoreBundle\Layers\Infrastructure\Exception\ControllerException;

/**
 * abstract Controller.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Generalisation
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 */
abstract class AbstractQueryController
{
    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;
    /** @var ManagerInterface */
    protected $manager;
    /** @var RequestInterface */
    protected $request;
    /** @var EngineInterface */
    protected $templating;
    /** @var Message */
    protected $message;

    use TraitParam;

    /**
     * abstractController constructor
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     * @param EngineInterface $templating
     * @param PiFormExtension $formExtension
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        ManagerInterface $manager,
        RequestInterface $request,
        EngineInterface $templating,
        PiFormExtension $formExtension
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->manager = $manager;
        $this->request = $request;
        $this->templating = $templating;

        $this->message = new Message(
            $this->request->getSession()->getFlashBag(),
            $formExtension
        );
    }
}
