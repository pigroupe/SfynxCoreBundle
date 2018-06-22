<?php
use Sfynx\CoreBundle\Generator\Domain\Component\File\ClassHandler;
?>
namespace <?php echo $templater->getTargetNamespace(); ?>;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as TypeCore;
use Symfony\Bridge\Doctrine\Form\Type as TypeForm;
use Doctrine\ORM\EntityRepository;
use Sfynx\CoreBundle\Layers\Application\Validation\Type\AbstractDefaultType;

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
class <?php echo $templater->getTargetClassname(); ?> extends AbstractDefaultType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->type == ClassHandler::TYPE_ENTITY): ?>
        $<?php echo lcfirst($field->name); ?>List = $this->data_form['<?php echo lcfirst($field->name); ?>List'];<?php echo PHP_EOL ?>
<?php endif; ?>
<?php endforeach; ?>

<?php foreach ($templater->getTargetCommandFields() as $field): ?>
<?php if ($field->type == ClassHandler::TYPE_ARRAY && property_exists($field, 'mapping')): ?>
        $<?php echo lcfirst($field->name); ?>List = $this->data_form['<?php echo lcfirst($field->name); ?>List'];<?php echo PHP_EOL ?>
<?php endif; ?>
<?php endforeach; ?>

        $builder
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
        <?php echo $templater->add(lcfirst($field->name), $field->type, $field) ?>
<?php endforeach; ?>
        <?php echo $templater->add('save', 'submit') ?>
        <?php echo $templater->add('saveAndContinue', 'submit', null, true) ?>
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => $this->data_form['data_class'],
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '<?php echo strtolower($templater->getNamespace()); ?>_<?php if (property_exists($templater, 'targetCqrs')) echo str_replace('\\', '_', strtolower($templater->getTargetCqrs())); else strtolower($templater->getTargetClassname()); ?>';
    }
}
