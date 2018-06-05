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
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
        '<?php echo lcfirst($field->name) ?>' => null,
<?php endforeach; ?>
    ];

    /**
     * @var string[] $required List of required parameters for each methods.
     */
    protected $required = [
        'GET'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->name != 'entityId'): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ],
        'POST'  => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endforeach; ?>
        ],
        'PATCH' => 'POST'
    ];

    /**
     * @var array[] $allowedTypes List of allowed types for each methods.
     */
    protected $allowedTypes = [
        'GET' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->name != 'entityId'): ?>
            '<?php echo lcfirst($field->name) ?>' => ['<?php if (strpos($field->type, 'entityId') !== false): ?>integer<?php elseif (strpos(strtolower($field->type), 'id') !== false): ?>integer<?php elseif (strtolower($field->type) == 'number'): ?>integer<?php elseif (strtolower($field->type) == 'datetime'): ?>DateTime<?php elseif (strtolower($field->type) == 'valueobject'): ?>array<?php else: ?><?php echo $field->type ?><?php endif; ?>', 'null'],
<?php endif; ?>
<?php endforeach; ?>
        ],
        'POST' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => ['<?php if (strpos($field->type, 'entityId') !== false): ?>integer<?php elseif (strpos(strtolower($field->type), 'id') !== false): ?>integer<?php elseif (strtolower($field->type) == 'number'): ?>integer<?php elseif (strtolower($field->type) == 'datetime'): ?>DateTime<?php elseif (strtolower($field->type) == 'valueobject'): ?>array<?php else: ?><?php echo $field->type ?><?php endif; ?>', 'null'],
<?php endforeach; ?>
        ],
        'PATCH' => 'POST'
    ];

    /**
     * @return void
     */
    protected function setOptions()
    {
        $this->options = $this->request->getRequest()->get('', []);

        // boolean transformation
        $dataBool = [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (strpos(strtolower($field->type), 'bool') !== false): ?>
            '<?php echo lcfirst($field->name) ?>',
<?php endif; ?>
<?php endforeach; ?>
        ];

        foreach ($dataBool as $data) {
            if (isset($this->options[$data])) {
                $this->options[$data] = (int)$this->options[$data] ? true : false;
            }
        }

        // identifier transformation
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ((strpos(strtolower($field->type), 'id') !== false) || (strpos(strtolower($field->type), 'integer') !== false)): ?>
        //$<?php echo lcfirst($field->name) ?> = $this->request->get('<?php echo lcfirst($field->name) ?>', '');
        //$this->options['<?php echo lcfirst($field->name) ?>'] = ('' !== $<?php echo lcfirst($field->name) ?>) ? (int)$<?php echo lcfirst($field->name) ?> : null;
        if (isset($this->options['<?php echo lcfirst($field->name) ?>'])) {
            $this->options['<?php echo lcfirst($field->name) ?>'] = ('' !== $this->options['<?php echo lcfirst($field->name) ?>']) ? (int)$this->options['<?php echo lcfirst($field->name) ?>'] : null;
        }

<?php endif; ?>
<?php endforeach; ?>

        // datetime transformation
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (strpos(strtolower($field->type), 'datetime') !== false): ?>
        if (isset($this->options['<?php echo lcfirst($field->name) ?>'])) {
            $data = $this->options['<?php echo lcfirst($field->name) ?>'];
            $this->options['<?php echo lcfirst($field->name) ?>'] = (null !== $data && !empty($data)) ? new \DateTime($data) : new \DateTime('now');
        }
<?php endif; ?>
<?php endforeach; ?>

        $this->options = (null !== $this->options) ? $this->options : [];
    }
}
