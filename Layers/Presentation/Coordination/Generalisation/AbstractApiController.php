<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Sfynx\CoreBundle\Layers\Presentation\Coordination\Generalisation\Traits\TraitParam;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;

/**
 * abstract Api Controller.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Coordination\Generalisation
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 */
abstract class AbstractApiController
{
    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;
    /** @var ManagerInterface */
    protected $manager;
    /** @var RequestInterface */
    protected $request;

    use TraitParam;

    /**
     * abstractController constructor
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ManagerInterface $manager
     * @param RequestInterface $request
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        ManagerInterface $manager,
        RequestInterface $request,
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->manager = $manager;
        $this->request = $request;
    }
}
