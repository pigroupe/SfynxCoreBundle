<?php
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;

$options = $templater->getTargetWidget()['options']['methods'] ?? ['GET', 'POST', 'PATCH'];
$extendsNamespace = $templater->getTargetWidget()['extends'] ?? 'Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\AbstractFormRequest';
$extendsName = ClassHandler::getClassNameFromNamespace($extendsNamespace);

?>
namespace <?php echo $templater->getTargetNamespace(); ?>;

use Symfony\Component\OptionsResolver\Options;
use <?php echo $extendsNamespace; ?>;

/**
 * Class <?php echo $templater->getTargetClassname(); ?><?php echo PHP_EOL ?>
 *
 * @category <?php echo $templater->getNamespace(); ?><?php echo PHP_EOL ?>
 * @package Presentation
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Presentation\\', '', $templater->getTargetNamespace()); ?><?php echo PHP_EOL ?>
 *
 * @author SFYNX <sfynx@pi-groupe.net>
 * @link http://www.sfynx.org
 * @license LGPL (https://opensource.org/licenses/LGPL-3.0)
 */
class <?php echo $templater->getTargetClassname(); ?> extends <?php echo $extendsName; ?><?php echo PHP_EOL ?>
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
<?php if (!isset($field->primaryKey) || !$field->primaryKey): ?>
            '<?php echo lcfirst($field->name) ?>' => <?php echo ClassHandler::getValue($field, 'false'); ?>,
<?php endif; ?>
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('PUT', $options)): ?>
        'PUT' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => <?php echo ClassHandler::getValue($field, 'false'); ?>,
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('PATCH', $options)): ?>
        'PATCH' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => null,
<?php endforeach; ?>
        ],
<?php endif; ?>
    ];

    /**
     * @var string[] $required List of required parameters for each methods.
     */
    protected $required = [
<?php if (\in_array('GET', $options)): ?>
        'GET'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('POST', $options)): ?>
        'POST'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ((!isset($field->primaryKey) || !$field->primaryKey)  && $field->required): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('PUT', $options)): ?>
        'PUT' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->required || (isset($field->primaryKey) && $field->primaryKey)): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
         ],
<?php endif; ?>
<?php if (\in_array('PATCH', $options)): ?>
        'PATCH' => ['entityId'],
<?php endif; ?>
    ];

    /**
     * @var array[] $allowedTypes List of allowed types for each methods.
     */
    protected $allowedTypes = [
<?php if (\in_array('GET', $options)): ?>
        'GET' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => ['<?php echo ClassHandler::getType($field->type); ?>', 'null'],
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('POST', $options)): ?>
        'POST' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (!isset($field->primaryKey) || !$field->primaryKey): ?>
            '<?php echo lcfirst($field->name) ?>' => ['<?php echo ClassHandler::getType($field->type); ?>', 'null'],
<?php endif; ?>
<?php endforeach; ?>
        ],
<?php endif; ?>
<?php if (\in_array('PUT', $options)): ?>
        'PUT' => [
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
        /* $this->options = \array_merge(
            $this->request->getRequest()->get('<?php echo strtolower($templater->getNamespace()); ?>_<?php if (property_exists($templater, 'targetCqrs')) echo str_replace('\\', '_', strtolower($templater->getTargetCqrs())); else strtolower($templater->getTargetClassname()); ?>', []),
            $this->parameters
        ); */
        parent::setOptions();

        /* boolean transformation */
        foreach ([
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (strpos(strtolower($field->type), 'bool') !== false): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ] as $data) {
            if (isset($this->options[$data])
                && (\is_bool($this->options[$data]) || \in_array($this->options[$data], [0, 1], true))
            ) {
                $this->options[$data] = (boolean)$this->options[$data];
            }
        }

        /* identifier transformation */
        foreach ([
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ('int' == ClassHandler::getType($field->type)) : ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ] as $data) {
            //$data = $this->request->get($data, '');
            //$this->options[$data] = ('' !== $data) ? (int)$data : null;
            $this->options[$data] = !empty($this->options[$data]) ? (int)$this->options[$data] : null;
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
    }
}
