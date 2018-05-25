namespace <?php echo $templater->getTargetNamespace(); ?>;

use Symfony\Component\OptionsResolver\Options;
use Sfynx\CoreBundle\Layers\Presentation\Request\Generalisation\AbstractFormRequest;

/**
 * Class <?php echo $templater->getTargetClassname(); ?><?php echo PHP_EOL ?>
 *
 * @category <?php echo $templater->getNamespace(); ?><?php echo PHP_EOL ?>
 * @package Presentation
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Presentation\\', '', $templater->getTargetNamespace()); ?><?php echo PHP_EOL ?>
 * @author SFYNX <contact@pi-groupe.net><?php echo PHP_EOL ?>
 * @licence LGPL
 */
class <?php echo $templater->getTargetClassname(); ?> extends AbstractFormRequest
{
    /**
     * @var array $defaults List of default values for optional parameters.
     */
    protected $defaults = [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
        '<?php echo $field->name ?>' => null,
<?php endforeach; ?>
    ];

    /**
     * @var string[] $required List of required parameters for each methods.
     */
    protected $required = [
        'GET'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->name != 'entityId'): ?>
            '<?php echo $field->name ?>',
<?php endif; ?>
<?php endforeach; ?>
        ],
        'POST'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo $field->name ?>',
<?php endforeach; ?>
        ],
        'PUT'  => 'POST'
    ];

    /**
     * @var array[] $allowedTypes List of allowed types for each methods.
     */
    protected $allowedTypes = [
        'GET' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->name != 'entityId'): ?>
            '<?php echo $field->name ?>' => ['<?php if ($field->type != 'id'): ?><?php if ($field->type == 'number'): ?>integer<?php elseif ($field->type == 'datetime'): ?>string<?php elseif ($field->type == 'valueObject'): ?>array<?php else: ?><?php echo $field->type ?><?php endif; ?><?php else: ?>string<?php endif; ?>', 'null'],
<?php endif; ?>
<?php endforeach; ?>
        ],
        'POST' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo $field->name ?>' => ['<?php if ($field->type != 'id'): ?><?php if ($field->type == 'number'): ?>integer<?php elseif ($field->type == 'datetime'): ?>string<?php elseif ($field->type == 'valueObject'): ?>array<?php else: ?><?php echo $field->type ?><?php endif; ?><?php else: ?>string<?php endif; ?>', 'null'],
<?php endforeach; ?>
        ],
        'PUT' => 'POST'
    ];

    /**
     * @return void
     */
    protected function setOptions()
    {
        $this->options = $this->request->getRequest()->get('', []);

        foreach ([] as $data) {
            if (isset($this->options[$data])) {
                $this->options[$data] = (int)$this->options[$data] ? true : false;
            }
        }
        $id = $this->request->get('id', '');
        $this->options['entityId'] = ('' !== $id) ? (int)$id : null;
        $this->options = (null !== $this->options) ? $this->options : [];
    }
}
