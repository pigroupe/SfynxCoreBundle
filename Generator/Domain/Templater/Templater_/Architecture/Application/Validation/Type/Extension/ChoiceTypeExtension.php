<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Validation\Type\Extension;

use \stdClass;
use Symfony\Component\Form\Extension\Core\Type as TypeCore;
use Symfony\Bridge\Doctrine\Form\Type as TypeForm;

use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\ExtensionInterface;
use Sfynx\CoreBundle\Layers\Domain\Component\Generalisation\AbstractResolver;

/**
 * ChoiceTypeExtension class
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Application\Validation\Type\Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ChoiceTypeExtension extends AbstractResolver implements ExtensionInterface
{
    /**
     * @var array $defaults List of default values for optional parameters.
     */
    protected $defaults = [
        'name' => '',
        'type' => '',
        'choices' => [],
        'expanded' => false,
        'multiple' => false,
        'required' => true,
        'placeholder' => 'Select a field',
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
        'choices' => ['array', 'null'],
        'expanded' => ['bool', 'null'],
        'multiple' => ['bool', 'null'],
        'required' => ['bool', 'null'],
        'placeholder' => ['string', 'null'],
    ];

    /**
     * @return stdClass
     */
    public function getClassExtention(): stdClass
    {
        return json_decode(json_encode([
            'name' => 'TypeCore\ChoiceType::class',
            'value' => TypeCore\ChoiceType::class
        ]), false);
    }

    /**
     * @param array $options
     * @return void
     */
    protected function transformParameters(array $options = []): void
    {
        if (!empty($options['mapping']['targetEntity'])) {
            $name = lcfirst($this->resolverParameters['name']);

            $return = $this->createReturnValue($options);

            $this->resolverParameters['choices'] = "\$${name}List";

            if (!empty($return)) {
            $this->resolverParameters['choice_label'] = "function (\$entity, \$key, \$index) {
                return is_object(\$entity) ? ${return} : \$entity;
            }";
            } else {
            $this->resolverParameters['choice_label'] = "function (\$entity, \$key, \$index) {
                return \$entity;
            }";
            }
            $this->resolverParameters['choice_value'] = "function (\$entity = null) {
                if (!\$entity) {
                    return '';
                }
                return is_object(\$entity) ? \$entity->getId() : \$entity;
            }";
        }

        unset($this->resolverParameters['type']);
        unset($this->resolverParameters['name']);
    }

    /**
     * @param string $name
     * @param array $options
     * @return string
     */
    protected function createReturnValue(array $options = []): string
    {
        $content = '';

        if (!empty($options['properties'])) {
            $start = true;
            foreach ($options['properties'] as $property) {
                $function = ucfirst($property);
                if ($start) {
                    $content .= "\$entity->get${function}()";
                    $start = false;
                } else {
                    $content .= " . ' - ' . \$entity->${function}()";
                }
            }
        }

        return $content;
    }
}
