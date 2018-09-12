<?php
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;

$options = $templater->getTargetWidget()['options']['methods'] ?? ['GET', 'POST', 'PATCH'];

?>
namespace <?php echo $templater->getTargetNamespace(); ?>;

use Symfony\Component\OptionsResolver\Options;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\AbstractFormRequest;

/**
 * Class <?php echo $templater->getTargetClassname(); ?><?php echo PHP_EOL ?>
 *
 * @category <?php echo $templater->getNamespace(); ?><?php echo PHP_EOL ?>
 * @package Presentation
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Presentation\\', '', $templater->getTargetNamespace()); ?><?php echo PHP_EOL ?>
 *
 * @author SFYNX <sfynx@pi-groupe.net>
 * @link http://www.sfynx.fr
 * @license LGPL (https://opensource.org/licenses/LGPL-3.0)
 */
class <?php echo $templater->getTargetClassname(); ?> extends AbstractFormRequest
{
    /**
     * @var array $defaults List of default values for optional parameters.
     */
    protected $defaults = [
<?php if (\in_array('GET', $options)): ?>
        'GET' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => <?php echo ClassHandler::getValue($field, 'true'); ?>,
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('POST', $options)): ?>
        'POST' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => <?php echo ClassHandler::getValue($field, 'false'); ?>,
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('PATCH', $options)): ?>
        'PATCH' => 'POST',
<?php endif; ?>
    ];

    /**
     * @var string[] $required List of required parameters for each methods.
     */
    protected $required = [
<?php if (\in_array('GET', $options)): ?>
        'GET'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->name != 'entityId'): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('POST', $options)): ?>
        'POST'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('PATCH', $options)): ?>
        'PATCH' => 'POST',
<?php endif; ?>
    ];

    /**
     * @var array[] $allowedTypes List of allowed types for each methods.
     */
    protected $allowedTypes = [
<?php if (\in_array('GET', $options)): ?>
        'GET' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->name != 'entityId'): ?>
            '<?php echo lcfirst($field->name) ?>' => ['<?php echo ClassHandler::getType($field->type); ?>', 'null'],
<?php endif; ?>
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('POST', $options)): ?>
        'POST' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => ['<?php echo ClassHandler::getType($field->type); ?>', 'null'],
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('PATCH', $options)): ?>
        'PATCH' => 'POST',
<?php endif; ?>
    ];

    /**
     * @return void
     */
    protected function setOptions()
    {
        $this->options = \array_merge(
            $this->request->getRequest()->get('<?php echo strtolower($templater->getNamespace()); ?>_<?php if (property_exists($templater, 'targetCqrs')) echo str_replace('\\', '_', strtolower($templater->getTargetCqrs())); else strtolower($templater->getTargetClassname()); ?>', []),
            $this->parameters
        );

        /* boolean transformation */
        foreach ([
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (strpos(strtolower($field->type), 'bool') !== false): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ] as $data) {
            if (isset($this->options[$data])) {
                $this->options[$data] = (boolean)$this->options[$data];
            }
        }

        /* identifier transformation */
        foreach ([
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (ClassHandler::isIntType($field->type)) : ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ] as $data) {
            //$$data = $this->request->get($data, '');
            //$this->options[$data] = ('' !== $$data) ? (int)$$data : null;
            if (isset($this->options[$data])) {
                $this->options[$data] = (int)$this->options[$data];
            }
        }

        /* datetime transformation */
        foreach ([
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (ClassHandler::isDateType($field->type)) : ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ] as $data) {
            $this->options[$data] = !empty($this->options[$data]) ? new \DateTime($this->options[$data]) : new \DateTime();
        }

        $this->options = (null !== $this->options) ? $this->options : [];
    }
}
