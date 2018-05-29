<?php
namespace Sfynx\CoreBundle\Generator\Domain\Templater\Templater_\Architecture\Application\Validation\Type\Extension;

use \stdClass;
use Symfony\Component\Form\Extension\Core\Type as TypeCore;
use Symfony\Bridge\Doctrine\Form\Type as TypeForm;

use Sfynx\CoreBundle\Generator\Domain\Templater\Generalisation\Interfaces\ExtensionInterface;
use Sfynx\CoreBundle\Layers\Domain\Component\Generalisation\AbstractResolver;

/**
 * SubmitTypeExtension class
 *
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage TemplaterTemplater_\Architecture\Application\Validation\Type\Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SubmitTypeExtension extends AbstractResolver implements ExtensionInterface
{
    /**
     * @var array $defaults List of default values for optional parameters.
     */
    protected $defaults = [
        'label' => '',
        'name' => '',
        'type' => '',
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
        'label' => ['string', 'null'],
        'name' => ['string', 'null'],
        'type' => ['string', 'null'],
        'required' => ['bool', 'null'],
    ];

    /**
     * @return stdClass
     */
    public function getClassExtention(): stdClass
    {
        return json_decode(json_encode([
            'name' => 'TypeCore\SubmitType::class',
            'value' => TypeCore\SubmitType::class
        ]), false);
    }

    /**
     * @param array $options
     * @return void
     */
    protected function transformParameters(array $options = []): void
    {
        unset($this->resolverParameters['type']);
        unset($this->resolverParameters['name']);
    }
}
