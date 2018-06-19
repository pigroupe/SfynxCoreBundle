<?php
namespace Sfynx\CoreBundle\Layers\Domain\Component\Generalisation;

use \stdClass;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sfynx\CoreBundle\Layers\Domain\Component\Generalisation\Interfaces\ResolverInterface;

/**
 * Class AbstractResolver
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Component\Generalisation
 * @abstract
 */
abstract class AbstractResolver implements ResolverInterface
{
    /** @var OptionsResolverInterface */
    protected $resolver;
    /** @var array|stdClass */
    protected $resolverParameters;
    /** @var array */
    protected $parameters;
    /** @var array */
    protected $defaults = [];
    /** @var array */
    protected $required = [];
    /** @var array */
    protected $allowedTypes = [];
    /** @var array */
    protected $allowedValues = [];

    /**
     * AbstractResolver constructor.
     * @param array $parameters
     * @param OptionsResolverInterface|null $OptionsResolver
     */
    public function __construct(array $parameters = [], OptionsResolverInterface $OptionsResolver = null)
    {
        $this->parameters = $parameters;
        $this->resolver = $OptionsResolver;
        if (null === $OptionsResolver) {
            $this->resolver = new OptionsResolver();
        }

        $this->process();
    }

    /**
     * @param bool $toStdClass
     * @param array $options
     * @return array|stdClass
     */
    public function getResolverParameters(bool $toStdClass = false, array $options = [])
    {
        $this->transformParameters($options);

        if ($toStdClass) {
            return json_decode(json_encode($this->resolverParameters), false);
        }
        return $this->resolverParameters;
    }

    /**
     * @return array
     */
    protected function getNormalizers(): array
    {
        return [];
    }

    /**
     * @return void
     */
    protected function process(): void
    {
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
        $this->resolverParameters = $this->resolver->resolve($this->parameters);
    }

    /**
     * @param array $options
     * @return void
     */
    protected function transformParameters(array $options = []): void {}
}
