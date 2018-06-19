<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Validation\Type\Extension;

use \stdClass;
use Symfony\Component\Form\Extension\Core\Type as TypeCore;
use Symfony\Bridge\Doctrine\Form\Type as TypeForm;

use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\ExtensionInterface;
use Sfynx\CoreBundle\Layers\Domain\Component\Generalisation\AbstractResolver;

/**
 * EntityTypeExtension class
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Application\Validation\Type\Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EntityTypeExtension extends AbstractResolver implements ExtensionInterface
{
    /**
     * @var array $defaults List of default values for optional parameters.
     */
    protected $defaults = [
        'name' => '',
        'type' => '',
        'group_by' => null,
        'choices' => null,
        'choices_as_values' => true,
        'choice_name' => '',
        'choice_label' => '',
        'choice_value' => 'id',
        'label' => '',
        'expanded' => false,
        'multiple' => false,
        'required' => true,
    ];

    /**
     * @var string[] $required List of required parameters for each methods.
     */
    protected $required = [];

    /**
     * @var array[] $allowedTypes List of allowed types for each methods.
     */
    protected $allowedTypes = [
        'name' => ['string', 'null'],
        'type' => ['string', 'null'],
        'group_by' => ['string', 'null'],
        'choices' => ['array', 'null'],
        'choices_as_values' => ['bool', 'null'],
        'choice_label' => ['string', 'null'],
        'choice_name' => ['string', 'null'],
        'choice_value' => ['string', 'null'],
        'label' => ['string', 'null'],
        'expanded' => ['bool', 'null'],
        'multiple' => ['bool', 'null'],
        'required' => ['bool', 'null'],
    ];

    /**
     * @return stdClass
     */
    public function getClassExtention(): stdClass
    {
        return json_decode(json_encode([
            'name' => 'TypeForm\EntityType::class',
            'value' => TypeForm\EntityType::class
        ]), false);
    }

    /**
     * @param array $options
     * @return void
     */
    protected function transformParameters(array $options = []): void
    {
        if (!empty($options['mapping']['targetEntity'])) {
            $templater = $options['templater'];

            str_replace('\\', '\\', $options['mapping']['targetEntity'], $count);
            if (0 == $count) {
                $class = '\\' . $templater->namespace . '\\Domain\\Entity\\' . $options['mapping']['targetEntity'];
            } else {
                $class = '\\' . $options['mapping']['targetEntity'];
            }

            $this->resolverParameters['class'] = "$class::class";
        }

        $name = lcfirst($this->resolverParameters['name']);

        $this->resolverParameters['query_builder'] = "function (EntityRepository \$er) use (\$${name}List) {
                return \$${name}List;
            }";

        unset($this->resolverParameters['type']);
        unset($this->resolverParameters['name']);
    }
}
