<?php
namespace Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation;

use stdClass;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\Interfaces\CommandRequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;
use Sfynx\CoreBundle\Layers\Domain\Specification\SpecIsValidRequest;

/**
 * Class AbstractRequest
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Presentation
 * @subpackage Request\Generalisation
 * @abstract
 */
abstract class AbstractFormRequest implements CommandRequestInterface
{
    /** @var array  */
    protected $defaults = [];
    /** @var array  */
    protected $required = [];
    /** @var array  */
    protected $allowedTypes = [];
    /** @var array */
    protected $allowedValues = [];
    /** @var array */
    protected $requestParameters;
    /** @var array */
    protected $options;
    /** @var OptionsResolverInterface */
    protected $resolver;
    /** @var RequestInterface */
    protected $request;
    /** @var stdClass */
    protected $object;

    /**
     * @param ResolverInterface $resolver
     */
    public function __construct(RequestInterface $request)
    {
        $this->request  = $request;
        $this->object = new stdClass();
        $this->resolver = new OptionsResolver();

        $this->execute();
    }

    /**
     * @return array
     */
    protected function getNormalizers(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getRequestParameters()
    {
        return $this->requestParameters;
    }

    /**
     * Prepare object attributs values used by class specifications
     */
    protected function prepareObject()
    {
        $this->object->requestMethod = $this->request->getMethod();
        $this->object->validMethod = $this->getValidMethods();
    }

    /**
     *
     * @return AbstractFormRequest
     */
    protected function execute(): AbstractFormRequest
    {
        // preapre object attribut used by specifications
        $this->prepareObject();
        // we abort if we are not in the edit form process
        $specs = new SpecIsValidRequest();
        if (!$specs->isSatisfiedBy($this->object)) {
            return $this;
        }
        // we run edit form process
        $this->process();

        return $this;
    }

    protected function process()
    {
        $this->setOptions();

        $mt = $this->object->requestMethod;
        foreach (['defaults', 'required', 'allowedTypes', 'allowedValues'] as $attribut) {
            $this->$attribut = isset($this->$attribut[$mt]) ? $this->$attribut[$mt] : $this->$attribut;
        }

        $this->defaults['_token'] = null;
        $this->allowedTypes['_token'] = ['string', 'null'];

        $this->resolver->setDefaults($this->defaults);
        $this->resolver->setRequired($this->required);

        foreach ($this->allowedTypes as $optionName => $optionTypes) {
            $this->resolver->setAllowedTypes($optionName, $optionTypes);
        }
        foreach ($this->allowedValues as $optionName => $optionValues) {
            $this->resolver->setAllowedValues($optionName, $optionValues);
        }
        foreach ($this->getNormalizers() as $optionName => $optionValues) {
            $this->resolver->setNormalizer($optionName, $optionValues);
        }
        $this->requestParameters = $this->resolver->resolve($this->options);
    }

    /**
     * @return array List of accepted request methods ['POST', 'GET', ...]
     */
    protected function getValidMethods(): array
    {
        return ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
    }

    /**
     * @return void
     */
    protected function setOptions()
    {
        $this->options = json_decode($this->request->getContent(), true);
        $this->options = (null !== $this->options) ? $this->options : [];
    }
}
