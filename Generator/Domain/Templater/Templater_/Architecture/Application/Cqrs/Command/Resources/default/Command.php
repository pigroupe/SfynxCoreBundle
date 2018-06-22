<?php
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
?>
namespace <?php echo $templater->getTargetNamespace(); ?>;

use Datetime;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\AbstractCommand;

/**
 * Class <?php echo $templater->getTargetClassname(); ?><?php echo PHP_EOL ?>
 *
 * @category <?php echo $templater->getNamespace(); ?><?php echo PHP_EOL ?>
 * @package Application
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Application\\', '', $templater->getTargetNamespace()); ?><?php echo PHP_EOL ?>
 *
 * @author SFYNX <sfynx@pi-groupe.net>
 * @link http://www.sfynx.fr
 * @license LGPL (https://opensource.org/licenses/LGPL-3.0)
 */
class <?php echo $templater->getTargetClassname(); ?> extends AbstractCommand
{
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
    /** @var <?php echo ClassHandler::getType($field->type, $field); ?> */
    protected $<?php echo lcfirst($field->name) ?>;
<?php endforeach; ?>
}
