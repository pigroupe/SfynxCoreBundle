<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Request\Command;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;

/**
 * Class PositionajaxRequest.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Request\Command
 */
class PositionajaxRequest implements CommandRequestInterface
{
    protected $defaults = [];

    protected $required = [
        'toPosition',
        'id'
    ];

    protected $allowedTypes = [
        'toPosition' => array('int'),
        'id' => array('int')
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
        $new_position = $this->request->get('toPosition', null);
        $data         = $this->request->get('id', null);
        $values       = explode('_', $data);
        $id           = $values[2];

        $this->option = ['toPosition' => $new_position, 'id' => $id];
    }
}
