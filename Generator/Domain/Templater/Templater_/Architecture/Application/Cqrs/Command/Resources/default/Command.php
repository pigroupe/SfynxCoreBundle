namespace <?php echo $templater->getTargetNamespace(); ?>;

use \Datetime;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\AbstractCommand;

/**
 * Class <?php echo $templater->getTargetClassname(); ?> <?php echo PHP_EOL ?>
 *
 * @category <?php echo $templater->getNamespace(); ?> <?php echo PHP_EOL ?>
 * @package Application
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Application\\', '', $templater->getTargetNamespace()); ?> <?php echo PHP_EOL ?>
 * @author SFYNX <contact@pi-groupe.net> <?php echo PHP_EOL ?>
 * @licence LGPL
 */
class <?php echo $templater->getTargetClassname(); ?> extends AbstractCommand
{
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
    /** @var <?php if ($field->type == 'id'): ?>integer<?php else: ?><?php echo $field->type ?><?php endif; ?> */
    protected $<?php echo $field->name ?>;
<?php endforeach; ?>
}
