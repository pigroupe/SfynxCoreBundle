<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Request\Query;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\QueryRequestInterface;

/**
 * Class IndexQueryRequest.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Request\Query
 */
class IndexQueryRequest implements QueryRequestInterface
{
    protected $defaults = [];

    protected $required = [
        'locale',
        'category',
        'noLayout',
        'sEcho',
        'isServerSide'
    ];

    protected $allowedTypes = [
        'locale' => array('string', 'null'),
        'category' => array('string', 'null'),
        'isServerSide' => array('bool', 'null'),
        'noLayout' => array('bool', 'null'),
        'sEcho' => array('int', 'null')
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
        $locale  = $this->request->getLocale();
        $NoLayout   = $this->request->getQuery()->get('NoLayout');
        $category   = $this->request->getQuery()->get('category');
        $isServerSide   = $this->request->get('isServerSide');
        $sEcho = intval($this->request->get('sEcho'));

        $this->option = [
            'locale' => $locale,
            'category' => $category,
            'noLayout' => $NoLayout,
            'isServerSide' => $isServerSide,
            'sEcho' => $sEcho
        ];

        foreach (['noLayout', 'isServerSide'] as $data) {
            if (isset($this->option[$data])) {
                $this->option[$data] = (int)$this->option[$data] ? true : false;
            }
        }
    }
}
