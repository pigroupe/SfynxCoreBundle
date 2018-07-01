<?php
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
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
        'GET' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => <?php echo ClassHandler::getValue($field, 'true'); ?>,
<?php endforeach; ?>
        ],
        'POST' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => <?php echo ClassHandler::getValue($field, 'false'); ?>,
<?php endforeach; ?>
        ]
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
            '<?php echo lcfirst($field->name) ?>' => ['<?php echo ClassHandler::getType($field->type); ?>', 'null'],
<?php endif; ?>
<?php endforeach; ?>
        ],
        'POST' => [
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            '<?php echo lcfirst($field->name) ?>' => ['<?php echo ClassHandler::getType($field->type); ?>', 'null'],
<?php endforeach; ?>
        ],
        'PATCH' => 'POST'
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
<?php if (ClassHandler::isIntType($field->type)): ?>
        //$<?php echo lcfirst($field->name) ?> = $this->request->get('<?php echo lcfirst($field->name) ?>', '');
        //$this->options['<?php echo lcfirst($field->name) ?>'] = ('' !== $<?php echo lcfirst($field->name) ?>) ? (int)$<?php echo lcfirst($field->name) ?> : null;
        if (!empty($this->options['<?php echo lcfirst($field->name) ?>'])) {
            $this->options['<?php echo lcfirst($field->name) ?>'] = (int)$this->options['<?php echo lcfirst($field->name) ?>'];
        }
<?php endif; ?>
<?php endforeach; ?>

        // datetime transformation
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if (ClassHandler::isDateType($field->type)): ?>
        $this->options['<?php echo lcfirst($field->name) ?>'] = (!empty($this->options['<?php echo lcfirst($field->name) ?>'])) ? new \DateTime($this->options['<?php echo lcfirst($field->name) ?>']) : new \DateTime('now');
<?php endif; ?>
<?php endforeach; ?>

        $this->options = (null !== $this->options) ? $this->options : [];
    }
}
