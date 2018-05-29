<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

use Sfynx\ToolBundle\Util\PiStringManager;
use Sfynx\ToolBundle\Twig\Extension\PiFormExtension;

use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Traits\TraitParam;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Message\Message;

/**
 * abstract Ajax controller.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Generalisation
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractAjaxController
{
    const body = [
        'id' => '-1',
        'error' => '',
        'fieldErrors' => '',
        'data' => ''
    ];

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;
    /** @var ManagerInterface $manager */
    protected $manager;
    /** @var RequestInterface */
    protected $request;
    /** @var CsrfTokenManagerInterface $securityManager */
    protected $securityManager;
    /** @var PiFormExtension $formExtension */
    protected $formExtension;
    /** @var Message */
    protected $message;

    use TraitParam;

    /**
     * abstractController constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     * @param CsrfTokenManagerInterface $securityManager
     * @param PiFormExtension $formExtension
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        ManagerInterface $manager,
        RequestInterface $request,
        CsrfTokenManagerInterface $securityManager,
        PiFormExtension $formExtension
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->manager = $manager;
        $this->request = $request;
        $this->securityManager = $securityManager;

        $this->message = new Message(
            $this->request->getSession()->getFlashBag(),
            $formExtension
        );
    }

    /**
     * execute events before request
     *
     * @param $action
     * @return boolean
     * @throw ControllerException
     */
    protected function beforeRequestEvents($action)
    {
        // test that the request is an ajax request
        $this->isXmlHttpRequest($action);
        // csrf control
        $this->checkCsrf('grid-action');
        // test if manager is defined and return false
        if (null !== $this->manager) {
            return false;
        }
        throw ControllerException::NotFoundArg('menager');
    }

    /**
     * Return exception if the request is not an ajax it
     *
     * @param $method
     * @return bool
     * @throws \Exception
     */
    protected function isXmlHttpRequest($method)
    {
        if ($this->request->isXmlHttpRequest()) {
            return true;
        }
        throw ControllerException::callAjaxOnlySupported($method);
    }

    /**
     * Check the validity of a token.
     *
     * <code>
     * in twig
     *     <a href="{{ path('admin_word', { 'NoLayout': NoLayout,  '_token': csrf_token('listword')  }) }}" class="button-ui-back-list">{{ 'pi.grid.action.back-to-the-list'|trans }}</a>
     * in Controller action with admin_word routename
     *     $this->checkCsrf('listword'); // name of the generated token, must be equal to the one from Twig
     * </code>
     *
     * @param string $name
     *
     * @return array The list of all the errors
     * @access protected
     */
    protected function checkCsrf($name)
    {
    	if (!$this->securityManager->isTokenValid($this->securityManager->getToken($name))) {
            throw new AccessDeniedHttpException('CSRF token is invalid.');
    	}
    	return true;
    }
}
