<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Request\Query;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\QueryRequestInterface;

/**
 * Class ShowQueryRequest.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Request\Query
 */
class ShowQueryRequest implements QueryRequestInterface
{
    protected $defaults = [];

    protected $required = [
        'entityId'
    ];

    protected $allowedTypes = [
        'entityId' => array('int')
    ];

    /**
     * @var array
     */
    protected $requestParameters;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var OptionsResolverInterface
     */
    protected $resolver;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param RequestInterface  $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request  = $request;
        $this->resolver = new OptionsResolver();

        $this->process($request);
    }

    /**
     * @return mixed
     */
    public function getRequestParameters()
    {
        return $this->requestParameters;
    }

    protected function process()
    {
        $this->setOption();

        $this->resolver->setDefaults($this->defaults);
        $this->resolver->setRequired($this->required);
        foreach ($this->allowedTypes as $optionName => $optionTypes) {
            $this->resolver->setAllowedTypes($optionName, $optionTypes);
        }
        $this->requestParameters = $this->resolver->resolve($this->option);
    }

    /**
     * @return void
     */
    protected function setOption()
    {
        $id = $this->request->get('id', null);
        $this->option = ['entityId' => (int)$id];
    }
}
