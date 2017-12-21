<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Request\Command;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;

/**
 * Class EnabledajaxRequest.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Request\Command
 */
class EnabledajaxRequest implements CommandRequestInterface
{
    protected $defaults = [];

    protected $required = [
        'position',
        'id'
    ];

    protected $allowedTypes = [
        'position' => array('int'),
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
        $this->setOptions();

        if(count($this->options) == 0) {
            throw new \Exception('count = 0');
        }

        foreach( $this->options as $option) {
            $this->resolver->setDefaults($this->defaults);
            $this->resolver->setRequired($this->required);
            foreach ($this->allowedTypes as $optionName => $optionTypes) {
                $this->resolver->setAllowedTypes($optionName, $optionTypes);
            }
            $this->requestParameters[] = $this->resolver->resolve($option);
        }
    }

    /**
     * @return void
     */
    protected function setOptions()
    {
        $data          = $this->request->get('data', null);
        $this->options = null;
        foreach ($data as $key => $value) {
            $values     = explode('_', $value);
            $id         = (int) $values[2];
            $position   = (int) $values[0];
            $this->options[$key] = ['position' => $position, 'id' => $id];
            $new_pos[$key]  = $position;
        }
        array_multisort($new_pos, SORT_ASC, $this->options);
        krsort($this->options);

        $this->options = (null !== $this->options) ? $this->options : [];
    }
}
