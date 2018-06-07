<?php
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
?>
namespace <?php echo $templater->getTargetNamespace(); ?>;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Sfynx\CoreBundle\Layers\Application\Command\Validation\ValidationHandler\Generalisation\AbstractCommandValidationHandler;
use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\Interfaces\CommandInterface;
use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\AssocAll;

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
class <?php echo $templater->getTargetClassname(); ?> extends AbstractCommandValidationHandler
{
    /** @var bool */
    protected $skipArrayValidator = [];

    /**
     * Init array of constraints
     *
     * @param CommandInterface $command
     * @return void
     */
    protected function initConstraints(CommandInterface $command): void
    {
        $this
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
    <?php if ($field->required && ($field->required == true)): ?>
    ->add('<?php echo lcfirst($field->name) ?>', new Assert\Required([
    <?php else: ?>
    ->add('<?php echo lcfirst($field->name) ?>', new Assert\Optional([
    <?php endif; ?>
         new Assert\NotBlank(),
<?php if ($field->type == ClassHandler::TYPE_ENTITY): ?>             new Assert\Regex('/^[0-9]+$/'),<?php endif; ?>
<?php if ($field->type == ClassHandler::TYPE_BOOLEAN): ?>             new Assert\Type('boolean'),<?php endif; ?>
<?php if ($field->type == ClassHandler::TYPE_DATE): ?>             new Assert\DateTime(['format' => 'yyyy-MM-dd']),<?php endif; ?>
<?php if ($field->type == ClassHandler::TYPE_ARRAY): ?>             new Assert\Type('array'),<?php endif; ?>
<?php if ($field->type == ClassHandler::TYPE_EMAIL): ?>             new Assert\Email(),<?php endif; ?><?php echo "\r\n" ?>
        ]))
<?php endforeach; ?>
        ->add('_token', new Assert\Optional(new Assert\NotBlank()));
    }
}
